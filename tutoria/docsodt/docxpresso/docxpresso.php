<?php 
$dir = __DIR__ . DIRECTORY_SEPARATOR . 'csv';
$fileCount = 0;
$daemonTimer = 10000;
//read the config file
$config = parse_ini_file( 'config.ini', true);
$os = strtoupper($config['soffice']['os']);
$path = $config['soffice']['path'];
$auto = $config['soffice']['auto'];
$refresh = $config['soffice']['refresh'];
$innerCounter = 0;
$pathArray = explode(DIRECTORY_SEPARATOR, $path);
$dirs = count($pathArray);


$invisible = ' --headless';
$invisible .= ' --nolockcheck';
$invisible .= ' --nodefault';
$invisible .= ' --nologo';
$invisible .= ' --norestore';
$invisible .= ' --nofirststartwizard';

if ($os == "WINDOWS") {
    //build the command
    $base = 'cd \\ && ';
    for ($j = 1; $j < $dirs - 1; $j++) {
        $base .= 'cd "' . $pathArray[$j] . '" ';
        $base .= '&& ';
    }
    //if we do not use the start parameter we may crash libreoffice
    //due to a known bug
    $cli = $base . 'start ' . $pathArray[$dirs-1] . $invisible;
    if ($auto) {
        $inicio = microtime(true);
        exec('taskkill /fi "imagename eq soffice*" /f', $kill);
        exec('start /b soffice.vbs "' . $path . '"', $start);
        unset($kill);
        unset($start);
        $completed = microtime(true);
        //echo 'started in: ' . $inicio .PHP_EOL;
        //echo 'completed in: ' . $completed .PHP_EOL;
    }
} else {
    //build the command
    $cli = 'cd ';
    for ($j = 1; $j < $dirs - 1; $j++) {
        $cli .= DIRECTORY_SEPARATOR . $pathArray[$j];
    }
    $cli .= ' & ' . $pathArray[$dirs-1];
    if ($auto) {
        $inicio = microtime(true);
        exec("kill -9 `pgrep -f soffice`", $kill);
        exec('nohup ' . $path . ' --norestore > /dev/null &', $start);
        unset($kill);
        unset($start);
        $completed = microtime(true);
        //echo 'started in: ' . $inicio .PHP_EOL;
        //echo 'completed in: ' . $completed .PHP_EOL;
    }
    $cli .= $invisible;
}

while(true) {
    usleep($daemonTimer);
    
    $files = glob($dir . '/*.csv');
    $newCount = count($files);
    if ($newCount > 0 && $newCount >= $fileCount) {
        if ($auto){
            $innerCounter++;
            if ($innerCounter > $refresh) {
                if ($os == "WINDOWS") {
                    //we restart soffice to keep low the  memory consumption
                    exec('taskkill /fi "imagename eq soffice*" /f', $kill);
                    exec('start /b soffice.vbs "' . $path . '"', $start);
                    unset($kill);
                    unset($start);
                } else {
                    exec("kill -9 `pgrep -f soffice`", $kill);
                    exec('nohup ' . $path . ' --norestore > /dev/null &', $start);
                    unset($kill);
                    unset($start);
                }
                $innerCounter = 0;
            }
        }
        $rendered = renderDoc($files[0], $cli, $os);
        //check that the file exists and have not been removed by other process
        //in the meantime
        $exist = file_exists($files[0]);
        if ($exist) {
            $unlink = unlink($files[0]);
            if ($unlink === false) {
                //If there is a problem unlinking files so we should try again
                //and leave because otherwise the script may crash the server
                usleep(100000);
                $exist = file_exists($files[0]);
                $unlink = unlink($files[0]);
                if ( $exist && !$unlink) {
                    //write to the log explaining the problem and exit
                    echo "The csv folder is not writable.";
                    exit();
                }
            }
        }
        $fileCount = $newCount -1;
    }
}

function renderDoc($file, $cli, $os) {
    //let it breath to avoid conflicts
    usleep(1000);
    //open file and read command
    $tmp = fopen($file, 'r');
    $data = fread($tmp, 1000);
    fclose($tmp);
    $info = explode('|', $data);
    $path = $info[0];
    $ext = $info[1];
    if ($ext == 'DOC' && $info[2] == 'false') {
        //this is a hack to update TOCs in .doc format
        $data = str_replace('|DOC|', '|ODT|', $data);
    }
    $exists = file_exists($path . '.odt');
    $init = microtime(true);
    if (!empty($data) && $exists) {
        $cmd = $cli . ' "macro:///Docxpresso.Docxpresso.Render(' . $data . ')"';
        exec($cmd, $res);
        unset($res);
    } 
    //rename the generated files
    $newPath = preg_replace('/(_h5p_[a-z0-9]*)$/', '', $path);
    if ($ext == 'ODT' || ($ext == 'DOC' && $info[2] == 'false')) {
        unlink($path . '.odt');
        $new = file_exists($path . '_new.odt');
        if ($new) {
            rename($path . '_new.odt',  $newPath . '.' . strtolower($ext));
        }
    } else {
        unlink($path . '.odt');
        $new = file_exists($path . '.' . strtolower($ext));
        if ($new) {
            rename($path . '.' . strtolower($ext),  $newPath . '.' . strtolower($ext));
        }
    }
    $end = microtime(true);
    //Uncomment next line if you wish to echo the time taken for the conversion
    //echo ($end - $init) . PHP_EOL;
    //back to the infinite loop
}

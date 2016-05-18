<?php 
$dir = __DIR__ . DIRECTORY_SEPARATOR . 'csv';
$sleepTimer = 2000000; //in microseconds
$repetitions = 0;
$maxRepetitions = 14;
$currentFile = "";
//read the config file
$config = parse_ini_file( 'config.ini', true);
$path = $config['soffice']['path'];



while(true) {
    
    $files = glob($dir . '/*.csv');   
    //if the older file is not csv we set a random file name to avoid
    //doing anything when the conversion process is idle
    if (count($files) == 0){
		$fileName = 'random_' . rand(9999, 999999999);
    } else {
        $csvPos = strpos($files[0], '.csv');
        if ($csvPos !== false) {
                $fileName = $files[0];
        } else {
                $fileName = 'random_' . rand(9999, 999999999);
        }
    }
    $fullPath = $dir . DIRECTORY_SEPARATOR . $fileName;
    
    //If the file is the same after a cycle we increment the counter by 1 otherwise we set it to zero
    if($fileName == $currentFile) {
        $repetitions++;
    } else {
        $repetitions = 0;
        $currentFile = $fileName;
    }
    //If the file is the same after the maximum number of repetitions we take action
    if ($repetitions > $maxRepetitions){
	//Reinitiate the counter
        $repetitions = 0;
        if (file_exists($fileName)){
            $csv = fopen($fileName, 'r');
            $csvRead = fread($csv, 2000);
            fclose($csv);
            unlink($fileName);
        }
	//check that docxpresso.php is running
	if (!CheckDocxpresso()){ 
            //write to the log
            $txt =  'The docxpresso.php was halted at: ' . date(DATE_RFC2822) . "\n";
            $txt .= 'The script halted while processing the ' . $fileName . ' file with contents:' . "\n";
            $txt .= $csvRead . "\n";
            $txt .= "\n";
        } else {
            //write to the log
            $txt =  'The docxpresso.php was stopped at: ' . date(DATE_RFC2822) . "\n";
            $txt .= 'The script halted while processing the ' . $fileName . ' file with contents:' . "\n";
            $txt .= $csvRead . "\n";
            $txt .= "\n";
        }
        $log = fopen($dir . DIRECTORY_SEPARATOR . 'monitor.log', 'w+');
        fwrite($log, $txt);
        fclose($log);
        //kill soffice and its childs
        unset($pidList);
        exec('pgrep -f soffice', $pidList);
        $test = implode('--', $pidList);
        echo 'pids: ' . $test . PHP_EOL;
        foreach ($pidList as $ppid){
            exec('kill -9 -' . $ppid, $killppid);
        }
        unset($killppid);
        exec("kill -9 `pgrep -f soffice`", $killsoffice);
        //exec("pkill -TERM `pgrep -f soffice`", $killsoffice);
        usleep(100000);
        unset($killsoffice);
        exec("kill -9 `pgrep -f docxpresso.php`", $kill);
        usleep(100000);
        unset($kill);
        //Relaunch docxpresso.php
        exec('nohup php docxpresso.php > /dev/null &', $launch);
	unset($launch);
    }
    
    usleep($sleepTimer);
}

function CheckDocxpresso() {
    $running = false;
    exec("pgrep -f docxpresso.php", $check); 
    //parse check to see what it returns
    //beware that the pgrep process itself will return a positive result
    if (isset($check[1])) {
        if (is_numeric($check[0])) {
            $running = true;
        }
    }
    unset ($check);
    return $running;
}


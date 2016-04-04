<?php
	session_start();
	include_once('../bbdd/connect.php');
	include_once('../func/generic.php');
	include_once('../func/constants.php');
	mysql_query("SET NAMES 'utf8'");
	
	$idalumnes    = isset($_SESSION['alumne'])       ? $_SESSION['alumne']    : 0;
	$curs_escolar = isset($_SESSION['curs_escolar']) ? $_SESSION['curs_escolar'] : 0;
		
	if ($idalumnes==0 || $curs_escolar==0) {
		exit;
	}	
?>    
        <h5>Est&agrave;s matriculat de ...</h5>
        <ul id="tree_mat" class="easyui-tree" data-options="animate:true">
            <li>
                <span>&nbsp;</span>
                <ul>
                   <?php
					$grup_actual   = 0;
					
                    $rsMateries = getMateriesAlumne($curs_escolar,$idalumnes);
					while ($row = mysql_fetch_assoc($rsMateries)) {
						if ($row['idgrups'] == $grup_actual) {
						}
						else {
							if ($grup_actual != 0) {
								echo "</ul>";	
								echo "</li>";
							}
							echo "<li>";
							echo "<span><strong>".$row['grup']."</strong></span>";
							echo "<ul>";
							$grup_actual = $row['idgrups'];
						}
						
						echo "<li>";
                        echo "<span>".$row['materia']."</span>";
                        echo "</li>";
						
					}
					
					if (isset($rsMateries)) {
						mysql_free_result($rsMateries);
					}
				   ?>                    
                </ul>
            </li>
        </ul>
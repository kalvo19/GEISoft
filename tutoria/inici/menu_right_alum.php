<?php
	/*session_start();
	include_once('../bbdd/connect.php');
	include_once('../func/generic.php');
	include_once('../func/constants.php');
	mysql_query("SET NAMES 'utf8'");*/
	
	$idalumnes    = isset($_SESSION['alumne'])       ? $_SESSION['alumne']    : 0;
	$curs_escolar = isset($_SESSION['curs_escolar']) ? $_SESSION['curs_escolar'] : 0;
	
	if ($idalumnes==0 || $curs_escolar==0) {
		exit;
	}	
?>
     <div id="menu_lateral" class="easyui-panel" style="padding:2px; height:auto">
        <h5>Est&agrave;s matriculat de ...</h5>
        <ul id="tree_mat" class="easyui-tree" data-options="animate:true">
            <li>
                <span>&nbsp;</span>
                <ul>
                   <?php
					$grup_actual = 0;
					
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
						echo "<span>".substr($row['materia'],0,58)."</span>";
                        //echo "<span>".$row['id_mat_uf_pla']."-".$row['materia']."</span>";
                        echo "</li>";
						
					}
					
					if (isset($rsMateries)) {
						mysql_free_result($rsMateries);
					}
				   ?>                    
                </ul>
            </li>
        </ul>
    </div>
    
       <!--
       <table id="dg_mat" class="easyui-datagrid" title="Estàs matriculat de ..." style="width:auto;height:auto"
			data-options="
				singleSelect: true,
                pagination: false,
                rownumbers: true,
				url: './alum_automat/alum_automat_getdata_materies.php',
				onClickRow: onClickRow
			">    
        <thead>  
            <tr>
            	<th field="grup" width="110" sortable="true">Grup</th>
                <th field="materia" width="280" sortable="true">Mat&egrave;ria</th>
            </tr>  
        </thead>  
	  </table>
      -->
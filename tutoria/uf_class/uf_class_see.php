<?php
  session_start();
  include_once('../bbdd/connect.php');
  include_once('../func/constants.php');
  include_once('../func/generic.php');
  mysql_query("SET NAMES 'utf8'");
  
  $idmoduls = isset($_REQUEST['idmoduls']) ? $_REQUEST['idmoduls'] : 0 ;
  $idgrups  = isset($_REQUEST['idgrups'])  ? $_REQUEST['idgrups']  : 0 ;
  
  $sql  = "SELECT gm.idgrups_materies,gm.id_mat_uf_pla as id_mat_uf_pla,CONCAT(m.nom_modul,'-',uf.nom_uf) AS nom, ";
  $sql .= "CONCAT(SUBSTR(gm.data_inici,9,2),'-',SUBSTR(gm.data_inici,6,2),'-',SUBSTR(gm.data_inici,1,4)) AS data_inici, ";
  $sql .= "CONCAT(SUBSTR(gm.data_fi,9,2),'-',SUBSTR(gm.data_fi,6,2),'-',SUBSTR(gm.data_fi,1,4)) AS data_fi ";
  $sql .= "FROM grups_materies gm ";
  $sql .= "INNER JOIN unitats_formatives uf ON gm.id_mat_uf_pla=uf.idunitats_formatives ";
  $sql .= "INNER JOIN moduls_ufs mu         ON gm.id_mat_uf_pla=mu.id_ufs ";
  $sql .= "INNER JOIN moduls m              ON mu.id_moduls=m.idmoduls ";
  $sql .= "INNER JOIN grups gr ON gm.id_grups=gr.idgrups ";
  $sql .= "WHERE m.idmoduls='".$idmoduls."' AND  gm.id_grups='".$idgrups."' ";
  $sql .= "ORDER BY 3";
  
  $rsUFs = mysql_query($sql);   
?>

<style type="text/css">
		.left{
			width:20px;
			float:left;
		}
		.left table{
			background:#E0ECFF;
		}
		.left td{
			background:#eee;
		}
		.right{
			float:left;
			
		}
		.right table{
			background:#E0ECFF;
			width:850px;
		}
		.right td{
			background:#fafafa;
			text-align:left;
			padding:2px;
		}
		.right td{
			background:#E0ECFF;
		}
		.right td.drop{
			background:#fafafa;
			
		}
		.right td.over{
			background:#FBEC88;
		}
		.item{
			text-align:center;
			border:1px solid #499B33;
			background:#fafafa;
			/*width:100px;*/
		}
		
	</style>

<div style="width:900px;">
 <h5 style="margin-bottom:0px">
  &nbsp;&nbsp;
  Unitats Formatives<br /><br />
  
  &nbsp;&nbsp;<a style=' color: #000066; font-size:16px; border:1px dashed #CCCCCC; padding:1px 1px 1px 1px '>
  <?= getModul($idmoduls)->nom_modul ?></a>&nbsp;<br /><br />
  &nbsp;&nbsp;<a style=' color: #000066; font-size:16px; border:1px dashed #CCCCCC; padding:1px 1px 1px 1px '>
  <?= getGrup($idgrups)->nom ?></a>&nbsp;<br /><br />
  
 </h5>       
	<div class="left">
		&nbsp;
	</div>
	<div class="right">
		<table>
                <?php
				   $idx=1;
				   while($row = mysql_fetch_object($rsUFs)){
						  echo "<tr>";
						  echo "<td valign='top' width='1'>".$idx."</td>";
						  echo "<td valign='top' align='left' class='drop'>";
						  echo $row->nom;
					  	  echo "</td>";
						  echo "<td valign='top' width='80' align='left' class='drop'>";
						  echo $row->data_inici;
					  	  echo "</td>";
						  echo "<td valign='top' width='80' align='left' class='drop'>";
						  echo $row->data_fi;
					  	  echo "</td>";
						  echo "</tr>";
						  $idx++;
				   }
				?>          
		</table>
	</div>
</div>

<?php
mysql_free_result($rsUFs);
mysql_close();
?>
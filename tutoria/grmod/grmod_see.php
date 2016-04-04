<?php
  session_start();
  include_once('../bbdd/connect.php');
  include_once('../func/constants.php');
  include_once('../func/generic.php');
  mysql_query("SET NAMES 'utf8'");
  
  $id_grups  = isset($_REQUEST['id_grups']) ? $_REQUEST['id_grups'] : 0 ;
  
  $sql  = "SELECT gm.idgrups_materies,gm.id_mat_uf_pla as id_mat_uf_pla,ma.nom_materia AS nom, ";
  $sql .= "CONCAT(SUBSTR(gm.data_inici,9,2),'-',SUBSTR(gm.data_inici,6,2),'-',SUBSTR(gm.data_inici,1,4)) AS data_inici, ";
  $sql .= "CONCAT(SUBSTR(gm.data_fi,9,2),'-',SUBSTR(gm.data_fi,6,2),'-',SUBSTR(gm.data_fi,1,4)) AS data_fi ";
  $sql .= "FROM grups_materies gm ";
  $sql .= "INNER JOIN materia ma ON gm.id_mat_uf_pla=ma.idmateria ";
  $sql .= "INNER JOIN grups gr ON gm.id_grups=gr.idgrups ";
  $sql .= "WHERE gm.id_grups='".$id_grups."' ";
  $sql .= "UNION ";
  $sql .= "SELECT gm.idgrups_materies,gm.id_mat_uf_pla as id_mat_uf_pla,CONCAT(m.nom_modul,'-',uf.nom_uf) AS nom, ";
  $sql .= "CONCAT(SUBSTR(gm.data_inici,9,2),'-',SUBSTR(gm.data_inici,6,2),'-',SUBSTR(gm.data_inici,1,4)) AS data_inici, ";
  $sql .= "CONCAT(SUBSTR(gm.data_fi,9,2),'-',SUBSTR(gm.data_fi,6,2),'-',SUBSTR(gm.data_fi,1,4)) AS data_fi ";
  $sql .= "FROM grups_materies gm ";
  $sql .= "INNER JOIN unitats_formatives uf ON gm.id_mat_uf_pla=uf.idunitats_formatives ";
  $sql .= "INNER JOIN moduls_ufs mu         ON gm.id_mat_uf_pla=mu.id_ufs ";
  $sql .= "INNER JOIN moduls m              ON mu.id_moduls=m.idmoduls ";
  $sql .= "INNER JOIN grups gr ON gm.id_grups=gr.idgrups ";
  $sql .= "WHERE gm.id_grups='".$id_grups."' ";
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
  Mat&egrave;ries / Unitats Formatives de <a style=" color: #000066; border:1px dashed #CCCCCC; padding:3px 3px 3px 3px ">
  <?php
     if ($id_grups != 0) {
	 	echo getGrup($id_grups)->nom;
	 }
  ?>
  </a>&nbsp;&nbsp;
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
<?php
  include('../bbdd/connect.php');
  mysql_query("SET NAMES 'utf8'");
  
  $rsDies     = mysql_query("select * from dies_setmana where laborable='S'");
  $rsHores    = mysql_query("select * from franges_horaries");
  $rsMaterias = mysql_query("select * from franges_horaries");
?>

<style type="text/css">
		.left{
			width:120px;
			float:left;
		}
		.left table{
			background:#E0ECFF;
		}
		.left td{
			background:#eee;
		}
		.right{
			float:right;
			width:650px;
		}
		.right table{
			background:#E0ECFF;
			width:100%;
		}
		.right td{
			background:#fafafa;
			text-align:center;
			padding:2px;
		}
		.right td{
			background:#E0ECFF;
		}
		.right td.drop{
			background:#fafafa;
			width:100px;
		}
		.right td.over{
			background:#FBEC88;
		}
		.item{
			text-align:center;
			border:1px solid #499B33;
			background:#fafafa;
			width:100px;
		}
		.assigned{
			border:1px solid #BC2A4D;
		}
		
	</style>
	<script>
		$(function(){
			$('.left .item').draggable({
				revert:true,
				proxy:'clone'
			});
			$('.right td.drop').droppable({
				onDragEnter:function(e,source){
					$(source).draggable('options').cursor='auto';
					$(source).draggable('proxy').css('border','1px solid red');
					$(this).addClass('over');
				},
				onDragLeave:function(e,source){
					$(source).draggable('options').cursor='not-allowed';
					$(source).draggable('proxy').css('border','1px solid #ccc');
					$(this).removeClass('over');
				},
				onDrop:function(e,source){
					if ($(source).hasClass('assigned')){  
						$(this).append(source);  
					} else {  
						var c = $(source).clone().addClass('assigned');  
						$(this).empty().append(c);  
						c.draggable({  
							revert:true  
						}); 
					} 
					$(this).removeClass('over');
				}
				
				
			});
		});
	</script>

<div style="width:950px;">
    <h2>Horaris</h2>
    
    <div id="headHorari" style="height:80px; border:1px dashed #CCCCCC">
        Pla d'estudis:&nbsp;
        <select id="s_pe" class="easyui-combobox" data-options="
					width:70,
                    url:'./mat/pe_mat_getdata.php',
					idField:'idplans_estudis',
                    valueField:'idplans_estudis',
					textField:'Acronim_pla_estudis',
					panelHeight:'auto'">
        </select>
        Grup:&nbsp;
        <select id="g_pe" class="easyui-combobox" data-options="
					width:70,
                    url:'./grma/grup_getdata.php',
					idField:'idgrups',
                    valueField:'idgrups',
					textField:'nom'
		">
        </select>
    </div>
        
	<div class="left">
		<table>
			<tr>
				<td><div class="item">English</div></td>
			</tr>
			<tr>
				<td><div class="item">Science</div></td>
			</tr>
			<tr>
				<td><div class="item">Music</div></td>
			</tr>
			<tr>
				<td><div class="item">History</div></td>
			</tr>
			<tr>
				<td><div class="item">Computer</div></td>
			</tr>
			<tr>
				<td><div class="item">Mathematics</div></td>
			</tr>
			<tr>
				<td><div class="item">Arts</div></td>
			</tr>
			<tr>
				<td><div class="item">Ethics</div></td>
			</tr>
		</table>
	</div>
	<div class="right">
		<table>
			<tr>
				<td class="blank"></td>
                <?php
				   while($row = mysql_fetch_object($rsDies)){
				      echo "<td class='title'>";
					  echo $row->dies_setmana;
					  echo "</td>";
				   }
				?>
			</tr>
			
                <?php
				   while($row = mysql_fetch_object($rsHores)){
					  if ($row->esbarjo=='S') {
					    echo "<tr height='20'>";
						echo "<td class='time'>".substr($row->hora_inici,0,5)."-".substr($row->hora_fi,0,5)."</td>";
						echo "<td class='lunch' colspan='5'>ESBARJO</td>";
						echo "</tr>";
					  }
					  else {
						  echo "<tr height='80'>";
						  echo "<td class='time'>".substr($row->hora_inici,0,5)."-".substr($row->hora_fi,0,5)."</td>";
						  echo "<td class='drop'></td>";
						  echo "<td class='drop'></td>";
						  echo "<td class='drop'></td>";
						  echo "<td class='drop'></td>";
						  echo "<td class='drop'></td>";
						  echo "</tr>";
					  }
				   }
				?>
         <!--   
			<tr>
				<td class="time">09:00</td>
				<td class="drop"></td>
				<td class="drop"></td>
				<td class="drop"></td>
				<td class="drop"></td>
				<td class="drop"></td>
			</tr>
			<tr>
				<td class="time">10:00</td>
				<td class="drop"></td>
				<td class="drop"></td>
				<td class="drop"></td>
				<td class="drop"></td>
				<td class="drop"></td>
			</tr>
			<tr>
				<td class="time">11:00</td>
				<td class="drop"></td>
				<td class="drop"></td>
				<td class="drop"></td>
				<td class="drop"></td>
				<td class="drop"></td>
			</tr>
			<tr>
				<td class="time">12:00</td>
				<td class="drop"></td>
				<td class="drop"></td>
				<td class="drop"></td>
				<td class="drop"></td>
				<td class="drop"></td>
			</tr>
			<tr>
				<td class="time">13:00</td>
				<td class="lunch" colspan="5">Lunch</td>
			</tr>
			<tr>
				<td class="time">14:00</td>
				<td class="drop"></td>
				<td class="drop"></td>
				<td class="drop"></td>
				<td class="drop"></td>
				<td class="drop"></td>
			</tr>
			<tr>
				<td class="time">15:00</td>
				<td class="drop"></td>
				<td class="drop"></td>
				<td class="drop"></td>
				<td class="drop"></td>
				<td class="drop"></td>
			</tr>
			<tr>
				<td class="time">16:00</td>
				<td class="drop"></td>
				<td class="drop"></td>
				<td class="drop"></td>
				<td class="drop"></td>
				<td class="drop"></td>
			</tr>
            -->
            
            
		</table>
	</div>
</div>

<?php
mysql_free_result($rsDies);
mysql_free_result($rsHores);
mysql_close();
?>
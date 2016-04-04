<?php
   session_start();
   include_once('../bbdd/connect.php');
   include_once('../func/generic.php');
   include_once('../func/constants.php');
   mysql_query("SET NAMES 'utf8'");
      
   $fechaSegundos = time();
   $strNoCache    = "?nocache=$fechaSegundos";
   $idalumne      = isset($_REQUEST['c_alumne']) ? $_REQUEST['c_alumne'] : 0 ;
   $idgrup        = getGrupAlumne($idalumne)->idgrups;
   
   if (getCarrecPrincipalGrup(TIPUS_TUTOR,$idgrup) == 0) {
		$idprofessor   = 0;
		$nom_tutor     = "&nbsp;";
   }
   else {
		$idprofessor   = getCarrecPrincipalGrup(TIPUS_TUTOR,$idgrup);
		$nom_tutor     = getProfessor($idprofessor,TIPUS_nom_complet);
   }   
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="whitesmoke">
<?php 
   echo "<tr>";
   echo "<td><h4>Grup</h4><h2>". getGrupAlumne($idalumne)->nom."</h2></td>";
   echo "<td><h4>Tutor/a</h4><h2>".$nom_tutor."</h2></td>";
   echo "<td><h4>Fill/a</h4><h2>".getAlumne($idalumne,TIPUS_nom_complet)."</h2></td>";
   echo "</tr>";
   mysql_close();

?>        
</table>   
    
    <form id="fm_missatge" method="post" novalidate>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="714">
        	<h5>Missatge</h5>
            <textarea name="missatge" style="height:90px; width:700px;"></textarea>
        </td>
       </tr>
       <tr>
        <td align="left" valign="bottom">
        	<a href="#" class="easyui-linkbutton" onclick="javascript:enviaMissatge(<?=$idalumne?>)" style=" vertical-align: baseline; ">
            <img src="./images/envelope.png" height="16" />&nbsp;Envia missatge</a>
        </td>
      </tr>
    </table>
	</form>
    <br />
      
    <table id="dg" class="easyui-datagrid" title="Missatges al tutor enviats" style="width:auto;height:400px;"
			data-options="
				singleSelect: true,
                pagination: false,
                rownumbers: true,
				toolbar: '#toolbar',
				url: './families/families_missatge_tutor_getdata.php?idalumne=<?=$idalumne?>',
				onClickRow: onClickRow
			">    
        <thead>  
            <tr>
				<th field="data" width="100" sortable="true">Data</th>
                <th field="hora" width="70" sortable="true">Hora</th>
                <th field="missatge" width="450" sortable="true">Missatge</th>
            </tr>  
        </thead>  
    </table> 
    
    <div id="toolbar" style="padding:5px;height:auto"> 
        <!--Desde: <input id="data_inici" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></input>
        fins a: <input id="data_fi" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></input>
        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="doSearch()">Cercar</a>-->
    </div>
    
    <iframe id="fitxer_pdf" scrolling="yes" frameborder="0" style="width:10px;height:10px; visibility:hidden" src=""></iframe>
    
    <script type="text/javascript">  
        var url;
		var editIndex    = undefined;
		var nou_registre = 0;
		var today        = new Date();
		
		function myformatter(date){  
            var y = date.getFullYear();  
            var m = date.getMonth()+1;  
            var d = date.getDate();  
            return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
        }
		
        function myparser(s){  
            if (!s) return new Date();  
            var ss = (s.split('-'));  
            var y = parseInt(ss[0],10);  
            var m = parseInt(ss[1],10);  
            var d = parseInt(ss[2],10);  
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)){  
                return new Date(d,m-1,y);  
            } else {  
                return new Date();  
            }  
        } 
								
		$(function(){
            $('#dg').datagrid({  
				view: detailview,
				detailFormatter:function(index,row){
					return '<div class="ddv" style="padding:5px 0"></div>';
				},
				onExpandRow: function(index,row){
					var ddv = $(this).datagrid('getRowDetail',index).find('div.ddv');
					ddv.panel({
						border:false,
						cache:false,
						href:'./families/families_missatge_tutor_getdetail.php?id='+row.idmissatges_tutor,
						onLoad:function(){
							$('#dg').datagrid('fixDetailRowHeight',index);
						}
					});
					$('#dg').datagrid('fixDetailRowHeight',index);
				},
				rowStyler:function(index,row){
				    return 'background-color:whitesmoke;color:#333;';
				}
            });  
        });
				
		function doSearch(){ 
			$('#dg').datagrid('load',{  
				data_inici: $('#data_inici').datebox('getValue'),
				data_fi   : $('#data_fi').datebox('getValue')  
			});  
		}
		
		function enviaMissatge(idalumne){
			var url = './families/families_missatge_tutor_nou.php?id='+idalumne;
			
			$('#fm_missatge').form('submit',{
				url: url,
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(data){
					data = eval('('+data+')');
					data.isNewRecord = false;
					$('#dg').datagrid('reload');
					$.messager.alert('Informaci&oacute;','Missatge enviat correctament!','info');
				}
			});
		}
		
		/*function enviaMissatge(){		    
			var id_alumne = $('#idalumne').combogrid('getValue');
			var row       = $('#dg').datagrid('getSelected');
			url = './almat_tree/almat_tree_nou.php';
			
			if (row) {
						$.post(url,{
								idgrups_materies:row.idgrups_materies,
								idalumnes:id_alumne},function(result){  
                            if (result.success){  
                                $.messager.alert('Informaci&oacute;','Alumne introdu&iuml;t correctament!','info');
								$('#dg_al').datagrid('reload');
                            } else { 
							    $.messager.alert('Error','Alumne introdu&iuml;t erroniament!','error');
								 
                                $.messager.show({  
                                    title: 'Error',  
                                    msg: result.msg  
                                });  
                            }  
                        },'json');
			}
        }*/
		
		function onClickRow(index){
				var row = $('#dg').datagrid('getSelected');
				
				if (editIndex != index){
					if (endEditing()){
						$('#dg').datagrid('selectRow', index)
								.datagrid('beginEdit', index);
						editIndex = index;
					} else {
						$('#dg').datagrid('selectRow', editIndex);
					}
				}
				$('#gestio_sancio').linkbutton('enable');
				$('#esborrar_ccc').linkbutton('enable');
				$('#imprimir_ccc').linkbutton('enable');
		}
					
		function endEditing(){
			if (editIndex == undefined){return true}
			if ($('#dg').datagrid('validateRow', editIndex)){
				$('#dg').datagrid('acceptChanges');
				$('#dg').datagrid('endEdit', editIndex);
				
				if (nou_registre) { 
					url = './sortides/sortides_nou.php';
					nou_registre = 0;
				}
				else {
					url = './sortides/sortides_edita.php?id='+$('#dg').datagrid('getRows')[editIndex]['idsortides'];
				}
				afterEdit(url,
						  $('#dg').datagrid('getRows')[editIndex]['data_inici'],
						  $('#dg').datagrid('getRows')[editIndex]['data_fi'],
						  $('#dg').datagrid('getRows')[editIndex]['hora_inici'],
						  $('#dg').datagrid('getRows')[editIndex]['hora_fi'],
						  $('#dg').datagrid('getRows')[editIndex]['lloc'],
						  $('#dg').datagrid('getRows')[editIndex]['descripcio']);
				
				editIndex = undefined;
				return true;
			} else {				
				return false;
			}
		}
				
		function reject(){
		    $('#dg').datagrid('rejectChanges');
			editIndex = undefined;
			$('#dlg_fh').dialog('close');
		}
		
	</script>
        
    <style type="text/css">  
        #fm{  
            margin:0;  
            padding:10px 30px;  
        }  
        .ftitle{  
            font-size:14px;  
            font-weight:bold;  
            padding:5px 0;  
            margin-bottom:10px;  
            border-bottom:1px solid #ccc;  
        }  
        .fitem{  
            margin-bottom:5px;  
        }  
        .fitem label{  
            display:inline-block;  
            width:80px;  
        }  
    </style>
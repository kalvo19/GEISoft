<?php
  session_start();
  $cognoms = isset($_REQUEST['cognoms']) ? $_REQUEST['cognoms'] : '' ;
?>
    <div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">
    <table id="dg" class="easyui-datagrid" style="height:540px" title="Substitucions"  
        data-options="
				singleSelect: true,
                pagination: true,
                rownumbers: true,
				toolbar: '#toolbar',
				url: './prof/prof_getdata.php',
                sortName:'cp.Valor',
                sortOrder:'asc',
				onClickRow: onClickRow
			">
        <thead>
            <tr>
                <th data-options="field:'codi_professor',width:120">ID</th>
                <th field="Valor" width="770" sortable="true">Nom</th> 
            </tr>  
        </thead>  
    </table>

    <div id="toolbar" style="padding:5px;height:auto">  
    <div style="margin-bottom:5px">
    	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newItem()">Nou professor</a>
		&nbsp;
        Cognoms: <input id="cognoms" class="easyui-validatebox" style="width:180px" value="<?=$cognoms?>">
        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="doSearch()"></a>
        <br />
        <a id="substitut_button" href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" plain="true" disabled="true" onclick="substitut()">Professor substitut</a>&nbsp;
        <a id="activa_button" href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" disabled="true" onclick="activa('S')">Professor d'alta</a> &nbsp;
        <a id="desactiva_button" href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" disabled="true" onclick="activa('N')">Professor de baixa</a>&nbsp;
        <a id="horari_button" href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" disabled="true" onclick="verHorario()">Veure horari</a>
    </div>
    </div>
	</div>
    
    <div id="dlg_substitut" class="easyui-dialog" style="width:600px;height:200px;"  
            closed="true" collapsible="true" resizable="true" modal="true" buttons="#dlg_substitut-buttons">
        	<form id="fm" method="post" action="./subst/subst_add.php" novalidate>
            <div class="fitem">
                <label style="width:150px;">Professor al que substitueix</label>&nbsp;
                <input id="professor_substitut" class="easyui-combobox" data-options="
                width: 350,
                valueField: 'id_professor',
                textField: 'Valor',
                url: './subst/subst_prof_getdata.php'
                ">
            </div>
            <div class="fitem">
                <label style="width:150px;">Vols que el professor de baixa segueixi tenint acc&egrave;s als seus grups?</label>
                <input id="acces" name="acces" type="checkbox">
            </div>
        	</form>
    </div>
        
    <div id="dlg_substitut-buttons">
        <table cellpadding="0" cellspacing="0" style="width:100%">  
            <tr>  
                <td>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveSubstitut()">Acceptar</a>
        			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_substitut').dialog('close')">Cancel.lar</a>
                </td>
            </tr>  
        </table>  
    </div> 
    
    <div id="dlg_hor" class="easyui-dialog" style="width:900px;height:600px;"  
            closed="true" maximized="true" maximizable="true" collapsible="true" resizable="true" modal="true" toolbar="#dlg_hor-toolbar">  
    </div>
    
    <div id="dlg_hor-toolbar">  
    <table cellpadding="0" cellspacing="0" style="width:100%">  
        <tr>  
            <td>
                <a href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="javascript:$('#dlg_hor').dialog('refresh')">Recarregar</a>
                <a href="#" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:imprimirHorario()">Imprimir</a>  
                <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="javascript:$('#dlg_hor').dialog('close')">Tancar</a>  
            </td>
        </tr>  
    </table>  
    </div>
    
    <iframe id="fitxer_pdf" scrolling="yes" frameborder="0" style="width:10px;height:10px; visibility:hidden" src=""></iframe>
    
    <script type="text/javascript">  
        var url;
		
		$('#dg').datagrid({
				view: detailview,
				detailFormatter:function(index,row){
					return '<div class="ddv"></div>';
				},				
				onExpandRow: function(index,row){
					var ddv = $(this).datagrid('getRowDetail',index).find('div.ddv');
					ddv.panel({
					border:false,
					cache:true,
					href:'./prof/prof_contacte.php?index='+index+'&idprofessors='+row.id_professor,
					onLoad:function(){
					$('#dg').datagrid('fixDetailRowHeight',index);
					$('#dg').datagrid('selectRow',index);
					$('#dg').datagrid('getRowDetail',index).find('form').form('load',row);
				}
				});
					$('#dg').datagrid('fixDetailRowHeight',index);
				}
		});
		
		$(function(){  
            $('#dg').datagrid({             
				rowStyler:function(index,row){
					if (row.activat=='N'){
						return 'background-color:whitesmoke;color:#CCC;';
					}
				}  
            });  
        });
		
		function onClickRow(index){ 
			var row = $('#dg').datagrid('getSelected');			
			
			$('#professor_substitut').combobox({
				url:'./subst/subst_prof_getdata.php?idprofessors='+row.id_professor,
			});
				
			if (row.activat=='S'){
				$('#activa_button').linkbutton('disable');
				$('#desactiva_button').linkbutton('enable');
				$('#substitut_button').linkbutton('enable');
			}
			if (row.activat=='N'){
				$('#activa_button').linkbutton('enable');
				$('#desactiva_button').linkbutton('disable');
				$('#substitut_button').linkbutton('disable');
			}
			$('#horari_button').linkbutton('enable');
		}
			
        function doSearch(){  
			$('#dg').datagrid('load',{  
				cognoms: $('#cognoms').val()  
			});  
		}
		
		function substitut(){
            var row = $('#dg').datagrid('getSelected');
			$('#dlg_substitut').dialog('open').dialog('setTitle','Dades professor');
            $('#fm').form('clear');
            url = './subst/subst_add.php?id='+row.id_professor;
        }
		
		function verHorario(){  
            var row = $('#dg').datagrid('getSelected');
            editIndex = undefined;
			
			if (row){  
				url = './prmat/prmat_see.php?idprofessors='+row.id_professor+'&curs=<?=$_SESSION['curs_escolar']?>&cursliteral=<?=$_SESSION['curs_escolar_literal']?>';
				$('#dlg_hor').dialog('open').dialog('setTitle','Horari de '+row.Valor);
				$('#dlg_hor').dialog('refresh', url);
            }
        }
		
		function imprimirHorario(){
			var row = $('#dg').datagrid('getSelected');
		
		    url = './prmat/prmat_print.php?idprofessors='+row.id_professor+'&curs=<?=$_SESSION['curs_escolar']?>&cursliteral=<?=$_SESSION['curs_escolar_literal']?>';
		    $('#fitxer_pdf').attr('src', url);
        }
		
		function saveSubstitut(){ 
		  var row                 = $('#dg').datagrid('getSelected');
          var professor_substitut = $('#professor_substitut').combobox('getValue');
		  
		  if (row && professor_substitut){   
			   $.messager.confirm('Confirmar','Procedim amb el substitut?',function(r){  
                    if (r){ 
						  $('#fm').form('submit',{
								onSubmit: function(param){
									param.professor_substitut = professor_substitut;
									param.professor           = row.id_professor;
									return $(fm).form('validate');
								},
								success:function(data){
									$('#dg').datagrid('reload');
									$.messager.alert('Informaci&oacute;','Professor substitut processat correctament!','info');
								}
						   });
	  				}
				});
		   }	
			
		}

		function saveItem(index){
			var row = $('#dg').datagrid('getRows')[index];
			var url = row.isNewRecord ? './prof/prof_nou.php' : './prof/prof_edita.php?id='+row.id_professor;
			
			$('#dg').datagrid('getRowDetail',index).find('form').form('submit',{
				url: url,
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(data){
					data = eval('('+data+')');
					data.isNewRecord = false;
					$('#dg').datagrid('collapseRow',index);
					$('#dg').datagrid('updateRow',{
						index: index,
						row: data
					});
				}
			});
			
			if (url=='./prof/prof_nou.php') {
				cognoms = $('#cognoms').val();
				open1('./prof/prof_grid.php?cognoms='+cognoms,this);
			}
		}
		
		function cancelItem(index){
			var row = $('#dg').datagrid('getRows')[index];
			if (row.isNewRecord){
				$('#dg').datagrid('deleteRow',index);
			} else {
				$('#dg').datagrid('collapseRow',index);
			}
		}
		
		function newItem(){
			$('#dg').datagrid('appendRow',{isNewRecord:true});
			var index = $('#dg').datagrid('getRows').length - 1;
			$('#dg').datagrid('expandRow', index);
			$('#dg').datagrid('selectRow', index);
		}
		
		function activa(op){  
            var row = $('#dg').datagrid('getSelected');  
            if (row){  
                $.messager.confirm('Confirmar','Procedim?',function(r){  
                    if (r){  
                        $.post('./prof/prof_desactiva.php',{op:op,id:row.id_professor},function(result){  
                            if (result.success){  
                                $('#dg').datagrid('reload');    // reload the user data  
                            } else {  
                                $.messager.show({   // show error message  
                                    title: 'Error',  
                                    msg: result.errorMsg  
                                });  
                            }  
                        },'json');  
                    }  
                });  
            }  
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
            margin-bottom:1px;  
        }  
        .fitem label{  
            display: inline-table;
            width:120px;  
        }  
    </style>
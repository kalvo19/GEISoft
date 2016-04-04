    <div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">
    <table id="dg" class="easyui-datagrid" title="Gu&agrave;rdies signades" style="height:540px;"  
			data-options="
				singleSelect: true,
                rownumbers: true,
				toolbar: '#toolbar',
				url: './guard_sign/guard_sign_getdata.php',
				onClickRow: onClickRow
			">    
        <thead>  
            <tr>
                <th field="Valor" width="450" sortable="true">Professor/a</th>
                <th field="Total" width="230">Num. gu&agrave;rdies signades</th>
            </tr>  
        </thead>  
    </table> 
    
    <div id="toolbar" style="padding:5px;height:auto"> 
          <form id="ff" name="ff" method="post">
          Desde <input id="data_inici" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></input>
          Fins a <input id="data_fi" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></input>
          &nbsp;&nbsp;<a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="doSearch()"></a>
          </form>
    </div>
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
		
		
		$('#dg').datagrid({  
			view: detailview,  
			detailFormatter:function(index,row){  
				return '<div style="padding:2px"><table id="ddv-' + index + '"></table></div>';  
			},
			rowStyler:function(index,row){
					if (row.Total>=3){
						return 'background-color:whitesmoke;color:blue;font-weight:bold;font-size:16px;';
					}
			}, 
			onExpandRow: function(index,row){ 
				data_inici = $('#data_inici').datebox('getValue');
				data_fi    = $('#data_fi').datebox('getValue');
				
				if (data_inici == '') {
						data_inici = '01-01-1989';
				}
				if (data_fi == '') {
						data_fi = '01-01-2189';
				}
				
				$('#ddv-'+index).datagrid({  
	                url:'./guard_sign/guard_sign_getdetail.php?idprofessors='+row.idprofessors+'&data_inici='+data_inici+'&data_fi='+data_fi,  
					fitColumns:false,  
					rownumbers:true,  
					loadMsg:'rel.laci&oacute; de gu&agrave;rdies ...',  
					height:'auto',
					columns:[[  
						{field:'data_signat',title:'Data',width:90},
						{field:'hora',title:'Hora',width:85},
						{field:'materia',title:'Mat&egrave;ria',width:370},
						{field:'grup',title:'Grup',width:200}
					]],
					onResize:function(){  
						$('#dg').datagrid('fixDetailRowHeight',index);  
					},  
					onLoadSuccess:function(){  
						setTimeout(function(){  
							$('#dg').datagrid('fixDetailRowHeight',index);  
						},0);  
					}  
				});  
				$('#dg').datagrid('fixDetailRowHeight',index);  
			}  
		});
						
		function doSearch(){					 
			$('#dg').datagrid('load',{  
				data_inici: $('#data_inici').datebox('getValue'),
				data_fi   : $('#data_fi').datebox('getValue')
			});
		}
		
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
		}
					
		function endEditing(){
			if (editIndex == undefined){return true}
			if ($('#dg').datagrid('validateRow', editIndex)){
				$('#dg').datagrid('acceptChanges');
				$('#dg').datagrid('endEdit', editIndex);
								
				editIndex = undefined;
				return true;
			} else {				
				return false;
			}
		}
				
		function reject(){
		    $('#dg').datagrid('rejectChanges');
			editIndex = undefined;
		}
		
	</script>
        
    <style type="text/css">  
        #fm{  
            margin:0;  
            padding:2px 3px;  
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
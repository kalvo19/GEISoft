<?php
	$idalumnes = $_REQUEST['idalumnes'];
	$grup      = $_REQUEST['grup'];
?>

<style type="text/css">
.bheader {
    background-color: #DDDDDD;
    border-radius: 1px 1px 0 0;
    padding: 5px 0;
}
.bbody {
    color: #000;
    overflow: hidden;
    padding-bottom: 2px;
	padding-left:5px;

    
}
.bbody h2, .info, .error {
    margin: 2px 0;
}
.step2, .error {
    display: none;
}
.error {
    font-size: 14px;
    font-weight: bold;
    color: red;
}
.info {
    font-size: 14px;
}
.etiqueta {
    margin: 0 5px;
}
.formulari {
    border: 1px solid #CCCCCC;
    border-radius: 2px;
    padding: 2px 2px;
    width: 400px;
}
.jcrop-holder {
    display: inline-block;
}
.enviar {
    background: #e3e3e3;
    border: 1px solid #bbb;
    border-radius: 3px;
    -webkit-box-shadow: inset 0 0 1px 1px #f6f6f6;
    box-shadow: inset 0 0 1px 1px #f6f6f6;
    color: #333;
    font: bold 12px/1 "helvetica neue", helvetica, arial, sans-serif;
    padding: 2px 0 2px;
    text-align: center;
    text-shadow: 0 1px 0 #fff;
    width: 150px;
}
</style>
	 
<div class="bbody">

    <!-- upload form -->
    <form id="frm_uf" enctype="multipart/form-data" method="post" action="./tutor/upload.php" onsubmit="return checkForm()">
        <!-- hidden crop params -->
        <input type="hidden" id="idalumnes" name="idalumnes" value="<?=$idalumnes?>" />
        
        <input type="hidden" id="x1" name="x1" />
        <input type="hidden" id="y1" name="y1" />
        <input type="hidden" id="x2" name="x2" />
        <input type="hidden" id="y2" name="y2" />

        <h5>Pas 1: Sisplau, selecciona la imatge</h5>
        <div><input class="formulari" type="file" name="image_file" id="image_file" onchange="fileSelectHandler()" /></div>

        <div class="error"></div>

        <div class="step2">
            <h5>Pas 2: Sisplau, selecciona l'area de retall</h5>
            
            <table>
            <tr>
                <td>
                    <img id="preview" style="border:3px dashed #666666;" />
                </td>
                <th valign="bottom">
                    Resultat
                    <div id="resultPhotoDiv" style="border:1px dashed #666666; width:80px; height:80px">
                    </div>
                </th>
            </tr>
            
            <tr>
            	<td align="right">
                    <input class="enviar" type="button" value="Esborrar foto" onClick="javascript:esborrarFoto()" />&nbsp;
                	<input class="enviar" type="button" value="Pujar foto" onClick="javascript:pujarFoto(<?=$grup?>)" />
                </td>
                <td></td>
            </tr>
            </table>
			<!--<input type="submit" value="Upload" />-->

            <div class="info">
                <label class="etiqueta">Mida fitxer</label> <input class="formulari" type="text" id="filesize" name="filesize" />
                <label class="etiqueta">Tipus</label> <input class="formulari" type="text" id="filetype" name="filetype" /><br>
                <label class="etiqueta">Dimensi&oacute; imatge</label> <input class="formulari" type="text" id="filedim" name="filedim" /><br>
                <label class="etiqueta">Amplada</label> <input class="formulari" type="text" id="w" name="w" />
                <label class="etiqueta">Al&ccedil;ada</label> <input class="formulari" type="text" id="h" name="h" />
            </div>

         </div>
    </form>
</div>


<script type="text/javascript">
    function pujarFoto(grup) {
		$('#frm_uf').form('submit',{  
                url: './tutor/upload.php', 
                onSubmit: function(){  
                    return $(this).form('validate');  
                },  
                success: function(result){
				    $('#resultPhotoDiv').html(result);
					//open1('./tutor/tutor_alum_grid.php?grup='+grup,this);
				    
                    //var result = eval('('+result+')');                    
                }  
            });  
	}
	
	function esborrarFoto() {
		url = './tutor/esborra_foto.php?id=<?=$idalumnes?>';
		$.post(url,{},function(result){  
            if (result.success){ 
				 $('#resultPhotoDiv').html(''); 
            } else {  
               $.messager.show({     
               title: 'Error',  
               msg: result.msg  
               });  
               }  
             },'json');
	}
</script>
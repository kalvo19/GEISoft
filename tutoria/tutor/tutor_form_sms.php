<div class="easyui-panel" title="SMS" style="padding-left:30px;width:505px; height:500px; filter:alpha(opacity=75);-moz-opacity:.75;opacity:.75;">
    
<form id="frm_sms" enctype="application/x-www-form-urlencoded" method="post">
<input class="easyui-validatebox" type="hidden" name="AUTH_USER" id="AUTH_USER" value="iescopernic" data-options="required:true">
<input class="easyui-validatebox" type="hidden" name="AUTH_PWD" id="AUTH_PWD" value="euldlmdcnnqa" data-options="required:true">
<input class="easyui-validatebox" type="hidden" name="account" id="account" value="937807517" class="form-text" data-options="required:true">

<label>Persones a enviar SMS</label><br />
<textarea readonly class="easyui-validatebox" name="destinataris" id="destinataris" class="form-text" rows="14" cols="50">
</textarea>


<input class="easyui-validatebox" type="hidden" name="from" id="from" value="INS" class="form-text">
<input class="easyui-validatebox" type="hidden" name="when" id="when" value="2013-08-30 12:20:11" class="form-text">
<input type="hidden" name="command" value="Sms_Send_Bulk" class="form-text" id="command">


<label>Plantilles de missatges SMS predefinides</label><br />
<input id="textos_sms" class="easyui-combobox" data-options="
        valueField: 'descripcio',
        textField: 'nom',
        url: './textos_sms/textos_sms_getdata.php',
        onSelect: function(rec){
            $('#contingut').val($('#textos_sms').combobox('getValue'));
        }">

<br />

<label>Missatge (m&agrave;xim 140 caracters)&nbsp;</label><br />
<textarea class="easyui-validatebox" name="contingut" id="contingut" class="form-text" rows="4" cols="50" style="overflow:hidden" onKeyDown="limitText(this.form.contingut,this.form.countdown,140);" 
onKeyUp="limitText(this.form.contingut,this.form.countdown,140);">
</textarea>
<br />
<label style="color:#990000">Et queden&nbsp;<input readonly type="text" name="countdown" size="3" value="140">&nbsp;caracters per utilitzar.</label>
<br />

<input style="font-size:24px; background-color:#FF0000; color:#FFFFFF" class="enviar" type="button" value="Enviar SMS" onClick="javascript:enviaSMS()" />
      
</form>

</div>

<div id="resultSMSDiv" style="position:absolute; left:540px; width:470px; color: #CC0000; font:16px bolder Geneva, Arial, Helvetica, sans-serif;">
 
</div>

<script type="text/javascript">
    function enviaSMS() {
		$('#frm_sms').form('submit',{  
                url: './tutor/tutor_envia_sms.php', 
                onSubmit: function(){  
                    return $(this).form('validate');  
                },  
                success: function(result){
				    $('#resultSMSDiv').html(result);
                }  
            });  
	}
	
	function limitText(limitField, limitCount, limitNum) {
		if (limitField.value.length > limitNum) {
			limitField.value = limitField.value.substring(0, limitNum);
		} else {
			limitCount.value = limitNum - limitField.value.length;
		}
	}
</script>
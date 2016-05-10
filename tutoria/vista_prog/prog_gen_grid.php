<?php
  include_once('../bbdd/connect.php');
  include_once('../func/constants.php');
  include_once('../func/generic.php');
  mysql_query("SET NAMES 'utf8'");
    
  if ( isset($_REQUEST['idprofessor']) && ($_REQUEST['idprofessor']==0) ) {
  	$idprofessor = 0;
  }
  else if ( isset($_REQUEST['idprofessor']) ) {
    $idprofessor = $_REQUEST['idprofessor'];
  }
  if (! isset($idprofessor)) {
    $idprofessor = 0;
  }
 
?>

<div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">
    <table id="dg" class="easyui-datagrid" title="Llistat programacions m&ograve;dul" style="height:auto;"
            data-options="
            singleSelect: true,
            pagination: false,
            rownumbers: true,
            toolbar: '#toolbar',
            url: './prog_gen/prog_gen_getdata.php',
            onClickRow: getProgramacioGenerals">    
        <thead>  
            <tr>
                <th field="nom_document" width="240" sortable="true">Document</th>
                <th field="nom_curs" width="160" sortable="false">Curs</th>
                <th field="data_creacio" width="160" sortable="true">Data creaci&oacute;</th>
                <th field="Nom" width="160" sortable="true">Periode escolar</th>
                <th field="aprovat" width="100" sortable="true">Estat</th>
            </tr>  
        </thead>  
    </table> 

    <div id="toolbar" style="height:auto; padding-top:7px; padding-bottom:7px;">
        <p>
            <label for="pla_estudis">&nbsp;Pl&agrave; d'estudi</label>
            <select id="pla_estudis" name="pla_estudis" style="width:610px">
            </select>
        </p>
        <p>
            <label for="moduls">&nbsp;M&ograve;dul</label>
            <select id="moduls" name="moduls" class="easyui-combogrid" style="width:610px">
            </select>
            <label for="curs">&nbsp;Curs</label>
            <select id="curs" name="curs" class="easyui-combogrid" style="width:110px">
            </select>
            <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="doSearch(<?php echo $idprofessor; ?>)">Cercar Programacions</a>
        </p>
        <p>
            <a id="add_button" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="gestioUFs()">Crear Programacio M&ograve;dul</a>
            <label for="menu-gestio">Gestionar: </label>
            <a href="#" id="prevButton" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-print', disabled: true">Visualitzar</a>
            <a href="#" id="modButton" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-cut', disabled: true">Modificar</a>
            <a href="#" id="delButton" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-cancel', disabled: true">Eliminar</a>
        </p>
    </div>
</div>

<div id="win-add" class="easyui-window" title="Nova programaci&oacute;" style="width:650px;height:400px"
        data-options="modal:true, closed: true">
    <div data-options="region:'north'">
        <div id="dlg_win" class="easyui-panel" style="width:auto;height:auto;">
            <p>
                <label for="pla_estudis_prog">&nbsp;Pl&agrave; d'estudi</label>
                <select id="pla_estudis_prog" class="easyui-combogrid prog_fields" name="pla_estudis" style="width:410px">
                </select>
            </p>
            <p>
                <label for="modul_prog">&nbsp;M&ograve;dul</label>
                <select id="modul_prog" class="easyui-combogrid prog_fields" name="moduls" style="width:410px">
                </select>
            </p>
            <p>
                <label for="curs_prog">&nbsp;Curs</label>
                <select id="curs_prog" class="easyui-combogrid prog_fields" name="curs" style="width:410px">
                </select>
            </p>
        </div>
    </div>
     <div data-options="region:'center'">
        <a href="#" id="makeButton" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-ok'">Crear</a>
        <a href="#" id="backButton" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-cancel'">Endarrere</a>   
     </div>
</div>

<div id="win-delete" class="easyui-window" title="Eliminar programaci&oacute;" style="width:350px;height:100px"
        data-options="modal:true, closed: true">
    <div data-options="region:'north'">
        Segur que desitja eliminar aquesta programaci&oacute;?
    </div>
     <div data-options="region:'center'">
        <a href="#" id="acceptButton" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-ok'">Acceptar</a>
        <a href="#" id="cancelButton" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-cancel'">Cancelar</a>   
     </div>
</div>

<script type="text/javascript">  
    var programacioModul = new Object();
    var llistaProgramacioModul = new Array(programacioModul);
    
    $("#pla_estudis").combogrid({delay: 500,
        mode: 'remote',
        url: './prof/prof_getpla.php?idprofessors=<?=$idprofessor?>',
        method: 'get',
        value: 'idplans_estudis',
        idField: 'idplans_estudis',
        textField: 'Nom_plan_estudis',
        fitColumns: true,
        columns: [[
            {field:'Nom_plan_estudis',title:'Nom',width:440}
        ]],
        onClickRow: getPlaEstudi
    });
    
    $("#pla_estudis").combogrid("setValue", "Selecciona un Pla d'estudi");
                                
    function getPlaEstudi() {
        var codiPlaEstudi = $("#pla_estudis").combogrid("getValue");
        programacioModul.pla_estudi = codiPlaEstudi;
        
        $("#moduls").combogrid({delay: 500,
            mode: 'remote',
            url: './pe/pe_getmodul.php?idplan_estudi=' + codiPlaEstudi,
            method: 'get',
            value: 'idmoduls',
            idField: 'idmoduls',
            textField: 'nom_modul',
            fitColumns: true,
            columns: [[
                {field:'nom_modul',title:'Nom',width:110}
            ]],
            onClickRow: getModul
        });
        
        $("#moduls").combogrid("setValue", "Selecciona un Mòdul");
        
        $("#curs").combogrid({delay: 500,
            mode: 'remote',
            url: './curs/curs_getdata.php?',
            method: 'get',
            value: 'idcurs',
            idField: 'idcurs',
            textField: 'nom_curs',
            fitColumns: true,
            columns: [[
                {field:'nom_curs',title:'Nom',width:440}
            ]],
            onClickRow: getCurs
        });
        
        $("#curs").combogrid("setValue", "Curs");
    }
    
    function getModul() {
        var codiModul = $("#moduls").combogrid("getValue");
        programacioModul.modul = codiModul;
        
        //enabledAddButton();
    }
    
    function getCurs() {
        var codiCurs = $("#curs").combogrid("getValue");
        programacioModul.curs = codiCurs;
        
        //enabledAddButton();
    }
    
    function doSearch(idprofessor) {
        console.log($('#pla_estudis').combogrid("getValue"));
        console.log($('#moduls').combogrid("getValue"));
        console.log($('#curs').combogrid("getValue"));
        $('#dg').datagrid('load',{  
                idprofessors : idprofessor,
                idplans_estudis : $('#pla_estudis').combogrid("getValue"),
                idmoduls : $('#moduls').combogrid('getValue'),
                idcurs  : $('#curs').combogrid('getValue')
        });
    }
    
    /*function enabledAddButton() {
        if (programacioModul.modul && programacioModul.curs) {
            $("#add_button").linkbutton({
                disabled: false
            });
        }   
    }*/
    
    function getProgramacioGenerals() {
        var dadesProgramacio = $("#dg").datagrid('getSelected');
        var idprogramacio_general = dadesProgramacio.idprogramacio_general;
        enabledMenuGestionar(idprogramacio_general);
    }
    
    function enabledMenuGestionar(idprogramacio) {
        $("#prevButton").linkbutton({disabled: false}); 
        $("#modButton").linkbutton({disabled: false});
        $("#delButton").linkbutton({disabled: false});
        
        $("#modButton").bind('click', function() {
            var url = "./vista_prog/prog_gen_form.php?idprogramacio=" + idprogramacio;
            open1(url, this);
        });
        
        $('#delButton').bind('click', function(){
            eliminarProgramacio(idprogramacio);
        });
    }
    
    function gestioUFs() {
        $("#win-add").window('open');
        $.post("./prog_gen/prog_gen_getFillFields.php", 
        {
            idpla_estudi: programacioModul.pla_estudi,
            idmodul: programacioModul.modul,
            idcurs: programacioModul.curs
        }, function(data) {
            var dadesCamps = JSON.parse(data);
            omplirCampsModalWin(dadesCamps);
        });
    }
    
    function omplirCampsModalWin(dadesCamps) {
        var camps = document.getElementsByClassName('prog_fields');
        for (i = 0; i < dadesCamps.length; i++) {
            for (camp in dadesCamps[i]) {
                $(camps[i]).combogrid("setValue", dadesCamps[i][camp]);
            }   
        }
    }
    
    function eliminarProgramacio(idprogramacio) {
        $("#win-delete").window('open');
        $('#acceptButton').bind('click', function(){
            $.post("./prog_gen/prog_gen_eliminar.php", {idprogramacio_general: idprogramacio});
            $("#win").window('close');
        });

        $('#cancelButton').bind('click', function(){
            $("#win").window('close');
        });
    }
</script>
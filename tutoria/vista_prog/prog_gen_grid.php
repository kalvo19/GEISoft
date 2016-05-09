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
            <a id="add_button" href="javascript:void(0)" class="easyui-linkbutton" disabled="true" data-options="iconCls:'icon-add',plain:true" onclick="gestioUFs(2)">Crear Programacio M&ograve;dul</a>
            <label for="menu-gestio">Gestionar: </label>
            <a href="#" id="prevButton" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-print', disabled: true">Visualitzar</a>
            <a href="#" id="modButton" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-cut', disabled: true">Modificar</a>
            <a href="#" id="delButton" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-cancel', disabled: true">Eliminar</a>
        </p>
    </div>
</div>

<div id="win" class="easyui-window" title="Eliminar programaci&oacute;" style="width:350px;height:100px"
        data-options="iconCls:'icon-cancel',modal:true, closed: true">
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
    }
    
    function getModul() {
        var codiModul = $("#moduls").combogrid("getValue");
        programacioModul.modul = codiModul;
        
        enabledAddButton();
    }
    
    function getCurs() {
        var codiCurs = $("#curs").combogrid("getValue");
        programacioModul.curs = codiCurs;
        
        enabledAddButton();
    }
    
    function doSearch(idprofessor) {
        $('#dg').datagrid('load',{  
                idprofessors : idprofessor,
                idplans_estudis : $('#pla_estudis').combogrid("getValue"),
                idmoduls : $('#moduls').combogrid('getValue'),
                idcurs  : $('#curs').combogrid('getValue')
        });
    }
    
    function enabledAddButton() {
        if (programacioModul.modul && programacioModul.curs) {
            $("#add_button").linkbutton({
                disabled: false
            });
        }   
    }
    
    function getProgramacioGenerals() {
        var dadesProgramacio = $("#dg").datagrid('getSelected');
        var idprogramacio_general = dadesProgramacio.idprogramacio_general;
        enabledMenuGestionar(idprogramacio_general);
    }
    
    function enabledMenuGestionar(idprogramacio) {
        $("#prevButton").linkbutton({disabled: false}); 
        $("#modButton").linkbutton({disabled: false});
        $("#delButton").linkbutton({disabled: false});
        $('#delButton').bind('click', function(){
            eliminarProgramacio(idprogramacio);
        });
    }
    
    function eliminarProgramacio(idprogramacio) {
        $("#win").window('open');
        $('#acceptButton').bind('click', function(){
            $.post("./prog_gen/prog_gen_eliminar.php", {idprogramacio_general: idprogramacio});
            $("#win").window('close');
        });

        $('#cancelButton').bind('click', function(){
            $("#win").window('close');
        });
    }
</script>
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
            url: './prog_gen/prog_gen_getdata_coord.php'",
            onClickRow: getProgramacioGeneral
            >    
        <thead>  
            <tr>
                <th field="idprogramacio_general" width="240" sortable="true">Document</th>
                <th field="Valor" width="320" sortable="true">Mòdul</th>
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
        <div class="document-manegement">
            <div class="menegement-document-buttons">
                <label for="menu-gestio">Gestionar: </label>
                <a href="#" id="prevButton" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-print', disabled: true">Visualitzar</a>
            </div>
            <div>
                <a id="approvedButton" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-send',plain:true, disabled: true">Aprovar</a>
                <a id="declinedButton" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-send',plain:true, disabled: true">Declinar</a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
     //Objecte que conté els filtres de cerca de la programació comuna.
     var programacioModul = new Object();
    
    $("#pla_estudis").combogrid({delay: 500,
        mode: 'remote',
        url: './prof/prof_getpla.php?idprofessors=<?=$idprofessor?>&idcarrecs=2',
        method: 'post',
        value: 'idplans_estudis',
        idField: 'idplans_estudis',
        textField: 'Nom_plan_estudis',
        fitColumns: true,
        columns: [[
            {field:'Nom_plan_estudis',title:'Nom',width:440}
        ]],
        onClickRow: getPlaEstudi
    });
    
    //Text per defecte que tindrà el selector dels plans d'estudi.
    $("#pla_estudis").combogrid("setValue", "Selecciona un Pla d'estudi");
    
    /**
     * Recull el valor de la selecció que el usuari ha fet al camp dels plans d'estudi, afegeix
     * el valor al objecte que guarda les dades de filtre de cerca i posteriorment prepara el 
     * segon selector amb els mòduls que corresponen al pla d'estudi marcat i el tercer selector amb tots 
     * els cursos disponibles (Primer Curs, Segon Curs).
     */
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
        
        //Text per defecte que tindrà el selector dels mòduls
        $("#moduls").combogrid("setValue", "Selecciona Mòdul");
        programacioModul.modul = false;

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
        
        //Text per defecte que tindrà el selsector dels cursos.
        $("#curs").combogrid("setValue", "Curs");
    }
    
     /**
     * Recull el modul seleccionat i ho afegeix a l'objecte que recull les dades del filtre de 
     * cerca.
     */
    function getModul() {
        var codiModul = $("#moduls").combogrid("getValue");
        programacioModul.modul = codiModul;
    }
    
    /**
     * Recull el curs seleccionat i ho afegeix a l'objecte que recull les dades del filtre de 
     * cerca.
     */
    function getCurs() {
        var codiCurs = $("#curs").combogrid("getValue");
        programacioModul.curs = codiCurs;
    }
    
    function doSearch(idprofessor) {
        disabledMenuGestionar();
        $('#dg').datagrid('load',{  
                idprofessors : idprofessor,
                idplans_estudis : $('#pla_estudis').combogrid("getValue"),
                idmoduls : $('#moduls').combogrid('getValue'),
                idcurs  : $('#curs').combogrid('getValue')
        });
    }
    
    /**
     * Funció que s'activa quan el event 'onSelectRow' del datagrid es disparà. Aquest event actua quan el usuari selecciona 
     * una de les programacions mostrades al datagrid. Recull el valor id de la programació seleccionada i posteriorment 
     * habilita els botons que permeten la gestió de la programació. 
     */
    function getProgramacioGeneral(idprofessor) {
        dadesProgramacioSeleccionada = $("#dg").datagrid('getSelected');
        idprogramacio_general = dadesProgramacioSeleccionada.idprogramacio_general;
        enabledMenuGestionar(idprogramacio_general);
    }
    
    /*
     * 
     * @param {integer} idprogramacio
     * Habilita els botons que permeten gestionar una programació (Modificar, eliminar, Visualitzar i Importar).
     * I a més carregà les funcions que s'activaran en el moment de prémer. 
     */
    function enabledMenuGestionar(idprogramacio) {
        $("#prevButton").linkbutton({disabled: false}); 
        $("#sendButton").unbind();
        $("#sendButton").bind('click', function() {
            enviarRevisio(idprogramacio);
        });
     
    }
    
    function disabledMenuGestionar() {
        $("#sendButton").linkbutton({disabled : true});
        $("#importButton").linkbutton({disabled: true});
    }
    
    function enviarRevisio() {
        var url = "./prog_gen/prog_gen_send"
        $.post();
    }
</script>

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

<div class="easyui-tabs">
    <div title="Programacions Comunes">
        <div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">
            <table id="dg" class="easyui-datagrid" title="PROGRAMACIONS DIDÀCTIQUES GENERALS PER A REVISAR" style="height:auto;"
                    data-options="
                    singleSelect: true,
                    fitColumns: true,
                    pagination: false,
                    rownumbers: true,
                    toolbar: '#toolbar',
                    url: './prog_gen/prog_gen_getdata_coord.php',
                    onClickRow: getProgramacioGeneral
                    ">    
                <thead>  
                    <tr>
                        <th field="nom_document" width="200" sortable="false">Document</th>
                        <th field="Valor" width="220" sortable="false">Autor</th>
                        <th field="data_creacio" width="120" sortable="false">Data Creacio</th>
                        <th field="nom_modul" width="280" sortable="false">Mòdul</th>
                        <th field="nom_curs" width="110" sortable="false">Curs</th>
                        <th field="Nom" width="110" sortable="false">Periode escolar</th>
                    </tr>  
                </thead>  
            </table> 

            <div id="toolbar" style="height:auto; padding-top:7px; padding-bottom:7px;">
                <div class="north_toolbar">
                    <div class="form_select">
                        <label for="pla_estudis">Pla d'estudi</label>
                        <select id="pla_estudis" name="pla_estudis" style="width:410px">
                        </select>
                    </div>
                    <div id="select_moduls">
                        <div class="form_select">
                            <label for="moduls">M&ograve;dul</label>
                            <select id="moduls" name="moduls" class="easyui-combogrid" style="width:410px">
                            </select>
                        </div>
                    </div>
                </div>
                <div class="center_toolbar">
                    <div class="left_corner_center_toolbar">
                        <div class="form_select">
                            <label for="curs">Curs</label>
                            <select id="curs" name="curs" class="easyui-combogrid" style="width:110px">
                            </select>
                        </div>
                    </div>
                    <div class="right_corner_toolbar">       
                        <div class="preview_toolbar">
                            <label for="menu-gestio">&nbsp;Visualitzar: </label>
                            <a href="#" id="prevButtonPDF" class="easyui-linkbutton" onclick="visualitzarProgramacio('pdf')" data-options="plain: true, iconCls: 'icon-pdf', disabled: true, text: 'PDF'">Descarregar PDF</a>
                            <a href="#" id="prevButtonODT" class="easyui-linkbutton" onclick="visualitzarProgramacio('odt')" data-options="plain: true, iconCls: 'icon-odt', disabled: true, text: 'ODT'">Descarregar ODT</a>
                        </div>
                        <div class="manegement_toolbar">
                            <label for="menu-gestio">&nbsp;Gestionar: </label>
                            <a id="approvedButton" href="javascript:void(0)" class="easyui-linkbutton" onclick="enviarRevisio(true, <?php echo $idprofessor; ?>)" data-options="iconCls:'icon-send',plain:true, disabled: true">Aprovar</a>
                            <a id="declinedButton" href="javascript:void(0)" class="easyui-linkbutton" onclick="enviarRevisio(false, <?php echo $idprofessor; ?>)" data-options="iconCls:'icon-cancel',plain:true, disabled: true">Declinar</a>
                        </div>
                    </div>
                </div>
                <div class="south_toolbar">
                    <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="doSearch(<?php echo $idprofessor; ?>)">Cercar Programacions</a>
                </div>
            </div>
        </div>
    </div>
    <div title="Programacions Aula"></div>
</div>

<div id="win-send" class="easyui-window" title="Enviar programaci&oacute;" style="width:400px;height:auto" data-options="modal:true, closed:true">
    <div data-options="region:'north'">
    </div>
    <div data-options="region:'south'">
        <a href="#" id="sendOk" class="easyui-linkbutton" onclick="tancarFinestresModal(<?php echo $idprofessor; ?>)" data-options="iconCls:'icon-ok'">Acceptar</a>
    </div>
</div>

<script type="text/javascript">
    //Objecte que conté els filtres de cerca de la programació comuna.
    var programacioModul = new Object();
     
    //Objecte que conté la programació seleccionada a la llista.
    var dadesProgramacioSeleccionada = new Object();
    
    /**
     * Selecciona mitjançant una consulta AJAX els plans d'estudis que el professor connectat
     * té assignats segons els 'grups' dels que es fa càrrec.
     */
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
        
        doSearch(<?=$idprofessor?>);
    }
    
     /**
     * Recull el modul seleccionat i ho afegeix a l'objecte que recull les dades del filtre de 
     * cerca.
     */
    function getModul() {
        var codiModul = $("#moduls").combogrid("getValue");
        programacioModul.modul = codiModul;
        
        doSearch(<?=$idprofessor?>);
    }
    
    /**
     * Recull el curs seleccionat i ho afegeix a l'objecte que recull les dades del filtre de 
     * cerca.
     */
    function getCurs() {
        var codiCurs = $("#curs").combogrid("getValue");
        programacioModul.curs = codiCurs;
        
        doSearch(<?=$idprofessor?>);
    }
    
    /**
     * Fa una consulta AJAX a la url especificada en el atribut 'data-options' del datagrid, passant com a paràmetres,
     * la id del professor, la id del pla d'estudi, la id del mòdul i la id del curs que el usuari ha seleccionat en els 
     * selectors. Aquesta consulta retorna en forma de files tots les programacions que coincideixen amb el filtre 
     * especificat.
     * 
     * @param {integer} idprofessor
     */
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
    function getProgramacioGeneral() {
        dadesProgramacioSeleccionada = $("#dg").datagrid('getSelected');
        console.log(dadesProgramacioSeleccionada);
        idprogramacio_general = dadesProgramacioSeleccionada.idprogramacio_general;
        enabledMenuGestionar(idprogramacio_general);
    }
    
    /*
     * Habilita els botons que permeten gestionar una programació (Aprovar, declinar i visualitzar).
     * I a més carregà les funcions que s'activaran en el moment de prémer cadascún dels botons. 
     * 
     * @param {String} idprogramacio
     */
    function enabledMenuGestionar(idprogramacio) {
        $("#prevButtonPDF").linkbutton({disabled: false}); 
        $("#prevButtonODT").linkbutton({disabled: false}); 
        $("#approvedButton").linkbutton({disabled: false}); 
        $("#declinedButton").linkbutton({disabled: false}); 
        
        afegirFuncionsMenuGestionar(idprogramacio); 
    }
    
    /*
     * Assigna els events corresponetns als botons de 'Aprovar' i 'Declinar'.
     */
    function afegirFuncionsMenuGestionar(idprogramacio) {
        $("#aprovedButton").unbind();
        $("#approvedButton").bind('click', function() {
            enviarRevisio(idprogramacio);
        });
        
        $("#declinedButton").unbind();
        $("#declinedButton").bind('click', function() {
            enviarRevisio(idprogramacio);
        });
    }
    
    /**
     * Deshabilita el botons que permeten la gestió de la programació.
     */
    function disabledMenuGestionar() {
        $("#prevButtonPDF").linkbutton({disabled: true}); 
        $("#prevButtonODT").linkbutton({disabled: true}); 
        $("#approvedButton").linkbutton({disabled : true});
        $("#declinedButton").linkbutton({disabled: true});
    }
    
    /**
     * Si el paràmetre 'aprovat' es true la programació s'enviarà per a que dugui a térme el vist-i-plau de la 
     * programació que es fa càrrec de l'usuari adminisrador. Sino, la 
     * programació queda declinada i queda registrat a la base de dades en ambdos casos mitjançant la petició POST
     * amb AJAX.
     * 
     * @param {boolean} revisat
     * @param {String} idprofessor
     */
    function enviarRevisio(revisat, idprofessor) {
        var idprogramacio = dadesProgramacioSeleccionada.idprogramacio_general;
        var url = "./prog_gen/prog_gen_send.php";
        console.log(revisat, idprofessor, idprogramacio);
        $.post(url, {
            idprogramacio: idprogramacio,
            revisat: revisat,
            idprofessor: idprofessor
        });
        
        $("#aprovedButton").unbind();
        $("#declinedButton").unbind();
        sendModalWin(revisat);
    }
    
    /**
    * Mostra un missatge informant a l'usuari sobre l'exit de la petició.
    * 
    * Si el paràmetre 'aprovat' es:
    * 
    * true --> La programació s'ha enviat correctament per a la seva aprovació
    * false --> La programació ha estat declinada, aquesta quedarà reflectida al estat del document.
    * 
    * @param (boolean) revisat
    */
    function sendModalWin(revisat) {
        $("#win-send").window('open');
        var element = $("#win-send div:first-child");
        if (revisat) {
            $(element).html("<p>La programació s'ha enviat correctament per a la seva aprovació</p>");
        } else {
            $(element).html("<p>La programació ha estat declinada, aquesta quedarà reflectida al estat del document.</p>");
        }
    }
    
    /**
     * Tanca les finestres modals que estiguin obertes.
     */
    function tancarFinestresModal(idprofessor) {
        $("#win-send").window('close');
        
        doSearch(idprofessor);
    }
    
    /**
    * S'activa quan el usuari prem el botó de 'Descarregar PDF' o 'Descarregar ODT'. Genera el document en el format
    * passat com a paràmetre a la funció (odt, pdf). 
    *
    * @param {String} format
    */
    function visualitzarProgramacio(format) {
        $.post("./prog_gen/prog_gen_getdocument.php", 
        {
            idprogramacio_general: dadesProgramacioSeleccionada.idprogramacio_general,
            format: format
        }, function(nom_fitxer) {
            descarregarDocument(nom_fitxer);
        });
    }
    
    /**
    * Descarregà el document generat del directori temporal de l'aplicació '/docsodt/temp/programacions_comunes' a 
    * l'ordinador de l'usuari.
    * 
    * @param {String} nom_fitxer
    */
    function descarregarDocument(nom_fitxer) {
        var url = "./docsodt/temp/programacions_comunes/" + nom_fitxer;
        document.location.href = url;
    }
    
</script>

<style type="text/css">
    .document-manegement {
        display: flex;
        justify-content: space-around;
    }
    
    .north_toolbar {
        display: flex;
        justify-content: space-between;
        align-content: center;
        margin: 15px 5px;
    }
    
    #select_moduls {
        text-align: right;
    }
    
    .center_toolbar {
        display: flex;
        justify-content: flex-start;
        align-content: center;
        margin: 15px 5px;
    }
    
    .north_toolbar > div {
        width: 50%;
    }
    
    .south_toolbar {
        display: flex;
        justify-content: flex-end;
        margin: 0 5px;
    }
    
    .left_corner_center_toolbar {
        width: 30%;
    }
    
    .right_corner_toolbar {
        width: 70%;
        display: flex;
        justify-content: space-between;
    }
    
    .icon-pdf {
         background: url('css/icons/pdf.png') no-repeat center center;
    }
    
    .icon-send {
         background: url('css/icons/send.png') no-repeat center center;
    }
    
    .icon-odt {
        background: url('css/icons/icon-odt.png') no-repeat center center;
    }
</style>
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
    <table id="dg" class="easyui-datagrid" title="GESTIÓ DE PROGRAMACIONS DIDÀCTIQUES GENERALS" style="height:auto;"
            data-options="
            singleSelect: true,
            pagination: false,
            fitColumns: true,
            rownumbers: true,
            toolbar: '#toolbar',
            url: './prog_gen/prog_gen_getdata.php',
            onClickRow: getProgramacioGeneral">    
        <thead>  
            <tr>
                <th field="nom_document" width="200" sortable="true">Document</th>
                <th field="nom_modul" width="320" sortable="true">Mòdul</th>
                <th field="nom_curs" width="90" sortable="false">Curs</th>
                <th field="data_creacio" width="90" sortable="true">Data creaci&oacute;</th>
                <th field="Nom" width="100" sortable="true">Periode escolar</th>
                <th field="estat" width="130" sortable="true">Estat</th>
            </tr>  
        </thead>  
    </table> 

    <div id="toolbar" style="height:auto; padding-top:7px; padding-bottom:7px;">
        <div class="north_toolbar">
            <div id="select_pla_estudis">
                <label for="pla_estudis">Pla d'estudi</label>
                <select id="pla_estudis" name="pla_estudis" style="width:410px">
                </select>
            </div>
            <div id="select_moduls">
                <label for="moduls">M&ograve;dul</label>
                <select id="moduls" name="moduls" class="easyui-combogrid" style="width:410px">
                </select>
            </div>
        </div>
        <div class="center_toolbar">  
            <div id="select_curs">
                <label for="curs">Curs</label>
                <select id="curs" name="curs" class="easyui-combogrid" style="width:110px">
                </select>
            </div>
            <div class="menegement-document-buttons">
                <label for="menu-gestio">Gestionar: </label>
                <a href="#" id="prevButtonPDF" class="easyui-linkbutton" onclick="visualitzarProgramacio('pdf')" data-options="iconCls: 'icon-pdf', plain: true, disabled: true, text: 'PDF'">Descarregar PDF</a>
                <a href="#" id="prevButtonODT" class="easyui-linkbutton" onclick="visualitzarProgramacio('odt')" data-options="iconCls: 'icon-odt', plain: true, disabled: true, text: 'ODT'">Descarregar ODT</a>
                <a href="#" id="modButton" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-cut', disabled: true" onclick="modificarProgramacio(<?php echo $idprofessor; ?>)">Modificar</a>
                <a href="#" id="delButton" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-cancel', disabled: true">Eliminar</a>
            </div>
            <div id="search_button">
                <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="doSearch(<?php echo $idprofessor; ?>)">Cercar Programacions</a>
            </div>
        </div>
        <div class="document-manegement">
            <div class="add-document-button">
                <a id="add_button" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add'" onclick="crearProgramacio()">Crear Programaci&oacute; General</a>
            </div>
            <div id="send_section">
                <a id="sendButton" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-send',plain:true, disabled: true">Enviar per aprovació</a>
                <a id="importButton" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-reload',plain:true, disabled: true" onclick="verificarProgramacioImportar()" >Importar al curs actual</a>
            </div>
        </div>
    </div>
</div>

<div id="win-add" class="easyui-window" title="Nova programaci&oacute;" style="width:650px;height:auto"
        data-options="modal:true, closed: true">
    <div data-options="region:'north'">
        <div id="dlg_win" class="easyui-panel" style="width:auto;height:auto;">
            <table id="formulari_programacio">
                <tr>
                    <td>
                        <label for="pla_estudis_prog">&nbsp;Pl&agrave; d'estudi</label>
                    </td>
                    <td>
                        <select id="pla_estudis_prog" class="easyui-combogrid prog_fields" name="pla_estudis" style="width:350px" 
                        data-options="
                            mode: 'remote',
                            url: './prof/prof_getpla.php?idprofessors=<?php echo $idprofessor; ?>&idcarrecs=1',
                            method: 'post',
                            value: 'idplans_estudis',
                            idField: 'idplans_estudis',
                            textField: 'Nom_plan_estudis',
                            fitColumns: true,
                            columns: [[
                                {field:'Nom_plan_estudis',title:'Nom',width:440}
                            ]],
                            onClickRow: getPlaEstudiProg
                        ">
                        </select>
                    </td>
                    <td>
                        <div class="error-field-message">
                            <span class="icon-no field-error"></span>
                            <span class="error-message"></span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="modul_prog">&nbsp;M&ograve;dul</label>
                    </td>
                    <td>
                        <select id="modul_prog" class="easyui-combogrid prog_fields" name="moduls" style="width:350px">
                        </select>
                    </td>
                    <td>
                        <div class="error-field-message">
                            <span class="icon-no field-error"></span>
                            <span class="error-message"></span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="curs_prog">&nbsp;Curs</label>
                    </td>
                    <td>
                        <select id="curs_prog" class="easyui-combogrid prog_fields" name="curs" style="width:350px"
                        data-options="
                            mode: 'remote',
                            url: './curs/curs_getdata.php?',
                            method: 'get',
                            value: 'idcurs',
                            idField: 'idcurs',
                            textField: 'nom_curs',
                            fitColumns: true,
                            columns: [[
                                {field:'nom_curs',title:'Curs',width:440}
                            ]],
                            onClickRow: getCursProg
                        ">
                        </select>
                    </td>
                    <td>
                        <div class="error-field-message">
                            <span class="icon-no field-error"></span>
                            <span class="error-message"></span>
                        </div>
                    </td>
                </tr>
            </table>
            <div class="advertencia_camps">
                <p>&nbsp;Tots els camps són obligatoris.</p>
            </div>
        </div>
    </div>
     <div data-options="region:'center'">
        <a href="#" id="makeButton" class="easyui-linkbutton" onclick="comprovarCampsModalWin(<?php echo $idprofessor; ?>)" data-options="plain:true,iconCls:'icon-ok'">Crear</a>
        <a href="#" id="backButton" class="easyui-linkbutton" onclick="tancarFinestraModal(this)" data-options="plain:true,iconCls:'icon-cancel'">Endarrere</a>   
     </div>
</div>

<div id="win-send" class="easyui-window" title="Enviar programaci&oacute;" style="width:400px;height:auto" data-options="modal:true, closed:true">
    <div data-options="region:'north'">
    </div>
    <div data-options="region:'south'">
        <a href="#" id="sendOk" class="easyui-linkbutton" onclick="doSearch(<?php echo $idprofessor; ?>)" data-options="iconCls:'icon-ok'">Acceptar</a>
    </div>
</div>

<div id="win-import" class="easyui-window" title="Importar programaci&oacute; al curs actual" style="width:500px;height:auto"
        data-options="modal:true, closed: true">
    <div data-options="region:'north'">
        <div id="import-document-advertice">
            <strong>ADVERTENCIA:</strong> Aquesta acció farà una copia de la programació seleccionada però que servirà per al curs actual.
        </div>
        <div class="import-document-name-field">
            <label for="importDocumentName">Nom del Document: </label>
            <input id="importDocumentName" class="easyui-textbox" data-options="iconCls:'icon-search'" style="width:150px">
            <div class="error-name-message">
                <span class="icon-no text-error"></span>
                <span class="error-message"></span>
            </div>
        </div>
        <div class="advertencia_camps">
            <p>&nbsp;El nom només pot contenir caràcters alfanúmerics, guió i guió baix.</p>
        </div>
    </div>
     <div data-options="region:'center'">
        <a href="#" id="acceptImportButton" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-ok'">Acceptar</a>
        <a href="#" id="cancelImportButton" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-cancel'">Cancelar</a>   
     </div>
</div>

<div id="win-import-alert" class="easyui-window" title="Avis" style="width:500px;height:auto"
        data-options="modal:true, closed: true">
    <div data-options="region:'north'">
        <div id="import-document-alert">
        </div>
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
    //Objecte que conté els filtres de cerca de la programació comuna.
    var programacioModul = new Object();
    
    //Objecte que conté els camps necessaris per a crear una nova programació comuna.
    var novaProgramacio = {idplans_estudis: false, idmoduls: false, idcurs: false};
    
    //Vector que conté l'objecte amb els filtres de cerca de la programació comuna.
    var llistaProgramacioModul = new Array(programacioModul);
    
    //És el identificador de la programació general seleccionada per al usuari al 'datagrid'.
    var idprogramacio_general = null;
    
    //Objecte que conté la programació seleccionada a la llista.
    var dadesProgramacioSeleccionada = new Object();
    
    /**
     * Selecciona mitjançant una consulta AJAX els plans d'estudis que el professor connectat
     * té assignats segons els 'grups' dels que es fa càrrec.
     */
    $("#pla_estudis").combogrid({delay: 500,
        mode: 'remote',
        url: './prof/prof_getpla.php?idprofessors=<?=$idprofessor?>&idcarrecs=1',
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
    
    //Text per defecte que tindrà el selector dels plans d'estudi.
    $("#pla_estudis").combogrid("setValue", "Selecciona un Pla d'estudi");
    
    /**
     * Recull el valor de la selecció que el usuari ha fet al camp dels plans d'estudi, afegeix
     * el valor al objecte que guarda les dades de filtre de cerca, i posteriorment prepara el 
     * segon selector amb els mòduls que corresponen al pla d'estudi marcat i el tercer selector amb tots 
     * els cursos disponibles (Primer Curs, Segon Curs).
     */
    function getPlaEstudi() {
        var codiPlaEstudi = $("#pla_estudis").combogrid("getValue");
        programacioModul.idplans_estudis = codiPlaEstudi;
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
        programacioModul.idmoduls = false;

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
        programacioModul.idmoduls = codiModul;
        
        doSearch(<?=$idprofessor?>);
    }
    
    /**
     * Recull el curs seleccionat i ho afegeix a l'objecte que recull les dades del filtre de 
     * cerca.
     */
    function getCurs() {
        var codiCurs = $("#curs").combogrid("getValue");
        programacioModul.idcurs = codiCurs;
        
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
        
        $('#dg').datagrid({
	rowStyler: function(index,row){
		if (row.estat === "Declinada"){
                    return 'background-color:#FF7979;'; 
		}
                
                if (row.estat === "Aprovada") {
                    return 'background-color:#6EEB89;'; 
                }
            }
        });
        
        tancarFinestraModal();
    }
    
    /**
     * Funció que s'activa quan el event 'onSelectRow' del datagrid es disparà. Aquest event actua quan el usuari selecciona 
     * una de les programacions mostrades al datagrid. Recull el valor id de la programació seleccionada i posteriorment 
     * habilita els botons que permeten la gestió de la programació. 
     */
    function getProgramacioGeneral() {
        dadesProgramacioSeleccionada = $("#dg").datagrid('getSelected');
        idprogramacio_general = dadesProgramacioSeleccionada.idprogramacio_general;
        enabledMenuGestionar(idprogramacio_general);
    }
    
    /*
     * Habilita els botons que permeten gestionar una programació (modificar, eliminar, visualitzar, Importar i enviar).
     * I a més carregà les funcions que s'activaran en el moment de prémer cadascún dels botons. 
     * 
     * @param {String} idprogramacio
     */
    function enabledMenuGestionar(idprogramacio) {
        $("#prevButtonPDF").linkbutton({disabled: false}); 
        $("#prevButtonODT").linkbutton({disabled: false}); 
        $("#modButton").linkbutton({disabled: false});
        $("#delButton").linkbutton({disabled: false});
        $("#importButton").linkbutton({disabled: false});
        $("#sendButton").linkbutton({disabled: false});
        
        afegirFuncionsMenuGestionar(idprogramacio);
    }
    
    /*
     * Assigna els events corresponetns als botons de 'Enviar per aprovació' i 'Eliminar'
     * 
     * @param (String) idprogramacio
     */
    function afegirFuncionsMenuGestionar(idprogramacio) {
        $("#sendButton").unbind();
        $("#sendButton").bind('click', function() {
            enviarProgramacio(idprogramacio);
        });
        
        $("#delButton").unbind();
        $('#delButton').bind('click', function(){
            eliminarProgramacio(idprogramacio);
        });
    }
    
    /**
     * Funció que s'activa quan el usuari prem el botó de 'Enviar per aprovació'. Comprova que la programació no hagi 
     * sigut enviada ni tampoc hagi estat declinada, si s'acompleix aquestes dues condicions la programació es enviada 
     * i per tant l'estat del document passarà a ser 'Pendent de revisió'.
     * 
     * @param {String} idprogramacio
     */
    function enviarProgramacio(idprogramacio) {
        $.post("./prog_gen/prog_gen_send.php", 
        {
            idprogramacio: idprogramacio
        }, function(correcte) {
            modalWinSend(correcte);
        });
    }
    
    /**
     * Obre una finestra modal amb un missatge d'avis a l'usuari. 
     * Si el paràmetre passat:
     * 
     * true 
     *      -> La programació s'ha enviat correctament per a la seva revisió
     * false 
     *      -> La programació no ha estat acceptada, cal fer una modificació per a poder tornar a enviar-la
     *      -> La programació ja ha sigut enviada i està pendent d'aprovació.
     *      
     * @param (boolean) correcte
     */
    function modalWinSend(correcte) {
        $("#win-send").window('open');
        var element = $("#win-send div:first-child");
        if (correcte) {
            $(element).html("<p>La programació s'ha enviat correctament per a la seva revisió</p>");
        } else {
            if (dadesProgramacioSeleccionada.revisat === "D" || dadesProgramacioSeleccionada.aprovat === "D") {
                $(element).html("<p>La programació no ha estat acceptada, cal fer una modificació per a poder tornar a enviar-la</p>");
            } else {
                $(element).html("<p>La programació ja ha sigut enviada i està pendent d'aprovació.</p>");
            }
        }
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
    
    /**
     * Deshabilita el botons que permeten la gestió de la programació.
     */
    function disabledMenuGestionar() {
        $("#sendButton").linkbutton({disabled: true});
        $("#prevButtonPDF").linkbutton({disabled: true}); 
        $("#prevButtonODT").linkbutton({disabled: true}); 
        $("#modButton").linkbutton({disabled: true});
        $("#delButton").linkbutton({disabled: true});
        $("#importButton").linkbutton({disabled: true});
    }
    
    /*
    * S'activa quan el usuari prem el botó de modificar. Carrega el panell que conté el formulari de creació de les 
    * programacions, però amb les dades de la programació seleccionada carregades. 
    */
    function modificarProgramacio(idprofessor) {
        novaProgramacio.idmoduls = dadesProgramacioSeleccionada.idmoduls;
        novaProgramacio.idcurs = dadesProgramacioSeleccionada.idcurs;
        novaProgramacio.idprofessors = idprofessor;
        novaProgramacio.idprogramacio_general = idprogramacio_general;
        
        var programacio = JSON.stringify(novaProgramacio);
        var url = "./vista_prog/prog_gen_form.php?programacio=" + programacio;
        open1(url, this);
    }
    
    /**
     * Funció que s'activa quan el usuari prem el botó d'Importar. Comprova mitjançant AJAX si el mòdul i el curs de la 
     * programació a importar (seleccionada) ja té carregada una programació per al curs actual.
     */
    function verificarProgramacioImportar() { 
        var modul = dadesProgramacioSeleccionada.idmoduls;
        var curs = dadesProgramacioSeleccionada.idcurs;
        
        $.post("./prog_gen/prog_gen_check.php", 
        {
            modul: modul,
            curs: curs
        }, function(data) {
            if (data) {
                importarProgramacio();
            } else {
                var nom_modul = dadesProgramacioSeleccionada.nom_modul;
                var nom_curs = dadesProgramacioSeleccionada.nom_curs;
                errorProgramacio(nom_modul, nom_curs);
            }
        });
    }
    
    function getNomModulCurs(modul, curs) {
        $.post("./prog_gen/prog_gen_getModulCurs.php", 
        {
            modul: modul,
            curs: curs
        }, function(data) {
            var resultats = JSON.parse(data);
            errorProgramacio(resultats[0].nom_modul, resultats[0].nom_curs);
        });
    }
    
    /*
     * Obre una finestra modal sol·licant a l'usuari el nom del document de la programació que vol importar per al curs 
     * actual. Afegeix l'event al botó '#acceptImportButton' que comprovarà si el text escrit en el textbox on es demana 
     * el nom del document contingui valors vàlida.
     */
    function importarProgramacio() {
        $("#win-import").window('open');
        $('#acceptImportButton').unbind();
        $("#acceptImportButton").bind('click', function() {
            var capaErrors = document.getElementsByClassName('text-error');
            $(capaErrors[0]).parent().css("display","none");
            var textbox = $("#importDocumentName");
            var nomDocument = comprovarNomDocument(textbox);
            if (nomDocument) {
                $.post("./prog_gen/prog_gen_import.php", 
                {
                    idprogramacio_general: idprogramacio_general,
                    nom_document: nomDocument
                }, function(data) {
                    var resultat = JSON.parse(data);     
                    doSearch(resultat[0].idprofessors);
                });
            }
        });
            
        $("#cancelImportButton").bind('click', function() {
            $("#win-import").window('close');
        });
      
    }
    
    /**
    * Finestra modal d'importació
    * 
    * Obre una finestra modal advertint al usuari que la programació de la que es vol importar, ja conté una altra per al 
    * curs actual i que per tant no es poden duplicar. 
    */
    function errorProgramacio(nom_modul, nom_curs) {
        nom_modul = nom_modul.match(/[A-Za-z0-9\s\.'áàéèííóòú]+/g)[1];
        $("#import-document-alert").html("<p>La programació comuna del mòdul <b>" + nom_modul + "</b> de <b>" + nom_curs + "</b> ja existeix per al curs actual.</p>");
        $("#win-import-alert").window("open"); 
    }
    
    /*
     * Finestra modal d'importació
     * 
     * Comprova que el camp o imput de text passat com a paràmetre contingui valors vàlids, o sigui ser caràcters 
     * alfanúmerics, guio i guio baix (/^[A-Za-z0-9-_]+$/). Si el camp té els valors vàlida retorna el contingut 
     * del camp, sino retorna fals.
     * 
     * @param {DOM Object} element
     * @return (String) 
     */
    function comprovarNomDocument(element) {
        var patro = /^[A-Za-z0-9-_]+$/;
        var nomDocument = $(element).val();
        if (nomDocument.search(patro)) {
            var capaErrors = document.getElementsByClassName('text-error');
            $(capaErrors[0]).parent().css("display","flex");
            $(capaErrors[0]).css({"display": "block", "width": "15px", "height": "15px"});
            $(capaErrors[0]).next(".error-message").html("Nom no vàlid");
            return false;
        } else {
            return nomDocument;
        }
    }
    
    /**
     * Obre una finestra modal demanant a l'usuari el pla d'estudi, el mòdul i el curs al que es vol crear la programació
     * utilitzant tres selectors. Es fa una còpia dels valors del objecte 'programacioModul' relacionats amb els filtres del 
     * formulari del qual hereta aquesta finestra, a l'objecte 'novaProgramacio' que serà l'element que contindrà les dades 
     * necessaries per a crear la programació.
     */
    function crearProgramacio() {
        $("#win-add").window('open');
        var capaErrors = document.getElementsByClassName('field-error');
        $(capaErrors).parent().css("display", "none");
        for (var camp in programacioModul) {
            novaProgramacio[camp] = programacioModul[camp];
        }
        
        $.post("./prog_gen/prog_gen_getFillFields.php", 
        {   
            idpla_estudi: novaProgramacio.idplans_estudis,
            idmodul: novaProgramacio.idmoduls,
            idcurs: novaProgramacio.idcurs
        }, function(data) {
            var dadesCamps = JSON.parse(data);
            omplirCampsModalWin(dadesCamps);
        });
    }
    
    /**
     * Finestra modal de creació
     * 
     * Omple els tres selectors que apareixen en la finestra modal de creació de la programació amb les dades passades com a 
     * paràmetres en el ordre corresponents.
     * 
     * @param {Object} dadesCamps
     */
    function omplirCampsModalWin(dadesCamps) {
        var camps = document.getElementsByClassName('prog_fields');
        
        //Recorre l'objecte 'dadesCamps' i omple els camps segons s'obtenen les dades del objecte passat com a paràmetre.
        for (i = 0; i < camps.length; i++) {
            for (var camp in dadesCamps[i]) {
                $(camps[i]).combogrid("setValue", dadesCamps[i][camp]);
            }   
        }
        
        /*
         * Comprova si el atribut 'pla_estudi' del objecte global 'novaProgramacio' es fals o no.
         * Si el atribut no es fals deixa el selector amb el valor que el usuari ha seleccionat en el anterior formulari 
         * (formulari de cerca). En cas contrari s'omple el camp amb un text per defecte ("Selecciona pla d'estudi").
         */
        if (!novaProgramacio.idplans_estudis) {
            $(camps[0]).combogrid("setValue", "Selecciona pla d'estudi");
        }
        
        mostrarModulsModalWin();
    }
    
     /**
     * Finestra Modal de creació
     * 
     * Mostra al selector dels mòduls de la finetra modal de creació, els mòduls que pertanyen al pla d'estudi marcat al 
     * selector dels plans d'estudi de la finestra modal de creació.  
     */
    function mostrarModulsModalWin() {
        $("#modul_prog").combogrid({delay: 500,
            mode: 'remote',
            url: './pe/pe_getmodul.php?idplan_estudi=' + novaProgramacio.idplans_estudis,
            method: 'get',
            value: 'idmoduls',
            idField: 'idmoduls',
            textField: 'nom_modul',
            fitColumns: true,
            columns: [[
                {field:'nom_modul',title:'Nom',width:110}
            ]],
            onClickRow: getModulProg
        });
    }
    
    /**
     * Finestra modal de creació
     * 
     * Funció que s'activa quan el usuari selecciona una opció del selector de plans d'estudi de la finestra modal de creació.
     * Canvia els valors del selector dels mòduls en funció del plà d'estudis escollit.
     */
    function getPlaEstudiProg() {
        var codiPlaEstudi = $("#pla_estudis_prog").combogrid("getValue");
        /**
         * Comprova si el pla d'estudi seleccionat es diferent al que hi havia marcat, si es així resetejen les opcions que el 
         * usuari pot escollir al selector dels mòduls mostrant als pertanyents al pla d'estudi ara seleccionat. I 
         */
        if (codiPlaEstudi != novaProgramacio.plans_estudis) {
            novaProgramacio.idplans_estudis = codiPlaEstudi;
            novaProgramacio.idmoduls = false;
            $("#modul_prog").combogrid("setValue","Selecciona Mòdul");
            mostrarModulsModalWin();
        } 
    }
    
    /**
     * Finestra modal de creació
     * 
     * Funció que recull el valor que hi ha al selector de mòduls de la finestra modal de creació i l'afegeix al objecte 
     * 'novaProgramacio'
     */
    function getModulProg() {
        var codiModul = $("#modul_prog").combogrid("getValue");
        novaProgramacio.idmoduls = codiModul;
    }
    
    /**
     * Funció que recull el valor que hi ha al selector de cursos de la finestra modal de creació i l'afegeix al objecte 
     * 'novaProgramacio'
     */
    function getCursProg() {
        var codiCurs = $("#curs_prog").combogrid("getValue");
        novaProgramacio.idcurs = codiCurs;
    }
    
    /**
     * Finestra modal de creació
     * 
     * Comprova que tots els selectors de la finestra modal de creació tinguin un valor diferent a fals. O el que és el mateix 
     * comprova que els atributs del objecte 'novaProgramacio' tinguin un valor diferent a fals. Això significa que l'objecte 
     * conté les dades necessaries per a poder crear la programació del mòdul. 
     * 
     * @param (String) idprofessor
     */
    function comprovarCampsModalWin(idprofessor) {
        var capaErrors = document.getElementsByClassName('field-error');
        var missatgesErrors = document.getElementsByClassName('error-message');
        var formulariCorrecte = true;
        var i = 0;
        /**
         * Recorre el objecte 'novaProgramacio' comprovant que no hi hagi cap atribut que sigui igual a false (que no s'hagi 
         * omplert). Si algun atribut es false, aquest mostrarà un missatge d'error al costat del selector, avisant a l'usuari 
         * de la necessitat d'omplir aquest camp
         */
        for (var camp in novaProgramacio) {
            if (novaProgramacio[camp] == false) {
                $(capaErrors[i]).parent().css("display", "flex");
                $(capaErrors[i]).css({"display": "block", "width": "15px", "height": "15px"});
                $(missatgesErrors[i]).html("&nbsp;Omple aquest camp.");
                formulariCorrecte = false;
            } else {//..En cas contrari, aquest missatge s'ocultarà
                $(capaErrors[i]).parent().css({"display": "none"});
                novaProgramacio.idprofessors = idprofessor;
            }
            i++;
        }
        
        if (formulariCorrecte) {
            verificarProgramacioCrear();
        }
    }

    /**
    * Fa una petició al servidor per comprovar que no hi hagi una programació registrada a la base de dades del mateix 
    * mòdul i del mateix curs seleccionats.
    */
    function verificarProgramacioCrear() {
        var modul = novaProgramacio.idmoduls;
        var curs = novaProgramacio.idcurs;
        
        $.post("./prog_gen/prog_gen_check.php", 
        {
            modul: modul,
            curs: curs
        }, function(data) {
            if (data) {
                reenviarFormulariCreacio();
            } else {
                getNomModulCurs(novaProgramacio.idmoduls, novaProgramacio.idcurs);
                delete novaProgramacio["professor"];
            }
        });
    }

   /**
    * Carrega el panell que conté el formulari de creació de programacions.
    */
   function reenviarFormulariCreacio() {
        tancarFinestraModal(); 
        var programacio = JSON.stringify(novaProgramacio);
        var url = "./vista_prog/prog_gen_form.php?programacio=" + programacio;
        open1(url, this);
    }
    
    /**
     * Tanca les finestres modals que estiguin obertes.
     */
    function tancarFinestraModal() {
        $("#win-add").window("close");
        $("#win-delete").window("close");
        $("#win-send").window("close");
        $("#win-import").window("close");
    }
    
    /*
     * Obra una finestra modal que sol·licita una darrere confirmació a l'usuari abans d'eliminar la programació seleccionada.
     * Aquesta eliminació es realitza mitjançant AJAX quan el usuari prem el botó d'acceptar. En cas de que el usuari prem el 
     * botó de cancel·lar, es tanca la finestra.
     * 
     * @param (string) idprogramacio
     */
    function eliminarProgramacio(idprogramacio) {
        $("#win-delete").window('open');
        $('#acceptButton').unbind();
        $('#acceptButton').bind('click', function(){
            $.post("./prog_gen/prog_gen_eliminar.php", {idprogramacio_general: idprogramacio},
            function(data) {
                var resultat = JSON.parse(data);     
                doSearch(resultat[0].idprofessors);
            });
            
            tancarFinestraModal();
        });

        $('#cancelButton').bind('click', function(){
            tancarFinestraModal();
        });
    }
</script>

<style type="text/css">
    .advertencia_camps p {
        font-size: 10px;
    }
    
    .field-error, .text-error {
        display: none;
    }
    
    .error-field-message, .error-name-message {
        display: none;
        border: 1px solid red;
        padding: 3px;
        background-color: #F4B2B2;
    }
    
    .north_toolbar {
        display: flex;
        margin: 5px;
        justify-content: space-between;
    }
    
    .center_toolbar {
        margin: 15px 5px;
        display: flex;
        justify-content: space-between;
    }
    
    .document-manegement {
        display: flex;
        justify-content: space-between;
        margin: 15px 5px;
    }
    
    .import-document-name-field {
        margin: 5px 0px;
        display: flex;
        align-content: center;
        height: 22px;
    }
    
    .import-document-name-field * {
        margin: 0 5px;
    }
    
    #import-document-advertice, #import-document-alert {
        margin-left: 5px;
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
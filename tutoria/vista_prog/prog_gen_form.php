<?php
    include_once('../bbdd/connect.php');
    include_once('../func/constants.php');
    include_once('../func/generic.php');
    mysql_query("SET NAMES 'utf8'"); 
    
    /*
     * Recull l'objecte enviat desde prog_gen_grid en format json i l'assigna a una variable php
     * anomenada $json
     */    
    if ($_REQUEST['programacio']){
        $json = $_REQUEST['programacio'];   
    }   
?>
    <div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;" title="FORMULARI DE PROGRAMACIONS DIDÀCTIQUES GENERALS"> 
        <a id="saveButton" href="#" class="easyui-linkbutton" onclick="guardarProgramacio(false)" data-options="iconCls:'icon-save',plain:'false'">Desar</a>   
        <a id="sendButton" href="#" class="easyui-linkbutton" onclick="modalSendWin()" data-options="iconCls:'icon-search',plain:'false'">Enviar per revisar</a> 
        <div class="easyui-tabs" style="width:auto;height:auto">
            <div title="Unitats Formatives" style="padding:10px;width:auto;height:auto">
                <p>Distribució de les hores de lliure disposició de cada Unitat Formativa del mòdul:</p>
                <p>
                    <div id="hores_restants">
                        <label>Hores restants: </label>
                        <input type="text" class="easyui-numberbox" readonly/>
                    </div>
                </p>
                <table id="unitats_formatives" style="width:auto;height:auto">
                    <thead>
                        <tr>
                            <th class="header_uf_row">Unitat formativa</th>
                            <th class="header_uf_row">Hores</th>
                            <th class="header_uf_row">Hores lliure disposició</th>
                        </tr>
                    </thead>
                    
                </table>
            </div>
            <div title="Estratègies i organització" style="padding:10px;width:auto;height:auto">
                <p>Definició de les estratègies metodològiques i l'organització del mòdul:</p>
                <textarea id="estrategies" style="height: 1000px;"></textarea>
            </div>
            <div title="Recursos" style="padding:10px;width:auto;height:auto">
                <p>Definició dels espais, equipaments i recursos utilitzats en el mòdul:</p>
                <textarea id="recursos" style="height: 1000px;"></textarea>
            </div>
            <div title="Document" style="padding:10px;width:auto;height:auto">
                Nom del document:
                <input id="nomDocument" class="easyui-textbox"> 
            </div>
        </div>
    </div>
    <div id="win-save" class="easyui-window" title="Guardar programaci&oacute;" style="width:350px;height:100px"
            data-options="modal:true, closed: true">
        <div data-options="region:'north'">
            La programaci&oacute; s'ha guardat correctament.
        </div>
         <div data-options="region:'center'">
            <a href="#" id="acceptButton" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-ok'">Acceptar</a>
         </div>
    </div>
    <div id="win-control" class="easyui-window" title="Alerta!" style="width:350px;height:100px"
            data-options="modal:true, closed: true">
        <div data-options="region:'north'">
            Per enviar la programació és necessari omplir tots els camps.
        </div>
         <div data-options="region:'center'">
            <a href="#" id="acceptButton1" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-ok'">Acceptar</a>
         </div>
    </div>
    <div id="win-alert" class="easyui-window" title="Enviar programaci&oacute;" style="width:350px;height:100px"
            data-options="modal:true, closed: true">
        <div data-options="region:'north'">
            Segur que desitges enviar la programació?
        </div>
         <div data-options="region:'center'">
            <a href="#" id="okButton" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-ok'">Si</a>
            <a href="#" id="cancelButton" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-cancel'">No</a>
         </div>
    </div>

<script type="text/javascript"> 
    //Objecte que conté els paràmetres de la programació general amb la que s'està treballant.
    var programacio = <?php echo $json?>;
    
    //Conte tots els identificadors de les UF que corresponen al mòdul i al curs seleccionat en la pàgina anterior.
    var idUFS = new Array();
    
    /*Carrega el editor de text per els apartats de estrategies metodòlogiques i recursos del mòdul*/
    $('#estrategies').ckeditor(); 
    $('#recursos').ckeditor();
    
    getHoresRestants();
    getUnitatsFormatives();  
    
    /*
     * Totes les pestanyes.
     * 
     * Comprova si existeix una id de programacio general i llença les funcions necessaries per carregar
     * els textos en els camps del formulari i obtenir el curs i mòdul de la programació..
     */
    if (programacio.hasOwnProperty('idprogramacio_general')) {
      AfegirTabModificacions();
      var programacioJson = JSON.stringify(programacio,true);
      $.post('./prog_gen/prog_gen_form_getdata.php',{programacio : programacioJson},
        function(obj){
            temporal = JSON.parse(obj); 
            programacio = temporal[0]; 
            programacio.modificacions = true;
            carregarText();       
            getModulCurs();
        });   
    } 
    
    /*
     * Assigna el id del mòdul i el id del curs de la programació seleccionada al objecte 'programacio'.
     * S'utilitza per poder visualitzar les unitats formatives quan es modifica una programació.
     */
    function getModulCurs(){
        var programacioJson = JSON.stringify(programacio,true);
        $.post('./prog_gen/prog_gen_form_getdata.php',{cursmodul : programacioJson},
                    function(obj){
                        temporal = JSON.parse(obj); 
                        programacio.idmoduls = temporal[0].idmoduls;
                        programacio.idcurs = temporal[0].idcurs;
        });   
    }
    
    /*
     * Omple els camps del formulari amb el contingut de l'objecte 'programacio'
     */
    function carregarText(){
        CKEDITOR.instances.estrategies.setData(programacio.estrategies_metodologies);
        CKEDITOR.instances.recursos.setData(programacio.recursos); 
        $('#nomDocument').val(programacio.nom_document);
    }
    
    /**
     * Obté les hores de lliure disposició restants del mòdul que correspont a la programació, i les mostra amb la 
     * funció 'mostrarHoresRestants()' 
     */
    function getHoresRestants() {
        $.post('./uf_class/uf_hores_getdata.php',{idmoduls : programacio.idmoduls},
            function(data){
                var resultat = JSON.parse(data);
                hores = resultat[0];
                mostrarHoresRestants();
            }
        );   
    }
    
    /*
     * Pestanya Unitats Formatives
     * 
     * Funció que es dispara quan el valor d'un dels inputs que conté les hores de lliure disposicio de les UF varia,
     * llavors fa els corresponents càlculs per a que la suma de les hores de lliure disposició de les UF 
     * que es mostren, no superi les hores restants mostrades en el input que hi ha la part superior del formulari. 
     * 
     * Exemple: 
     * Hores restants --> 9
     * UF1 --> 3
     * UF2 --> 4
     * UF3 --> 2
     * 
     * Comprova que 3 + 4 + 2 <= Hores restants (9)
     */
    function gestorHores(newValue, oldValue) {
 
        if (newValue) {
            hores.horeslld_uf = parseInt(hores.horeslld_uf - parseInt(oldValue));

            var horesRestants = (hores.horeslld_mod - hores.horeslld_uf);

            if (horesRestants < newValue) {
                $(this).numberbox({
                       min: 0,
                       value: horesRestants,
                       onChange: gestorHores
                });
                hores.horeslld_uf += parseInt(horesRestants);
                horesRestants = 0;
                $("#hores_restants").children("input").val(horesRestants);
            } else {
                horesRestants -= parseInt(newValue);
                $("#hores_restants").children("input").val(horesRestants);
                hores.horeslld_uf += parseInt(newValue);
            }
        
            guardarHoresUFS();
            
        } else {
            $(this).numberbox({
                       min: 0,
                       value: oldValue,
                       onChange: gestorHores
            });
        }
    }
    
    /**
     * Pestanya Unitats Formatives
     * 
     * Guarda les hores de lliure disposicio de les UF introduides en els inputs del formulari, mitjançant una 
     * petiió POST al servidor.
     */
    function guardarHoresUFS() {
        var gestorHores = {id: idUFS, hores: new Array()};
        var sumaHoresTotals = 0;
        for (i = 0; i < idUFS.length; i++) {
            gestorHores.hores[i] = $("#horeslliuredisposicio" + i).numberbox('getValue');
            sumaHoresTotals += parseInt($("#horeslliuredisposicio" + i).numberbox('getValue'));
        }
        gestorHores.totals = sumaHoresTotals;
        gestorHores.idmoduls = programacio.idmoduls;
        gestorHores = JSON.stringify(gestorHores);
        
        $.post('./prog_gen/prog_gen_form_getdata.php',{gestorHores: gestorHores},
            function(data){
                console.log(data);
                mostrarHoresRestants();
            }
        );   
    }
    
    /**
     * Pestanya Unitats Formatives
     * 
     * Mostra les hores restants obtingudes de la funció getHoresRestants() en el primer input de tipus text que 
     * apareix al formulari del apartat d'Unitats Formatives.
     */
    function mostrarHoresRestants() {
        var horesRestants = hores.horeslld_mod - hores.horeslld_uf;
        if (horesRestants >= 0) { 
            $("#hores_restants").children("input").val(horesRestants);
        } else {
            hores.horeslld_uf = hores.horeslld_mod;
            $("#hores_restants").children("input").val(0);
        }
    }
    
    /*
     * Pestanya Unitats Formatives
     * 
     * Funció que es dispara al carregar el document i que obté informació de les UFS que conté el mòdul i el curs 
     * seleccionats en la plana anterior. ID de la UF, nom de la UF, durada i les hores de lliure disposicio
     */
    function getUnitatsFormatives(){
        var peticio = {idmoduls: programacio.idmoduls, idcurs: programacio.idcurs, unitats_formatives: true};
        peticio = JSON.stringify(peticio);
        $.post('./prog_gen/prog_gen_form_getdata.php',{peticio: peticio},
            function(data){ 
                var resultat = JSON.parse(data);
                mostrarHoresUF(resultat);
            }
        );
        
    }
    
    /**
     * Mostra una taula amb la informació de les UFS carregades a la funció getUnitatsFormatives().
     * 
     * @param {array} unitats_formatives
     */
    function mostrarHoresUF(unitats_formatives) {
        var camp = "";
        for (i = 0; i < unitats_formatives.length; i++) {
            idUFS.push(unitats_formatives[i]["idunitats_formatives"]);     
            $("#unitats_formatives").append("<tr class='fields_uf_row'></tr>");  
            var fila = $("#unitats_formatives tr").last();
            $(fila).append("<td>" + unitats_formatives[i]["nom_uf"] + "</td><td>" + unitats_formatives[i]["hores"] + "</td>");
            $(fila).append("<td><input type='text' id='horeslliuredisposicio" + i + "' style='width:50px'/></td>");
            
            if (unitats_formatives[i]["horeslliuredisposicio"]) {
                $("#horeslliuredisposicio" + i).numberbox({
                    min: 0,
                    value: unitats_formatives[i]["horeslliuredisposicio"],
                    onChange: gestorHores
                });
            } else {
                $("#horeslliuredisposicio" + i).numberbox({
                    min: 0,
                    value: 0,
                    onChange: gestorHores
                });
            }
        } 
    }
    
    /*
     * Recull tots els camps del formulari i els guarda a l'objecte 'programacio', posteriorment
     * el codifica en format json i l'envia al servidor per tal de guardar-lo en la base de dades.
     * Si rep el parametre enviar en 'true' també llençara la funció 'enviarProgramacio'.
     * 
     * @param {boolean} enviar
     *  
     */
    function guardarProgramacio(enviar){
       var control = false;
       if (enviar) {
           control = controlFormulari();
       }
       
       if (control || !enviar) {
            programacio.estrategies = CKEDITOR.instances.estrategies.getData();
            programacio.recursos = CKEDITOR.instances.recursos.getData();
            programacio.nomDocument = $('#nomDocument').val();
            programacio.revisat = 'G';
            
            if (programacio.hasOwnProperty('idprogramacio_general') === false){
                programacio.idprogramacio_general = null; 
            }
            
            if (programacio.hasOwnProperty('modificacions')){
                programacio.modificacions = $("#modificacions").val();
            }
            
            programacio.enviar = enviar;
            
            var programacioJson = JSON.stringify(programacio,true);
            $.post('./prog_gen/prog_gen_form_save.php',{programacio:programacioJson},
                 function(data){ 
                     if(enviar){
                         enviarProgramacio();
                     } else {
                         var resultat = JSON.parse(data);
                         programacio.idprogramacio_general = resultat.idprogramacio_general;
                         $("#win-save").window('open');
                         $('#acceptButton').unbind();
                         $('#acceptButton').bind('click',function(){tancarFinestraModal();});  
                     }
             });
       } else {
            $("#win-control").window('open');
            $('#acceptButton').unbind();
            $('#acceptButton1').bind('click',function(){tancarFinestraModal();});
       }
       
    }
    
    /*
     * Comprova si tots els camps del formulari están omplerts.
     * Envia l'objecte 'programacio' en format json al servidor a través d'una petició
     * POST per tal de modificar l'estat de l'atribut 'revisat' a la base de dades. 
     */
    function enviarProgramacio(){  
        var programacioJson = JSON.stringify(programacio,true); 
        $.post('./prog_gen/prog_gen_form_send.php',{programacio:programacioJson},
             function(correcte){
                 if(correcte){
                     var url = "./vista_prog/prog_gen_grid.php?idprofessor=" + programacio.idprofessors;
                     open1(url, this);
                 }
             }                                                
       );                    
    }
    
    /*
    * Obre una finestra modal amb dos botons, i afegeix els events corresponents per a cada un.
    */ 

    function modalSendWin() {
        $("#win-alert").window('open');
        $('#okButton').unbind();
        $('#okButton').bind('click',function(){guardarProgramacio(true);});
        $('#cancelButton').bind('click',function(){tancarFinestraModal();});
    }
    
    /*
     * Controla que tots els camps del formulari tinguin contingut.
     * En cas afirmatiu retorna 'true', en cas negatiu retorna 'false'.
     * 
     * return (boolean)
     */
    function controlFormulari(){
       var control = true;
       if($("#recursos").val()==''){
           control = false;
       }   
       if($("#estrategies").val()==''){
           control = false;
       }
       if($("#nomDocument").val()==''){
           control = false; 
       }
       if($("#modificacions")){
           if($("#modificacions").val()==''){
                control = false; 
           }
       }
       return control;
    }
    
    /*
     * Funció que tanca totes les finestres modals obertes.
     */
    function tancarFinestraModal() {
        $("#win-save").window("close");
        $("#win-control").window("close");
        $("#win-alert").window("close");
    }
    /*
     * Funció que afegeix un nou element 'modificacions' en el contenidor de tabs i afegeix l'atribut modificacions 
     * al objecte 'programacio'.
     */
    function AfegirTabModificacions(){
        $( ".easyui-tabs").append( $( "<div title='Modificacions' style='padding:10px;width:auto;height:auto'>\n\
            <p>Modificacions realitzades en el document:</p>\n\
            <textarea id='modificacions' style='resize: none;height:100px;width:500px;'></textarea></div>" )
        );
        programacio.modificacions = null;
    }
    
</script>
<style type="text/css">
    #unitats_formatives {
        border-spacing: 10px;
        border-left: 1px solid grey;
    }
    
    #unitats_formatives tr {
        border-bottom: 1px solid grey;
    }
</style>

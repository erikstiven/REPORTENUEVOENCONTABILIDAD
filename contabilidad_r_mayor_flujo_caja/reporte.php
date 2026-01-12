<? /********************************************************************/ ?>
<? /* NO MODIFICAR ESTA SECCION*/ ?>
<? include_once('../_Modulo.inc.php');?>
<? include_once(HEADER_MODULO);?>
<? if ($ejecuta) { ?>
<? /********************************************************************/ ?>

<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="<?=$_COOKIE["JIREH_COMPONENTES"]?>bower_components/bootstrap/dist/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?=$_COOKIE["JIREH_INCLUDE"]?>css/dataTables/dataTables.buttons.min.css" media="screen">

<!-- Select2 -->
<link rel="stylesheet" href="<?=$_COOKIE["JIREH_COMPONENTES"]?>bower_components/select2/dist/css/select2.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="<?=$_COOKIE["JIREH_COMPONENTES"]?>dist/css/AdminLTE.min.css">
<!-- AdminLTE Skins. Choose a skin from the css/skinsfolder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="<?=$_COOKIE["JIREH_COMPONENTES"]?>dist/css/skins/_all-skins.min.css">
<link rel="stylesheet" type="text/css" href="<?=$_COOKIE["JIREH_INCLUDE"]?>css/dataTables/dataTables.bootstrap.min.css" media="screen">

<!--JavaScript--> 
<script type="text/javascript" language="JavaScript" src="<?=$_COOKIE["JIREH_INCLUDE"]?>js/dataTables/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="JavaScript" src="<?=$_COOKIE["JIREH_INCLUDE"]?>js/dataTables/dataTables.bootstrap.min.js"></script>

<script type="text/javascript" language="JavaScript" src="<?=$_COOKIE["JIREH_INCLUDE"]?>js/dataTables/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="JavaScript" src="<?=$_COOKIE["JIREH_INCLUDE"]?>js/dataTables/dataTables.buttons.flash.min.js"></script>
<script type="text/javascript" language="JavaScript" src="<?=$_COOKIE["JIREH_INCLUDE"]?>js/dataTables/dataTables.jszip.min.js"></script>
<script type="text/javascript" language="JavaScript" src="<?=$_COOKIE["JIREH_INCLUDE"]?>js/dataTables/dataTables.pdfmake.min.js"></script>
<script type="text/javascript" language="JavaScript" src="<?=$_COOKIE["JIREH_INCLUDE"]?>js/dataTables/dataTables.vfs_fonts.js"></script>
<script type="text/javascript" language="JavaScript" src="<?=$_COOKIE["JIREH_INCLUDE"]?>js/dataTables/dataTables.buttons.html5.min.js"></script>
<script type="text/javascript" language="JavaScript" src="<?=$_COOKIE["JIREH_INCLUDE"]?>js/dataTables/dataTables.buttons.print.min.js"></script>

<!-- Select2 -->
<script src="<?=$_COOKIE["JIREH_COMPONENTES"]?>bower_components/select2/dist/js/select2.full.min.js"></script>

<!-- AdminLTE App -->
<script src="<?=$_COOKIE["JIREH_COMPONENTES"]?>dist/js/adminlte.min.js"></script>
	
<!-- Bootstrap Toggle -->
<link  href="<?=$_COOKIE["JIREH_COMPONENTES"]?>bower_components/bootstrap-toggle-master/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="<?=$_COOKIE["JIREH_COMPONENTES"]?>bower_components/bootstrap-toggle-master/js/bootstrap-toggle.min.js"></script>

<!--CSS--> 
<link rel="stylesheet" type="text/css" href="<?=$_COOKIE["JIREH_INCLUDE"]?>css/bootstrap-3.3.7-dist/css/bootstrap.css" media="screen">
<link rel="stylesheet" type="text/css" href="<?=$_COOKIE["JIREH_INCLUDE"]?>css/bootstrap-3.3.7-dist/css/bootstrap.min.css" media="screen">
<link rel="stylesheet" type="text/css" href="<?=$_COOKIE["JIREH_INCLUDE"]?>js/treeview/css/bootstrap-treeview.css" media="screen"> 

<style>
    .input-group-addon.primary {
        color: rgb(255, 255, 255);
        background-color: rgb(50, 118, 177);
        border-color: rgb(40, 94, 142);
    }
</style>

<script>	
	function genera_formulario(){
                xajax_genera_formulario_reporte();
                generaSelect2();
	}	
		
    function generaSelect2(){
        $('.select2').select2();
    }

    function cargar_sucu(){
            xajax_cargar_sucu( xajax.getFormValues("form1") );
    }    
    
    function anadir_elemento(x, i, elemento, form ){            
        var lista = document.getElementById(form);
        var option = new Option(elemento,i);
        lista.options[x] = option;
    }
    
    function borrar_lista(form){
        document.getElementById(form).options.length= 0;
    }
  
    function consultar(){
        // COMERCIAL        
        var validado = $("#form1").valid();			
        if (validado) {
         //   jsShowWindowLoad();
            xajax_consultar( xajax.getFormValues("form1") );
		}  
    }
  
	function cargar_fechas(){			
		document.getElementById("divFecDesde").style.display='';
		document.getElementById("divFecHasta").style.display='';
		document.getElementById("divAnio").style.display='none';
		document.getElementById("divMes").style.display='none';
		document.getElementById("divMesFin").style.display='none';
		document.getElementById("periodo").checked = false;
		document.getElementById("periodo").value = 'I';		
		document.getElementById("fechas").value = 'A';		
	}
  
	function cargar_periodos(){
		document.getElementById("divAnio").style.display='';
		document.getElementById("divMes").style.display='';
		document.getElementById("divMesFin").style.display='';
		document.getElementById("divFecDesde").style.display='none';
		document.getElementById("divFecHasta").style.display='none';
		document.getElementById("fechas").checked = false;
		document.getElementById("periodo").value = 'A';	
		document.getElementById("fechas").value = 'I';				
	}
    // abrir archivo excel
    function abrir() {
        document.location = "../conta_r_est_result_centro_actividad/excel.php";
    }

    function generar_pdf_mayor_flujo_caja() {
        var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=730, height=.370, top=255, left=130";
        var pagina = '../../Include/documento_pdf3_mayor_flujo_caja.php?sesionId=<?= session_id() ?>';
        window.open(pagina, "", opciones);
    }
</script>
<body>
        <div class="row" id="Div_Principal">
            <form id="form1" class="form-horizontal" name="form1" action="javascript:void(null);">
                <div class="main-row col-md-12">
                    <div class="col-md-12">
                        <h4 class="text-primary">REPORTE <small> MAYOR POR FLUJO DE CAJA </small></h4>
                            <?
                                global $DSN_Ifx, $DSN;
                                if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
                                $idempresa  = $_SESSION['U_EMPRESA'];
                                $idsucursal = $_SESSION['U_SUCURSAL'];
                                $idPerfil   = $_SESSION['U_PERFIL'];

                                $oCon = new Dbo;
                                $oCon->DSN = $DSN;
                                $oCon->Conectar();
                                
                                $oIfx = new Dbo;
                                $oIfx->DSN = $DSN_Ifx;
                                $oIfx->Conectar();

                                $fu = new Formulario;
                                $fu->DSN = $DSN;

                                $sql_empr = '';
                                if ($idPerfil != 1 && $idPerfil != 2) {
                                    $sql_empr = " where empr_cod_empr = $idempresa ";
                                }

                                // EMPRESA
                                $sql = "select empr_cod_empr, empr_nom_empr from saeempr $sql_empr ";
                                $lista_empr = lista_boostrap_func($oIfx, $sql, $idempresa, 'empr_cod_empr',  'empr_nom_empr' );

                                $sqlSucu = "";
                                if ($idPerfil != 1 && $idPerfil != 2) {
                                    $sqlSucu = " and sucu_cod_sucu = $idsucursal";
                                }

                                $sql = "select sucu_cod_sucu, sucu_nom_sucu
                                        from saesucu  where sucu_cod_empr = $idempresa
                                        $sqlSucu";
                                $lista_sucu = lista_boostrap_func($oIfx, $sql, $idsucursal, 'sucu_cod_sucu',  'sucu_nom_sucu' );    
                                
                                $sql = "select grpv_cod_grpv, grpv_nom_grpv from saegrpv where
                                            grpv_cod_empr = $idempresa and
                                            grpv_cod_modu = 3 ";
                                $lista_grupo = lista_boostrap_func($oIfx, $sql, '', 'grpv_cod_grpv',  'grpv_nom_grpv' );  

                                // FECHAS
                                $id_anio = date("Y");
                                $id_mes  = date("m");
								$fechaActual = date("Y-m-d");
                                $sql = "select ejer_cod_ejer from saeejer where DATE_PART('year', ejer_fec_inil) = $id_anio and ejer_cod_empr = $idempresa ";
                                $ejer_cod_ejer = consulta_string_func($sql, 'ejer_cod_ejer', $oIfx, 0);

                                $sql = "select ejer_cod_ejer,  DATE_PART('year', ejer_fec_inil) as anio from saeejer where
                                                ejer_cod_empr = $idempresa order by 2 desc ";
                                $lista_ejer = lista_boostrap_func($oIfx, $sql, $id_anio, 'anio',  'anio' );   

                                $sql = "select prdo_num_prdo, prdo_nom_prdo from saeprdo where
                                                prdo_cod_empr = $idempresa and
                                                prdo_cod_ejer = $ejer_cod_ejer
                                                order by 1 ";
                                $lista_mes = lista_boostrap_func($oIfx, $sql, $id_mes, 'prdo_num_prdo',  'prdo_nom_prdo' );
                                // LISTA DE CUENTAS
                                $sql = "select min(cuen_ord_cuen) as cta_min, max(cuen_ord_cuen) as cta_max
                                        from saecuen
                                        where cuen_cod_empr = $idempresa and cuen_mov_cuen = 1";
                                $cuen_min = consulta_string_func($sql, 'cta_min', $oIfx, 0);
                                $cuen_max = consulta_string_func($sql, 'cta_max', $oIfx, 0);
                
                                $sql = " select cuen_ord_cuen, (cuen_cod_cuen||' '||cuen_nom_cuen) as cuen_nom_cuen, cuen_cod_cuen
                                         from saecuen where cuen_cod_empr = $idempresa and cuen_mov_cuen = 1 order by 3 ";
                                $listaCuentas  = lista_boostrap_func($oIfx, $sql, $cuen_min, 'cuen_ord_cuen',  'cuen_nom_cuen' );
                                $listaCuentasF = lista_boostrap_func($oIfx, $sql, $cuen_max, 'cuen_ord_cuen',  'cuen_nom_cuen' );
                                // LISTA CENTRO DE ACTIVIDAD   
                                $sql = "SELECT min(cact_cod_cact)as minimo, max(cact_cod_cact) as maximo 
                                        FROM saecact WHERE cact_cod_empr = $idempresa ";
                                $cact_min = consulta_string_func($sql, 'minimo', $oIfx, 0);
                                $cact_max = consulta_string_func($sql, 'maximo', $oIfx, 0);

                                $sql = " select cact_cod_cact, (cact_cod_cact||' '||cact_nom_cact) as cact_nom_cact
                                         from saecact where cact_cod_empr = $idempresa order by 1 ";
                                $listaCActi  = lista_boostrap_func($oIfx, $sql, $cact_min, 'cact_cod_cact',  'cact_nom_cact' );
                                $listaCActiF = lista_boostrap_func($oIfx, $sql, $cact_max, 'cact_cod_cact',  'cact_nom_cact' );

                                // LISTA MODEDA
                                $sql = " select pcon_mon_base from saepcon where pcon_cod_empr = $idempresa ";
                                $monedaBase = consulta_string_func($sql, 'pcon_mon_base', $oIfx, 0);

								$sql = " select mone_cod_mone, mone_des_mone from saemone where mone_cod_empr = $idempresa and mone_est_mone = '1' order by 1 ";
                                $lista_moneda = lista_boostrap_func($oIfx, $sql, $monedaBase, 'mone_cod_mone',  'mone_des_mone' );

                            ?>
                    </div>
                    <div class="col-md-12">
                            <div class="btn-group">
                                <div class="btn btn-primary btn-sm" onclick="location.reload();">
                                    <span class="glyphicon glyphicon-file"></span>
                                    Nuevo
                                </div>
                                <div class="btn btn-primary btn-sm" onclick="abrir()" >
                                    <span class="glyphicon glyphicon-print"></span>
                                    Excel
                                </div>
                                <div class="btn btn-primary btn-sm" onclick="generar_pdf_mayor_flujo_caja()">
                                    <span class="glyphicon glyphicon-print"></span>
                                    Imprimir
                                </div>
                            </div>                
                    </div>                  

                    <div class="col-md-12">
                        <div class="form-row">
                            <div class="col-md-3">
                                <label for="empresa">* Empresa </label>
                                <select id="empresa" name="empresa" class="form-control input-sm" onchange="cargar_sucu();" required>
                                    <option value="0">Seleccione una opcion..</option>
                                    <?=$lista_empr;?>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label for="periodo"> Por Mes </label>
                                <input type="checkbox" id="periodo" name="periodo" value="A" onchange="cargar_periodos();" checked>
                            </div>
							 <div class="col-md-2">
                                <label for="fechas"> Por Fechas </label>
                                <input type="checkbox" id="fechas" name="fechas" value="I" onchange="cargar_fechas();">
                            </div>
                            <div class="col-md-1" id="divAnio">
                                <label for="anio"> * A&ntildeo </label>
                                <select id="anio" name="anio" class="form-control input-sm" >
                                    <option value="0">Seleccione una opcion..</option>
                                    <?=$lista_ejer;?>
                                </select>
                            </div>
                            <div class="col-md-2" id="divMes">
                                <label for="mes"> * Mes Inicial </label>
                                <select id="mes" name="mes" class="form-control input-sm" >
                                    <option value="0">Seleccione una opcion..</option>
                                    <?=$lista_mes;?>
                                </select>
                            </div>
                            <div class="col-md-2" id="divMesFin">
                                <label for="mes_fin"> * Mes Final </label>
                                <select id="mes_fin" name="mes_fin" class="form-control input-sm" >
                                    <option value="0">Seleccione una opcion..</option>
                                    <?=$lista_mes;?>
                                </select>
                            </div>
                            <div class="col-md-2" id="divFecDesde" style= "display:none">
                                <label for="fechaInicio"> * Fecha Desde </label>
                                <input type="date" id="fechaInicio" name="fechaInicio" value="<?=$fechaActual;?>">
                            </div>
                            <div class="col-md-2" id="divFecHasta" style= "display:none">
                                <label for="fechaFinal"> * Fecha Hasta </label>
                                <input type="date" id="fechaFinal" name="fechaFinal" value="<?=$fechaActual;?>">
                            </div>
                            <div class="col-md-1" id="divMoneda">
                                <label for="moneda"> * Moneda </label>
                                <select id="moneda" name="moneda" class="form-control input-sm" >
                                    <option value="0">Seleccione una opcion..</option>
                                    <?=$lista_moneda;?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-row">
                        <div class="col-md-3">
                            <label for="cuenta_ini"> Cuenta Inicial </label>
                                <select id="cuenta_ini" name="cuenta_ini" class="form-control input-sm">
                                    <option value="">Seleccione una opcion..</option>
                                    <?=$listaCuentas;?>
                                </select>
                            </div>
							<div class="col-md-3">
                                <label for="cuenta_fin"> Cuenta Final </label>
                                <select id="cuenta_fin" name="cuenta_fin" class="form-control input-sm">
                                    <option value="">Seleccione una opcion..</option>
                                    <?=$listaCuentasF;?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="ccostos_ini"> C. Actividad Inicial </label>
                                <select id="ccostos_ini" name="ccostos_ini" class="form-control input-sm">
                                    <option value="">Seleccione una opcion..</option>
                                    <?=$listaCActi;?>
                                </select>
                            </div>
							<div class="col-md-2">
                                <label for="ccostos_fin"> C. Actividad Final </label>
                                <select id="ccostos_fin" name="ccostos_fin" class="form-control input-sm">
                                    <option value="">Seleccione una opcion..</option>
                                    <?=$listaCActiF;?>
                                </select>
                            </div>
                            
                            
                            <div class="col-md-2">
                                <div><label for="consultar">* Consultar:</label></div>
                                <div class="btn btn-primary btn-sm" onclick="consultar();" style="width: 100%">
                                    <span class="glyphicon glyphicon-search"></span>
                                    Consultar
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div id="divFormularioDetalle"  class="table-responsive"></div>
                </div>
                <div class="col-md-12">
                    <div id="divTotal"  class="table-responsive"></div>
                </div>                 
                
                <div style="width: 100%;">
                    <div class="modal fade" id="ModalClpv"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel"  aria-hidden="true"></div>	
                    <div class="modal fade" id="ModalProd"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true"></div>                           
                </div>
            </form>
        </div>
        <br><br><br>
</body>



<script>genera_formulario();</script>
<? /********************************************************************/ ?>
<? /* NO MODIFICAR ESTA SECCION*/ ?>
<? } ?>
<? include_once(FOOTER_MODULO); ?>
<? /********************************************************************/ ?>

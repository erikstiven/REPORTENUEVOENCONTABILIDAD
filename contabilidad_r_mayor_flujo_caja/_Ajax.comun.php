<?php
/* ARCHIVO COMUN PARA LA EJECUCION DEL SERVIDOR AJAX DEL MODULO */
/***************************************************/
/* NO MODIFICAR */
include_once('../../Include/config.inc.php');
include_once(path(DIR_INCLUDE).'conexiones/db_conexion.php');
include_once(path(DIR_INCLUDE).'comun.lib.php');
include_once(path(DIR_INCLUDE).'Clases/Formulario/Formulario.class.php');
require_once (path(DIR_INCLUDE).'Clases/xajax/xajax_core/xajax.inc.php');
require_once (path(DIR_INCLUDE).'Clases/Equipos.class.php');
require_once (path(DIR_INCLUDE).'Clases/Contratos.class.php');
require_once (path(DIR_INCLUDE).'Clases/Tareas.class.php');


/***************************************************/
/* INSTANCIA DEL SERVIDOR AJAX DEL MODULO*/
$xajax = new xajax('_Ajax.server.php');
$xajax->setCharEncoding('ISO-8859-1');
/***************************************************/
//	FUNCIONES PUBLICAS DEL SERVIDOR AJAX DEL MODULO 
//	Aqui registrar todas las funciones publicas del servidor ajax
//	Ejemplo,
//	$xajax->registerFunction("Nombre de la Funcion");
/***************************************************/
// envio muestra
$xajax->registerFunction("genera_formulario_reporte");
$xajax->registerFunction("guardar");
$xajax->registerFunction("reporte");

// retorno muestra
$xajax->registerFunction("genera_formulario_retorno");
$xajax->registerFunction("guardar_ret");
$xajax->registerFunction("reporte_ret");
$xajax->registerFunction("cargar_costos");
$xajax->registerFunction("cargar_arbol");
$xajax->registerFunction("cargar_bode");
$xajax->registerFunction("cargar_talla");
$xajax->registerFunction("cargar_color");
$xajax->registerFunction("cargar_prod");

$xajax->registerFunction("cargar_sucu");
$xajax->registerFunction("consultar");
/***************************************************/
?>

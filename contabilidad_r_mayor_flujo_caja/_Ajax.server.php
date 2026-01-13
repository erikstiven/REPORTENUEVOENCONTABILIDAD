<?php
require("_Ajax.comun.php"); // No modificar esta linea
require_once 'reader/Classes/PHPExcel/IOFactory.php';

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// S E R V I D O R   A J A X //
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/**
Herramientas de apoyo 
 */
// 
function consultar( $aForm='' ){
    //Definiciones
		global $DSN_Ifx, $DSN;

		if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

	    $oCon = new Dbo;
		$oCon -> DSN = $DSN;
		$oCon -> Conectar();

        $oConA = new Dbo;
        $oConA -> DSN = $DSN;
        $oConA -> Conectar();

		$oIfx = new Dbo;
		$oIfx -> DSN = $DSN_Ifx;
		$oIfx -> Conectar();

		$oReturn = new xajaxResponse();
        $user_web   = $_SESSION['U_ID']; 
		$idempresa   = $aForm['empresa'];
	    $idsucursal  = isset($aForm['sucursal']) ? $aForm['sucursal'] : '';
		$anio        = $aForm['anio'];        
        $mes         = $aForm['mes'];
        $mes_fin     = $aForm['mes_fin'];
        $cuenta_ini  = $aForm['cuenta_ini'];
		$cuenta_fin  = $aForm['cuenta_fin'];
        $cact_ini    = $aForm['ccostos_ini'];
		$cact_fin    = $aForm['ccostos_fin'];
		$moneda      = isset($aForm['moneda']) ? $aForm['moneda'] : '';
		
		$periodo	= $aForm['periodo'];
		$fechas		= $aForm['fechas'];
		$fechaInicioRaw = isset($aForm['fechaInicio']) ? $aForm['fechaInicio'] : '';
		$fechaFinalRaw = isset($aForm['fechaFinal']) ? $aForm['fechaFinal'] : '';
		$fechaInicio= fecha_informix_func($fechaInicioRaw);
		$fechaFinal	= fecha_informix_func($fechaFinalRaw);
		
		//echo 'Periodo: '.$periodo.' - Fechas: '.$fechas; exit;		
		$sql = "select prdo_cod_ejer, prdo_fec_ini, prdo_nom_prdo 
				from saeprdo 
				where prdo_cod_empr = $idempresa
				and prdo_num_prdo = $mes
				and DATE_PART('year', prdo_fec_ini) = $anio"; 
		$fec_i = consulta_string($sql, 'prdo_fec_ini', $oIfx, '');		
		$nombreMesI = consulta_string($sql, 'prdo_nom_prdo', $oIfx, '');		
		$codEjercicio = consulta_string($sql, 'prdo_cod_ejer', $oIfx, '');		
		// CALCULAR FECHA INICIO DEL AÑO ANTERIOR
		//$dato = explode("/", $fec_i);  
		//$anioAnt = $dato[2] -1;
		//$fechaAnt = "01/01/".$anioAnt;

		$sql = "select prdo_cod_ejer, prdo_fec_fin, prdo_nom_prdo 
		from saeprdo 
		where prdo_cod_empr = $idempresa
		and prdo_num_prdo = $mes_fin
		and DATE_PART('year', prdo_fec_ini) = $anio"; 
		$fec_f = consulta_string($sql, 'prdo_fec_fin', $oIfx, '');				
		$nombreMesF = consulta_string($sql, 'prdo_nom_prdo', $oIfx, '');		

		if ($periodo == "A") {
			$fechaInicio = $fec_i;
			$fechaFinal  = $fec_f;
		}
		// NOMBRE DE MESES
		$sql = "SELECT  prdo_num_prdo, prdo_nom_prdo 
				FROM saeprdo 
				WHERE prdo_cod_empr = $idempresa
				AND DATE_PART('year', prdo_fec_ini) = $anio ";
		if($oIfx->Query($sql)){
			if($oIfx->NumFilas() > 0){	
				unset($arrayMes);
				do{
					$arrayMes[$oIfx->f('prdo_num_prdo')] = ($oIfx->f('prdo_nom_prdo'));						
				}while($oIfx->SiguienteRegistro());
			}
		}	
		$oIfx->Free();
		$sql = "select cuen_cod_cuen, cuen_nom_cuen
				from saecuen where cuen_mov_cuen = 1
				and cuen_cod_empr = $idempresa
				order by cuen_ord_cuen
				";
				if($oIfx->Query($sql)){
					if($oIfx->NumFilas() > 0){	
						unset($arrayCuenta);
						do{
							$arrayCuenta[$oIfx->f('cuen_cod_cuen')] = ($oIfx->f('cuen_nom_cuen'));						
						}while($oIfx->SiguienteRegistro());
					}
				}	
		$oIfx->Free();        
		try {
			$empresaNombre = consulta_string_func("select empr_nom_empr from saeempr where empr_cod_empr = $idempresa", 'empr_nom_empr', $oIfx, '');
			$empresaDireccion = consulta_string_func("select empr_dir_empr from saeempr where empr_cod_empr = $idempresa", 'empr_dir_empr', $oIfx, '');
			$empresaRuc = consulta_string_func("select empr_ruc_empr from saeempr where empr_cod_empr = $idempresa", 'empr_ruc_empr', $oIfx, '');
			$sucursalNombre = '';
			if (!empty($idsucursal)) {
				$sucursalNombre = consulta_string_func("select sucu_nom_sucu from saesucu where sucu_cod_empr = $idempresa and sucu_cod_sucu = $idsucursal", 'sucu_nom_sucu', $oIfx, '');
			}
			if (empty($sucursalNombre) && !empty($_SESSION['U_SUCURSAL'])) {
				$sucuSesion = $_SESSION['U_SUCURSAL'];
				$sucursalNombre = consulta_string_func("select sucu_nom_sucu from saesucu where sucu_cod_empr = $idempresa and sucu_cod_sucu = $sucuSesion", 'sucu_nom_sucu', $oIfx, '');
			}
			$monedaNombre = '';
			if (!empty($moneda)) {
				$monedaNombre = consulta_string_func("select mone_des_mone from saemone where mone_cod_empr = $idempresa and mone_cod_mone = '$moneda'", 'mone_des_mone', $oIfx, '');
			}
			$descripcionPeriodo = '';
			if ($periodo == "A") {
				$descripcionPeriodo = 'Periodo: ' . $nombreMesI . ' ' . $anio . ' - ' . $nombreMesF . ' ' . $anio;
			} else {
				$descripcionPeriodo = 'Fechas: ' . $fechaInicioRaw . ' al ' . $fechaFinalRaw;
			}
			$fechaGenerado = date('d/m/Y');
			$horaGenerado = date('H:i:s');
			$desdeTexto = $fechaInicioRaw;
			$hastaTexto = $fechaFinalRaw;
			$headerPdf = '<table class="report-header" cellpadding="0" cellspacing="0" style="width: 100%;">
				<tr>
					<td style="text-align:center; font-size: 12pt; font-weight: bold;">' . $empresaNombre . '</td>
				</tr>
				<tr>
					<td style="text-align:center; font-size: 8pt;">Dirección:</td>
				</tr>
				<tr>
					<td style="text-align:center; font-size: 8pt;">' . $empresaDireccion . '</td>
				</tr>
				<tr>
					<td style="text-align:center; font-size: 8pt;">RUC:</td>
				</tr>
				<tr>
					<td style="text-align:center; font-size: 8pt;">' . $empresaRuc . '</td>
				</tr>
				<tr>
					<td style="text-align:center; font-size: 10pt; font-weight: bold;">MAYOR CENTRO DE ACTIVIDAD</td>
				</tr>
				<tr>
					<td>
						<table class="report-meta" cellpadding="0" cellspacing="0" style="width: 100%;">
							<tr>
								<td style="font-size: 8pt;"><strong>Fecha:</strong> ' . $fechaGenerado . '&nbsp;&nbsp;&nbsp;<strong>Hora:</strong> ' . $horaGenerado . '</td>
								<td style="font-size: 8pt; text-align:center;"><strong>Desde:</strong> ' . $desdeTexto . '</td>
								<td style="font-size: 8pt; text-align:center;"><strong>Hasta:</strong> ' . $hastaTexto . '</td>
								<td style="font-size: 8pt; text-align:right;"><strong>Pag:</strong> 1/1</td>
							</tr>
						</table>
					</td>
				</tr>
			</table><br>';

				$sql = "  SELECT DISTINCT (saedasi.dasi_cod_cuen ),
				date('$fechaInicio') asto_fec_asto,   
				date_part('month', date('$fechaInicio')) as mes,   
				'SA' asto_tipo_mov,   
				'000000' asto_num_mayo,   
				max(saeasto.asto_cot_asto) asto_cot_asto,
				max(saedasi.dasi_tip_camb) dasi_tip_camb,
				'SALDO ANTERIOR' dasi_det_asi,   
				0 dasi_dml_dasi,   
				0 dasi_cml_dasi,
				   0 dasi_dme_dasi,   
				  0 dasi_cme_dasi,
				date('$fechaInicio')  asto_fec_fina,  
				   max(saepcon.pcon_mon_base) pcon_mon_base, 
				saecact.cact_cod_cact,   
				saecact.cact_nom_cact,   
				0.000001 cotizacion,   
				0.00 credito,   
				0.00 debito,   
				sum(COALESCE(saedasi.dasi_dml_dasi,0) - COALESCE(saedasi.dasi_cml_dasi,0)) saldo_anterior,   
				0.000 saldos,   
				' ' cuen_nom_cuen,   
				--' ' dchc_benf_dchc,   
				' ' asto_ben_asto,
				   ' ' dasi_num_depo  ,
				   1 TIPO
		   FROM saeasto,   
				saedasi,   
				saecuen,   
				--saedchc,
				saepcon,
				saecact
		  WHERE ( saecuen.cuen_cod_cuen = saedasi.dasi_cod_cuen ) and  
				( saeasto.asto_cod_asto = saedasi.asto_cod_asto ) and  
				( saeasto.asto_cod_empr = saedasi.asto_cod_empr ) and  
				( saeasto.asto_cod_sucu = saedasi.asto_cod_sucu ) and  
				( saeasto.asto_cod_ejer = saedasi.asto_cod_ejer ) and  
				( saecuen.cuen_cod_empr = saedasi.asto_cod_empr ) and  
				--( saedchc.dchc_cod_asto = saeasto.asto_cod_asto ) and  
				--( saedchc.asto_cod_empr = saeasto.asto_cod_empr ) and  
				--( saedchc.asto_cod_sucu = saeasto.asto_cod_sucu ) and  
				--( saedchc.asto_cod_ejer = saeasto.asto_cod_ejer ) and  
				( saeasto.asto_cod_empr = saepcon.pcon_cod_empr ) and
				( saedasi.dasi_cod_cact = saecact.cact_cod_cact ) and  
				( saedasi.asto_cod_empr = saecact.cact_cod_empr ) and
				( ( saeasto.asto_cod_empr = $idempresa  ) AND  
				( saeasto.asto_fec_asto < '$fechaInicio'  ) AND  
				( saeasto.asto_cod_ejer = $codEjercicio ) AND  
				( saecuen.cuen_ord_cuen between $cuenta_ini and $cuenta_fin ) AND 
				( saecact.cact_cod_cact between '$cact_ini' and '$cact_fin' ) AND				
				( saeasto.asto_est_asto = 'MY' ) )    
	   group by saedasi.dasi_cod_cuen ,
				   saecact.cact_cod_cact,   
				saecact.cact_nom_cact   
	   HAVING sum(COALESCE(saedasi.dasi_dml_dasi,0) - COALESCE(saedasi.dasi_cml_dasi,0))<>0
	   
	   UNION all
	   
		 SELECT saedasi.dasi_cod_cuen,   
				saeasto.asto_fec_asto, 
				date_part('month',saeasto.asto_fec_asto) as mes, 
				saeasto.asto_tipo_mov,   
				saeasto.asto_num_mayo,   
				   saeasto.asto_cot_asto,
				   saedasi.dasi_tip_camb,
				saedasi.dasi_det_asi,   
				saedasi.dasi_dml_dasi,   
				saedasi.dasi_cml_dasi,
				   saedasi.dasi_dme_dasi,   
				  saedasi.dasi_cme_dasi,
				saeasto.asto_fec_fina,  
				   saepcon.pcon_mon_base, 
				   saecact.cact_cod_cact,   
				saecact.cact_nom_cact,   
				0.0000001 cotizacion,   
				0.001 credito,   
				0.001 debito,   
				0.000 saldo_anterior,   
				0.001 saldos,   
				saecuen.cuen_nom_cuen,      
				saeasto.asto_ben_asto,
				   saedasi.dasi_num_depo  ,
				   2 TIPO
		   FROM saeasto,   
				saedasi,   
				saecuen,   
				--saedchc,
				saepcon,
				saecact
		  WHERE ( saecuen.cuen_cod_cuen = saedasi.dasi_cod_cuen ) and  
				( saeasto.asto_cod_asto = saedasi.asto_cod_asto ) and  
				( saeasto.asto_cod_empr = saedasi.asto_cod_empr ) and  
				( saeasto.asto_cod_sucu = saedasi.asto_cod_sucu ) and  
				( saeasto.asto_cod_ejer = saedasi.asto_cod_ejer ) and  
				( saecuen.cuen_cod_empr = saedasi.asto_cod_empr ) and  
				--( saedchc.dchc_cod_asto = saeasto.asto_cod_asto ) and  
				--( saedchc.asto_cod_empr = saeasto.asto_cod_empr ) and  
				--( saedchc.asto_cod_sucu = saeasto.asto_cod_sucu ) and  
				--( saedchc.asto_cod_ejer = saeasto.asto_cod_ejer ) and  
				   ( saeasto.asto_cod_empr = saepcon.pcon_cod_empr ) and
				( saedasi.dasi_cod_cact = saecact.cact_cod_cact ) and  
				( saedasi.asto_cod_empr = saecact.cact_cod_empr ) and
				( ( saeasto.asto_cod_empr = $idempresa  ) AND  
				( saeasto.asto_fec_asto between '$fechaInicio' and '$fechaFinal' ) AND  
				( saeasto.asto_cod_ejer = $codEjercicio ) AND  
				( saecuen.cuen_ord_cuen between $cuenta_ini and $cuenta_fin ) AND 
				( saecact.cact_cod_cact between '$cact_ini' and '$cact_fin' ) AND				
				( saeasto.asto_est_asto = 'MY' ) )  
				ORDER BY 15, 1, 2  
	   ";		
				//echo $sql; exit;		
				//var_dump ($arrayDatos); exit;
				//unset ($_SESSION['ACT_REPORTE']);
			
					$html.='</br>
						<table class="table table-bordered table-striped table-condensed" style="width: 98%; margin-bottom: 0px; margin-left: 10px;">
						<tr>						
							<td class="bg-primary" align = "center"> Fecha </td>
							<td class="bg-primary" align = "center"> Tipo </td>
							<td class="bg-primary" align = "center"> No. Comprobante </td>
							<td class="bg-primary" align = "center"> Beneficiario </td>
							<td class="bg-primary" align = "center"> Detalles </td>
							<td class="bg-primary" align = "center"> Debito </td>
							<td class="bg-primary" align = "center"> Credito </td>
							<td class="bg-primary" align = "center"> Saldo </td>
						</tr>';
			//CENTRO DE COSTOS
			/*if($oIfx->Query($sql)){
				if($oIfx->NumFilas() > 0){	
					unset($arrayTotales);
					do{
						$arrayTotales[$oIfx->f('dasi_cod_cact')] = ($oIfx->f('acumulado'));						
					}while($oIfx->SiguienteRegistro());
				}
			}*/
			$i = 1;
			$sumaDebito = 0;
			$sumaCredito = 0;
			if($oIfx->Query($sql)){
				if($oIfx->NumFilas() > 0){
					do{
						$sumaDebito  += $oIfx->f('dasi_dml_dasi');
						$sumaCredito += $oIfx->f('dasi_cml_dasi');
	
						if ($i == 1){
							//agrege erik
							$tipo = $oIfx->f('asto_tipo_mov');
							//fin agrege erik
									$html.='<tr>
												<td class="bg-info" colspan="8"> '.$oIfx->f('cact_cod_cact').' '.$oIfx->f('cact_nom_cact').' </td>
											</tr>
											<tr class="report-saldo">											
												<td colspan="4"> '.$oIfx->f('dasi_cod_cuen').' '.$arrayCuenta[$oIfx->f('dasi_cod_cuen')].' </td>
												<td colspan="4" style="text-align:right;"> SALDO ANTERIOR: '.number_format( round($oIfx->f('saldo_anterior'),2),2,'.',',').' </td>
											</tr>';
							$anterior = $oIfx->f('cact_cod_cact');
							$cuentaAnterior = $oIfx->f('dasi_cod_cuen');
							$mesAnterior = $oIfx->f('mes');
							$saldoAnterior = $oIfx->f('saldo_anterior');
							//agrege erik
							if ($tipo != 'SA') {
								$saldoCuenta = $saldoAnterior + ( $oIfx->f('dasi_dml_dasi') - $oIfx->f('dasi_cml_dasi') );
								$html.='<tr>
											<td> </td>
											<td colspan="7"> '.$arrayMes[$mesAnterior].' </td>
										</tr>
										<tr>
											<td> '.$oIfx->f('asto_fec_asto').' </td>
											<td> '.$oIfx->f('asto_tipo_mov').' </td>
											<td> '.$oIfx->f('asto_num_mayo').' </td>
											<td> '.$oIfx->f('asto_ben_asto').' </td>
											<td> '.$oIfx->f('dasi_det_asi').' </td>
											<td style="text-align:right;"> '.number_format( round($oIfx->f('dasi_dml_dasi'),2),2,'.',',').' </td>
											<td style="text-align:right;"> '.number_format( round($oIfx->f('dasi_cml_dasi'),2),2,'.',',').' </td>
											<td style="text-align:right;"> '.number_format( round($saldoCuenta,2),2,'.',',').' </td>												
										</tr>';
								$saldoAnterior = $saldoCuenta;
							}
							//fin agrege erik
						} else {
							$actual = $oIfx->f('cact_cod_cact');
							$cuentaActual = $oIfx->f('dasi_cod_cuen');
							$tipo = $oIfx->f('asto_tipo_mov');
							$mesActual = $oIfx->f('mes');														
							if ( $actual == $anterior ){
								if( ( $cuentaActual == $cuentaAnterior ) && ( $tipo !='SA' ) ){
									$saldoCuenta = $saldoAnterior + ( $oIfx->f('dasi_dml_dasi') - $oIfx->f('dasi_cml_dasi') );
									if ( $mesActual != $mesAnterior) {
										$html.='<tr>
													<td> </td>
													<td colspan="7"> '.$arrayMes[$mesActual].' </td>
												</tr>';	

									}
									$html.='<tr>
												<td> '.$oIfx->f('asto_fec_asto').' </td>
												<td> '.$oIfx->f('asto_tipo_mov').' </td>
												<td> '.$oIfx->f('asto_num_mayo').' </td>
												<td> '.$oIfx->f('asto_ben_asto').' </td>
												<td> '.$oIfx->f('dasi_det_asi').' </td>
												<td style="text-align:right;"> '.number_format( round($oIfx->f('dasi_dml_dasi'),2),2,'.',',').' </td>
												<td style="text-align:right;"> '.number_format( round($oIfx->f('dasi_cml_dasi'),2),2,'.',',').' </td>
												<td style="text-align:right;"> '.number_format( round($saldoCuenta,2),2,'.',',').' </td>												
											</tr>';
											$saldoAnterior = $saldoCuenta;
								} else {
										$html.='<tr class="report-saldo">													
													<td colspan="4"> '.$oIfx->f('dasi_cod_cuen').' '.$arrayCuenta[$oIfx->f('dasi_cod_cuen')].' </td>
													<td colspan="4" style="text-align:right;"> SALDO ANTERIOR: '.number_format( round($oIfx->f('saldo_anterior'),2),2,'.',',').' </td>
												</tr>';
										$saldoAnterior = $oIfx->f('saldo_anterior');
										$mesAnterior = $oIfx->f('mes');	
										$html.='<tr>
													<td> </td>
													<td colspan="7"> '.$arrayMes[$mesActual].' </td>
												</tr>';											
								}

							} else {
								$html.='<tr>
											<td class="bg-info" colspan="8"> '.$oIfx->f('cact_cod_cact').' '.$oIfx->f('cact_nom_cact').' </td>
										</tr>
											<tr class="report-saldo">										
												<td colspan="4"> '.$oIfx->f('dasi_cod_cuen').' '.$arrayCuenta[$oIfx->f('dasi_cod_cuen')].' </td>
												<td colspan="4" style="text-align:right;"> SALDO ANTERIOR: '.number_format( round($oIfx->f('saldo_anterior'),2),2,'.',',').' </td>
											</tr>';
							}
							$anterior = $oIfx->f('cact_cod_cact');
							$cuentaAnterior = $oIfx->f('dasi_cod_cuen');
							$mesAnterior = $oIfx->f('mes');
						}	
						$i++;					
					}while ($oIfx->SiguienteRegistro());
				}
			}

			$html.='<tr class="report-total">
						<td style="text-align:right;" colspan="5"> TOTAL GENERAL: </td>
						<td style="text-align:right;"> '.number_format( round($sumaDebito,2),2,'.',',').'</td>
						<td style="text-align:right;"> '.number_format( round($sumaCredito,2),2,'.',',').'</td>	
						<td style="text-align:right;"> '.number_format( round($saldoCuenta,2),2,'.',',').' </td>					
					</tr>
					</table>';
			$oReturn->assign("divFormularioDetalle","innerHTML",$html);
        } catch (Exception $ex) {
            $oReturn->alert( $ex->getMessage());
        }


        //Armado Cabecera Excel
        //unset($_SESSION['sHtml_cab']);
        //unset($_SESSION['sHtml_det']);
        //$_SESSION['sHtml_det'] = $html;

		unset($_SESSION['pdf']);    
		$_SESSION['pdf'] = $html;
		$_SESSION['pdf_header'] = $headerPdf;

        $oReturn->script("jsRemoveWindowLoad();");
        return $oReturn;
}

// SUCURSAl
function cargar_sucu($aForm = '') {
    //Definiciones
        global $DSN_Ifx;
    
        if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
    
        $oIfx = new Dbo;
        $oIfx->DSN = $DSN_Ifx;
        $oIfx->Conectar();
    
        $oReturn = new xajaxResponse();
        
        $idempresa  = $aForm['empresa'];
        $sucu       = "sucursal";       
            
        // SUCURSAL
        $sql = "select  sucu_cod_sucu, sucu_nom_sucu from saesucu where
                        sucu_cod_empr = $idempresa  ";
        if ($oIfx->Query($sql)) {
            $oReturn->script('borrar_lista(\'' . $sucu . '\');');
            if ($oIfx->NumFilas() > 0) {
                $i = 1;
                do {
                    $detalle = $oIfx->f('sucu_nom_sucu');
                    $oReturn->script(('anadir_elemento(' . $i++ . ',\'' . $oIfx->f('sucu_cod_sucu') . '\', \'' . $detalle . '\' ,\'' . $sucu . '\' )'));
                } while ($oIfx->SiguienteRegistro());
            }
        }
        $oIfx->Free();


        return $oReturn;
}

//FUNCIONES DE EXCEL
function get_cell($cell, $objPHPExcel){
    //select one cell
    $objCell = ($objPHPExcel->getActiveSheet()->getCell($cell));
    //get cell value
    return $objCell->getvalue();
}

function pp(&$var){
    $var = chr(ord($var)+1);
    return true;
}


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/* PROCESO DE REQUEST DE LAS FUNCIONES MEDIANTE AJAX NO MODIFICAR */
$xajax->processRequest();
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

?>

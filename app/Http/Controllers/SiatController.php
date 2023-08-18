<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

require_once  app_path('/Siat/autoload.php');
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioFacturacionSincronizacion;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioSiat;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioFacturacionCodigos;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\SiatConfig;

class SiatController extends Controller
{
	public function facturas(){
		$config =  new SiatConfig([
			'nombreSistema'	=> 'AHORASOFT',
			'codigoSistema'	=> '7712BECBDC82B1442CBE8B7',
			'tipo' 			=> 'PROVEEDOR',
			'nit'			=> 7926141018,
			'razonSocial'	=> 'SAAVEDRA JALIRI LIZBETH ALEJANDRA',
			'modalidad' 	=> ServicioSiat::MOD_ELECTRONICA_ENLINEA,
			'ambiente' 		=> ServicioSiat::AMBIENTE_PRUEBAS,
			'tokenDelegado'	=> 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJMc2FhdmVkcmE3OSIsImNvZGlnb1Npc3RlbWEiOiI3NzEyQkVDQkRDODJCMTQ0MkNCRThCNyIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETzNOREl6TkRFME1MUUFBT0JNU1A0S0FBQUEiLCJpZCI6NDY4MDkxLCJleHAiOjE3MTI3MDcyMDAsImlhdCI6MTY4MTIyMDYyMSwibml0RGVsZWdhZG8iOjc5MjYxNDEwMTgsInN1YnNpc3RlbWEiOiJTRkUifQ.UWylFJWckIpDXToc5W7oabt7BQAOVQ0_IvyQ-jfpklL6IxSYa4T367v2PE20GFNcyzkFjhp5MqmJtyY2oMfOSA',
			'cuis'			=> null,
			'cufd'			=> null,
		]);
		$config->validate();

		$servCodigos = new ServicioFacturacionCodigos(null, null, $config->tokenDelegado);
		$servCodigos->setConfig((array)$config);
		$resCuis = $servCodigos->cuis();
	
		$sync = new ServicioFacturacionSincronizacion($resCuis->RespuestaCuis->codigo, null, $config->tokenDelegado);
		$sync->setConfig((array)$config);

		$tds = call_user_func([$sync, "sincronizarParametricaTipoDocumentoSector"]);
		$tds = (array)$tds;
		$tds = $tds["RespuestaListaParametricas"]->listaCodigos;
		$tds = json_encode($tds);
        return view("facturacion.index", compact("tds"));
    }
}

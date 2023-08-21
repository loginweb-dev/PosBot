<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

require_once  app_path('/Siat/autoload.php');
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioFacturacionSincronizacion;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioSiat;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioFacturacionCodigos;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\SiatConfig;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioOperaciones;

class SiatController extends Controller
{
	public function facturas(){
		$minegocio = \App\User::where("username", Auth::user()->username)->with("business")->first(); 
		$config =  new SiatConfig([
			'nombreSistema'	=> env('FAC_NAME_SYS'),
			'codigoSistema'	=> env('FAC_CODE_SYS'),
			'tipo' 			=> 'PROVEEDOR',
			'nit'			=> $minegocio->business->tax_number_1,
			'razonSocial'	=> $minegocio->business->tax_label_1,
			'modalidad' 	=> ServicioSiat::MOD_ELECTRONICA_ENLINEA,
			'ambiente' 		=> ServicioSiat::AMBIENTE_PRUEBAS,
			'tokenDelegado'	=> env('FAC_TOKEN_SYS'),
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

		$puntos_ventas = new ServicioOperaciones($resCuis->RespuestaCuis->codigo);
		$puntos_ventas->setConfig((array)$config);
		// return (array)$puntos_ventas->consultaPuntoVenta(0);

		// $midata = response()->json([
		// 	'tds' => $tds,
		// 	'puntos_ventas' => $puntos_ventas,
		// 	'cuis' => $resCuis->RespuestaCuis->codigo
		// ]);
		// return $midata;
		$micuis = $resCuis->RespuestaCuis->codigo;
		$micufd = 'en proceso';
        return view("facturacion.index", compact("micuis", "micufd"));
    }
}

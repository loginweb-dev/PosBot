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
		// $misetting = 
		$config =  new SiatConfig([
			'nombreSistema'	=> env('FAC_NAME_SYS'),
			'codigoSistema'	=> env('FAC_CODE_SYS'),
			'tipo' 			=> 'PROVEEDOR',
			'nit'			=> 7926141018,
			'razonSocial'	=> 'SAAVEDRA JALIRI LIZBETH ALEJANDRA',
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
        return view("facturacion.index", compact("tds"));
    }
}

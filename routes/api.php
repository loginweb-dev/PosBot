<?php

use Illuminate\Http\Request;
use App\Events\NewMessage;
use Illuminate\Support\Facades\Auth;

require_once  app_path('/Siat/autoload.php');
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioFacturacionSincronizacion;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioSiat;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioFacturacionCodigos;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\SiatConfig;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\SiatFactory;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Invoices\CompraVenta;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Invoices\InvoiceDetail;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Invoices\SiatInvoice;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioFacturacionElectronica;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//rutas de negocios---------------------------------------
Route::prefix('business')->group(function () {
    Route::get('/id/{id}', function ($id) {
        $bussine = App\Business::where('id', $id)->with('locations')->first();
        return $bussine;
    });
});


//rutas de productos---------------------------------------
Route::prefix('productos')->group(function () {
    Route::get('/id/{id}', function ($id) {
        $producto = App\Product::find($id);
        return $producto;
    });
    Route::get('/busine/{id}', function ($id) {
        $productos = App\Product::where("business_id", $id)->get();
        return $productos;
    });
});
Route::prefix('extras')->group(function () {
    Route::get('/get/{id}', function ($id) {
        $extras = App\Product::where('business_id', $id)->where('category_id', 3)->get();
        return $extras;
    });
});

//rutas de chatbots-------------------------------------
Route::prefix('chatbots')->group(function () {
    Route::post('/save', function (Request $request) {
        // return $request;
        App\Chatbots::create([
            'phone' => $request->phone,
            'message' => $request->message,
            'type' => $request->type,
            'busine_id' => $request->busine_id,
        ]);
        return App\Chatbots::where("phone", $request->phone)->get();
    });

    Route::post('/send', function (Request $request) {
        event(new NewMessage($request->message, $request->phone, $request->type, $request->busine));
    });
});

//leads --------------------------------------------
Route::post('leads', function (Request $request) {
    $midata = App\Lead::where("phone", $request->phone)->first();
    if (!$midata) {
        App\Lead::create([
            'phone' => $request->phone,
            'message' => $request->message,
            'categoria' => 'General',
            'session' => $request->session
        ]);
    }else{
        $midata->message = $request->message;
        $midata->save();
    }
    return $request;
});

//rutas de configuracion---------------------------------------
Route::post('setting', function (Request $request) {
    $minegocio = App\User::where("username", $request->username)->with("business")->first(); 
    $milocation = App\BusinessLocation::where("business_id", $minegocio->business->id)->get();
    $midata = [
        'minegocio' => $minegocio,
        'milocation' => $milocation
    ];
    return $midata;
});

// facturacion ---------------------------------------
//----------------------------------------------------

Route::post('factura/eventos', function (Request $request) {
    $config = new SiatConfig([
        'nombreSistema'	=> env('FAC_NAME_SYS'),
        'codigoSistema'	=> env('FAC_CODE_SYS'),
        'tipo' 			=> 'PROVEEDOR',
        'nit'			=> $request->nit,
        'razonSocial'	=> $request->razonSocial,
        'modalidad' 	=> ServicioSiat::MOD_ELECTRONICA_ENLINEA,
        'ambiente' 		=> ServicioSiat::AMBIENTE_PRUEBAS,
        'tokenDelegado'	=> env('FAC_TOKEN_SYS'),
        'pubCert'        => 'siat/tiluchi/certificado_Sin_Certificado.pem',
        'privCert'        => 'siat/tiluchi/clave_Sin_Certificado.pem',
    ]);
    $codigoPuntoVenta = 0;
    $codigoSucursal = 0;
    $serviceCodigos = new ServicioFacturacionCodigos(null, null, $config->tokenDelegado);
    $serviceCodigos->setConfig((array)$config);
    $resCuis = $serviceCodigos->cuis($codigoPuntoVenta, $codigoSucursal);

    switch ($request->type) {             
        // sincronizarParametricaTipoMoneda
        case 'sincronizarParametricaTipoMoneda':            
            $sync = new ServicioFacturacionSincronizacion($resCuis->RespuestaCuis->codigo, null, $config->tokenDelegado);
            $sync->setConfig((array)$config);
            $res = call_user_func([$sync, 'sincronizarParametricaTipoMoneda']);    
            return $res->RespuestaListaParametricas->listaCodigos; 
            break;  
        case 'sincronizarListaProductosServicios':            
            $sync = new ServicioFacturacionSincronizacion($resCuis->RespuestaCuis->codigo, null, $config->tokenDelegado);
            $sync->setConfig((array)$config);
            $res = call_user_func([$sync, 'sincronizarListaProductosServicios']);    
            return $res->RespuestaListaProductos->listaCodigos; 
            break;  
        case 'sincronizarParametricaTipoPuntoVenta':            
            $sync = new ServicioFacturacionSincronizacion($resCuis->RespuestaCuis->codigo, null, $config->tokenDelegado);
            $sync->setConfig((array)$config);
            $res = call_user_func([$sync, 'sincronizarParametricaTipoPuntoVenta']);    
            return $res->RespuestaListaParametricas->listaCodigos; 
            break;  
        case 'sincronizarListaLeyendasFactura':            
            $sync = new ServicioFacturacionSincronizacion($resCuis->RespuestaCuis->codigo, null, $config->tokenDelegado);
            $sync->setConfig((array)$config);
            $res = call_user_func([$sync, 'sincronizarListaLeyendasFactura']);    
            return $res->RespuestaListaParametricasLeyendas->listaLeyendas; 
            break;  
        case 'sincronizarParametricaTipoEmision':            
            $sync = new ServicioFacturacionSincronizacion($resCuis->RespuestaCuis->codigo, null, $config->tokenDelegado);
            $sync->setConfig((array)$config);
            $res = call_user_func([$sync, 'sincronizarParametricaTipoEmision']);    
            return $res->RespuestaListaParametricas->listaCodigos;     
            break;  
        case 'sincronizarActividades':            
            $sync = new ServicioFacturacionSincronizacion($resCuis->RespuestaCuis->codigo, null, $config->tokenDelegado);
            $sync->setConfig((array)$config);
            $res = call_user_func([$sync, 'sincronizarActividades']);    
            return $res->RespuestaListaActividades->listaActividades;     
            break;       
        case 'verificarComunicacion':            
            $sync = new ServicioFacturacionSincronizacion($resCuis->RespuestaCuis->codigo, null, $config->tokenDelegado);
            $sync->setConfig((array)$config);
            $res = call_user_func([$sync, 'verificarComunicacion']);    
            return (array)$res;     
            break;       
        case 'sincronizarParametricaTipoMoneda':
            $sync = new ServicioFacturacionSincronizacion($resCuis->RespuestaCuis->codigo, null, $config->tokenDelegado);
            $sync->setConfig((array)$config);
            $res = call_user_func([$sync, 'sincronizarParametricaTipoMoneda']);
            return $res->RespuestaListaParametricas->listaCodigos;
            break;
        case 'sincronizarParametricaTipoDocumentoSector':
            $sync = new ServicioFacturacionSincronizacion($resCuis->RespuestaCuis->codigo, null, $config->tokenDelegado);
            $sync->setConfig((array)$config);
            $res = call_user_func([$sync, 'sincronizarParametricaTipoDocumentoSector']);
            return $res->RespuestaListaParametricas->listaCodigos;
            break;
        case 'sincronizarParametricaTiposFactura':
            $sync = new ServicioFacturacionSincronizacion($resCuis->RespuestaCuis->codigo, null, $config->tokenDelegado);
            $sync->setConfig((array)$config);
            $res = call_user_func([$sync, 'sincronizarParametricaTiposFactura']);
            return $res->RespuestaListaParametricas->listaCodigos;
            break;
        case 'sincronizarParametricaTipoDocumentoIdentidad':
            $sync = new ServicioFacturacionSincronizacion($resCuis->RespuestaCuis->codigo, null, $config->tokenDelegado);
            $sync->setConfig((array)$config);
            $res = call_user_func([$sync, 'sincronizarParametricaTipoDocumentoIdentidad']);
            return $res->RespuestaListaParametricas->listaCodigos;
            break;
        case 'cuis':
            return (array)$resCuis->RespuestaCuis;
            break;
        case 'cufd':
            $serviceCodigos->cuis = $resCuis->RespuestaCuis->codigo;
            $res = $serviceCodigos->cufd($codigoPuntoVenta, $codigoSucursal);
            return (array)$res->RespuestaCufd;
            break;
        case 'sincronizacion':
            $serviceSync = new ServicioFacturacionSincronizacion($resCuis->RespuestaCuis->codigo);
            $serviceSync->setConfig((array)$config);
            $eventsList = $serviceSync->sincronizarParametricaEventosSignificativos();
            return (array)$eventsList->RespuestaListaParametricas->listaCodigos;
            break;           
        case 'servicio':
            $serviceCodigos->cuis = $resCuis->RespuestaCuis->codigo;
            $resCufd = $serviceCodigos->cufd($codigoPuntoVenta, $codigoSucursal);        
            $service = SiatFactory::obtenerServicioFacturacion($config, $resCuis->RespuestaCuis->codigo, $resCufd->RespuestaCufd->codigo, $resCufd->RespuestaCufd->codigoControl);
            return (array)$service;
            break;    
            
        default:
            return 'sin datos..';
            break;
    }
    
});

Route::post('factura/crear', function (Request $request) {
    $config = new SiatConfig([
        'nombreSistema'	=> env('FAC_NAME_SYS'),
        'codigoSistema'	=> env('FAC_CODE_SYS'),
        'tipo' 			=> 'PROVEEDOR',
        'nit'			=> $request->nit,
        'razonSocial'	=> $request->razonSocial,
        'modalidad' 	=> ServicioSiat::MOD_ELECTRONICA_ENLINEA,
        'ambiente' 		=> ServicioSiat::AMBIENTE_PRUEBAS,
        'tokenDelegado'	=> env('FAC_TOKEN_SYS'),
        'pubCert'        => 'siat/tiluchi/certificado_Sin_Certificado.pem',
        'privCert'        => 'siat/tiluchi/clave_Sin_Certificado.pem',
    ]);
    $codigoPuntoVenta = 0;
    $codigoSucursal = 0;

    //##cabecera de la factura
    $factura = new CompraVenta();
    $factura->cabecera->razonSocialEmisor    = $request->razonSocial;
    $factura->cabecera->municipio            = null;
    $factura->cabecera->telefono             = 59177988343;
    $factura->cabecera->numeroFactura        = 100;
    $factura->cabecera->codigoSucursal       = 0;
    $factura->cabecera->direccion            = 'calle de prueba';
    $factura->cabecera->codigoPuntoVenta     = 0;
    $factura->cabecera->fechaEmision         = date('Y-m-dTH:i:s.v');
    $factura->cabecera->nombreRazonSocial    = 'Alvarez';
    $factura->cabecera->codigoTipoDocumentoIdentidad = 1; //CI - CEDULA DE IDENTIDAD
    $factura->cabecera->numeroDocumento      = 5619016;
    $factura->cabecera->codigoCliente        = null;
    $factura->cabecera->codigoMetodoPago     = 1;
    $factura->cabecera->montoTotal           = 200;
    $factura->cabecera->montoTotalMoneda     = $factura->cabecera->montoTotal;
    $factura->cabecera->montoTotalSujetoIva  = $factura->cabecera->montoTotal;
    $factura->cabecera->descuentoAdicional   = 0;
    $factura->cabecera->codigoMoneda         = 1; //BOLIVIANO
    $factura->cabecera->tipoCambio           = 1;
    $factura->cabecera->usuario              = 'admin_tiluchi';

    //##detalle de la factura
    $detalle = new InvoiceDetail();
    $detalle->cantidad           = 1;
    $detalle->actividadEconomica = '475200'; //475200  //466300
    $detalle->codigoProducto     = '001';
    $detalle->codigoProductoSin  = '99100';
    $detalle->descripcion        = 'Nombre del producto #001';
    $detalle->precioUnitario     = 100;
    $detalle->montoDescuento     = 0;
    $detalle->subTotal           = $detalle->cantidad * $detalle->precioUnitario;
    
    //##adicionar el detalle a la factura
    $factura->detalle[] = $detalle;

 

    //##enviar la factura al siat
    $serviceCodigos = new ServicioFacturacionCodigos(null, null, $config->tokenDelegado);
    $serviceCodigos->setConfig((array)$config);
    $resCuis = $serviceCodigos->cuis($codigoPuntoVenta, $codigoSucursal);
    $serviceCodigos->cuis = $resCuis->RespuestaCuis->codigo;
    $resCufd = $serviceCodigos->cufd($codigoPuntoVenta, $codigoSucursal);

    $service = new ServicioFacturacionElectronica($resCuis->RespuestaCuis->codigo, $resCufd->RespuestaCufd->codigo, $config->tokenDelegado);
    $service->setConfig((array)$config);
    $service->codigoControl = $resCufd->RespuestaCufd->codigoControl;
    $service->setPublicCertificateFile('siat/tiluchi/certificado_Sin_Certificado.pem');
    $service->setPrivateCertificateFile('siat/tiluchi/clave_Sin_Certificado.pem');

    // return (array)$factura;

    $res = $service->recepcionFactura($factura, SiatInvoice::TIPO_EMISION_ONLINE, SiatInvoice::FACTURA_DERECHO_CREDITO_FISCAL);
    return (array)$res;
    // return $res->RespuestaServicioFacturacion;

});


//ventas-------------------------------------------
Route::post('/venta/id', function (Request $request) {
    return App\Transaction::find($request->id);
});

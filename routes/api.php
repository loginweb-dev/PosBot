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
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Invoices\ElectronicaCompraVenta;


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
        'ambiente' 		=> ServicioSiat::AMBIENTE_PRODUCCION,
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
        case 'sincronizarParametricaUnidadMedida':            
            $sync = new ServicioFacturacionSincronizacion($resCuis->RespuestaCuis->codigo, null, $config->tokenDelegado);
            $sync->setConfig((array)$config);
            $res = call_user_func([$sync, 'sincronizarParametricaUnidadMedida']);    
            return $res->RespuestaListaParametricas->listaCodigos; 
            break;  
        case 'sincronizarParametricaTipoMetodoPago':            
            $sync = new ServicioFacturacionSincronizacion($resCuis->RespuestaCuis->codigo, null, $config->tokenDelegado);
            $sync->setConfig((array)$config);
            $res = call_user_func([$sync, 'sincronizarParametricaTipoMetodoPago']);    
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
    // return $request;
    $miventa = App\Transaction::where('id', $request->id)->with('location', 'business', 'contact', 'sell_lines')->first();
    $micontacto = App\Contact::where('id', $miventa->contact->id)->first();
    $micustomer_group = App\CustomerGroup::find($miventa->contact->customer_group_id);
    // return $miventa;
    $config = new SiatConfig([
        'nombreSistema'	=> env('FAC_NAME_SYS'),
        'codigoSistema'	=> env('FAC_CODE_SYS'),
        'tipo' 			=> 'PROVEEDOR',
        'nit'			=> $miventa->business->tax_number_1,
        'razonSocial'	=> $miventa->location->name,
        'modalidad' 	=> ServicioSiat::MOD_ELECTRONICA_ENLINEA,
        'ambiente' 		=> ServicioSiat::AMBIENTE_PRODUCCION, //AMBIENTE_PRUEBAS AMBIENTE_PRODUCCION
        'tokenDelegado'	=> env('FAC_TOKEN_SYS')
    ]);
    $codigoPuntoVenta = 0;
    $codigoSucursal = 0;
// return (array)$config;
    $serviceCodigos = new ServicioFacturacionCodigos(null, null, $config->tokenDelegado);
    // new ServicioFacturacionCodigos()
    $serviceCodigos->setConfig((array)$config);
    $resCuis = $serviceCodigos->cuis($codigoPuntoVenta, $codigoSucursal);
    $serviceCodigos->cuis = $resCuis->RespuestaCuis->codigo;
    $resCufd = $serviceCodigos->cufd($codigoPuntoVenta, $codigoSucursal);

    //##cabecera de la factura
    $factura = new ElectronicaCompraVenta();
    // CompraVenta
    $factura->cabecera->nitEmisor            = $miventa->business->tax_number_1;
    $factura->cabecera->razonSocialEmisor    = $miventa->location->name;
    $factura->cabecera->municipio            = $miventa->location->city;
    $factura->cabecera->numeroFactura        = 71; 
    // $factura->cabecera->cuf                  = $resCufd->RespuestaCufd->codigoControl;
    // $factura->cabecera->cufd                 = $resCufd->RespuestaCufd->codigo;
    $factura->cabecera->telefono             = $miventa->location->mobile;
    $factura->cabecera->codigoSucursal       = 0; //$miventa->location->zip_code;
    // $factura->cabecera->codigoPuntoVenta     = 0; //$miventa->location->landmark;
    $factura->cabecera->direccion            = $miventa->location->alternate_number;   
    $factura->cabecera->fechaEmision         = date('Y-m-d\TH:i:s.v'); //transaction_date
    $factura->cabecera->nombreRazonSocial    = $micontacto->name ? $micontacto->name : $micontacto->supplier_business_name;
    $factura->cabecera->codigoTipoDocumentoIdentidad = 4; //$micustomer_group->amount; //CI - CEDULA DE IDENTIDAD
    $factura->cabecera->numeroDocumento      = $micontacto->tax_number;
    $factura->cabecera->codigoCliente        = 'GS'.$micontacto->id;    
    $factura->cabecera->codigoMetodoPago     = 1; //EFECTIVO
    $factura->cabecera->montoTotal           = (float)$miventa->total_before_tax;
    $factura->cabecera->montoTotalSujetoIva  = (float)$miventa->total_before_tax;
    $factura->cabecera->codigoMoneda         = 1; //BOLIVIANO
    $factura->cabecera->tipoCambio           = 1;    
    $factura->cabecera->montoTotalMoneda     = (float)$miventa->total_before_tax;    
    // $factura->cabecera->leyenda              = 'Ley N° 453: Tienes derecho a recibir información sobre las características y contenidos de los productos que consumes.';
    $factura->cabecera->usuario              = 'ADMIN';
    // $factura->cabecera->codigoDocumentoSector= 1;    

    //##detalle de la factura
    for ($i=0; $i < count($miventa->sell_lines); $i++) { 
        $miproducto = App\Product::find($miventa->sell_lines[$i]->product_id);
        $detalle = new InvoiceDetail();
        $detalle->actividadEconomica = '475200';
        $detalle->codigoProductoSin  = 62161;
        $detalle->descripcion        = $miproducto->name; //nombre
        $detalle->codigoProducto     = $miproducto->sku;
        $detalle->cantidad           = (int)$miventa->sell_lines[$i]->quantity;
        $detalle->unidadMedida       = 47; // PIEZAS
        $detalle->precioUnitario     = (float)$miventa->sell_lines[$i]->unit_price;
        $detalle->subTotal           = $detalle->cantidad * $detalle->precioUnitario;
        $detalle->montoDescuento     = 0;
        //##adicionar el detalle a la factura
        $factura->detalle[] = $detalle;
    }

    $service = new ServicioFacturacionElectronica($resCuis->RespuestaCuis->codigo, $resCufd->RespuestaCufd->codigo, $config->tokenDelegado);
    $service->setConfig((array)$config);
    $service->codigoControl = $resCufd->RespuestaCufd->codigoControl;
    $service->setPublicCertificateFile('siat/tiluchi/certificado_Sin_Certificado.pem');
    $service->setPrivateCertificateFile('siat/tiluchi/clave_Sin_Certificado.pem');
    $service->debug = true;

    $res = $service->recepcionFactura($factura);
    return (array)$res;
});


//ventas-------------------------------------------
Route::post('/venta/id', function (Request $request) {
    return App\Transaction::where('id', $request->id)->with('location', 'business', 'contact')->first();
});

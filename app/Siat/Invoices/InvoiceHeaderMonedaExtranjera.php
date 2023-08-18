<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Invoices;

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Message;
use Exception;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\DocumentTypes;

class InvoiceHeaderMonedaExtranjera extends InvoiceHeader
{
	public	$nitEmisor;
	public	$razonSocialEmisor;
	public	$municipio;
	public	$telefono;
	public	$numeroFactura;
	public	$cuf;
	public	$cufd;
	public	$codigoSucursal;
	public	$direccion;
	public	$codigoPuntoVenta;
	public	$fechaEmision;
	public	$nombreRazonSocial;
	public	$codigoTipoDocumentoIdentidad;
	public	$numeroDocumento;
	public	$complemento;
	public	$codigoCliente;
	public	$codigoTipoOperacion;
	public	$codigoMetodoPago;
	public	$numeroTarjeta;
	protected	$numeroTarjetaReal;
	public	$montoTotal;
	public	$montoTotalSujetoIva;
	public	$ingresoDiferenciaCambio;
	public	$codigoMoneda;
	public	$tipoCambio;
	public	$montoTotalMoneda;
	public	$descuentoAdicional;
	public	$codigoExcepcion;
	public	$cafc;
	public	$leyenda = 'Ley Nro 453: Tienes derecho a recibir información sobre las características y contenidos de los servicios que utilices.';
	public	$usuario;
	public	$tipoCambioOficial;
	public	$codigoDocumentoSector;
	
	public function __construct()
	{
		parent::__construct();
		$this->codigoDocumentoSector = DocumentTypes::FACTURA_COMPRA_VENTA_MONEDA_EXTRAJERA;
	}
	public function validate()
	{
		parent::validate();
	}
	/**
	 * Verifica los montos de la factura
	 */
	public function checkAmounts()
	{
		parent::checkAmounts();
		$this->ingresoDiferenciaCambio = $this->codigoTipoOperacion == 1 ? 
			($this->tipoCambio - $this->tipoCambioOficial) :
			($this->tipoCambioOficial - $this->tipoCambio);
		
	}
}
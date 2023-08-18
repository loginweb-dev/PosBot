<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Invoices;

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Message;

class RegistroCompra extends Message
{
	public $nro = null;
	public $nitEmisor = null;
	public $razonSocialEmisor = null;
	public $codigoAutorizacion = null;
	public $numeroFactura = null;
	public $numeroDuiDim = null;
	public $fechaEmision = null;
	public $montoTotalCompra = null;
	public $importeIce = null;
	public $importeIehd = null;
	public $importeIpj = null;
	public $tasas = null;
	public $otroNoSujetoCredito = null;
	public $importesExentos = null;
	public $importeTasaCero = null;
	public $subTotal = null;
	public $descuento = null;
	public $montoGiftCard = null;
	public $montoTotalSujetoIva = null;
	public $creditoFiscal = null;
	public $tipoCompra = 1;
	public $codigoControl = null;
	
	public function __construct()
	{
		
	}
	public function validate()
	{
		
	}
	public function toXml()
	{
		$xml = parent::toXml(null, true, true);
		
		return $xml;
	}
}
<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Invoices;

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\DocumentTypes;

class MonedaExtranjera extends SiatInvoice
{
	public function __construct()
	{
		parent::__construct();
		
		$this->classAlias 	= 'facturaComputarizadaMonedaExtranjera';
		$this->cabecera		= new InvoiceHeaderMonedaExtranjera();
		$this->endpoint		= 'https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl';
	}
	public function validate()
	{
		parent::validate();
	}
	public function checkAmounts()
	{
		parent::checkAmounts();
		$this->cabecera->checkAmounts();
		foreach($this->detalle as $detalle)
		{
			if( method_exists($detalle, 'checkAmounts') )
				$detalle->checkAmounts();
		}
	}
	/**
	 * 
	 * @return InvoiceDetailMonedaExtranjera
	 */
	public function instanceDetail()
	{
		$detalle = new InvoiceDetailMonedaExtranjera();
		
		return $detalle;
	}
}
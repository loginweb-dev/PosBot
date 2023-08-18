<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Invoices;


class ComercializacionHidrocarburos extends SiatInvoice
{
	public function __construct()
	{
		parent::__construct();
		
		$this->classAlias 	= 'facturaComputarizadaComercializacionHidrocarburo';
		$this->cabecera		= new InvoiceHeaderComercializacionHidrocarburos();
		$this->endpoint		= 'https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionHidrocarburos?wsdl';
	}
	public function validate()
	{
		parent::validate();
	}
	public function checkAmounts()
	{
		foreach($this->detalle as $detalle)
		{
			
		}
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Invoices\SiatInvoice::instanceDetail()
	 * @return InvoiceDetail
	 */
	public function instanceDetail()
	{
		$detail = new InvoiceDetail();
		$detail->addSkipProperty('numeroSerie');
		$detail->addSkipProperty('numeroImei');
		
		return $detail;
	}
}

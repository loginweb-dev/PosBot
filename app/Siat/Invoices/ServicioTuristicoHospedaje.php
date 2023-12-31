<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Invoices;

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\DocumentTypes;

class ServicioTuristicoHospedaje extends SiatInvoice
{
	public function __construct()
	{
		parent::__construct();
		
		$this->classAlias 	= 'facturaComputarizadaServicioTuristicoHospedaje';
		$this->cabecera 	= new InvoiceHeaderTuristico();
		$this->cabecera->codigoDocumentoSector 	= DocumentTypes::FACTURA_SERVICIO_TURISTICO;
	}
	public function validate()
	{
		parent::validate();
	}
	public function instanceDetail()
	{
		$detail = new InvoiceDetailTuristico();
		
		return $detail;
	}

}

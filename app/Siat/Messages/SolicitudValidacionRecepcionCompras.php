<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Messages;

use Exception;

class SolicitudValidacionRecepcionCompras extends SolicitudCompras
{
	public $codigoRecepcion;
	
	public function validate()
	{
		if( empty($this->codigoRecepcion) )
			throw new Exception('VALIDACION RECEPCION COMPRAS ERROR: codigoRecepcion esta vacio');
		parent::validate();
	}
	
}
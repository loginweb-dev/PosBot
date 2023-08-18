<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Messages;

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Message;

class SolicitudCompras extends Message
{
	public $codigoAmbiente;
	public $codigoPuntoVenta;
	public $codigoSistema;
	public $codigoSucursal;
	public $cufd;
	public $cuis;
	public $nit;
	
	public function validate()
	{
		
	}
}
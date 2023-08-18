<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Messages;

use Exception;

class SolicitudRecepcionCompras extends SolicitudCompras
{
	public $archivo;
	public $cantidadFacturas = 0;
	public $fechaEnvio;
	public $gestion;
	public $hashArchivo;
	public $periodo;
	
	public function __construct()
	{
		
	}
	public function setInvoices(array $facturas)
	{
		if( !count($facturas) )
			throw new Exception('RECEPCION COMPRAS ERROR: No existen facturas para el envio');
		
		$this->cantidadFacturas = count($facturas);
		$package = MOD_SIAT_TEMP_DIR . SB_DS . 'facturas-compras'. time() .'.tar';
		$tar = new \PharData($package);
		$tar->startBuffering();
		foreach($facturas as $i => $item)
		{
			$xml = $item;
			if( is_object($item) && method_exists($item, 'toXml') )
			{
				$item->validate();
				$xml = $item->toXml();
			}
			
			$localname = sprintf("compra-%d.xml", $i);
			$tar->addFromString($localname, $xml);
		}
		$tar->stopBuffering();
		$this->setBuffer(file_get_contents($package));
		sleep(2);
		if( is_file($package) )
			@unlink($package);
		
	}
	public function setBuffer($binaryBuffer, $compress = true)
	{
		if( empty($binaryBuffer) )
			throw new Exception('The invoice buffer is empty');
			
		$this->archivo 		= $compress ? gzcompress($binaryBuffer, 9, FORCE_GZIP) : $binaryBuffer;
		$this->hashArchivo 	= hash('sha256', $this->archivo, !true);
	}
}
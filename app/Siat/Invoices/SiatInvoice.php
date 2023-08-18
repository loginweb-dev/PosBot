<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Invoices;

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Message;
use SimpleXMLElement;
use Exception;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioSiat;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\DocumentTypes;

abstract class SiatInvoice extends Message
{
	const 	FACTURA_DERECHO_CREDITO_FISCAL = 1;
	const 	FACTURA_SIN_DERECHO_CREDITO_FISCAL = 2;
	const 	FACTURA_DOCUMENTO_AJUSTE = 3;
	
	const 	TIPO_EMISION_ONLINE 	= 1;
	const 	TIPO_EMISION_OFFLINE 	= 2;
	const 	TIPO_EMISION_MASIVA 	= 3;
	
	/**
	 * @var InvoiceHeader
	 */
	public	$cabecera;
	/**
	 * @var InvoiceDetail[]
	 */
	public	$detalle = [];
	
	
	protected	$required = [];
	protected	$nsData = [];
	
	public		$endpoint;
	
	public function __construct()
	{
		$this->xmlAllFields = true;
		$this->skipProperties = ['endpoint', 'classAlias', 'xmlAllFields', 'namespaces', 'skipProperties', 'xmlAttributes'];
		$this->namespaces = [
			//['name' => 'xmlns:xsi', 'value' => 'http://www.w3.org/2001/XMLSchema-instance', 'ns' => 'http://www.w3.org/2001/XMLSchema-instance']
		];
		$this->cabecera = new InvoiceHeader();
		$this->detalle = [];
	}
	public function validate()
	{
		$this->cabecera->validate();
		foreach($this->detalle as $d)
		{
			$d->validate();
		}
		
	}
	public function calculaDigitoMod11(string $cadena, int $numDig, int $limMult, bool $x10)
	{
		$cadenaSrc = $cadena;
		
		$mult = $suma = $i = $n = $dig = 0;
		
		if (!$x10) $numDig = 1;
		
		for($n = 1; $n <= $numDig; $n++) 
		{
			$suma = 0;
			$mult = 2;
			for($i = strlen($cadena) - 1; $i >= 0; $i--) 
			{
				$cadestr = $cadena[$i];//substr($cadena, $i, $i + 1);
				$intNum = (int)($cadestr);
				//echo 'cadestr: ', $cadestr, "\n";
				//echo 'intNum: ', $intNum, "\n";
				$suma += ($mult * $intNum);
				if(++$mult > $limMult) $mult = 2;
			}
			if ($x10) 
			{
				$dig = (($suma * 10) % 11) % 10;
			}
			else 
			{
				$dig = $suma % 11;
			}
			if ($dig == 10) 
			{
				$cadena .= "1";
			}
			if ($dig == 11) 
			{
				$cadena .= "0";
			}
			if ($dig < 10) {
				
				//$cadena .= String.valueOf(dig);
				$cadena .= $dig;
			}
			//echo "Dig: ", $dig, "\n";
		}
		
		$modulo = substr($cadena, strlen($cadena) - $numDig, strlen($cadena));
		
		//echo $cadena, "\n";
		//echo 'Calculado modulo 11: ', $cadenaSrc, " => ", $modulo, "\n";
		
		return $modulo;
	}
	public function buildCuf($sucursal, $modalidad, $tipoEmision, $tipoFactura, $codigoControl)
	{
		$nitEmisor 			= str_pad($this->cabecera->nitEmisor, 13, '0', STR_PAD_LEFT);
		$sucursalNro 		= str_pad($this->cabecera->codigoSucursal, 4, '0', STR_PAD_LEFT);
		$tipoSector 		= str_pad($this->cabecera->codigoDocumentoSector, 2, '0', STR_PAD_LEFT);
		$numeroFactura 		= str_pad($this->cabecera->codigoDocumentoSector == DocumentTypes::FACTURA_NOTA_CRED_DEB ? 
			$this->cabecera->numeroNotaCreditoDebito : $this->cabecera->numeroFactura, 10, '0', STR_PAD_LEFT);
		$numeroPuntoVenta 	= str_pad($this->cabecera->codigoPuntoVenta, 4, '0', STR_PAD_LEFT);
		$fechaHora 			= date('YmdHisv', strtotime($this->cabecera->fechaEmision));
		
		$cadena 		= "{$nitEmisor}{$fechaHora}{$sucursalNro}{$modalidad}{$tipoEmision}{$tipoFactura}{$tipoSector}{$numeroFactura}{$numeroPuntoVenta}";
		$verificador 	= $this->calculaDigitoMod11($cadena, 1, 9, false);
		$b16_str 		= strtoupper( $this->bcdechex( $cadena . $verificador ) );
		$this->cabecera->cuf = $b16_str . $codigoControl;
	}
	public function bcdechex($dec) 
	{
		$hex = '';
		do {
			$last = bcmod($dec, 16);
			$hex = dechex($last).$hex;
			$dec = bcdiv(bcsub($dec, $last), 16);
		} while($dec>0);
		return $hex;
	}
	public function getUrl($ticket = true)
	{
		return self::buildUrl($this->cabecera->nitEmisor, $this->cabecera->cuf, $this->cabecera->numeroFactura);
	}
	public static function buildUrl($nit, $cuf, $nroFactura, $ambiente = ServicioSiat::AMBIENTE_PRUEBAS, $type = 2)
	{
		//
		$url = sprintf($ambiente != ServicioSiat::AMBIENTE_PRODUCCION ? 
			"https://pilotosiat.impuestos.gob.bo/consulta/QR?nit=%d&cuf=%s&numero=%d&t=%d" :
			"https://siat.impuestos.gob.bo/consulta/QR?nit=%d&cuf=%s&numero=%d&t=%d",
			$nit,
			$cuf,
			$nroFactura,
			$type
		);
		return $url;
	}
	/*
	public function toXml($rootTagName = null)
	{
		if( !$this->type )
			throw new Exception('Invalid invoice type [computarizada|enlinea]');
		
		$this->namespaces = [
			$this->nsData[$this->type]
		];
		$xml = parent::toXml($rootTagName);
		
		$header = $xml->addChild('cabecera', '', null);
		$detail = $xml->addChild('detalle', '', null);
		$this->buildHeader($header);
		$this->buildDetail($detail);
		
		//$buffer = $xml->asXML();
		
		return $xml;
	}
	*/
	/**
	 * 
	 * @param string $filename
	 * @throws Exception
	 * @return SiatInvoice
	 */
	public static function buildFromXmlFile($filename)
	{
		if( !is_file($filename) )
			throw new Exception('Invalid invoice file');
		$obj = simplexml_load_file($filename, static::class);
		return $obj;
	}
	public function getEndpoint( $modalidad, $ambiente )
	{
		return self::getWsdl($modalidad, $ambiente, $this->cabecera->codigoDocumentoSector);
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Message::toXml()
	 */
	public function toXml($rootTagName = null, $isRoot = false, $standalone = false)
	{
		$this->cabecera->obfuscarTarjeta();
		$xml = parent::toXml($rootTagName, true, true);
		$this->cabecera->recuperarTarjeta();
		return $xml;
	}
	public static function getWsdl($modalidad, $ambiente, $documentoSector)
	{
		if( $documentoSector == DocumentTypes::FACTURA_SERV_BASICOS )
			return $ambiente == 1 ? 
				"https://siatrest.impuestos.gob.bo/v2/ServicioFacturacionServicioBasico?wsdl" :
				"https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionServicioBasico?wsdl";
		if( $documentoSector == DocumentTypes::FACTURA_COMPRA_VENTA )
			return $ambiente == 1 ? 
				"https://siatrest.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl" : 
				"https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl";
		if( $documentoSector == DocumentTypes::FACTURA_ENT_FINANCIERA )
			return $ambiente == 1 ? 
				"https://siatrest.impuestos.gob.bo/v2/ServicioFacturacionEntidadFinanciera?wsdl" : 
				"https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionEntidadFinanciera?wsdl";
		if( $documentoSector == DocumentTypes::FACTURA_TELECOMUNICACIONES )
			return $ambiente == 1 ?
				"https://siatrest.impuestos.gob.bo/v2/ServicioFacturacionTelecomunicaciones?wsdl" :
				"https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionTelecomunicaciones?wsdl";
		if( $documentoSector == DocumentTypes::FACTURA_HIDROCARBUROS )
			return $ambiente == 1 ?
				'https://siatrest.impuestos.gob.bo/v2/ServicioFacturacionHidrocarburos?wsdl' :
				'https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionHidrocarburos?wsdl';
		if( $documentoSector == DocumentTypes::FACTURA_NOTA_CRED_DEB )
			return $ambiente == 1 ?
				'https://siatrest.impuestos.gob.bo/v2/ServicioFacturacionDocumentoAjuste?wsdl' :
				'https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionDocumentoAjuste?wsdl';

		if( ServicioSiat::MOD_ELECTRONICA_ENLINEA == $modalidad )
		{
			return $ambiente == 1 ? 
				'https://siatrest.impuestos.gob.bo/v2/ServicioFacturacionElectronica?wsdl' : 
				'https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionElectronica?wsdl';
		}
		
		return $ambiente == 1 ? 
			'https://siatrest.impuestos.gob.bo/v2/ServicioFacturacionComputarizada?wsdl' : 
			'https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionComputarizada?wsdl';
	}
	public function checkAmounts()
	{
		
	}
	protected function formatDetails($jsonStr)
	{
		if( !($data = json_decode($jsonStr)) )
			return $jsonStr;
			
		$obj = [];
		foreach($data as $item)
		{
			foreach($item as $key => $val)
			{
				$obj[$key] = $val;
			}
		}
		//print_r($obj);
		return json_encode($obj);
	}
	public abstract function instanceDetail();
	public static function tieneIVA(int $documentoSector)
	{
		$docs = [
			DocumentTypes::FACTURA_COMERCIAL_EXPORTACION,
			DocumentTypes::FACTURA_SERVICIO_TURISTICO,
			DocumentTypes::FACTURA_TASA_CERO_LIBROS,
			DocumentTypes::FACTURA_COM_EXPORT_SERVICIOS,
		];
		return in_array($documentoSector, $docs) ? false : true;
		
	}
	public static function obtenerTipoFactura(int $documentoSector)
	{
		if( in_array($documentoSector, [DocumentTypes::FACTURA_NOTA_CRED_DEB]) )
			return self::FACTURA_DOCUMENTO_AJUSTE;
		
		$docs = [
			DocumentTypes::FACTURA_COMERCIAL_EXPORTACION,
			DocumentTypes::FACTURA_SERVICIO_TURISTICO,
			DocumentTypes::FACTURA_TASA_CERO_LIBROS,
			DocumentTypes::FACTURA_COM_EXPORT_SERVICIOS,
		];
		return in_array($documentoSector, $docs) ? self::FACTURA_SIN_DERECHO_CREDITO_FISCAL : self::FACTURA_DERECHO_CREDITO_FISCAL;
	}
}
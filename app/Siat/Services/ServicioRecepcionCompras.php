<?php
/**
 * @author J. Marcelo Aviles Paco
 * @copyright Sintic Bolivia
 * @link https://sinticbolivia.net
 * @package LibSiat
 */
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services;

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Messages\SolicitudRecepcionCompras;
use Exception;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Messages\SolicitudAnulacionCompra;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Messages\SolicitudConsultaCompras;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Messages\SolicitudConfirmacionCompras;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Messages\SolicitudValidacionRecepcionCompras;

class ServicioRecepcionCompras extends ServicioSiat
{
	protected $wsdl = 'https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioRecepcionCompras?wsdl=ServicioFacturacion.wsdl';
	
	public function __construct($cuis = null, $cufd = null)
	{
		$this->cuis = $cuis;
		$this->cufd = $cufd;
	}
	public function setConfig(array $data)
	{
		parent::setConfig($data);
		if( (int)$this->ambiente == self::AMBIENTE_PRODUCCION )
		{
			$this->wsdl = 'https://siatrest.impuestos.gob.bo/v2/ServicioRecepcionCompras?wsdl=ServicioFacturacion.wsdl';
		}
	}
	public function validacionRecepcionPaqueteCompras(int $sucursal, int $puntoventa, $codigoRecepcion)
	{
		$solicitud = new SolicitudValidacionRecepcionCompras();
		$solicitud->codigoAmbiente = $this->ambiente;
		$solicitud->codigoPuntoVenta = $puntoventa;
		$solicitud->codigoSistema = $this->codigoSistema;
		$solicitud->codigoSucursal = $sucursal;
		$solicitud->cufd = $this->cufd;
		$solicitud->cuis = $this->cuis;
		$solicitud->nit = $this->nit;
		$solicitud->codigoRecepcion = $codigoRecepcion;
		$solicitud->validate();
		$data = [
			$solicitud->toArray()
		];
		try
		{
			$res = $this->callAction('validacionRecepcionPaqueteCompras', $data);
			
			return $res;
		}
		catch(\SoapFault $e)
		{
			throw new Exception($e->getMessage());
		}
		
	}
	public function recepcionPaqueteCompras(int $sucursal, int $puntoventa, array $facturas, int $gestion, int $periodo)
	{
		$solicitud = new SolicitudRecepcionCompras();
		$solicitud->codigoAmbiente = $this->ambiente;
		$solicitud->codigoPuntoVenta = $puntoventa;
		$solicitud->codigoSistema = $this->codigoSistema;
		$solicitud->codigoSucursal = $sucursal;
		$solicitud->cufd = $this->cufd;
		$solicitud->cuis = $this->cuis;
		$solicitud->fechaEnvio = date(SIAT_DATETIME_FORMAT);
		$solicitud->gestion = $gestion;
		$solicitud->nit = $this->nit;
		$solicitud->periodo = $periodo;
		$solicitud->setInvoices($facturas);
		$solicitud->validate();
		$data = [
			$solicitud->toArray()
		];
		try
		{
			$res = $this->callAction('recepcionPaqueteCompras', $data);
			return $res;
		}
		catch(\SoapFault $e)
		{
			throw new Exception($e->getMessage());
		}
		
	}
	public function anulacionCompra(int $sucursal, int $puntoventa, $codAutorizacion, $nitProveedor, $nroDuiDim, $nroFactura)
	{
		$solicitud = new SolicitudAnulacionCompra();
		$solicitud->codigoAmbiente = $this->ambiente;
		$solicitud->codigoPuntoVenta = $puntoventa;
		$solicitud->codigoSistema = $this->codigoSistema;
		$solicitud->codigoSucursal = $sucursal;
		$solicitud->cufd = $this->cufd;
		$solicitud->cuis = $this->cuis;
		$solicitud->nit = $this->nit;
		
		$solicitud->codAutorizacion = $codAutorizacion;
		$solicitud->nitProveedor = $nitProveedor;
		$solicitud->nroDuiDim = $nroDuiDim;
		$solicitud->nroFactura = $nroFactura;
		$solicitud->validate();
		
		$data = [
			$solicitud->toArray()
		];
		try
		{
			$res = $this->callAction('anulacionCompra', $data);
		
			return $res;
		}
		catch(\SoapFault $e)
		{
			throw new Exception($e->getMessage());
		}
	}
	public function confirmacionCompras($sucursal, $puntoventa, array $compras, $gestion, $periodo)
	{
		$solicitud = new SolicitudConfirmacionCompras();
		$solicitud->codigoAmbiente = $this->ambiente;
		$solicitud->codigoPuntoVenta = $puntoventa;
		$solicitud->codigoSistema = $this->codigoSistema;
		$solicitud->codigoSucursal = $sucursal;
		$solicitud->cufd = $this->cufd;
		$solicitud->cuis = $this->cuis;
		$solicitud->fechaEnvio = date(SIAT_DATETIME_FORMAT);
		$solicitud->gestion = $gestion;
		$solicitud->nit = $this->nit;
		$solicitud->periodo = $periodo;
		$solicitud->setInvoices($compras);
		$solicitud->validate();
		$data = [
			$solicitud->toArray()
		];
		try
		{
			$res = $this->callAction('confirmacionCompras', $data);
			return $res;
		}
		catch(\SoapFault $e)
		{
			throw new Exception($e->getMessage());
		}
	}
	/**
	 * 
	 * @param string $fecha
	 */
	public function consultaCompras($sucursal, $puntoventa, $fecha)
	{
		$solicitud = new SolicitudConsultaCompras();
		$solicitud->codigoAmbiente = $this->ambiente;
		$solicitud->codigoPuntoVenta = $puntoventa;
		$solicitud->codigoSistema = $this->codigoSistema;
		$solicitud->codigoSucursal = $sucursal;
		$solicitud->cufd = $this->cufd;
		$solicitud->cuis = $this->cuis;
		$solicitud->nit = $this->nit;
		$solicitud->fecha = date(SIAT_DATETIME_FORMAT, strtotime($fecha));
		$solicitud->validate();
		
		$data = [
			$solicitud->toArray()
		];
		try
		{
			$res = $this->callAction('consultaCompras', $data);
			return $res;
		}
		catch(\SoapFault $e)
		{
			throw new Exception($e->getMessage());
		}
		
	}
}
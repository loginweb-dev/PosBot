<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Invoices;

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\DocumentTypes;

class AlcanzadaIce extends SiatInvoice
{
	/**
	 * @var InvoiceHeaderIce
	 */
	public	$cabecera;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->classAlias 	= 'facturaComputarizadaAlcanzadaIce';
		$this->cabecera		= new InvoiceHeaderIce();
	}
	public function validate()
	{
		parent::validate();
	}
	public function checkAmounts()
	{
		$this->cabecera->montoIceEspecifico = 0;
		$this->cabecera->montoIcePorcentual = 0;
		$this->cabecera->montoTotal 		= 0;
		
		$subtotal = 0;
		
		/**
		 * @var InvoiceDetailIce $detalle
		 */
		foreach($this->detalle as $detalle)
		{
			$detalle->alicuotaIva			= ($detalle->cantidad * $detalle->precioUnitario - $detalle->montoDescuento) * 0.13;
			$detalle->precioNetoVentaIce	= (($detalle->cantidad * $detalle->precioUnitario) - $detalle->montoDescuento) - $detalle->alicuotaIva;
			$detalle->montoIceEspecifico	= $detalle->cantidadIce * $detalle->alicuotaEspecifica;
			$detalle->montoIcePorcentual	= ($detalle->precioNetoVentaIce * ($detalle->alicuotaPorcentual / 100)) * 100;
			$detalle->subTotal				= (($detalle->cantidad * $detalle->precioUnitario) - $detalle->montoDescuento) 
												+ $detalle->montoIcePorcentual + $detalle->montoIceEspecifico;
			
			$subtotal += $detalle->subTotal;
			$detalle->subTotal				= number_format($detalle->subTotal, 5, '.', '');
			//TODO: Find the correct value for marceIce
			$detalle->marcaIce				= 1; 
			$this->cabecera->montoIceEspecifico += $detalle->montoIceEspecifico;
			$this->cabecera->montoIcePorcentual	+= $detalle->montoIcePorcentual;
		}
		$this->cabecera->montoTotal 		= $subtotal - $this->cabecera->descuentoAdicional;
		$this->cabecera->montoTotalMoneda	= $this->cabecera->montoTotal * $this->cabecera->tipoCambio;
		$this->cabecera->montoTotalSujetoIva = $this->cabecera->montoTotal - $this->cabecera->montoIceEspecifico - $this->cabecera->montoIcePorcentual;
		
		$this->cabecera->montoIceEspecifico = number_format(round($this->cabecera->montoIceEspecifico, 2), 2, '.', '');
		$this->cabecera->montoIcePorcentual = number_format(round($this->cabecera->montoIcePorcentual, 2), 2, '.', '');
		$this->cabecera->montoTotal			= number_format($this->cabecera->montoTotal, 2, '.', '');
		$this->cabecera->montoTotalMoneda	= number_format($this->cabecera->montoTotalMoneda, 2, '.', '');
	}
	public function instanceDetail()
	{
		$detail = new InvoiceDetailIce();
		
		return $detail;
	}

}

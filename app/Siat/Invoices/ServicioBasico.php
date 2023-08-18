<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Invoices;

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\DocumentTypes;

class ServicioBasico extends CompraVenta
{
	public function __construct()
	{
		parent::__construct();
		$this->cabecera		= new InvoiceHeaderServicioBasico();
		$this->classAlias 	= 'facturaComputarizadaServicioBasico';
	}
	public function validate()
	{
		//$this->cabecera->validate();
		parent::validate();
	}
	public function checkAmounts()
	{
		$this->cabecera->checkAmounts();
		$subtotal = $this->getSubtotal();
		$subtotal -= $this->cabecera->descuentoAdicional;
		
		$this->cabecera->montoTotal = $subtotal;
		$this->cabecera->montoTotalMoneda = $this->cabecera->montoTotal * $this->cabecera->tipoCambio;
		$this->cabecera->montoTotalSujetoIva = $this->getAmountIVA();
		
		$this->cabecera->detalleAjusteNoSujetoIva 		= $this->formatDetails($this->cabecera->detalleAjusteNoSujetoIva);
		$this->cabecera->detalleAjusteSujetoIva 		= $this->formatDetails($this->cabecera->detalleAjusteSujetoIva);
		$this->cabecera->detalleOtrosPagosNoSujetoIva 	= $this->formatDetails($this->cabecera->detalleOtrosPagosNoSujetoIva);
		
	}
	public function getSubtotal()
	{
		$subtotal = 0;
		foreach($this->detalle as $d)
		{
			//$d->checkAmounts();
			$subtotal += $d->subTotal;
		}
		$subtotal += $this->getAmountTasas();
		$subtotal += $this->cabecera->ajusteSujetoIva;
		$subtotal += $this->cabecera->otrosPagosNoSujetoIva;
		//$subtotal -= $this->cabecera->descuentoAdicional;
		
		return $subtotal;
	}
	public function getAmountIVA()
	{
		$amount = $this->getSubtotal();
		$amount -= $this->cabecera->tasaAseo;
		$amount -= $this->cabecera->tasaAlumbrado;
		$amount -= $this->cabecera->otrasTasas;
		$amount -= $this->cabecera->otrosPagosNoSujetoIva;
		
		return $amount;
	}
	public function getAmountTasas()
	{
		$total = $this->cabecera->tasaAseo;
		$total += $this->cabecera->tasaAlumbrado;
		$total += $this->cabecera->otrasTasas;
		
		return $total;
	}
	public function getPayAmount()
	{
		return $this->getSubtotal() - $this->cabecera->descuentoAdicional - $this->cabecera->ajusteNoSujetoIva;
	}
	public function instanceDetail()
	{
		$detail = new InvoiceDetail();
		$detail->addSkipProperty('numeroSerie');
		$detail->addSkipProperty('numeroImei');
		return $detail;
	}
}
<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Invoices;

use Exception;

class InvoiceDetailIce extends InvoiceDetail
{
	public	$actividadEconomica;
	public	$codigoProductoSin;
	public	$codigoProducto;
	public	$descripcion;
	public	$cantidad;
	public	$unidadMedida;
	public	$precioUnitario;
	public	$montoDescuento;
	public	$subTotal;
	public	$marcaIce;
	public	$alicuotaIva 		= null;
	public	$precioNetoVentaIce = null;
	public	$alicuotaEspecifica	= null;
	public	$alicuotaPorcentual	= null;
	public	$montoIceEspecifico	= null;
	public	$montoIcePorcentual	= null;
	public	$cantidadIce		= 1.98;
	
	public function __construct()
	{
		parent::__construct();
		$this->skipProperties[] = 'numeroSerie';
		$this->skipProperties[] = 'numeroImei';
		
		$this->xmlAttributes['alicuotaIva'] = [['attr' => 'xsi:nil', 'value' => 'true', 'ns' => 'http://www.w3.org/2001/XMLSchema-instance']];
		$this->xmlAttributes['precioNetoVentaIce'] = [['attr' => 'xsi:nil', 'value' => 'true', 'ns' => 'http://www.w3.org/2001/XMLSchema-instance']];
		$this->xmlAttributes['alicuotaEspecifica'] = [['attr' => 'xsi:nil', 'value' => 'true', 'ns' => 'http://www.w3.org/2001/XMLSchema-instance']];
		$this->xmlAttributes['alicuotaPorcentual'] = [['attr' => 'xsi:nil', 'value' => 'true', 'ns' => 'http://www.w3.org/2001/XMLSchema-instance']];
		$this->xmlAttributes['montoIceEspecifico'] = [['attr' => 'xsi:nil', 'value' => 'true', 'ns' => 'http://www.w3.org/2001/XMLSchema-instance']];
		$this->xmlAttributes['montoIcePorcentual'] = [['attr' => 'xsi:nil', 'value' => 'true', 'ns' => 'http://www.w3.org/2001/XMLSchema-instance']];
		$this->xmlAttributes['cantidadIce'] = [['attr' => 'xsi:nil', 'value' => 'true', 'ns' => 'http://www.w3.org/2001/XMLSchema-instance']];
	}
	public function validate()
	{
		parent::validate();
	}
}
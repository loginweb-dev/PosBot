<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Invoices;


class ElectronicaAlcanzadaIce extends AlcanzadaIce
{
	public function __construct()
	{
		parent::__construct();
		
		$this->classAlias 	= 'facturaElectronicaAlcanzadaIce';
	}
	public function validate()
	{
		parent::validate();
	}
}

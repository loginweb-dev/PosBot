<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Invoices;

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Message;
use Exception;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\DocumentTypes;

class InvoiceHeaderServicioBasico extends InvoiceHeader
{
	public	$nitEmisor;
	public	$razonSocialEmisor;
	public	$municipio;
	public	$telefono;
	public	$numeroFactura;
	public	$cuf;
	public	$cufd;
	public	$codigoSucursal;
	public	$direccion;
	public	$codigoPuntoVenta;
	public	$mes;
	public	$gestion;
	public	$ciudad;
	public	$zona;
	public	$numeroMedidor;
	public	$fechaEmision;
	public	$nombreRazonSocial;
	public	$domicilioCliente;
	public	$codigoTipoDocumentoIdentidad;
	public	$numeroDocumento;
	public	$complemento;
	public	$codigoCliente;
	public	$codigoMetodoPago;
	public	$numeroTarjeta;
	public	$montoTotal;
	public	$montoTotalSujetoIva;
	public	$consumoPeriodo;
	public	$beneficiarioLey1886;
	public	$montoDescuentoLey1886;
	public	$montoDescuentoTarifaDignidad;
	public	$tasaAseo;
	public	$tasaAlumbrado;
	public	$ajusteNoSujetoIva;
	public	$detalleAjusteNoSujetoIva;
	public	$ajusteSujetoIva;
	public	$detalleAjusteSujetoIva;
	public	$otrosPagosNoSujetoIva;
	public	$detalleOtrosPagosNoSujetoIva;
	public	$otrasTasas;
	public	$codigoMoneda;
	public	$tipoCambio;
	public	$montoTotalMoneda;
	public	$descuentoAdicional;
	public	$codigoExcepcion;
	public	$cafc;
	public	$leyenda = 'Ley Nro 453: Tienes derecho a recibir información sobre las características y contenidos de los servicios que utilices.';
	public	$usuario;
	public	$codigoDocumentoSector;
	
	public function __construct()
	{
		parent::__construct();
		$this->xmlAttributes['mes'] = [['attr' => 'xsi:nil', 'value' => 'true', 'ns' => 'http://www.w3.org/2001/XMLSchema-instance']];
		$this->xmlAttributes['gestion'] = [['attr' => 'xsi:nil', 'value' => 'true', 'ns' => 'http://www.w3.org/2001/XMLSchema-instance']];
		$this->xmlAttributes['ciudad'] = [['attr' => 'xsi:nil', 'value' => 'true', 'ns' => 'http://www.w3.org/2001/XMLSchema-instance']];
		$this->xmlAttributes['zona'] = [['attr' => 'xsi:nil', 'value' => 'true', 'ns' => 'http://www.w3.org/2001/XMLSchema-instance']];
		$this->xmlAttributes['domicilioCliente'] = [['attr' => 'xsi:nil', 'value' => 'true', 'ns' => 'http://www.w3.org/2001/XMLSchema-instance']];
		$this->xmlAttributes['consumoPeriodo'] = [['attr' => 'xsi:nil', 'value' => 'true', 'ns' => 'http://www.w3.org/2001/XMLSchema-instance']];
		$this->xmlAttributes['beneficiarioLey1886'] = [['attr' => 'xsi:nil', 'value' => 'true', 'ns' => 'http://www.w3.org/2001/XMLSchema-instance']];
		$this->xmlAttributes['montoDescuentoLey1886'] = [['attr' => 'xsi:nil', 'value' => 'true', 'ns' => 'http://www.w3.org/2001/XMLSchema-instance']];
		$this->xmlAttributes['montoDescuentoTarifaDignidad'] = [['attr' => 'xsi:nil', 'value' => 'true', 'ns' => 'http://www.w3.org/2001/XMLSchema-instance'] ];
		$this->xmlAttributes['tasaAseo'] = [['attr' => 'xsi:nil', 'value' => 'true', 'ns' => 'http://www.w3.org/2001/XMLSchema-instance'] ];
		$this->xmlAttributes['tasaAlumbrado'] = [['attr' => 'xsi:nil', 'value' => 'true', 'ns' => 'http://www.w3.org/2001/XMLSchema-instance'] ];
		$this->xmlAttributes['ajusteNoSujetoIva'] = [['attr' => 'xsi:nil', 'value' => 'true', 'ns' => 'http://www.w3.org/2001/XMLSchema-instance'] ];
		$this->xmlAttributes['detalleAjusteNoSujetoIva'] = [['attr' => 'xsi:nil', 'value' => 'true', 'ns' => 'http://www.w3.org/2001/XMLSchema-instance'] ];
		$this->xmlAttributes['ajusteSujetoIva'] = [['attr' => 'xsi:nil', 'value' => 'true', 'ns' => 'http://www.w3.org/2001/XMLSchema-instance'] ];
		$this->xmlAttributes['detalleAjusteSujetoIva'] = [['attr' => 'xsi:nil', 'value' => 'true', 'ns' => 'http://www.w3.org/2001/XMLSchema-instance'] ];
		$this->xmlAttributes['otrosPagosNoSujetoIva'] = [['attr' => 'xsi:nil', 'value' => 'true', 'ns' => 'http://www.w3.org/2001/XMLSchema-instance'] ];
		$this->xmlAttributes['detalleOtrosPagosNoSujetoIva'] = [['attr' => 'xsi:nil', 'value' => 'true', 'ns' => 'http://www.w3.org/2001/XMLSchema-instance'] ];
		$this->xmlAttributes['otrasTasas'] = [['attr' => 'xsi:nil', 'value' => 'true', 'ns' => 'http://www.w3.org/2001/XMLSchema-instance'] ];
		
		$this->addSkipProperty('montoGiftCard');
		$this->codigoDocumentoSector = DocumentTypes::FACTURA_SERV_BASICOS;
	}
	public function validate()
	{
		if( empty($this->numeroMedidor) )
			throw new Exception('Numero medidor invalido');
		if( $this->consumoPeriodo <= 0 )
			$this->consumoPeriodo = null;
		if( $this->beneficiarioLey1886 <= 0 )
			$this->beneficiarioLey1886 = null;
		if( $this->montoDescuentoLey1886 <= 0 )
			$this->montoDescuentoLey1886 = null;
		if( $this->montoDescuentoTarifaDignidad <= 0 )
			$this->montoDescuentoTarifaDignidad = null;
		if( $this->tasaAseo <= 0 )
			$this->tasaAseo = null;
		if( $this->tasaAlumbrado <= 0 )
			$this->tasaAlumbrado = null;
		if( $this->ajusteNoSujetoIva <= 0 )
			$this->ajusteNoSujetoIva = null;
		if( empty($this->detalleAjusteNoSujetoIva) )
			$this->detalleAjusteNoSujetoIva = null;
		if( $this->ajusteSujetoIva <= 0 )
			$this->ajusteSujetoIva = null;
		if( empty($this->detalleAjusteSujetoIva) )
			$this->detalleAjusteSujetoIva = null;
		if( $this->otrosPagosNoSujetoIva <= 0 )
			$this->otrosPagosNoSujetoIva = null;
		if( empty($this->detalleOtrosPagosNoSujetoIva) )
			$this->detalleOtrosPagosNoSujetoIva = null;
		if( $this->otrasTasas <= 0 )
			$this->otrasTasas = null;
		parent::validate();
	}
	public function checkAmounts()
	{
		//print_r($this);die(__METHOD__);
		
		
	}
}
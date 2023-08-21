@extends('layouts.app')

@section('title', "Facturacion")
@section('content')
   <section class="content">
        <div class="row">
        <div class="col-xs-12">
            <div class="col-xs-12 pos-tab-container">
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pos-tab-menu">
                    <div class="list-group">
                        <a href="#" class="list-group-item">Facturas</a>
                        <a href="#" class="list-group-item">Productos</a>
                        <a href="#" class="list-group-item">Clientes</a>
                        <a href="#" class="list-group-item active">Sincronizacion</a>
                        {{-- <a href="#" class="list-group-item">Eventos</a>
                        <a href="#" class="list-group-item">CUFDs</a>
                        <a href="#" class="list-group-item">Codigos</a>
                        <a href="#" class="list-group-item">Actividades</a>
                        <a href="#" class="list-group-item">Act. Doc. Sector</a>
                        <a href="#" class="list-group-item">Leyendas Factura</a>
                        <a href="#" class="list-group-item">Tipos de Habitacion</a>
                        <a href="#" class="list-group-item">Productos Servicios</a>
                        <a href="#" class="list-group-item">Evento Significativos</a>
                        <a href="#" class="list-group-item">Motivos Anulacion</a>
                        <a href="#" class="list-group-item">Tipos Doc. Identidad</a>
                        <a href="#" class="list-group-item">Tipos Doc. Sector</a>
                        <a href="#" class="list-group-item">Tipos Emision</a>
                        <a href="#" class="list-group-item">Tipos Metodo de Pago</a>
                        <a href="#" class="list-group-item">Tipos Moneda</a> --}}
                    </div>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 pos-tab">

                    <div class="pos-tab-content">    
                        <h2>Facturas</h2>                
                    </div>

                    <div class="pos-tab-content">     
                        <h2>Productos</h2>                                                                
                    </div>

                    <div class="pos-tab-content">    
                        <h2>Clientes</h2>                  
                    </div>

                    <div class="pos-tab-content active">
                        <h2>Sincronizacion</h2>

                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#sinc01" aria-controls="sinc01" role="tab" data-toggle="tab">Codigos</a></li>
                            {{-- <li role="presentation"><a href="#sinc02" aria-controls="sinc02" role="tab" data-toggle="tab">Clientes</a></li> --}}
                            {{-- <li role="presentation"><a href="#sinc03" aria-controls="sinc03" role="tab" data-toggle="tab">Productos</a></li> --}}
                            <li role="presentation"><a href="#sinc04" aria-controls="sinc04" role="tab" data-toggle="tab">Sucursales</a></li>
                            <li role="presentation"><a href="#sinc05" aria-controls="sinc05" role="tab" data-toggle="tab">Punto de ventas</a></li>
                            <li role="presentation"><a href="#sinc06" aria-controls="sinc06" role="tab" data-toggle="tab">Tipo de Documentos</a></li>
                        </ul>            
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="sinc01">
                                <div class="panel panel-bordered">            
                                    <div class="row">
                                        <div class="col-sm-6 form-group">
                                            <label for="">CUIS</label>
                                            <input type="text" class="form-control" value="{{ $micuis }}">
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label for="">CUFD</label>
                                            <input type="text" class="form-control" value="{{ $micufd }}">
                                        </div>
                                    </div>                            
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="sinc02">  
                                <h4>Clientes</h4>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="sinc03">  
                                <h4>Productos</h4>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="sinc06">     
                                <table class="table" id="tb_sincronizarParametricaTipoDocumentoSector">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>  
                            </div>
                        </div>                 
                    </div>

                </div>
            </div>

        </div>
    </section>
</div>

@stop

@section('javascript')

<script>

</script>
@stop
@extends('layouts.app')

@section('title', "Siat")
@section('content')
   <section class="content">
        <div class="row">
        <div class="col-xs-12">

            <div class="col-xs-12 pos-tab-container">
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pos-tab-menu">
                    <div class="list-group">
                        <a href="#" class="list-group-item text-center active">Informacion</a>
                        <a href="#" class="list-group-item text-center">Catalogo</a>
                        <a href="#" class="list-group-item text-center">Unidades</a>
                        <a href="#" class="list-group-item text-center">Pasarelas</a>
                        <a href="#" class="list-group-item text-center">-------</a>
                    </div>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 pos-tab">

                    <div class="pos-tab-content active">
                        <table class="table">
                            <tr>
                                <td>Cliente</td>
                                <td>{{ $misdatos["razon_social"] }}</td>
                            </tr>
                            <tr>
                                <td>Codigo</td>
                                <td>{{ $misdatos["codigo_sistema"] }}</td>
                            </tr>
                            <tr>
                                <td>Actidades</td>
                                <td>
                                    <ul>
                                        @foreach ($atividades as $item)
                                            <li>{{ $item["codigoCaeb"]." | ".$item["descripcion"]." | ".$item["tipoActividad"]  }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                        
                            </tr>
                            <tr>
                                <td>Fecha</td>
                                <td>{{ $mifecha["fechaHora"] }}</td>
                            </tr>
                        </table>
                        <hr>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>codigoActividad</th>
                                    <th>descripcionLeyenda</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($leyendas as $item)
                                    <tr>
                                        <td> {{ $item['codigoActividad'] }}</td>
                                        <td> {{ $item['descripcionLeyenda'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="pos-tab-content">
                      
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>codigoActividad</th>
                                        <th>codigoProducto</th>
                                        <th>descripcionProducto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $item)
                                        <tr>
                                            <td> {{ $item['codigoActividad'] }}</td>
                                            <td> {{ $item['codigoProducto'] }}</td>
                                            <td> {{ $item['descripcionProducto'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                    
                    </div>

                    <div class="pos-tab-content">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>codigoActividad</th>
                                    <th>codigoProducto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($unidades as $item)
                                    <tr>
                                        <td> {{ $item['codigoClasificador'] }}</td>
                                        <td> {{ $item['descripcion'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="pos-tab-content">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>codigoActividad</th>
                                    <th>codigoProducto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pagos as $item)
                                    <tr>
                                        <td> {{ $item['codigoClasificador'] }}</td>
                                        <td> {{ $item['descripcion'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="pos-tab-content">
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

@stop

@section('javascript')

@stop
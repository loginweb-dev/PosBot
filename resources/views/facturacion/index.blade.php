@extends('layouts.app')

@section('title', "Facturacion")
@section('content')
   <section class="content">
        <div class="row">
           
            <div class="col-sm-12 form-group">
                <label for="">Consular al SIAT</label>
                <select name="" id="myasync" class="form-control">
                    <option value="">-- Selecciona una opcion --</option>
                                    
                    
                    <option value="sincronizarParametricaTipoMoneda">sincronizarParametricaTipoMoneda</option>
                    <option value="sincronizarListaProductosServicios">sincronizarListaProductosServicios</option>
                    <option value="sincronizarParametricaTipoPuntoVenta">sincronizarParametricaTipoPuntoVenta</option>
                    <option value="sincronizarListaLeyendasFactura">sincronizarListaLeyendasFactura</option>
                    <option value="sincronizarParametricaTipoEmision">sincronizarParametricaTipoEmision</option>
                    <option value="sincronizarActividades">sincronizarActividades</option>
                    <option value="verificarComunicacion">verificarComunicacion</option>
                    <option value="sincronizarParametricaTipoMoneda">sincronizarParametricaTipoMoneda</option>
                    <option value="sincronizarParametricaTipoDocumentoSector">sincronizarParametricaTipoDocumentoSector</option>
                    <option value="sincronizarParametricaTiposFactura">sincronizarParametricaTiposFactura</option>
                    <option value="sincronizarParametricaTipoDocumentoIdentidad">sincronizarParametricaTipoDocumentoIdentidad</option>
                    <option value="cuis">Obtener codigos CUIS</option>
                    <option value="cufd">Obtener codigos CUFD</option>
                    <option value="sincronizacion">Sincronizacion</option>
                    <option value="servicio">Obtener servicio</option>                                                                                                
                </select>
                <br>
                <label for="">Mensaje de respuesta</label>
                <textarea name="" id="micode" cols="" rows="28" class="form-control" placeholder="click en el boton para obterner el cufd"></textarea>
            </div>
                        
        </div>
    </section>
</div>

@stop

@section('javascript')


<script>
    $("#myasync").change(async function (e) { 
        e.preventDefault();        
        var midata = await axios.post("/api/factura/eventos", {
            nit: "{{ $minegocio->business->tax_number_1 }}",
            razonSocial: "{{ $minegocio->business->tax_label_1 }}",
            type: this.value
        })
        $("#micode").val(JSON.stringify(midata.data)) 
        $('#micode').json_beautify();        
    });

    // The plugin
    $.fn.json_beautify= function() {
        this.each(function(){
            var el = $(this),
                obj = JSON.parse(el.val()),
                pretty = JSON.stringify(obj, undefined, 4);
            el.val(pretty);
        });
    };    
</script>
@stop
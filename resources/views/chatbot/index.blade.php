@extends('layouts.app')
@section('title', 'Chatbot')
@section('css')

@endsection
@php
    $username = Auth::user()->username;
    $miledas = App\Lead::where("session", $username)->orderBy("id", "DESC")->limit(100)->get(); 
    $minegocio = App\User::where("username", $username)->with("business")->first(); 
    $milocation = App\BusinessLocation::where("business_id", $minegocio->business->id)->get();
@endphp
@section('content')

<section class="content">
    {{-- @if (count($miledas) > 0)          --}}
    <!-- Nav tabs -->
    <h4>Perfil: {{ $username }} | Whatsapp: {{ $milocation[0]->mobile }} | Sucursal:  {{ $milocation[0]->name }}</h4>
   
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#estado" aria-controls="eastado" role="tab" data-toggle="tab">Inicio</a></li>
        <li role="presentation"><a href="#leads" aria-controls="flujos" role="tab" data-toggle="tab">Leads (clientes)</a></li>
        <li role="presentation"><a href="#masivo" aria-controls="masivo" role="tab" data-toggle="tab">Envios masivos</a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="estado">
            <div class="panel panel-bordered">            
                <div class="form-group col-sm-4 text-center">
                    <p>Escanea la imagen con tu whatsapp (como whatsapp web)</p>
                    @if ($username == "percyalvarez2023")
                        <img src="{{ asset('base-baileys-mysql/percyalvarez2023.qr.png') }}" class="img-responsive" alt="">   
                    @else
                        <img src="" class="img-responsive" alt="">   
                    @endif
                         
                </div>
                <div class="col-sm-8">
                    <div class="form-group">
                        <label for="">Telefono o Invitacion</label>
                        <input type="text" id="phone" class="form-control" value="59172823861">
                    </div>
                    <div class="form-group">
                        <label for="">Mensaje</label>
                        <textarea rows="6" id="message" class="form-control">Mensaje de prueba</textarea>
                    </div>
                    <a href="#" onclick="misend()"  class="btn btn-primary">Enviar mensaje</a>
                    <a href="#" onclick="migroup()"  class="btn btn-primary">Info grupo</a>
                    <a href="/business/settings"  class="btn btn-success">Preguntas</a> 
                    <a href="/business-location"  class="btn btn-success">Respuestas</a> 
                </div>                                       
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="leads">
            <div class="col-sm-12">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <td>Fecha</td>    
                            <td>Sesion</td>  
                            <td>Cateroria</td>                    
                            <td>Mensaje</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($miledas as $item)                                          
                            <tr>
                                <td>
                                    ID: {{ $item->id }}
                                    <br>
                                    {{ $item->phone }}
                                    <br>
                                    {{ $item->created_at }}
                                    
                                </td>
                                <td>
                                    {{ $item->session }}
                                </td>
                                <td>
                                    {{ $item->categoria }}
                                </td>
                                <td>
                                    {{ $item->message }}
                                </td>
                                <td>
                                    <a href="#" class="btn btn-xs btn-success" data-toggle="modal" data-target="#exampleModalCenter">Acciones</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="masivo">        
        </div>
    </div>
    {{-- @else
        <h2 class="text-center">Habilita el modulo del chatbot para whstapp, copmunicandote con el administrador +591 72823861</h2>
    @endif     --}}
</section>


<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#opcion1" aria-controls="opcion1" role="tab" data-toggle="tab">opcion 1</a></li>
                <li role="presentation"><a href="#opcion2" aria-controls="opcion2" role="tab" data-toggle="tab">opcion 2</a></li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="opcion1">

                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
</div>
@endsection

@section('javascript')

<script>
    async function misend() {    
        var miurl = "{{ env('CB_URL').$username.'/message' }}"
        var midata = {
            phone: $("#phone").val(),
            message: $("#message").val()
        }
        await axios.post(miurl, midata)
            .then(function (response) {
                console.log(response);
            })
            .catch(function (error) {
                if (error.message) {
                    toastr.error("Error en el chatbot, escanea el QR")
                }else{
                    toastr.info("mensaje enviado...")
                }                    
            })
    }
    async function migroup() {    
        var miurl = "{{ env('CB_URL').$username.'/group' }}"
        var midata = {
            invitacion: $("#phone").val()
        }
        await axios.post(miurl, midata)
            .then(function (response) {
                    console.log(response.data);
                    
                    $("#message").val(JSON.stringify(response.data))
                })
            .catch(function (error) {
                if (error.message) {
                    toastr.error("Error en el chatbot, escanea el QR")
                }else{
                    toastr.info("Obteniendo datos del grupo...")
                }                    
            })
    }
    
</script>
@endsection
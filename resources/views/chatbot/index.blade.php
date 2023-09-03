@extends('layouts.app')
@section('title', 'Chatbot')
@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
@endsection
@php
    $username = Auth::user()->username;
    $miledas = App\Lead::where("session", $username)->orderBy("id", "DESC")->limit(100)->get(); 
    $minegocio = App\User::where("username", $username)->with("business")->first(); 
    $milocation = App\BusinessLocation::where("business_id", $minegocio->business->id)->get();
    $miclientes = App\Contact::where("business_id", $minegocio->business->id)->get();
    $custom_labels = json_decode(session('business.custom_labels'), true);
@endphp
@section('content')
    <section class="content">
        <div class="col-xs-12 pos-tab-container">
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pos-tab-menu">
                <div class="list-group">
                    <a href="#" class="list-group-item active">Agente 01</a>
                    <a href="#" class="list-group-item">Agente 02</a>
                    <a href="#" class="list-group-item">Agente 03</a>
                    <a href="#" class="list-group-item">Leads</a>
                    <a href="#" class="list-group-item">SCRM</a>
                    <a href="#" class="list-group-item">Multimedia</a>
                    <a href="#" class="list-group-item">Call Center</a>
                </div>
            </div>
            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 pos-tab">
                <div class="pos-tab-content active">
                    <h4>Perfil: {{ $username }} | Whatsapp: {{ $milocation[0]->mobile }} | Sucursal:  {{ $milocation[0]->name }}</h4>
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#estado" aria-controls="eastado" role="tab" data-toggle="tab">Inicio</a></li>
                        {{-- <li role="presentation"><a href="#group" aria-controls="group" role="tab" data-toggle="tab">Grupos</a></li> --}}
                        <li role="presentation"><a href="#masivo" aria-controls="masivo" role="tab" data-toggle="tab">Envios masivos</a></li>
                    </ul>            
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="estado">
                            <div class="panel panel-bordered">            
                                <div class="col-sm-5">
                                    <div class="form-group">                                
                                        <label for="">Respuestas rapidas</label>
                                        <select name="" id="res_rap" class="form-control">
                                            <option value="">Elije una opcion</option>
                                            <option value="{{ $milocation[0]->custom_field1 }}">{{ $milocation[0]->custom_field1 }}</option>
                                            <option value="{{ $milocation[0]->custom_field2 }}">{{ $milocation[0]->custom_field2 }}</option>
                                            <option value="{{ $milocation[0]->custom_field3 }}">{{ $milocation[0]->custom_field3 }}</option>
                                            <option value="{{ $milocation[0]->custom_field4 }}">{{ $milocation[0]->custom_field4 }}</option>
                                        </select>      
                                    </div>    
                                    <div class="form-group">
                                        <label for="">Nombre de Imagen, video o audio</label>
                                        <input type="text" id="multimedia" class="form-control" value="" placeholder="{{ asset('storage/') }}">                                    
                                    </div>
                                    <div class="form-group">
                                        <label for="">Escanea la imagen con tu whatsapp (como whatsapp web)</label>
                                        @if ($username == "percyalvarez2023")

                                            <img src="{{ asset('base-baileys-mysql/percyalvarez2023.qr.png') }}" class="img-responsive" alt="">   
                                        @elseif ($username == "paulmuiba2023")
                                            <img src="{{ asset('base-baileys-mysql/paulmuiba2023.qr.png') }}" class="img-responsive" alt="">
                                        @elseif ($username == "stevenkenny7269")
                                            <img src="{{ asset('base-baileys-mysql/stevenkenny7269.qr.png') }}" class="img-responsive" alt="">
                                        @else
                                            <img src="" class="img-responsive" alt="">   
                                        @endif      
                                    </div>  
                            
                                </div>
                                <div class="col-sm-7">
                                    <div class="form-group">
                                        <label for="">Clientes, proveedores y grupos</label>
                                        <select name="" id="micontacto" class="form-control select2">
                                            <option value="">Elije una opcion</option>
                                            @foreach ($miclientes as $item)
                                                <option value="{{ $item->mobile }}">{{ $item->supplier_business_name  }}  {{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Telefono, grupo o invitacion</label>
                                        <input type="text" id="phone" class="form-control" value="" placeholder="ingresa el id">                                    
                                    </div>
                                    <div class="form-group">
                                        <label for="">Mensaje de texto o caption</label>
                                        <textarea rows="12" id="message" class="form-control" placeholder="ingresa en texto"></textarea>     
                                    </div>
                    
                                </div>  
                                <div class="col-sm-12 form-group text-center">                                
                                    <a href="#" onclick="misend('message_text')"  class="btn btn-primary">Enviar text</a>
                                    <a href="#" onclick="misend('message_image')"  class="btn btn-primary">Enviar image</a>
                                    <a href="#" onclick="misend('message_video')"  class="btn btn-primary">Enviar video</a>
                                    <a href="#" onclick="misend('message_audio')"  class="btn btn-primary">Enviar audio</a>
                                </div>    
                                <div class="col-sm-12 form-group text-center">
                                    <a href="#" onclick="misend('message_group_text')"  class="btn btn-warning">Enviar text</a>
                                    <a href="#" onclick="misend('message_group_image')"  class="btn btn-warning">Enviar image</a>
                                    <a href="#" onclick="misend('message_group_video')"  class="btn btn-warning">Enviar video</a>
                                    <a href="#" onclick="misend('message_group_audio')"  class="btn btn-warning">Enviar audio</a>
                                    <a href="#" onclick="misend('group_info')"  class="btn btn-warning">Info grupo</a>
                                </div>    
                                <div class="col-sm-12 form-group text-center">                         
                                    <a href="/business/settings"  class="btn btn-success">Preguntas</a> 
                                    <a href="/business-location"  class="btn btn-success">Respuestas</a> 
                                    <p>Los botonos azules son para enviar a otro whatsapp y los naranja para enviar a grupos, y los botones verdes son para editar las opciones y respuestas del bot.</p>
                                </div>                                                            
                            </div>
                        
                        </div>
                        {{-- <div role="tabpanel" class="tab-pane" id="group">        
                        </div> --}}
                        <div role="tabpanel" class="tab-pane" id="masivo">        
                        </div>
                    </div> 
                
                </div>

                <div class="pos-tab-content">       
                    {{-- <h4>Perfil: {{ $username }} | Whatsapp: {{ $milocation[1]->mobile }} | Sucursal:  {{ $milocation[1]->name }}</h4>         --}}
                </div>

                <div class="pos-tab-content">
                    {{-- <h4>Perfil: {{ $username }} | Whatsapp: {{ $milocation[2]->mobile }} | Sucursal:  {{ $milocation[2]->name }}</h4> --}}
                </div>

                <div class="pos-tab-content">
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
                <div class="pos-tab-content">
                    <h4>Gestion de clientes CRM</h4>
                </div>
                <div class="pos-tab-content">
                    <div id="fm"></div>
                </div>

                <div class="pos-tab-content">

                </div>
            </div>
        </div>



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
    <script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>
    <script>
        async function misend(mitype) {    
            var miurl = "{{ env('CB_URL').$username }}"
            // console.log("{{ asset('storage') }}/"+$("#multimedia").val())
            var midata = {
                phone: $("#phone").val(),
                message: $("#message").val(),
                type: mitype,
                multimedia: "{{ asset('storage') }}/"+$("#multimedia").val()
            }
            // console.log(midata);
            await axios.post(miurl, midata)
                .then(function (response) {
                    toastr.info("mensaje enviado...")
                    $("#message").val(JSON.stringify(response.data))
                })
                .catch(function (error) {
                    if (error.message) {
                        toastr.error("Error en el chatbot, escanea el QR")
                    }else{
                        toastr.info("mensaje enviado...")
                    }                    
                })
        }    

        $("#micontacto").change(function (e) { 
            e.preventDefault();
            
            $("#phone").val(this.value)
            var misms = $("#res_rap").val()
            $("#message").val(misms)
        });

        $("#res_rap").change(function (e) { 
            e.preventDefault();
            
            $("#message").val(this.value)

            var miphone = $("#micontacto").val()
            $("#phone").val(miphone)
        });        
    </script>
@endsection
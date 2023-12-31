@extends('layouts.home')
@section('title', config('app.name', 'ultimatePOS'))

@section('content')
    <style type="text/css">
        .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
                /* margin-top: 10%; */
            }
        .title {
                font-size: 84px;
            }
        .tagline {
                font-size:25px;
                font-weight: 300;
                text-align: center;
            }

        @media only screen and (max-width: 600px) {
            .title{
                font-size: 38px;
            }
            .tagline {
                font-size:18px;
            }
        }
    </style>
    <style>
        form {
          display: flex;
          flex-direction: column;
          align-items: center;
          /* margin-top: 20px; */
        }
        
        label {
          font-size: 20px;
          margin-bottom: 10px;
        }
        
        textarea {
          width: 90%;
          height: 150px;
          padding: 10px;
          font-size: 16px;
          border: 1px solid #ccc;
          border-radius: 5px;
          margin-bottom: 20px;
          color:black;
        }
        
        button {
          background-color: #25d366;
          color: #fff;
          font-size: 20px;
          padding: 10px 20px;
          border: none;
          border-radius: 5px;
          cursor: pointer;
        }
        </style>
    <div class="title flex-center" style="font-weight: 600 !important;">
        {{ env('APP_TITLE', 'ultimatePOS') }}
    </div>
    <p class="tagline">        
        ¡Bienvenido(a) a nuestro sistema de ventas y facturacón en línea! Estamos encantados de que hayas decidido utilizar nuestros servicios para hacer crecer tu negocio e internet. 
        Nuestro objetivo es proporcionarte una plataforma fácil de usar y segura para gestionar tus ventas y facturación en línea. Estamos comprometidos en ayudarte a ahorrar tiempo y dinero, brindándote una experiencia de usuario excepcional.
    </p>
    <p class="tagline text-center"> Mas información comunicarse al {{ env('PHONE')
    }}</p>
    <hr>
    <form action="https://api.whatsapp.com/send" method="get" target="_blank">
        <label for="message">enviar sms a un agente de ventas:</label>
        <textarea id="message" name="text"></textarea>
        <input type="hidden" name="phone" value="{{ env('PHONE')
     }}" />
        <button type="submit">Enviar mensaje</button>
    </form>
      
    <hr>
    <iframe width="100%" height="600" src="https://www.youtube.com/embed/aMaGIL6Mgdc" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
@endsection
            
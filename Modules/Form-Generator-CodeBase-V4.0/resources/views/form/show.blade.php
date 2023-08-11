@extends('layouts.app')

@section('content')
@php
    $bg_color = $form->schema['settings']['color']['background'];
    $error_msg_color = $form->schema['settings']['color']['error_msg'];
    $additional_css = $form->schema['additional_js_css']['css'];
    $additional_js = $form->schema['additional_js_css']['js'];
@endphp
    
<div class="@if(!empty($iframe_enabled) && $iframe_enabled) container-fluid @else container @endif">
    <div class="row justify-content-center">
        <div class="@if(!empty($iframe_enabled) && !$iframe_enabled) col-md-12 @endif">
    		@include('form.partials.form_card')
        </div>
    </div>
</div>
@endsection

@section('footer')
<!-- form theme -->
@if(isset($form->schema['settings']['theme']) && $form->schema['settings']['theme'] != 'default')
    <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.4.1/{{$form->schema['settings']['theme']}}/bootstrap.min.css" rel="stylesheet">
@endif
<style type="text/css">
    .error {
        color:{{$error_msg_color}}
    }
</style>

@if(!empty($additional_js))
    {!!$additional_js!!}
@endif

@endsection

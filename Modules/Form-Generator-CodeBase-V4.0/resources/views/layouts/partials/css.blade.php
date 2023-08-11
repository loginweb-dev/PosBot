@php
    $is_download = isset($is_download) ? $is_download : false;
    $error_msg_color = isset($error_msg_color) ? $error_msg_color : 'red';
@endphp

@if(!$is_download)
	<link href="{{asset(mix('css/app.css'))}}" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.18/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/fc-3.3.1/fh-3.1.4/datatables.min.css?v={{$asset_version}}"/>
    <!-- ladda.js -->
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/ladda-themeless.min.css?v={{$asset_version}}">
    <!-- intro.js -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/2.9.3/introjs.min.css?v={{$asset_version}}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/intro.js@2.9.3/themes/introjs-nassim.min.css?v={{$asset_version}}">
@else
    <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css?v={{$asset_version}}" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
@endif

<!-- form theme -->
@if($is_download && isset($form->schema['settings']['theme']) && $form->schema['settings']['theme'] != 'default')
    <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.4.1/{{$form->schema['settings']['theme']}}/bootstrap.min.css" rel="stylesheet">
@endif

<link href="//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-bs4.css?v={{$asset_version}}" rel="stylesheet">
<link href="//cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css?v={{$asset_version}}" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css?v={{$asset_version}}" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/rangeslider.js/2.3.2/rangeslider.min.css?v={{$asset_version}}">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/css/tempusdominus-bootstrap-4.min.css?v={{$asset_version}}" />

<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css?v={{$asset_version}}">

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.10/css/bootstrap-select.min.css?v={{$asset_version}}"/>

<link href="//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-bs4.css?v={{$asset_version}}" rel="stylesheet">

<!-- bootstrap star rating -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.6/css/star-rating.min.css?v={{$asset_version}}" media="all" rel="stylesheet" type="text/css" />
<!-- if you need to use a theme, then include the theme CSS file -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.6/themes/krajee-svg/theme.css?v={{$asset_version}}" media="all" rel="stylesheet" type="text/css" />

<style type="text/css">
    .xs{
        font-size: 0.575rem !important;
    }
    .error {
        color:{!!$error_msg_color!!}
    }

    @if(!$is_download)
    body {
        margin: 0; /* If not already reset */
    }

    .content {
        min-height: calc(100vh - 80px);
    }

    footer {
        height: 50px;
    }
    @endif
    /*custom css for switch*/
    .switch {
      font-size: 1rem;
      position: relative;
    }
    .switch input {
      position: absolute;
      height: 1px;
      width: 1px;
      background: none;
      border: 0;
      clip: rect(0 0 0 0);
      clip-path: inset(50%);
      overflow: hidden;
      padding: 0;
    }
    .switch input + label {
      position: relative;
      min-width: calc(calc(2.375rem * .8) * 2);
      border-radius: calc(2.375rem * .8);
      height: calc(2.375rem * .8);
      line-height: calc(2.375rem * .8);
      display: inline-block;
      cursor: pointer;
      outline: none;
      user-select: none;
      vertical-align: middle;
      text-indent: calc(calc(calc(2.375rem * .8) * 2) + .5rem);
    }
    .switch input + label::before,
    .switch input + label::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: calc(calc(2.375rem * .8) * 2);
      bottom: 0;
      display: block;
    }
    .switch input + label::before {
      right: 0;
      background-color: #dee2e6;
      border-radius: calc(2.375rem * .8);
      transition: 0.2s all;
    }
    .switch input + label::after {
      top: 2px;
      left: 2px;
      width: calc(calc(2.375rem * .8) - calc(2px * 2));
      height: calc(calc(2.375rem * .8) - calc(2px * 2));
      border-radius: 50%;
      background-color: white;
      transition: 0.2s all;
    }
    .switch input:checked + label::before {
      background-color: #08d;
    }
    .switch input:checked + label::after {
      margin-left: calc(2.375rem * .8);
    }
    .switch input:focus + label::before {
      outline: none;
      box-shadow: 0 0 0 0.2rem rgba(0, 136, 221, 0.25);
    }
    .switch input:disabled + label {
      color: #868e96;
      cursor: not-allowed;
    }
    .switch input:disabled + label::before {
      background-color: #e9ecef;
    }
    .switch.switch-sm {
      font-size: 0.875rem;
    }
    .switch.switch-sm input + label {
      min-width: calc(calc(1.9375rem * .8) * 2);
      height: calc(1.9375rem * .8);
      line-height: calc(1.9375rem * .8);
      text-indent: calc(calc(calc(1.9375rem * .8) * 2) + .5rem);
    }
    .switch.switch-sm input + label::before {
      width: calc(calc(1.9375rem * .8) * 2);
    }
    .switch.switch-sm input + label::after {
      width: calc(calc(1.9375rem * .8) - calc(2px * 2));
      height: calc(calc(1.9375rem * .8) - calc(2px * 2));
    }
    .switch.switch-sm input:checked + label::after {
      margin-left: calc(1.9375rem * .8);
    }
    .switch.switch-lg {
      font-size: 1.25rem;
    }
    .switch.switch-lg input + label {
      min-width: calc(calc(3rem * .8) * 2);
      height: calc(3rem * .8);
      line-height: calc(3rem * .8);
      text-indent: calc(calc(calc(3rem * .8) * 2) + .5rem);
    }
    .switch.switch-lg input + label::before {
      width: calc(calc(3rem * .8) * 2);
    }
    .switch.switch-lg input + label::after {
      width: calc(calc(3rem * .8) - calc(2px * 2));
      height: calc(calc(3rem * .8) - calc(2px * 2));
    }
    .switch.switch-lg input:checked + label::after {
      margin-left: calc(3rem * .8);
    }
    .switch + .switch {
      margin-left: 1rem;
    }

    .dropdown-menu {
      margin-top: .75rem;
    }
    .signature-pad {
      background: aliceblue;
    }
    .pointer{
      cursor: pointer;
    }
</style>
@if(!empty($additional_css))
    {!!$additional_css!!}
@endif
@extends('layouts.app')
@section('title', 'Multimedia')
@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
@endsection

@section('content')
    <section class="content">
        <div id="fm"></div>
    </section>
@endsection

@section('javascript')
    <script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>
@endsection
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <create-form form-data="{{json_encode($form)}}" :placeholder-img="{{json_encode($placeholder_img)}}" :save-template="{{json_encode($save_as_template)}}">
            </create-form>
        </div>
    </div>
</div>
@endsection

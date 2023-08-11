@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row">
        <div class="col-md-12">
            @include('layouts/partials/status')
        </div>
    </div>
    @if(auth()->user()->can('superadmin') || Auth::user()->can_create_form)
        <div class="row mb-5">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-file-alt"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">@lang('messages.forms')</span>
                        <span class="info-box-number">{{$form_count}}</span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-align-justify"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">@lang('messages.templates')</span>
                        <span class="info-box-number">{{$template_count}}</span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-green elevation-1"><i class="fas fa-hand-pointer"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">@lang('messages.submissions')</span>
                        <span class="info-box-number">{{$submission_count}}</span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <button type="button" data-href="{{action('FormController@create')}}" class="btn btn-primary float-right col-md-9 createForm mt-3">
                <i class="fas fa-plus" aria-hidden="true"></i>  @lang('messages.new_form')</button>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-12 col-sm-12">
            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs
                        @if(auth()->user()->can('superadmin') || Auth::user()->can_create_form)
                            nav-justified
                        @endif"
                        id="custom-tabs-four-tab" role="tablist">
                        @if(auth()->user()->can('superadmin') || Auth::user()->can_create_form)
                            <li class="nav-item">
                                <a class="nav-link active" id="custome-tabs-all-forms" data-toggle="pill" href="#custome-tabs-forms" role="tab" aria-controls="custome-tabs-forms" aria-selected="true">
                                    <i class="fas fa-file-alt" aria-hidden="true"></i> @lang('messages.all_forms')
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custome-tabs-all-templates" data-toggle="pill" href="#custome-tabs-templates" role="tab" aria-controls="custome-tabs-templates">
                                    <i class="fas fa-align-justify" aria-hidden="true"></i> @lang('messages.all_templates')
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link 
                                @if(!auth()->user()->can('superadmin') && !Auth::user()->can_create_form)
                                    active
                                @endif
                                " id="custome-tabs-shared-forms" data-toggle="pill" href="#custome-tabs-shared-forms-assigned" role="tab" aria-controls="custome-tabs-shared-forms-assigned"
                                @if(!auth()->user()->can('superadmin') && !Auth::user()->can_create_form)
                                    aria-selected="true"
                                @endif>
                               <i class="fas fa-file-alt" aria-hidden="true"></i> @lang('messages.assigned_forms')
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                        @if(auth()->user()->can('superadmin') || Auth::user()->can_create_form)
                            <div class="tab-pane fade active show" id="custome-tabs-forms" role="tabpanel" aria-labelledby="custome-tabs-all-forms">
                                <div class="table-responsive">
                                   <table class="table" id="form_table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>@lang('messages.name')</th>
                                                <th>@lang('messages.description')</th>
                                                <th>@lang('messages.created_at')</th>
                                                <th>@lang('messages.submissions')</th>
                                                <th>@lang('messages.action')</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="custome-tabs-templates" role="tabpanel" aria-labelledby="custome-tabs-all-templates">
                                <div class="table-responsive">
                                    <table class="table" id="template_table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>@lang('messages.name')</th>
                                                <th>@lang('messages.description')</th>
                                                <th>@lang('messages.action')</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                        <div class="tab-pane fade
                            @if(!auth()->user()->can('superadmin') && !Auth::user()->can_create_form)
                                    active show
                                @endif
                            " id="custome-tabs-shared-forms-assigned" role="tabpanel" aria-labelledby="custome-tabs-shared-forms">
                            <div class="table-responsive">
                                <table class="table" id="assigned_form_table" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>@lang('messages.name')</th>
                                            <th>@lang('messages.description')</th>
                                            <th>@lang('messages.created_by')</th>
                                            <th>@lang('messages.action')</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="collab_modal" role="dialog" aria-hidden="true"></div>
@endsection

@section('footer')
<script type="text/javascript">
    $(document).ready(function(){
        // form dataTable
        var form_table =   $('#form_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/home',
                buttons: [],
                dom: 'lfrtip',
                fixedHeader: false,
                aaSorting: [[2, 'desc']],
                "columnDefs": [
                    { "width": "22%", "targets": 0 },
                    { "width": "40%", "targets": 1 },
                    { "width": "15%", "targets": 2 },
                    { "width": "3%", "targets": 3 },
                    { "width": "20%", "targets": 4 }
                ],
                columns: [
                    { data: 'name' , name: 'name'},
                    { data: 'description' , name: 'description'},
                    { data: 'created_at' , name: 'created_at'},
                    { data: 'data_count', name: 'data_count', searchable:false},
                    { data: 'action', name: 'action', sortable:false }
                ]
            });

        // template dataTable
        var template_table = $('#template_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/home-template',
                buttons: [],
                dom: 'lfrtip',
                fixedHeader: false,
                "columnDefs": [
                    { "width": "40%", "targets": 0 },
                    { "width": "40%", "targets": 1 },
                    { "width": "20%", "targets": 2 }
                ],
                columns: [
                    { data: 'name' , name: 'name'},
                    { data: 'description' , name: 'description'},
                    { data: 'action', name: 'action', sortable:false }
                ]
            });

        //delete form
        $(document).on('click', '.delete_form', function(){
            var url = $(this).data("href");
            var result = confirm('Are You Sure?');
            if (result == true) {
                $.ajax({
                    method: "DELETE",
                    url: url,
                    dataType: "json",
                    success: function(result){
                        if(result.success == true){
                            toastr.success(result.msg);
                            form_table.ajax.reload();
                        } else {    
                            toastr.error(result.msg);
                        }
                    }
                });
            }
        });

        //delete template
        $(document).on('click', '.delete_template', function(){
            var url = $(this).data("href");
            var result = confirm('Are You Sure?');
            if (result == true) {
                $.ajax({
                    method: "DELETE",
                    url: url,
                    dataType: "json",
                    success: function(result){
                        if(result.success == true){
                            toastr.success(result.msg);
                            template_table.ajax.reload();
                        } else {    
                            toastr.error(result.msg);
                        }
                    }
                });
            }
        });

        // create form
        $(document).on('click', '.createForm', function(){
            var url = $(this).data('href');
            $.ajax({
                method: "GET",
                url: url,
                dataType: "html",
                success: function(response) {
                    $("#modal_div").html(response).modal("show");
                }
            });
        });

        // create widget
        $(document).on('click', '.generate_widget', function(){
            var url = $(this).data('href');
            $.ajax({
                method: "GET",
                url: url,
                dataType: "html",
                success: function(response) {
                    $("#modal_div").html(response).modal("show");
                }
            });
        });

        //copy form
        $(document).on('click', '.copy_form', function(){
            var url = $(this).data('href');
            $.ajax({
                method: "GET",
                url: url,
                dataType: "html",
                success: function(response) {
                    $("#modal_div").html(response).modal("show");
                }
            });
        });

        //assigned form to user
        var assigned_form_table =   $('#assigned_form_table').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: '/home-assigned-forms',
                            buttons: [],
                            dom: 'lfrtip',
                            fixedHeader: false,
                            aaSorting: [[0, 'desc']],
                            "columnDefs": [
                                { "width": "25%", "targets": 0 },
                                { "width": "40%", "targets": 1 },
                                { "width": "15%", "targets": 2 },
                                { "width": "20%", "targets": 3 }
                            ],
                            columns: [
                                { data: 'name' , name: 'forms.name'},
                                { data: 'description' , name: 'forms.description'},
                                { data: 'created_by' , name: 'users.name', sortable:false},
                                { data: 'action', name: 'action', sortable:false }
                            ]
                        });

        //form collaborate
        $(document).on('click', '.collab_btn', function() {
            var url = $(this).data('href');
            $.ajax({
                method: "GET",
                url: url,
                dataType: "html",
                success: function(response) {
                    $("#collab_modal").html(response).modal("show");
                }
            });
        });

        $("#collab_modal").on('shown.bs.modal', function () {
            if ($("#form_design").length) {
                $(document).on('change', '#form_design', function(){
                    if ($("#form_design").is(":checked")) {
                        $("#form_view").attr('checked', true);
                    } else {
                        $("#form_view").attr('checked', false);
                    }
                });
            }
        });

        $(document).on('submit', 'form#collaborate_form', function (e) {
            e.preventDefault();
            var data = $("form#collaborate_form").serialize();
            var url = $("form#collaborate_form").attr('action');
            var ladda = Ladda.create(document.querySelector('.submit_btn'));
            ladda.start();
            $.ajax({
                method: "POST",
                url: url,
                dataType: "json",
                data: data,
                success: function (response) {
                    ladda.stop();
                    if (response.success) {
                        $("#collab_modal").modal('hide');
                        toastr.success(response.msg);
                    } else {
                        toastr.error(response.msg);
                    }
                }
            });
        });

        $('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr('href');
            if (target == '#custome-tabs-forms') {
                if(typeof form_table != 'undefined') {
                    form_table.ajax.reload();
                }
            } else if (target == '#custome-tabs-templates') {
                if(typeof template_table != 'undefined') {
                    template_table.ajax.reload();
                }
            } else if (target == '#custome-tabs-shared-forms-assigned') {
                if(typeof assigned_form_table != 'undefined') {
                    assigned_form_table.ajax.reload();
                }
            }
        })
    });
</script>
@endsection
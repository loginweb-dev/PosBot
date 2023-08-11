@extends('layouts.app')
@section('css')
<style type="text/css">
    .no-print {
        display: block;
    }

    @media screen {
        #printSection {
            display: none;
        }
    }

    @media print {
        body * {
            visibility:hidden;
        }

        #printSection, #printSection * {
            visibility:visible;
        }

        #printSection {
            position:absolute;
            left:0;
            top:0;
        }
        .no-print {
            display: none;
        }
    }
</style>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center no-print">
        <div class="col-md-12">
            @php
                $date_format = config('constants.APP_DATE_FORMAT');
                if (config('constants.APP_TIME_FORMAT') == '12') {
                    $date_format .= ' h:i A';
                } else if (config('constants.APP_TIME_FORMAT') == '24') {
                    $date_format .= ' H:i';
                } else {
                    $date_format = 'm/d/Y h:i A';
                }
            @endphp
    		<div class="card">
        		<div class="card-header">
        		{{$form->name}}
        		</div>
                @php
                    $is_enabled_sub_ref_no = false;
                    if(isset($form->schema['settings']['form_submision_ref']['is_enabled']) && $form->schema['settings']['form_submision_ref']['is_enabled']) {
                        $is_enabled_sub_ref_no = true;
                    }

                @endphp
		        <div class="tab-content card-body table-responsive" role="tabpanel">
                    @if(!empty($form->schema))
                        @php
                            $schema = $form->schema['form'];
                            $col_visible = $form['schema']['settings']['form_data']['col_visible'];
                            $btn_enabled = $form['schema']['settings']['form_data']['btn_enabled'];
                        @endphp
                        <table class="table" id="submitted_data_table" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>@lang('messages.action')</th>
                                    @if($is_enabled_sub_ref_no)
                                        <th>@lang('messages.submission_numbering')</th>
                                    @endif
                                    @foreach($schema as $element)
                                        @if(in_array($element['name'], $col_visible))
                                            <th>
                                                {{$element['label']}}
                                            </th>
                                        @endif
                                    @endforeach
                                    <th>@lang('messages.submitted_on')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($data as $k => $row)
                                    <tr>
                                        <td>
                                            @if(in_array('view', $btn_enabled))
                                                <button type="button" class="btn btn-info btn-sm view_form_data m-1" data-href="{{action('FormDataController@viewData', [$row->id])}}" data-toggle="modal">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                    @lang('messages.view')
                                                </button>
                                            @endif
                                            @if(in_array('delete', $btn_enabled))
                                                <button type="button" class="btn btn-danger btn-sm delete_form_data m-1" data-href="{{action('FormDataController@destroy', [$row->id])}}">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                    @lang('messages.delete')
                                                </button>
                                            @endif
                                            <a class="btn btn-primary btn-sm m-1" target="_blank" href="{{action('FormDataController@downloadPdf', [$row->id])}}">
                                                <i class="far fa-file-pdf" aria-hidden="true"></i>
                                                @lang('messages.download_pdf')
                                            </a>
                                            @php
                                                $form_id = !empty($form->slug) ? $form->slug : $form->id;
                                            @endphp
                                            <a class="btn btn-dark btn-sm m-1" target="_blank" href="{{action('FormDataController@getEditformData', ['slug' => $form_id,'id' => $row->id])}}">
                                                <i class="far fa-edit" aria-hidden="true"></i>
                                                @lang('messages.edit')
                                            </a>
                                        </td>
                                        @if($is_enabled_sub_ref_no)
                                            <td>
                                                {{$row['submission_ref']}}
                                            </td>
                                        @endif
                                        @foreach($schema as $row_element)
                                            @if(in_array($row_element['name'], $col_visible))
                                                <td>
                                                    @isset($row->data[$row_element['name']])
                                                        @if($row_element['type'] == 'file_upload')

                                                          @include('form_data.file_view', ['form_upload' => $row->data[$row_element['name']]])
                                                        @elseif($row_element['type'] == 'signature')
                                                            <img src="{{$row->data[$row_element['name']]}}" class="signature">
                                                        @elseif(is_array($row->data[$row_element['name']]) && $row_element['type'] != 'file_upload')
                                                            {{implode(', ', $row->data[$row_element['name']])}}
                                                        @else
                                                            {!! nl2br($row->data[$row_element['name']]) !!}
                                                        @endif
                                                        
                                                    @endisset
                                                </td>
                                            @endif
                                        @endforeach
                                        <td>
                                            {{\Carbon\Carbon::createFromTimestamp(strtotime($row->created_at))->format($date_format)}}
                                            <br/>
                                            <small>
                                                {{$row->created_at->diffForHumans()}}
                                            </small>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
		            @else
                        <p>Form Not found</p>
                    @endif
		        </div>
    		</div>
        </div>
    </div>
</div>
@endsection
@section('footer')
<script type="text/javascript">
    $(document).ready(function(){
        $('#submitted_data_table').DataTable({
        scrollY:        "600px",
        scrollX:        true,
        scrollCollapse: true,
        paging:         true,
        fixedColumns:   {
            leftColumns: 2
        }
    });
        // view form data
        $(document).on('click', '.view_form_data', function(){
            var url = $(this).data("href");
            $.ajax({
                method: "GET",
                dataType: "html",
                url: url,
                success: function(result){
                    $("#modal_div").html(result).modal("show");
                }
            });
        });

        //delete form data
        $(document).on('click', '.delete_form_data', function(){
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
                            setTimeout(function(){
                                location.reload();
                            }, 1000);
                        } else {    
                            toastr.error(result.msg);
                        }
                    }
                });
            }
        });

        //print form data on btn click
        $(document).on('click', '.formDataPrintBtn', function() {
            printElement(document.getElementById("print_form_data"));    
        });

        $("#modal_div").on('shown.bs.modal', function () {
            if ($("form#add_comment_form").length) {
                $("form#add_comment_form").validate();
            }
        });

        $(document).on('submit', 'form#add_comment_form', function (e) {
            e.preventDefault();
            var data = $("form#add_comment_form").serialize();
            var url = $("form#add_comment_form").attr('action');
            var ladda = Ladda.create(document.querySelector('.add_comment_btn'));
            ladda.start();
            $.ajax({
                method: "POST",
                url: url,
                dataType: "json",
                data: data,
                success: function (response) {
                    ladda.stop();
                    if (response.success) {
                        $("#comment").val('');
                        $('.direct-chat-messages').prepend(response.comment);
                        toastr.success(response.msg);
                    } else {
                        toastr.error(response.msg);
                    }
                }
            });
        });

        $(document).on('click', '.delete-comment', function(e) {
            e.preventDefault();
            var element = $(this);
            var comment_id = $(this).data('comment_id');
            var form_data_id = $(this).data('form_data_id');
            if (confirm('Are you sure.?')) {
                $.ajax({
                    method:'DELETE',
                    dataType: 'json',
                    url: '/form-data-comment/'+comment_id+'?form_data_id='+form_data_id,
                    success: function(response){
                        if (response.success) {
                            toastr.success(response.msg);
                            element.closest('.direct-chat-msg').remove();
                        } else {
                            toastr.error(response.msg);
                        }
                    }
                });
            }
        });
    });
</script>
@endsection
@php
    $is_download = isset($is_download) ? $is_download : false;
    $form = !empty($form) ? $form : '';
@endphp

<!-- reCaptcha -->
<script src="//www.google.com/recaptcha/api.js?v={{$asset_version}}" async defer></script>

<script type="text/javascript">
    if(typeof jQuery == 'undefined'){
        document.write('<script src="//code.jquery.com/jquery-3.4.1.min.js?v={{$asset_version}}" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></'+'script>');
    }
</script>

<!-- Bootstrap Js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js?v={{$asset_version}}" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="//stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js?v={{$asset_version}}" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script type="text/javascript" src="//cdn.jsdelivr.net/npm/moment@2.24.0/moment.min.js?v={{$asset_version}}"></script>
<script src="//cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js?v={{$asset_version}}"></script>
<script src="//cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js?v={{$asset_version}}"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/rangeslider.js/2.3.2/rangeslider.min.js?v={{$asset_version}}"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/js/tempusdominus-bootstrap-4.min.js?v={{$asset_version}}"></script>

<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js?v={{$asset_version}}"></script>

<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.10/js/bootstrap-select.min.js?v={{$asset_version}}"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-bs4.min.js?v={{$asset_version}}"></script>

<!-- Boostrap star rating -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.6/js/star-rating.min.js?v={{$asset_version}}" type="text/javascript"></script>
<!-- if you need to use a theme, then include the theme Js file -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.6/themes/krajee-svg/theme.js?v={{$asset_version}}"></script>
<!-- optionally if you need translation for your language then include locale file as mentioned below -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.6/js/locales/<lang>.js"></script> -->
<!-- signature pad (https://github.com/szimek/signature_pad)-->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js?v={{$asset_version}}"></script>
@if(!$is_download)
    
    <!-- app js values -->
    <script type="application/javascript">
        var APP = {};
        APP.APP_URL = '{{config('app.url')}}';
        APP.CURRENCY_SYMBOL = '{{env('CURRENCY_SYMBOL')}}';
        $.ajaxSetup({
            beforeSend: function(jqXHR, settings) {
                if (settings.url.indexOf('http') === -1) {
                    settings.url = APP.APP_URL + settings.url;
                }
            },
        });
    </script>

    <script type="text/javascript">
        //function to print page
        function printElement(elem) {
            var domClone = elem.cloneNode(true);
            var printSection = document.getElementById("printSection");
            
            if (!printSection) {
                var printSection = document.createElement("div");
                printSection.id = "printSection";
                document.body.appendChild(printSection);
            }

            printSection.innerHTML = "";
            printSection.appendChild(domClone);
            window.print();
        }
    </script>

    <script src="//cdn.jsdelivr.net/npm/jquery-validation-unobtrusive@3.2.10/dist/jquery.validate.unobtrusive.min.js?v={{$asset_version}}"></script>

    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js?v={{$asset_version}}"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js?v={{$asset_version}}"></script>
    <script type="text/javascript" src="//cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.18/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/fc-3.3.1/fh-3.1.4/datatables.min.js?v={{$asset_version}}"></script>

    <!-- ladda.js -->
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/spin.min.js?v={{$asset_version}}"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/ladda.min.js?v={{$asset_version}}"></script>
    <!-- localization -->
    <script src="{{ url('/js/lang.js') . '?v=' . $asset_version }}"></script>
    <script src="{{ asset(mix('js/app.js')) }}" defer></script>
    <!-- intro.js -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/2.9.3/intro.min.js?v={{$asset_version}}"></script>
    <script src="{{ asset('js/iframeResizercontentWindow.js') }}"></script>
@endif

<script type="text/javascript">

    jQuery.validator.setDefaults({
        errorPlacement: function(error, element) {

            // if (element.is(':hidden')) {
            //     console.log(element);

                // if(element.parents('form').find('label.error:visible').length <= 1){
            //         var position = element.closest('div').position().top;
                    
            //         $('html, body').animate({
            //             scrollTop: position
            //         }, 300);
            //     }
            // }
            
            if (element.hasClass('select2') && element.parent().hasClass('input-group')) {
                error.insertAfter(element.parent());
            } else if (element.hasClass('select2')) {
                error.insertAfter(element.next('span.select2-container'));
            } else if (element.parent().hasClass('input-group')) {
                error.insertAfter(element.parent());
            } else if (element.parent().hasClass('multi-input')) {
                error.insertAfter(element.closest('.multi-input'));
            } else if(element.hasClass('summer_note')){
                error.insertAfter(element.next('.note-editor'));
            } else if(element.hasClass('custom-control-input')) {
                error.insertAfter(element.parent().parent());
            } else if(element.hasClass('star_rating')) {
                error.insertAfter(element.parent().parent());
            } else if (element.hasClass('switch')) {
                error.insertAfter(element.parent().parent());
            } else if (element.hasClass('signature')) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }


        },

        invalidHandler: function() {
            toastr.error("{{ __('messages.some_error_in_input_field') }}");
        }
    });
    
    $(document).ready(function(){
        @if(!$is_download)
            jQuery.extend($.fn.dataTable.defaults, {
                fixedHeader: false,
                aLengthMenu: [[25, 50, 100, 200, 500, 1000, -1], [25, 50, 100, 200, 500, 1000, 'All']],
                iDisplayLength: 25,
                dom: 'lBfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'colvis'
                ]
            });

            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });
        @endif
    });

    function initialize_datetimepicker(element_name, start_date, end_date, date_format, time_format, disabled_days, enable_time_picker, time_picker_inline){
        var start = null;
        var end = null;
        var format = '';
        var display_time = null;
        var disabled_week_days = '';
        var side_by_side = time_picker_inline;

        if (disabled_days.length > 0) {
            disabled_week_days = disabled_days.join(',');
        }
        //format
        if (date_format != null) {
            format = date_format.toUpperCase();
        }

        if (enable_time_picker) {
            display_time = 'hh:mm A';
            if (time_format == '24') {
                display_time = 'HH:mm';
            }
            format += ' ' + display_time;
        }

        // start date
        if (start_date == 'none') {
            start = false;
        } else if (start_date == 'today') {
            start = moment();
        } else if (start_date == 'current_year') {
            start = moment().startOf('year');
        } else if (start_date == 'current_month') {
            start = moment().startOf('month');
        }
        //end date
        if (end_date == 'none') {
            end = false;
        } else if (end_date == 'today') {
            end = moment().add(0, 'd');
        } else if (end_date == 'current_year') {
            end = moment().endOf('year');
        } else if (end_date == 'current_month') {
            end = moment().endOf('month');
        }

        $('#'+element_name).datetimepicker({
            icons: {
                time: 'far fa-clock',
            },
            format: format,
            minDate: start,
            maxDate: end,
            daysOfWeekDisabled: [disabled_week_days],
            showClear: true,
            ignoreReadonly: true,
            sideBySide: side_by_side,
            defaultDate: $("input#"+element_name).data("defaultdate")
        });
    }

    function initialize_rangeslider(element_name) {
        $('#'+element_name).rangeslider({
            polyfill: false,
            //Callback function
            onInit: function() {
            },
            // Callback function
            onSlide: function(position, value) {
                $('.'+element_name).text(value);
            },
        });
    }
    
    Dropzone.autoDiscover = false;
    function initialize_dropzone(element_name, file_upload_msg, no_of_files_can_be_uploaded, max_file_size, allowed_file_type, url = null) {
        
        var file_remove_url = "library/delete_file.php";
        if (url == null) {
            url = "{{ url('/file-upload')}}";
            file_remove_url = "{{ url('/file-delete')}}";
        }

        var file_names = [];

        if ($('input#'+element_name).val().length > 0) {
            file_names.push($('input#'+element_name).val());
        }

        $('#'+element_name).dropzone({
            paramName: "file",
            addRemoveLinks: true,
            url: url,
            maxFilesize: max_file_size,
            dictDefaultMessage: file_upload_msg,
            maxFiles: no_of_files_can_be_uploaded,
            headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
            acceptedFiles: allowed_file_type,
            init: function() {
                //function to be use on editing a form, to display existing files
                if ($('input#'+element_name).val().length > 0) {
                    myDropzone = this;
                    var file_obj = { files : $('input#'+element_name).val()};
                    $.ajax({
                        method: "GET",
                        url: '/existing-file-display',
                        dataType: "json",
                        data: file_obj,
                        success: function(result){
                            if (result.success) {
                                $.each(result.files, function(key,file) {
                                    var mockFile = { name: file.name, uploaded_as: file.uploaded_as, size: file.size};
                                    myDropzone.emit("addedfile", mockFile);
                                    myDropzone.emit("thumbnail", mockFile, file.path);
                                    myDropzone.emit("complete", mockFile);
                                });
                            }
                        }
                    });
                }

                //function to be use on removeing a file
                this.on("removedfile", function(file) {
                    $.ajax({
                        url: file_remove_url,
                        data: { "file_name": file.uploaded_as },
                        type: "POST",
                        success: function(result) {
                            if (typeof(result) == 'string') {
                                var result = JSON.parse(result);
                            }

                            if(result.success == 1){
                                toastr.success(result.msg);
                                var index = file_names.indexOf(file.uploaded_as);
                    
                                if(index!=-1){
                                   file_names.splice(index, 1);
                                }
                                
                                var elementVal = $('input#'+element_name).val();
                                var oldVal = elementVal.split(",");

                                index = oldVal.indexOf(file.uploaded_as);

                                if(index!=-1){
                                   oldVal.splice(index, 1);
                                }

                                var newVal = oldVal.join(",");
                                $('input#'+element_name).val(newVal);
                            } else {
                                toastr.error(result.msg);
                            }
                        }
                    });
                });
            },
            success:function(file, response) {
                if (typeof(response) == 'string') {
                    var response = JSON.parse(response);
                }
                if (response.success == true) {
                    toastr.success(response.msg);
                    file_names.push(response.path);
                    file.uploaded_as = response.path;
                    $('input#'+element_name).val(file_names); //store file_names
                } else {
                    toastr.error(response.msg);
                }
            }
        });
    }

    function initialize_text_editor(element_name, placeholder, height) {
        
        $('#'+element_name).summernote({
            placeholder: placeholder,
            height: height
        });
    }   

    function initialize_star_rating(element_name)  {
        $("#"+element_name).rating({
            theme: 'krajee-fas',
            filledStar: '<i class="fas fa-star"></i>',
            emptyStar: '<i class="fas fa-star"></i>'
        });
    }

    function initialize_datetimepicker_for_form_scheduling() {

        $('div#start_date_time').datetimepicker({
            icons: {
                time: 'far fa-clock',
            },
            format: 'YYYY-MM-DD hh:mm A',
            minDate: moment(),
            showClear: true,
            ignoreReadonly: true
        });

        $('div#end_date_time').datetimepicker({
            icons: {
                time: 'far fa-clock',
            },
            format: 'YYYY-MM-DD hh:mm A',
            minDate: moment(),
            showClear: true,
            ignoreReadonly: true
        });
    }

    function initialize_signature_pad(element) {
        var signaturePad = element;
        var canvas = document.getElementById(element);
        signaturePad = new SignaturePad(canvas, {
            onEnd: function(event) {                
                var element = $(this)[0]._canvas.id
                var signature = $(this)[0].toDataURL();
                $('#output_'+element).val(signature);
            }
        });
        
        if ($('#output_'+element).val().length > 0) {

            signaturePad.fromDataURL($('#output_'+element).val());
        }
        
        $(document).on('click', '#clear_'+element, function() {
           signaturePad.clear();
           $('#output_'+$(this).data('name')).val('');
        });

        $(document).on('click', '#undo_'+element, function() {
           var data = signaturePad.toData();
            if (data) {
                data.pop(); // remove the last dot or line
                signaturePad.fromData(data); //draw signature from array of data
                if (data.length > 0) {
                    var signature = signaturePad.toDataURL();
                    $('#output_'+$(this).data('name')).val(signature);
                } else {
                    $('#output_'+$(this).data('name')).val('');
                }
            }
        });
    }

    function initializeToastrSettingsForForm (position) {
        var toastr_position = 'toast-top-right';
        if (position) {
            toastr_position = position;
        }
        toastr.options = {
          "positionClass": toastr_position
        }
    }
</script>
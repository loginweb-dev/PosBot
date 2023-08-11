<!DOCTYPE html>
<html>
<head>
	<title>{{ucFirst($form_data->form->name)}}</title>
	<style type="text/css">
		th, td {
		  padding: 15px;
		  text-align: left;
		}
		tr:nth-child(even) {
			background-color: #f2f2f2;
		}
	</style>
</head>
<body>
	<div style="width: 100% !important;text-align: center;margin-bottom: 20px;">
		<div style="width: 50% !important;">
			<h3>
	            {{ucFirst($form_data->form->name)}} 
	            @if(!empty($form_data->submittedBy))
	                <small>
	                    (
	                        @lang('messages.submitted_by'): 
	                        {{$form_data->submittedBy->name}}
	                    )
	                </small>
	            @endif
	        </h3>
		</div>
		<div style="width: 50% !important;">
			@if(isset($form_data->form->schema['settings']['form_submision_ref']['is_enabled']) && $form_data->form->schema['settings']['form_submision_ref']['is_enabled'] && !empty($form_data->submission_ref))
	          <b>@lang('messages.submission_numbering'):</b> 
	          {{$form_data->submission_ref}}
	        @endif
		</div>
	</div>

	<table style="width:100%">
	    <tbody>
	        @foreach($form_data->form->schema['form'] as $element)
	            @isset($form_data->data[$element['name']])
	            <tr>
	                <td style="width: 50% !important;">
	                    <strong>{{$element['label']}}</strong>
	                </td>
	                <td style="width: 50% !important;">
	                    @if($element['type'] == 'file_upload')
	                        @include('form_data.file_view', ['form_upload' => $form_data->data[$element['name']]])
	                    @elseif($element['type'] == 'signature')
	                        <img src="{{$form_data->data[$element['name']]}}" class="signature">
	                    @elseif(is_array($form_data->data[$element['name']]) && $element['type'] != 'file_upload')

	                        {{implode(', ', $form_data->data[$element['name']])}}
	                        
	                    @else
	                        {!! nl2br($form_data->data[$element['name']]) !!}
	                    @endif
	                </td>
	            </tr>
	            @endisset
	        @endforeach
	    </tbody>
	</table>
</body>
</html>
	
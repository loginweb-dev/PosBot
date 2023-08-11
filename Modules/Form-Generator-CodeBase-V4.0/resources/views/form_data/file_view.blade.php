@php
    $datas = implode(',', $form_upload);
    $files = explode(',', $datas);
@endphp
@foreach($files as $file)
    <a target="_blank" href="{{Storage::url(config('constants.doc_path').'/'.$file)}}">
        {{Storage::url(config('constants.doc_path').'/'.$file)}}
    </a><br>
@endforeach
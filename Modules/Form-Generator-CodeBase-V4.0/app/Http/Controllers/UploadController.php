<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        try {
            $file = $request->file('file');

            //Generate a unique name for the file.
            $file_name = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs(config('constants.doc_path'), $file_name);

            $output = $this->respondSuccess('Uploaded successfully', ['path' => $file_name]);
        } catch (Exception $e) {
            $output = $this->respondWentWrong($e);
        }
        return $output;
    }

    public function deleteFile(Request $request)
    {
        try {
            $file_name = $request->input('file_name');
            $doc_path = config('constants.doc_path');
            Storage::delete($doc_path.'/'.$file_name);
            $output = $this->respondSuccess('Removed');
        } catch (Exception $e) {
            $output = $this->respondWentWrong($e);
        }
        return $output;
    }

    public function getExistingFiles(Request $request)
    {
        try {
            $files = $request->get('files');
            $file_array = explode(',', $files);
            $file_response = [];
            $doc_path = config('constants.doc_path');
            foreach ($file_array as $key => $file) {
                if (Storage::disk('local')->exists($doc_path.'/'.$file)) {
                    $file_name = explode('_', $file, 2);
                    $file_response[$key]['uploaded_as'] = $file;
                    $file_response[$key]['name'] = !empty($file_name[1]) ? $file_name[1] : $file_name[0];

                    $file_response[$key]['size'] = Storage::size($doc_path.'/'.$file);

                    $file_response[$key]['path'] = Storage::url($doc_path.'/'.$file);
                }
            }
            $output = $this->respondSuccess('Uploaded successfully', ['files' => $file_response]);
        } catch (Exception $e) {
            $output = $this->respondWentWrong($e);
        }
        return $output;
    }
}

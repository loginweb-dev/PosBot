<?php
if (!(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) {
    echo "Permission denied";
    exit;
}
if (!empty($_FILES)) {
    error_reporting(E_ERROR | E_PARSE);
    $file_name = $_FILES['file']['name'];
    $file_size =$_FILES['file']['size'];
    $file_tmp =$_FILES['file']['tmp_name'];
    if ($file_size > 0) {
        $upload_dir =  './uploads' ;
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0777, true)) {
                echo json_encode([ 'success' => 0,
                                        'msg' => 'Permission denied. Make sure the intended directory is writable.'
                                        ]);
                exit;
            }
        }
        $new_file_name = time() . '_' . $file_name;
        move_uploaded_file($file_tmp, $upload_dir . '/'. $new_file_name);
        $uploaded_file = [
                            'success' => 1,
                            'original_file_name' => $file_name,
                            'path' => $new_file_name,
                            'msg' => 'Uploaded Successfully'
                        ];
        echo json_encode($uploaded_file);
        exit;
    }
}

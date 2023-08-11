<?php
if (!(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) {
    echo "Permission denied";
    exit;
}
if (!empty($_POST['file_name'])) {
    $toDelete = './uploads/' . $_POST['file_name'];
    if (unlink($toDelete)) {
        echo json_encode(['success' => 1,
                        'msg' => 'Removed']);
        exit;
    } else {
        echo json_encode(['success' => 0,
                        'msg' => 'Try again']);
        exit;
    }
}

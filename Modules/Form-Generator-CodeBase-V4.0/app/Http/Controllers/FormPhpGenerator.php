<?php

namespace App\Http\Controllers;

class FormPhpGenerator
{
    public function __construct()
    {
    }

    public function phpStart()
    {
        $php = '<?php ' . PHP_EOL;

        //Check if the form is submitted or not.
        $php .=
    '
    if (empty($_POST)) {
        die("Permission denied.");
    }
    include_once("library/library.php");
    ini_set("display_errors", "0");
    ' . PHP_EOL . PHP_EOL;

        return $php;
    }

    //TODO:Add validation as per form data.
    public function renderFormPhp()
    {
    }

    public function reCaptcha($secret)
    {
        $php =
            '
            $is_error = verifyReCaptcha(\'' . $secret . '\', $_POST["g-recaptcha-response"]);
            if(!empty($is_error)){
                echo json_encode($is_error);
                exit;
            }' . PHP_EOL . PHP_EOL;
            
        return $php;
    }

    public function attachmentFields($elements)
    {
        $fields = [];
        if (!empty($elements)) {
            foreach ($elements as $element) {
                if ($element['type'] == 'file_upload' && $element['send_as_email_attachment'] == 1) {
                    $fields[] = $element['name'];
                }
            }
        }

        $php = '//check for send as attachment'. PHP_EOL;

        $php .= '
        $attachment_fields = json_decode(\'' . json_encode($fields) . '\', true);'. PHP_EOL;

        $php .= '$attachments = getAttachments($attachment_fields);'. PHP_EOL;

        return $php;
    }

    public function email($email_details, $smtp = [], $response_variable, $is_autoresponse = false, $signature_fields = [])
    {
        $php = '//Sending email Start';

        //Initialise variables. TODO: Make variable names as dynamic.
        $php .= '
        $smtp = json_decode(\'' . json_encode($smtp) . '\', true);

        $email_details = json_decode(\'' . json_encode($email_details) . '\', true);'. PHP_EOL . PHP_EOL;

        //If auto-response then Get email "TO" value from users input
        if ($is_autoresponse) {
            $php .= '
            $email_details["to"] = !empty($_POST["fields"][$email_details["to"]]) ? $_POST["fields"][$email_details["to"]] : $email_details["from"];'. PHP_EOL . PHP_EOL;
        }

        //Replace tags from subject, body
        $php .=
        '$temp = replaceTags($_POST["fields"], array("subject" => $email_details["subject"], "body" => $email_details["body"]), json_decode(\'' . json_encode($signature_fields) . '\', true));

        $email_details["subject"] = $temp["subject"];
        $email_details["body"] = $temp["body"];
        ' . PHP_EOL . PHP_EOL;

        //Send email.
        $php .= $response_variable . ' = sendEmail($email_details, $smtp, $attachments);' . PHP_EOL . PHP_EOL;

        $php .= '//Sending email Start End' . PHP_EOL . PHP_EOL;

        return $php;
    }

    public function phpEnd($notification, $email_variable)
    {
        $php =
    'if( ' . $email_variable . ' ){' . PHP_EOL;

        //For display message
        if (empty($notification['post_submit_action']) || $notification['post_submit_action'] == 'same_page') {
            $php .=
        '$output = array("success" => 1, 
                    "redirect" => 0, 
                    "msg" => "' . $notification['success_msg'] . '"
                );' . PHP_EOL;
        }

        //For redirect
        if ($notification['post_submit_action'] == 'redirect') {
            $php .=
        '$output = array("success" => 1, 
                        "redirect" => 1, 
                        "url" => "' . $notification['redirect_url'] .
                    '");' . PHP_EOL;
        }

        $php .=
        'echo json_encode($output); exit;
    } else {' . PHP_EOL;

        $php .=
        '$output = array("success" => 0, 
                    "error" => "Email error",
                    "msg" => "' . $notification['failed_msg'] . '",
                );
            echo json_encode($output); exit;' . PHP_EOL;
        $php .= '}
?>';

        return $php;
    }

    public function databaseIntegration($form_data)
    {
        if (isset($form_data['enable_database']) &&  ($form_data['enable_database'] == '1')) {
            // $php =
            // 'if( $email_sent ){' . PHP_EOL;
            $php = 'try{
                    require_once ("config.php");'. PHP_EOL;
            $php .= '$db_data = array();' . PHP_EOL;
            if (!empty($form_data['db_data'])) {
                foreach ($form_data['db_data'] as $db_data) {
                    $column = $db_data['column'];
                    $col_val = $db_data['value'];
                    $php .= '$db_data["' . $column . '"] = "' . $col_val . '";' . PHP_EOL;
                }
            }
            $php .=
                '$insert_data = array();
                foreach( $db_data as $col => $col_val){
                    foreach($_POST["fields"] as $key => $value){
                        if(is_array($value)){
                            $value = implode(" ,", $value);
                        }
                        $col_val = str_replace( "__" . $key . "__", htmlspecialchars( $value ) , $col_val);
                    }
                    $insert_data[$col] = $col_val;
                }' . PHP_EOL;
            $php .= 'if(!empty($insert_data)){
                        $insert_id = $db->insert ("' . $form_data['db_table'] . '" , $insert_data);
                        if ($insert_id){
                            $db_msg = "Data inserted successfully";
                            $db_success = 1;
                        } else {
                            $db_msg = "Insert failed: " . $db->getLastError();
                            $db_success = 0;
                        }
                    }' . PHP_EOL;
            $php .= '}catch(Exception $e){
                $db_msg = "Database insert failed" . $e->getMessage();
                $db_success = 0;
            }' . PHP_EOL;
                    
            return $php;
        } else {
            return '';
        }
    }

    public function generateDatabaseConfig($form_data)
    {
        if (isset($form_data['enable_database']) &&  ($form_data['enable_database'] == '1')) {
            $php =
            '<?php' . PHP_EOL;
            ;
            $php .= 'require_once ("MysqliDb.php");' . PHP_EOL;
            $php .= '$db = new MysqliDb ( "' . $form_data["db_host"] . '", "' . $form_data["db_username"] . '", "' . $form_data["db_password"] . '", "' . $form_data["db_name"] .'" );' . PHP_EOL;
            $php .= '?>';
            
            return $php;
        } else {
            return '';
        }
    }
}

<?php

require "PHPMailer/src/Exception.php";
require "PHPMailer/src/PHPMailer.php";
require "PHPMailer/src/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;

//Verification for google reCaptcha 2
//Returns void on success, array on error.
function verifyReCaptcha($secret, $g_recaptcha_response)
{
    if (isset($g_recaptcha_response) && !empty($g_recaptcha_response)) {

        //get verify response data
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $g_recaptcha_response);

        $responseData = json_decode($verifyResponse);
        
        if (!$responseData->success) {
            $output = ["success" => 0,
                                "error" => "reCaptcha error",
                                "msg" => "reCaptcha error"
                            ];
            return $output;
        }
    } else {
        $output = ["success" => 0,
                            "error" => "reCaptcha error",
                            "msg" => "reCaptcha error"
                        ];

        return $output;
    }
}

//Send email. Return True/False, as per the mail send.
function sendEmail($email_details, $smtp = [], $attachments = [])
{
    $mail = new PHPMailer;
    $mail->isHTML(true);

    //Add SMTP details.
    if (!empty($smtp)) {
        // $mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->SMTPAuth = true;

        $mail->Host = $smtp['host'];
        $mail->Username = $smtp['username'];
        $mail->Password = $smtp['password'];
        $mail->Port = $smtp['port'];
        
        if (!empty($smtp['encryption'])) {
            $mail->SMTPSecure = $smtp['encryption'];
        }
    }

    //From, If explicitly set in email details then use it else use smtp from.
    if (!empty($email_details['from'])) {
        $mail->setFrom($email_details['from']);
    } else {
        $mail->setFrom($smtp['from_address'], $smtp['from_name']);
    }

    //TO, comman seperated multiple email.
    $toArray = explode(',', $email_details['to']);
    foreach ($toArray as $to) {
        if (!empty($to)) {
            $mail->addAddress(trim($to));
        }
    }

    //CC, comman seperated multiple email.
    if (!empty($email_details['cc'])) {
        $ccArray = explode(',', $email_details['cc']);
        foreach ($ccArray as $cc) {
            if (!empty($cc)) {
                $mail->addCC(trim($cc));
            }
        }
    }

    //BCC, comman seperated multiple email.
    if (!empty($email_details['bcc'])) {
        $bccArray = explode(',', $email_details['bcc']);
        foreach ($bccArray as $bcc) {
            if (!empty($bcc)) {
                $mail->addBCC(trim($bcc));
            }
        }
    }

    //Reply to.
    $reply_to = !empty($email_details['reply_to']) ? $email_details['reply_to'] : '';
    if (!empty($reply_to)) {
        if (!empty($reply_to)) {
            $mail->addReplyTo(trim($reply_to));
        }
    }

    //Attachements
    if (!empty($attachments)) {
        foreach ($attachments as $attachment) {
            $attachment_path = dirname(__DIR__) . "/library/uploads/" .  $attachment ;
            if (file_exists($attachment_path)) {
                $name = explode('_', $attachment, 2);
                $mail->addAttachment($attachment_path, $name[1]);
            }
        }
    };

    //Subject
    $mail->Subject = $email_details['subject'];

    //Body
    $mail->Body = nl2br($email_details['body']);

    //Send
    return $mail->send();
}

//This function replaces the tags present in string array as per form_data
function replaceTags($form_data, $strings, $signature_fields = [])
{
    foreach ($form_data as $name => $value) {
        //replace signature field with image tag
        if (!empty($signature_fields) && $signature_fields[$name] == $name) {
            $signature = '<img src="__'.$name.'__" style="width: 100px;height: 50px;">';
            $strings['body'] = preg_replace('/__'.$name.'__/', $signature, $strings['body']);
        }

        foreach ($strings as $key => $string) {

            //If value is array(like for multiselect or checkbox) then implode it.
            $value = is_array($value) ? implode(',', $value) : $value;

            $strings[$key] = str_replace('__' . $name . '__', $value, $string);
        }
    }

    // $body = str_replace( "__SENDER_IP__", $_SERVER["REMOTE_ADDR"], $body);
    // $body = str_replace( "__DATE_TIME__", date("Y-m-d H:i:s"), $body)

    return $strings;
}

function getAttachments($attachment_fields)
{
    $attachments = [];
    foreach ($attachment_fields as $attachment_field) {
        foreach ($_POST['fields'] as $key => $values) {
            if ($attachment_field == $key) {
                foreach ($values as $key => $value) {
                    $uploaded_file = explode(",", $value);
                }
                $attachments = array_merge($attachments, $uploaded_file);
            }
        }
    }

    return $attachments;
}

<?php
require_once './vendor/autoload.php';
require_once './Tools/IniTool.php';
include_once './Tools/TemplateTool.php';
class EmailTool
{
    public static function sendEmail($data, $template = '')
    {
        $toEmail = $data['toEmail'];
        $subject = $data['subject'];
        $fromEmail = $data['fromEmail'];
        $fromPassword = $data['fromPassword'];
        $message = TemplateTool::ApplyTemplate($data, $template);

        $phpmailer = new PHPMailer\PHPMailer\PHPMailer();
        $phpmailer->isSMTP();
        $phpmailer->SMTPSecure = 'ssl';
        $phpmailer->Host = 'smtp.gmail.com'; // Servidor SMTP de Gmail
        $phpmailer->Port = 465;
        $phpmailer->SMTPAuth = true;
        $phpmailer->Username = $fromEmail;
        $phpmailer->Password = $fromPassword;

        $phpmailer->setFrom($fromEmail, 'Destiny Airlines');
        $phpmailer->addAddress($toEmail);
        $phpmailer->Subject = $subject;
        $phpmailer->isHTML(true);
        $phpmailer->CharSet = 'UTF-8';
        $phpmailer->Body = $message;

        if ($phpmailer->send()) {
            return true;
        } else {
            return false;
        }
    }
}

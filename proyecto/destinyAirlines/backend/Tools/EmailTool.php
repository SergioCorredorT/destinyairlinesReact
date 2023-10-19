<?php
    use PHPMailer\PHPMailer\PHPMailer;
    require_once './vendor/autoload.php';
    require_once './Tools/IniTool.php';
class EmailTool
{
    public static function sendEmail($Data)
    {
        $to = $Data["to"];
        $subject = $Data["subject"];
        $message = $Data["message"];
        $fromEmail =$Data["fromEmail"];
        $fromPassword =$Data["fromPassword"];

        $phpmailer = new PHPMailer();
        $phpmailer->isSMTP();
        $phpmailer->SMTPSecure = 'ssl';
        $phpmailer->Host = 'smtp.gmail.com'; // Servidor SMTP de Gmail
        $phpmailer->Port = 465;
        $phpmailer->SMTPAuth = true;
        $phpmailer->Username = $fromEmail;
        $phpmailer->Password = $fromPassword;

        $phpmailer->setFrom($fromEmail, 'Destiny Airlines');
        $phpmailer->addAddress($to);
        $phpmailer->Subject = $subject;
        $phpmailer->isHTML(true);
        $phpmailer->Body = $message;

        if (!$phpmailer->send()) {
            // No se ha enviado
            return false;
        } else {
            // Se ha enviado
            return true;
        }
    }
}

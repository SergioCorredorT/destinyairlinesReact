<?php
class EmailTool
{
    public static function sendEmail($Data)
    {
        //AQUI USAR PHP MAILER PARA EVITAR TENER QUE EDITAR PHP.INI
        $to = $Data["to"];
        $subject = $Data["subject"];
        $message = $Data["message"];
        $headers = "From: " . $Data["email"];

        /*Enviamos el mail*/
        include('template.php');
        include "class.phpmailer.php";
        include "class.smtp.php";
        $email_user = 'TU CORREO';
        $email_password = 'TU CONTRASEÑA';
        $the_subject = $Data["subject"];//Asunto
        $address_to = $Data["to"];//Correo destino
        $from_name = 'Cursos de programación';
        $phpmailer = new PHPMailer();
        $phpmailer->Username = $email_user;
        $phpmailer->Password = $email_password; 
        $phpmailer->SMTPSecure = 'ssl'; 
        $phpmailer->Host = 'SERVIDOR'; 
        $phpmailer->Port = 465; 
        $phpmailer->isSMTP(); 
        $phpmailer->SMTPAuth = true;
        $phpmailer->setFrom($phpmailer->Username,$from_name);
        $phpmailer->AddAddress($address_to); 
        $phpmailer->FromName = 'Programación Online';	
        $phpmailer->Subject = $the_subject;	
        $phpmailer->Body .= $mensaje_correo;
        $phpmailer->IsHTML(true);

        if (!$phpmailer->Send()) {
        //No se ha enviado
        } else {
        //se ha enviado
        }

        return true;
    }
}

<?php
class TemplateTool
{
    static function ApplyTemplate(array $data, string $template)
    {
        switch (strtolower($template)) {
            case 'contacttemplate': {
                    require_once './Templates/ContactTemplate.php';
                    $emailBody = ContactTemplate::applyContactTemplate($data);
                    //Aquí llamar a la template y rellenarla de algún modo para guardarla en el string y devolverla
                    return $emailBody;
                }
            case 'failedattemptstemplate': {
                    require_once './Templates/FailedAttemptsTemplate.php';
                    $emailBody = FailedAttemptsTemplate::applyFailedAttemptsTemplate($data);
                    //Aquí llamar a la template y rellenarla de algún modo para guardarla en el string y devolverla
                    return $emailBody;
                }
            case 'forgotpasswordtemplate': {
                    require_once './Templates/ForgotPasswordTemplate.php';
                    $emailBody = ForgotPasswordTemplate::applyForgotPasswordTemplate($data);
                    //Aquí llamar a la template y rellenarla de algún modo para guardarla en el string y devolverla
                    return $emailBody;
                }
            default: {
                    //CUANDO ES '' P.E.
                    return $data['message'];
                }
        }
    }
}

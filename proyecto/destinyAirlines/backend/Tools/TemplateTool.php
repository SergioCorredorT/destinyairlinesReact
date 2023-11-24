<?php

class TemplateTool
{
    static function ApplyTemplate(array $data, string $template)
    {
        switch (strtolower($template)) {
            case 'contacttemplate': {
                    require_once './Templates/ContactTemplate.php';
                    $emailBody = ContactTemplate::applyContactTemplate($data);
                    return $emailBody;
                }
            case 'failedattemptstemplate': {
                    require_once './Templates/FailedAttemptsTemplate.php';
                    $emailBody = FailedAttemptsTemplate::applyFailedAttemptsTemplate($data);
                    return $emailBody;
                }
            case 'forgotpasswordtemplate': {
                    require_once './Templates/ForgotPasswordTemplate.php';
                    $emailBody = ForgotPasswordTemplate::applyForgotPasswordTemplate($data);
                    return $emailBody;
                }
            /*
            case 'invoicetemplate': {
                    require_once './Templates/invoiceTemplate.php';
                    $emailBody = ForgotPasswordTemplate::applyInvoiceTemplate($data);
                    return $emailBody;
                }
            */
            default: {
                    //CUANDO ES '' P.E.
                    return $data['message'];
                }
        }
    }
}

/*
class TemplateTool
{
    private static $templateClasses = [
        'contacttemplate' => 'ContactTemplate',
        'failedattemptstemplate' => 'FailedAttemptsTemplate',
        'forgotpasswordtemplate' => 'ForgotPasswordTemplate',
        'invoicetemplate' => 'InvoiceTemplate'
    ];
    

    static function ApplyTemplate(array $data, string $template)
    {
        $template = strtolower($template);
        //Si $template es el nombre de una de las class template que tenemos
        if (isset(self::$templateClasses[$template])) {
            $templateClass = self::$templateClasses[$template];
            //la requerimos y llamamos a su método aplicar
            require_once "./Templates/{$templateClass}.php";
            if (method_exists($templateClass, 'apply')) {
                return call_user_func([$templateClass, 'apply'], $data);
            }
        }
        return $data['message'];
    }
}
*/
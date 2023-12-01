<?php

class TemplateTool
{
    static function ApplyEmailTemplate(array $data, string $template)
    {
        switch (strtolower($template)) {
            case 'contacttemplate': {
                    require_once './Templates/email/ContactTemplate.php';
                    $emailBody = ContactTemplate::applyEmailContactTemplate($data);
                    return $emailBody;
                }
            case 'failedattemptstemplate': {
                    require_once './Templates/email/FailedAttemptsTemplate.php';
                    $emailBody = FailedAttemptsTemplate::applyEmailFailedAttemptsTemplate($data);
                    return $emailBody;
                }
            case 'forgotpasswordtemplate': {
                    require_once './Templates/email/ForgotPasswordTemplate.php';
                    $emailBody = ForgotPasswordTemplate::applyEmailForgotPasswordTemplate($data);
                    return $emailBody;
                }
            /*
            case 'invoicetemplate': {
                    require_once './Templates/email/invoiceTemplate.php';
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

    static function ApplyPageTemplate(array $data, string $template)
    {
        switch (strtolower($template)) {
            case 'invoicetemplate': {
                    require_once './Templates/page/InvoiceTemplate.php';
                    $page = InvoicePageTemplate::applyInvoicePageTemplate($data);
                    return $page;
                }
            default: {
                    //CUANDO ES '' P.E.
                    return $data['message'];
                }
        }
    }
}


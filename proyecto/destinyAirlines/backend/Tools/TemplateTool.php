<?php

class TemplateTool
{
    static function ApplyEmailTemplate(array $data, string $template): string
    {
        switch (strtolower($template)) {
            case 'contacttemplate': {
                    require_once ROOT_PATH . '/Templates/email/ContactTemplate.php';
                    $emailBody = ContactTemplate::applyEmailContactTemplate($data);
                    return $emailBody;
                }
            case 'failedattemptstemplate': {
                    require_once ROOT_PATH . '/Templates/email/FailedAttemptsTemplate.php';
                    $emailBody = FailedAttemptsTemplate::applyEmailFailedAttemptsTemplate($data);
                    return $emailBody;
                }
            case 'forgotpasswordtemplate': {
                    require_once ROOT_PATH . '/Templates/email/ForgotPasswordTemplate.php';
                    $emailBody = ForgotPasswordTemplate::applyEmailForgotPasswordTemplate($data);
                    return $emailBody;
                }
            case 'invoicetemplate': {
                    require_once ROOT_PATH . '/Templates/email/InvoiceTemplate.php';
                    $emailBody = InvoiceTemplate::applyInvoiceTemplate($data);
                    return $emailBody;
                }
            case 'emailverificationtemplate': {
                    require_once ROOT_PATH . '/Templates/email/EmailVerificationTemplate.php';
                    $emailBody = EmailVerificationTemplate::applyEmailVerificationTemplate($data);
                    return $emailBody;
                }
            case 'accountdeletiontemplate': {
                    require_once ROOT_PATH . '/Templates/email/AccountDeletionTemplate.php';
                    $emailBody = AccountDeletionTemplate::applyAccountDeletionTemplate($data);
                    return $emailBody;
                }
            case 'changepasswordtemplate': {
                    require_once ROOT_PATH . '/Templates/email/ChangePasswordTemplate.php';
                    $emailBody = ChangePasswordTemplate::applyChangePasswordTemplate($data);
                    return $emailBody;
                }
            case 'welcometemplate': {
                    require_once ROOT_PATH . '/Templates/email/WelcomeTemplate.php';
                    $emailBody = WelcomeTemplate::applyWelcomeTemplate($data);
                    return $emailBody;
                }
            default: {
                    //CUANDO ES '' P.E.
                    return $data['message'];
                }
        }
    }

    static function ApplyPageTemplate(array $data, string $template): string
    {
        switch (strtolower($template)) {
            case 'invoicetemplate': {
                    require_once ROOT_PATH . '/Templates/page/InvoiceTemplate.php';
                    $page = InvoicePageTemplate::applyInvoicePageTemplate($data);
                    return $page;
                }
            case 'boardingpasstemplate': {
                    require_once ROOT_PATH . '/Templates/page/BoardingPassTemplate.php';
                    $page = BoardingPassPageTemplate::applyBoardingPassPageTemplate($data);
                    return $page;
                }
            default: {
                    //CUANDO ES '' P.E.
                    return $data['message'];
                }
        }
    }
}

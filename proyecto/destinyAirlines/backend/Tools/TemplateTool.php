<?php
class TemplateTool
{
    static function ApplyTemplate($data, $template)
    {
        switch (strtolower($template)) {
            case "contacttemplate": {
                    require_once "./Templates/ContactTemplate.php";
                    $emailBody = ContactTemplate::applyContactTemplate($data);
                    //Aquí llamar a la template y rellenarla de algún modo para guardarla en el string y devolverla
                    return $emailBody;
                }
                case "failedattemptstemplate": {
                    require_once "./Templates/FailedAttemptsTemplate.php";
                    $emailBody = FailedAttemptsTemplate::applyFailedAttemptsTemplate($data);
                    //Aquí llamar a la template y rellenarla de algún modo para guardarla en el string y devolverla
                    return $emailBody;
                }
            default: {
                    //CUANDO ES "" P.E.
                    return $data["message"];
                }
        }
    }
}

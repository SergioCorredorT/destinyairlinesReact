<?php
require_once ROOT_PATH . '/Controllers/BaseController.php';
final class PageDetailsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getCompanyInfo(): array
    {
        require_once ROOT_PATH . '/Tools/IniTool.php';
        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
        $companyInfo = $iniTool->getKeysAndValues('companyInfo');
        return ['response' => $companyInfo];
    }
}

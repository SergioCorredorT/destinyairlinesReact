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
        $companyInfo = $this->iniTool->getKeysAndValues('companyInfo');
        return ['response' => $companyInfo];
    }
}

<?php
class Aligent_EmailTemplateFallback_Block_Adminhtml_System_Email_Template_Edit extends Mage_Adminhtml_Block_System_Email_Template_Edit {
    /*
     * Set fall back template to include store theme field
     * Ideally this should be done in layout xml but Magento Admin block generation
     * makes it's more complicated than it should be
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('system/email/template/edit_fallback.phtml');
    }
}
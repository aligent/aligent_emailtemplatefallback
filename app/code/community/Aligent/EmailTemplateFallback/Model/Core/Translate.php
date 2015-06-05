<?php

/**
 * Class Aligent_EmailTemplateFallback_Model_Core_Translate
 * Rewrite Core class method to support email template fallback
 */
class Aligent_EmailTemplateFallback_Model_Core_Translate extends Mage_Core_Model_Translate {
    /**
     * Retrieve translated template file
     * Try to find template file in store theme folder
     * @param string $file
     * @param string $type
     * @param string $localeCode
     * @return string
     */
    public function getTemplateFile($file, $type, $localeCode=null)
    {
        if (is_null($localeCode) || preg_match('/[^a-zA-Z_]/', $localeCode)) {
            $localeCode = $this->getLocale();
        }
        // Try to find template file in store theme folder /locale/template/[type]/[file]
        $filePath = null;
        $vStoreId = Mage::registry(Aligent_EmailTemplateFallback_Model_Core_Email_Template_Mailer::EMAIL_STORE_ID_REGISTRY_KEY);
        if (is_null($vStoreId)) {
            $vStoreId = Mage::app()->getRequest()->getParam('store_select');
        }
        if ($type=="email" && !is_null($vStoreId)) { // Only affect email for now, can be extended to include other file type.
            $oDesignPackage = Mage::getModel('core/design_package')->setArea('frontend')->setStore($vStoreId);
            $filePath = $oDesignPackage->getLocaleFileName('template' . DS . $type . DS . $file);
            // Unset the registry immediately
            Mage::unregister(Aligent_EmailTemplateFallback_Model_Core_Email_Template_Mailer::EMAIL_STORE_ID_REGISTRY_KEY);
        }
        // Fallback to normal Magento standard process
        if (is_null($filePath) || !file_exists($filePath)) {
            $filePath = Mage::getBaseDir('locale')  . DS
                . $localeCode . DS . 'template' . DS . $type . DS . $file;
        }

        if (!file_exists($filePath)) { // If no template specified for this locale, use store default
            $filePath = Mage::getBaseDir('locale') . DS
                . Mage::app()->getLocale()->getDefaultLocale()
                . DS . 'template' . DS . $type . DS . $file;
        }

        if (!file_exists($filePath)) {  // If no template specified as  store default locale, use en_US
            $filePath = Mage::getBaseDir('locale') . DS
                . Mage_Core_Model_Locale::DEFAULT_LOCALE
                . DS . 'template' . DS . $type . DS . $file;
        }

        $ioAdapter = new Varien_Io_File();
        $ioAdapter->open(array('path' => Mage::getBaseDir('locale')));

        return (string) $ioAdapter->read($filePath);
    }
}
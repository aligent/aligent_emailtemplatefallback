<?php
class Aligent_EmailTemplateFallback_Model_Core_Email_Template_Mailer extends Mage_Core_Model_Email_Template_Mailer {

    const EMAIL_STORE_ID_REGISTRY_KEY = 'email_store_id';

    /**
     *  This class is typically not rewritten by 3rd party email module (Aschroder and Mandrill) but registry stuffing
     *  seems to be the only way available to send storeID to Mage_Core_Model_Translate
     */
    public function send()
    {
        // Unset registry key in case of exception before getting to Aligent_EmailTemplateFallback_Model_Core_Translate
        // and the registry key was never unset.
        Mage::unregister(self::EMAIL_STORE_ID_REGISTRY_KEY);
        Mage::register(self::EMAIL_STORE_ID_REGISTRY_KEY, $this->getStoreId());
        parent::send();
    }
}
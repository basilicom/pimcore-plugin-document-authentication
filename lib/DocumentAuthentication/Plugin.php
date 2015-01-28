<?php

class DocumentAuthentication_Plugin  extends Pimcore_API_Plugin_Abstract implements Pimcore_API_Plugin_Interface {

    const DOC_PROPERTY_DOCUMENT_AUTHENTICATION_ENABLED
        = 'documentAuthenticationEnabled';

    const CONFIG_DOCUMENT_AUTHENTICATION_PASSWORD
        = 'documentAuthenticationPassword';

    const CONFIG_DOCUMENT_AUTHENTICATION_USERNAME
        = 'documentAuthenticationUser';

    const DB_TABLE_WEBSITE_SETTINGS
        = 'website_settings';

    const DB_TABLE_PRE_PROPERTIES
        = 'properties_predefined';

    public function init()
    {
        // register your events here

        Pimcore::getEventManager()->attach("system.startup", function ($event) {

            $front = Zend_Controller_Front::getInstance();

            $frontControllerPlugin = new DocumentAuthentication_FrontControllerPlugin();
            $front->registerPlugin($frontControllerPlugin);
        });

    }

    public function handleDocument ($event)
    {

        // do something
        //$document = $event->getTarget();
    }

    public static function install()
    {

        $db = Pimcore_Resource_Mysql::getConnection();

        if (!self::isInstalled()) {

            $db->insert(self::DB_TABLE_PRE_PROPERTIES, array(
                'name' => self::DOC_PROPERTY_DOCUMENT_AUTHENTICATION_ENABLED,
                'key' => self::DOC_PROPERTY_DOCUMENT_AUTHENTICATION_ENABLED,
                'type' => 'bool',
                'inheritable' => 1,
                'ctype' => 'document'
            ));

            $db->insert(self::DB_TABLE_WEBSITE_SETTINGS, array(
                'name' => self::CONFIG_DOCUMENT_AUTHENTICATION_USERNAME,
                'type' => 'text',
                'data' => 'preview'
            ));

            $db->insert(self::DB_TABLE_WEBSITE_SETTINGS, array(
                'name' => self::CONFIG_DOCUMENT_AUTHENTICATION_PASSWORD,
                'type' => 'text',
                'data' => md5(uniqid('', true))
            ));
        }

        return 'Successfully installed plugin DocumentAuthentication.';
    }

    public static function uninstall()
    {
        $db = Pimcore_Resource_Mysql::getConnection();

        $sql = "DELETE FROM " . self::DB_TABLE_PRE_PROPERTIES . " WHERE name = ?";
        $db->query($sql, array(self::DOC_PROPERTY_DOCUMENT_AUTHENTICATION_ENABLED));

        $sql = "DELETE FROM " . self::DB_TABLE_WEBSITE_SETTINGS . " WHERE name = ?";
        $db->query($sql, array(self::CONFIG_DOCUMENT_AUTHENTICATION_USERNAME));

        $sql = "DELETE FROM " . self::DB_TABLE_WEBSITE_SETTINGS . " WHERE name = ?";
        $db->query($sql, array(self::CONFIG_DOCUMENT_AUTHENTICATION_PASSWORD));

        return 'Successfully removed plugin DocumentAuthentication.';
    }

    public static function isInstalled()
    {
        $db = Pimcore_Resource_Mysql::getConnection();

        $sql = "SELECT COUNT(id) as num FROM " . self::DB_TABLE_PRE_PROPERTIES . " WHERE name = ?";
        $isInstalled = ((int)$db->fetchOne($sql, array(self::DOC_PROPERTY_DOCUMENT_AUTHENTICATION_ENABLED)) > 0);

        return $isInstalled;
    }

}
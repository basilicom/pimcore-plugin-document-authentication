<?php

class DocumentAuthentication_Plugin
    extends Pimcore_API_Plugin_Abstract
    implements Pimcore_API_Plugin_Interface
{

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
        Pimcore::getEventManager()->attach("system.startup", function ($event) {

            $front = Zend_Controller_Front::getInstance();

            $frontControllerPlugin = new DocumentAuthentication_FrontControllerPlugin();
            $front->registerPlugin($frontControllerPlugin);
        });
    }

    public static function install()
    {

        $database = Pimcore_Resource_Mysql::getConnection();

        if (!self::isInstalled()) {

            $database->insert(self::DB_TABLE_PRE_PROPERTIES, array(
                'name' => self::DOC_PROPERTY_DOCUMENT_AUTHENTICATION_ENABLED,
                'key' => self::DOC_PROPERTY_DOCUMENT_AUTHENTICATION_ENABLED,
                'type' => 'bool',
                'inheritable' => 1,
                'ctype' => 'document'
            ));

            $database->insert(self::DB_TABLE_WEBSITE_SETTINGS, array(
                'name' => self::CONFIG_DOCUMENT_AUTHENTICATION_USERNAME,
                'type' => 'text',
                'data' => 'preview'
            ));

            $database->insert(self::DB_TABLE_WEBSITE_SETTINGS, array(
                'name' => self::CONFIG_DOCUMENT_AUTHENTICATION_PASSWORD,
                'type' => 'text',
                'data' => md5(uniqid('', true))
            ));
        }

        return 'Successfully installed plugin DocumentAuthentication.';
    }

    public static function uninstall()
    {
        $database = Pimcore_Resource_Mysql::getConnection();

        $sqlQuery = "DELETE FROM " . self::DB_TABLE_PRE_PROPERTIES . " WHERE name = ?";
        $database->query($sqlQuery, array(self::DOC_PROPERTY_DOCUMENT_AUTHENTICATION_ENABLED));

        $sqlQuery = "DELETE FROM ".self::DB_TABLE_WEBSITE_SETTINGS." WHERE name = ?";
        $database->query($sqlQuery, array(self::CONFIG_DOCUMENT_AUTHENTICATION_USERNAME));
        $database->query($sqlQuery, array(self::CONFIG_DOCUMENT_AUTHENTICATION_PASSWORD));

        return 'Successfully removed plugin DocumentAuthentication.';
    }

    public static function isInstalled()
    {
        $database = Pimcore_Resource_Mysql::getConnection();

        $sqlQuery= "SELECT COUNT(id) as num FROM " . self::DB_TABLE_PRE_PROPERTIES . " WHERE name = ?";
        $isInstalled = (
            (int)$database->fetchOne(
                $sqlQuery,
                array(
                    self::DOC_PROPERTY_DOCUMENT_AUTHENTICATION_ENABLED
                )
            ) > 0);

        return $isInstalled;
    }

    public static function needsReloadAfterInstall()
    {
        return false; // backend only functionality!
    }

}

<?php

    namespace DocumentAuthentication;

    use Pimcore\API\Plugin as PluginApi;
    use Pimcore\Db;
    use Pimcore\Model\Property\Predefined as PropertyPredefined;

    class Plugin extends PluginApi\AbstractPlugin implements PluginApi\PluginInterface
    {

        const DOC_PROPERTY_DOCUMENT_AUTHENTICATION_ENABLED
            = 'documentAuthenticationEnabled';

        const CONFIG_DOCUMENT_AUTHENTICATION_PASSWORD
            = 'documentAuthenticationPassword';

        const CONFIG_DOCUMENT_AUTHENTICATION_USERNAME
            = 'documentAuthenticationUser';

        const DB_TABLE_WEBSITE_SETTINGS
            = 'website_settings';

        public function init()
        {
            \Pimcore::getEventManager()->attach("system.startup", function ($event) {

                $front = \Zend_Controller_Front::getInstance();

                $frontControllerPlugin = new FrontControllerPlugin();
                $front->registerPlugin($frontControllerPlugin);
            });
        }

        public static function install()
        {
            $database = Db::get();

            if (!self::isInstalled()) {

                $prop = new PropertyPredefined();
                $prop->setName(self::DOC_PROPERTY_DOCUMENT_AUTHENTICATION_ENABLED);
                $prop->setKey(self::DOC_PROPERTY_DOCUMENT_AUTHENTICATION_ENABLED);
                $prop->setType('bool');
                $prop->setInheritable(1);
                $prop->setCtype('document');
                $prop->save();

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
            $database = Db::get();

            $prop = PropertyPredefined::getByKey(self::DOC_PROPERTY_DOCUMENT_AUTHENTICATION_ENABLED);
            $prop->delete();

            $sqlQuery = "DELETE FROM " . self::DB_TABLE_WEBSITE_SETTINGS . " WHERE name = ?";
            $database->query($sqlQuery, array(self::CONFIG_DOCUMENT_AUTHENTICATION_USERNAME));
            $database->query($sqlQuery, array(self::CONFIG_DOCUMENT_AUTHENTICATION_PASSWORD));

            return 'Successfully removed plugin DocumentAuthentication.';
        }

        public static function isInstalled()
        {
            return (PropertyPredefined::getByKey(self::DOC_PROPERTY_DOCUMENT_AUTHENTICATION_ENABLED)
                != null);
        }

        public static function needsReloadAfterInstall()
        {
            return false; // backend only functionality!
        }

    }

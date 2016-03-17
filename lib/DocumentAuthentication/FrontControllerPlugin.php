<?php

    namespace DocumentAuthentication;

    use Pimcore\Model\Document\Page;
    use Pimcore\Model\User;
    use Pimcore\Tool\Authentication;
    use Pimcore\Tool\Frontend;

    class FrontControllerPlugin extends \Zend_Controller_Plugin_Abstract
    {

        public function preDispatch(\Zend_Controller_Request_Abstract $request)
        {
            parent::preDispatch($request);

            if ($request->getParam("document") instanceof Page) {

                $this->handleDocumentAuthentication($request->getParam("document"));
            }

        }

        /**
         * @param Page $document
         */
        private function handleDocumentAuthentication($document)
        {
            if (is_object($document)) {

                if (!$document->getProperty(Plugin::DOC_PROPERTY_DOCUMENT_AUTHENTICATION_ENABLED)) {
                    return; // all OK, show page
                }
            }

            $user = Authentication::authenticateSession();
            if ($user instanceof User) {
                return; // all OK, show page
            }

            if (self::isDocumentAuthenticationValid()) {
                return; // all OK, show page
            }

            $this->sendHttpBasicAuthResponse();
            exit;
        }

        /**
         * @return bool
         */
        private function isDocumentAuthenticationValid()
        {

            $config = Frontend::getWebsiteConfig();

            $username = $config->get(Plugin::CONFIG_DOCUMENT_AUTHENTICATION_USERNAME, 'preview');
            $password = $config->get(Plugin::CONFIG_DOCUMENT_AUTHENTICATION_PASSWORD, '');

            if (trim($password) == '') {
                // empty password - this is not good; Deny access!
                return false;
            }

            if (($_SERVER['PHP_AUTH_USER'] === $username) && ($_SERVER['PHP_AUTH_PW'] === $password)) {
                return true;
            }

            return false;
        }

        private function sendHttpBasicAuthResponse()
        {
            $config = Frontend::getWebsiteConfig();
            $password = $config->get(Plugin::CONFIG_DOCUMENT_AUTHENTICATION_PASSWORD, null);

            if (($password === null) || (trim($password) == '')) {

                $notice = 'Missing or empty Website Property '
                    . Plugin::CONFIG_DOCUMENT_AUTHENTICATION_PASSWORD;

            } else {

                $notice = 'Authentication required';
            }

            /** @var $response \Zend_Controller_Response_Http */
            $response = $this->getResponse();

            $response->setHeader('Cache-Control', 'max-age=0');
            $response->setHttpResponseCode(401);
            $response->setHeader(
                'WWW-Authenticate',
                'Basic realm="' . $notice . '"'
            );

            $response->setBody('Unauthorized.');
            $response->sendResponse();
        }
    }

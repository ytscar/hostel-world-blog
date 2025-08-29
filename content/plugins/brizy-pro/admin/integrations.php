<?php


class BrizyPro_Admin_Integrations
{

    const INTEGRATIONS = 'brizy-integrations';

    /**
     * @return BrizyPro_Admin_Integrations
     * @throws Exception
     */
    public static function _init()
    {

        static $instance;

        return $instance ? $instance : $instance = new self();
    }

    /**
     * Brizy_Admin_Integrations constructor.
     *
     * @throws Exception
     */
    protected function __construct()
    {

        if (Brizy_Editor_User::is_user_allowed()) {
            add_action('admin_menu', array($this, 'actionRegisterIntegrationsPage'), 11);
            add_action('current_screen', array($this, 'actionDeleteAccounts'));
        }
    }

    public function actionRegisterIntegrationsPage()
    {
        add_submenu_page(
            Brizy_Admin_Settings::menu_slug(),
            __('Integrations', 'brizy-pro'),
            __('Integrations', 'brizy-pro'),
            'manage_options',
            self::INTEGRATIONS,
            array(
                $this,
                'render',
            )
        );
    }

    public function render()
    {
        echo '<div class="wrap">'.$this->renderContent().'</div>';
    }

    public function renderContent()
    {
        $content = '';

        try {
            $accountManager = new Brizy_Editor_Accounts_ServiceAccountManager(Brizy_Editor_Project::get());

            $params = array(
                'title' => __('Integrations', 'brizy-pro'),
                'nonce' => wp_create_nonce('brizy-integrations'),
                'accounts' => $accountManager->getAccountsByGroup(Brizy_Editor_Accounts_AbstractAccount::INTEGRATIONS_GROUP),
                'pageLink' => menu_page_url(self::INTEGRATIONS, false),
            );

            $content = Brizy_Editor_View::get(BRIZY_PRO_PLUGIN_PATH.'/admin/views/integrations/view', $params);

        } catch (Exception $e) {
        }

        return $content;
    }

    public function actionDeleteAccounts()
    {
        if (isset($_REQUEST['delete-service-account']) &&
            count($_REQUEST['delete-service-account']) > 0 &&
            Brizy_Editor_User::is_user_allowed()) {
            if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])), 'brizy-integrations')) {
                wp_redirect(menu_page_url(self::INTEGRATIONS, false));
                exit;
            }
            // delete accounts
            $formManager = new Brizy_Editor_Forms_FormManager(Brizy_Editor_Project::get());
            foreach ($_REQUEST['delete-service-account'] as $serviceId => $accounts) {
                foreach ($accounts as $accountId) {
                    $forms = $formManager->getAllForms();
                    foreach ($forms as $form) {
                        $integrations = $form->getIntegrations();
                        foreach ($integrations as $integration) {
                            if ($integration instanceof Brizy_Editor_Forms_ServiceIntegration && $integration->getUsedAccount(
                                ) == $accountId) {
                                $integration->setUsedAccount(null);
                                $integration->setCompleted(false);
                                $integration->setUsedList(null);
                                $integration->setLists(array());
                                $integration->setFields(array());
                                $integration->setFieldsMap('[]');
                                $formManager->addForm($form);
                            }
                        }
                    }
                    $accountManager = new Brizy_Editor_Accounts_ServiceAccountManager(Brizy_Editor_Project::get());
                    $accountManager->deleteAccountById($accountId);
                }
            }
            wp_redirect(menu_page_url(self::INTEGRATIONS, false));
        }
    }

}

<?php

namespace KWSSO_CORE\Src\Utility\Tab;

use KWSSO_CORE\Src\Utility\Page\KWSSO_SubPage;
use KWSSO_CORE\Traits\Instance;

if (!defined('ABSPATH')) {
    exit;
}

final class KWSSO_PluginSubTabs {

    use Instance;

    public $sub_tab_details;
    public $parent_slug;

    private function __construct() {
        $this->parent_slug = 'kwsso-main-settings';

        $sub_tabs_config = [
            'kwsso-saml-settings' => [
                [
                    'title' => 'Attribute Mapping',
                    'slug' => 'attr-mapping',
                    'file' => 'kwsso-saml-settings.php',
                    'background' => 'background:#D8D8D8',
                ],
                [
                    'title' => 'Role Mapping',
                    'slug' => 'role-mapping',
                    'file' => 'kwsso-saml-settings.php',
                    'background' => 'background:#D8D8D8',
                ],
            ],
        ];

        $this->sub_tab_details = $this->generateSubTabs($sub_tabs_config);
    }

    /**
     * Generate subtabs dynamically based on configuration.
     *
     * @param array $config Array of subtabs configuration.
     * @return array Generated subtabs.
     */
    private function generateSubTabs(array $config): array {
        $sub_tabs = [];

        foreach ($config as $parent_slug => $tabs) {
            $sub_tabs[$parent_slug] = array_map(function ($tab) {
                return new KWSSO_SubPage(
                    $tab['title'],
                    $tab['title'],
                    $tab['title'],
                    $tab['file'],
                    $tab['slug'],
                    $tab['background']
                );
            }, $tabs);
        }

        return $sub_tabs;
    }
}

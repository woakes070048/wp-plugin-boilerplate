<?php

namespace WPPluginBoilerplate\Support\Settings;

use WPPluginBoilerplate\Support\Settings\Contracts\TabContract;
use WPPluginBoilerplate\Support\Settings\Tabs\AboutTab;
use WPPluginBoilerplate\Support\Settings\Tabs\EnhancedFields;
use WPPluginBoilerplate\Support\Settings\Tabs\CoreFields;
use WPPluginBoilerplate\Support\Settings\Tabs\HelpTab;
use WPPluginBoilerplate\Support\Settings\Tabs\ToolsTab;

class Tabs
{
    /**
     * Return ALL tabs, unfiltered.
     * No capability checks here. Ever.
     */
    public static function all(): array
    {
        $tabs = [
            new CoreFields(),
            new EnhancedFields(),
            new ToolsTab(),
            new AboutTab(),
            new HelpTab(),
        ];

        return array_values(
            array_filter($tabs, function ($tab) {

                // Settings tabs only
                if (method_exists($tab, 'scope')) {

                    // Network-only tab outside Network Admin
                    if ($tab::scope() === 'network' && ! is_network_admin()) {
                        return false;
                    }
                }

                // Capability check
                if (method_exists($tab, 'viewCapability')) {
                    return current_user_can($tab->viewCapability());
                }

                return true;
            })
        );
    }

    /**
     * Tabs visible to the current user.
     */
    public static function visible(): array
    {
        return array_values(array_filter(
            self::all(),
            fn($tab) => current_user_can($tab->viewCapability())
                && (
                    !method_exists($tab, 'scope')
                    || $tab::scope() !== 'network'
                    || is_network_admin()
                )
        ));
    }

    /**
     * Currently active tab.
     */
    public static function active(): TabContract
    {
        $tabs = self::visible();

        if (empty($tabs)) {
            wp_die('No accessible tabs.');
        }

        $activeId = $_GET['tab'] ?? $tabs[0]->id();

        foreach ($tabs as $tab) {
            if ($tab->id() === $activeId) {
                return $tab;
            }
        }

        return $tabs[0];
    }
}

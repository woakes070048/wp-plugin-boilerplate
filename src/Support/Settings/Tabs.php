<?php

namespace WPPluginBoilerplate\Support\Settings;

use WPPluginBoilerplate\Support\Settings\Contracts\TabContract;
use WPPluginBoilerplate\Support\Settings\Tabs\AboutTab;
use WPPluginBoilerplate\Support\Settings\Tabs\AdvancedTab;
use WPPluginBoilerplate\Support\Settings\Tabs\GeneralTab;
use WPPluginBoilerplate\Support\Settings\Tabs\ToolsTab;

class Tabs
{
    /**
     * Return ALL tabs, unfiltered.
     * No capability checks here. Ever.
     */
    public static function all(): array
    {
        return [
            new GeneralTab(),
            new AdvancedTab(),
            new ToolsTab(),
            new AboutTab(),
        ];
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

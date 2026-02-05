<?php

namespace WPPluginBoilerplate\Settings;

use WPPluginBoilerplate\Settings\Contracts\TabContract;
use WPPluginBoilerplate\Settings\Tabs\AboutTab;
use WPPluginBoilerplate\Settings\Tabs\CoreFieldsTab;
use WPPluginBoilerplate\Settings\Tabs\EnhancedFieldsTab;
use WPPluginBoilerplate\Settings\Tabs\HelpTab;
use WPPluginBoilerplate\Settings\Tabs\ToolsTab;

class Tabs
{

	/**
	 * Currently active tab.
	 */
	public static function active(): TabContract
	{
		$tabs = self::visible();

		if (empty($tabs)) {
			wp_die('No accessible tabs.');
		}

		$active_id = $_GET['tab'] ?? $tabs[0]->id();

		foreach ($tabs as $tab) {
			if ($tab->id() === $active_id) {
				return $tab;
			}
		}

		return $tabs[0];
	}

	/**
	 * Tabs visible to the current user.
	 */
	public static function visible(): array
	{
		return array_values(
			array_filter(
				self::all(),
				fn($tab) => current_user_can($tab->viewCapability())
					&& (
						!method_exists($tab, 'scope')
						|| $tab::scope() !== 'network'
						|| is_network_admin()
					)
			)
		);
	}

	/**
	 * Return ALL tabs, unfiltered.
	 * No capability checks here. Ever.
	 */
	public static function all(): array
	{
		$tabs = array(
			new CoreFieldsTab(),
			new EnhancedFieldsTab(),
			new ToolsTab(),
			new AboutTab(),
			new HelpTab(),
		);

		return array_values(
			array_filter(
				$tabs,
				function ($tab) {

					// Settings tabs only.
					if (method_exists($tab, 'scope')) {

						// Network-only tab outside Network Admin.
						if ($tab::scope() === 'network' && !is_network_admin()) {
							return false;
						}
					}

					// Capability check.
					if (method_exists($tab, 'viewCapability')) {
						return current_user_can($tab->viewCapability());
					}

					return true;
				}
			)
		);
	}

	public static function find(string $id): ?TabContract
	{
		foreach (self::all() as $tab) {
			if ($tab->id() === $id) {
				return $tab;
			}
		}

		return null;
	}

}

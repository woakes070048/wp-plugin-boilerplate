<?php

namespace WPPluginBoilerplate\Admin\Actions;

use WPPluginBoilerplate\Plugin;
use WPPluginBoilerplate\Settings\SettingsRepository;
use WPPluginBoilerplate\Settings\Tabs;
use WPPluginBoilerplate\Settings\Contracts\SettingsContract;

class ResetSettings
{
	public function handle(): void
	{
		// Resolve active tab from request context
		$tab = Tabs::active();

		// Tab must provide settings
		if (! $tab instanceof SettingsContract) {
			wp_die(__('This tab does not support settings.', Plugin::text_domain()));
		}

		// Capability check MUST match tab capability
		if (! current_user_can($tab->manageCapability())) {
			wp_die(__('Sorry, you are not allowed to access this page.', Plugin::text_domain()));
		}

		// Nonce check (must match the one used in the button)
		check_admin_referer( Plugin::prefix() . 'reset');

		// Build defaults from fields (single source of truth)
		$defaults = [];

		foreach ($tab::fields() as $key => $definition) {
			$defaults[$key] = $definition['default'] ?? null;
		}

		// Persist defaults
		SettingsRepository::update(
			$tab::optionKey(),
			$defaults
		);

		// Redirect back to the tab
		wp_safe_redirect(admin_url('admin.php?page='. Plugin::slug() .'&tab=' . $tab->id()));

		exit;
	}
}

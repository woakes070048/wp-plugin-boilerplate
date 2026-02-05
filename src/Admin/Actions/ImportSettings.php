<?php

namespace WPPluginBoilerplate\Admin\Actions;

use WPPluginBoilerplate\Plugin;
use WPPluginBoilerplate\Settings\SettingsRepository;
use WPPluginBoilerplate\Settings\Support\ScopeResolver;
use WPPluginBoilerplate\Settings\Tabs;
use WPPluginBoilerplate\Settings\Contracts\SettingsContract;

class ImportSettings
{
	public function handle(): void
	{
		if (! current_user_can('manage_options')) {
			wp_die(__('Sorry, you are not allowed to access this page.'));
		}

		check_admin_referer(Plugin::prefix() .'import_all');

		$file = $_FILES['import_file'] ?? null;

		if (! $file || $file['error'] !== UPLOAD_ERR_OK) {
			wp_die(__('Invalid upload.', Plugin::text_domain()));
		}

		$payload = json_decode(
			file_get_contents($file['tmp_name']),
			true
		);

		if (($payload['plugin'] ?? null) !== Plugin::slug()) {
			wp_die(__('This settings file does not belong to this plugin.', Plugin::text_domain()));
		}

		if (! is_array($payload) || ! isset($payload['tabs']) || ! is_array($payload['tabs'])) {
			wp_die(__('Invalid settings file.', Plugin::text_domain()));
		}

		foreach ($payload['tabs'] as $tab_id => $tab_data) {

			$tab = Tabs::find($tab_id);

			if (! $tab) {
				// Tab not present in this install → skip safely
				continue;
			}

			if (! $tab instanceof SettingsContract) {
				continue;
			}

			if (! current_user_can($tab->manageCapability())) {
				continue;
			}

			if (! isset($tab_data['data']) || ! is_array($tab_data['data'])) {
				continue;
			}

			$scope = ScopeResolver::resolve($tab);

			SettingsRepository::update($tab::optionKey(), $tab_data['data'], $scope);
		}

		wp_safe_redirect(add_query_arg(Plugin::prefix() .'notice', 'imported', admin_url('admin.php?page='. Plugin::slug() .'&tab=tools')));

		exit;
	}
}

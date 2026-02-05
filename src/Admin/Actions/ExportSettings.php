<?php

namespace WPPluginBoilerplate\Admin\Actions;

use WPPluginBoilerplate\Plugin;
use WPPluginBoilerplate\Settings\Contracts\SettingsContract;
use WPPluginBoilerplate\Settings\SettingsRepository;
use WPPluginBoilerplate\Settings\Support\ScopeResolver;
use WPPluginBoilerplate\Settings\Tabs;

class ExportSettings
{
	public function handle(): void
	{
		if (! current_user_can('manage_options')) {
			wp_die(__('Sorry, you are not allowed to access this page.'));
		}

		check_admin_referer('wppb_export_all');

		$export = [
			'exported_at' => gmdate('c'),
			'plugin'      => Plugin::slug(),
			'version'     => Plugin::version(),
			'tabs'        => [],
		];

		foreach (Tabs::all() as $tab) {

			if (! $tab instanceof SettingsContract) {
				continue;
			}

			if (! current_user_can($tab->manageCapability())) {
				continue;
			}

			$scope = ScopeResolver::resolve($tab);

			$export['tabs'][$tab->id()] = [
				'label'      => $tab->label(),
				'option_key' => $tab::optionKey(),
				'scope'      => $scope,
				'data'       => SettingsRepository::get(
					$tab::optionKey(),
					$scope
				),
			];
		}

		header('Content-Type: application/json');
		header(
			'Content-Disposition: attachment; filename="' .
			Plugin::slug() . '-settings-' . gmdate('Ymd-His') . '.json"'
		);

		echo wp_json_encode($export, JSON_PRETTY_PRINT);

		exit;
	}
}

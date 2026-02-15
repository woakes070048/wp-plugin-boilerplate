<?php

namespace WPPluginBoilerplate\Settings\Registry;

use WPPluginBoilerplate\Settings\Contracts\SettingsContract;
use WPPluginBoilerplate\Settings\Fields\FieldDefinition;
use WPPluginBoilerplate\Settings\Fields\FieldRenderer;
use WPPluginBoilerplate\Settings\SettingsRepository;
use WPPluginBoilerplate\Settings\Tabs;

class SettingsRegistrar
{
	public function register(): void
	{
		foreach (Tabs::all() as $tab) {
			if (!$tab instanceof SettingsContract) {
				continue;
			}

			$this->register_tab($tab);
		}
	}

	protected function register_tab(SettingsContract $tab): void
	{
		$option_key = $tab::optionKey();

		register_setting($option_key, $option_key);

		add_settings_section('default', '', '__return_null', $option_key);

		foreach ($tab::fields() as $field_key => $definition) {

			$field = FieldDefinition::fromSchema($field_key, $definition);

			add_settings_field($field->key, esc_html($field->label), function () use ($tab, $field) {

					$values = SettingsRepository::get($tab::optionKey());
					$raw = $values[$field->key] ?? null;

					$value = ($raw === null || $raw === 'null') ? $field->resolvedDefault() : $raw;
					echo '<div class="wppb-fields-row">';
					FieldRenderer::render($tab::optionKey(), $field, $value);
					echo '</div>';
				},
				$option_key,
				'default'
			);
		}
	}
}

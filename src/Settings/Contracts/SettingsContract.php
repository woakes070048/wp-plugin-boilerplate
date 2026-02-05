<?php

namespace WPPluginBoilerplate\Settings\Contracts;

interface SettingsContract
{
	/**
	 * Fully-qualified option key.
	 */
	public static function optionKey(): string;

	/**
	 * Field definitions owned by this tab.
	 *
	 * @return array<string, array>
	 */
	public static function fields(): array;
}


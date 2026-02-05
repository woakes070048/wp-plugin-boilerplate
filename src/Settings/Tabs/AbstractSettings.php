<?php

namespace WPPluginBoilerplate\Settings\Tabs;

use WPPluginBoilerplate\Settings\Contracts\SettingsContract;

abstract class AbstractSettings implements SettingsContract
{
	public static function definition(): array
	{
		return static::schema()::definition();
	}

	/**
	 * Backing schema class
	 */
	abstract public static function schema(): string;


	public static function defaults(): array
	{
		return static::schema()::defaults();
	}


	public function id(): string
	{
		return $this->config()['id'];
	}

	/**
	 * Tab identity & policy
	 */
	abstract protected function config(): array;


	public function label(): string
	{
		return $this->config()['label'];
	}


	public function hasForm(): bool
	{
		return true;
	}


	public function hasActions(): bool
	{
		return true;
	}

	/*
	-----------------------------------------------------------------
	 * Schema delegation
	 * ----------------------------------------------------------------- */


	public function manageCapability(): string
	{
		return $this->config()['manage_capability'] ?? 'manage_options';
	}


	public function render(): void
	{
		settings_fields(static::optionKey());
		do_settings_sections(static::optionKey());
	}


	public static function optionKey(): string
	{
		return static::schema()::optionKey();
	}


	public function isVisible(): bool
	{
		// Network-only tab, but not in Network Admin.
		if (static::scope() === 'network' && !is_network_admin()) {
			return false;
		}

		return current_user_can($this->viewCapability());
	}


	public static function scope(): string
	{
		return static::schema()::scope();
	}


	public function viewCapability(): string
	{
		return $this->config()['view_capability'] ?? 'manage_options';
	}
}

<?php

namespace WPPluginBoilerplate\Lifecycle;

use WPPluginBoilerplate\Plugin;

class Activator
{
	public static function activate(): void
	{
		self::init_options();
	}

	private static function init_options(): void
	{
		if (get_option(Plugin::option_key()) !== false) {
			return;
		}

		add_option(
			Plugin::option_key(),
			[
				'version' => Plugin::version(),
				'tabs_as_submenus' => false,
			]
		);
	}
}

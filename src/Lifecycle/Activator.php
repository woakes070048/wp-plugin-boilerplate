<?php

namespace WPPluginBoilerplate\Lifecycle;

use WPPluginBoilerplate\Plugin;

class Activator {

	public static function activate(): void {

		// Core plugin meta
		add_option( Plugin::prefix() . 'version', Plugin::version() );
		add_option( Plugin::prefix() . 'status', true );
	}
}

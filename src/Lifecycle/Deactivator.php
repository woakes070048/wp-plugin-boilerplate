<?php

namespace WPPluginBoilerplate\Lifecycle;

use WPPluginBoilerplate\Plugin;

class Deactivator
{
	public static function deactivate(): void
	{
		wp_clear_scheduled_hook(
			Plugin::cron_hook('cron')
		);

		flush_rewrite_rules();
	}
}


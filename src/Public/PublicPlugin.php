<?php

namespace WPPluginBoilerplate\Public;

use WPPluginBoilerplate\Loader;
use WPPluginBoilerplate\Plugin;

class PublicPlugin
{
	public function register(Loader $loader): void
	{
		// Public hooks go here.

		$loader->action('wp_enqueue_scripts', $this, 'enqueue_assets');
	}

	public function enqueue_assets(): void
	{
		wp_enqueue_script(Plugin::prefix() . 'public', Plugin::url() . 'assets/public/public.js', [], Plugin::version(), true);
	}
}

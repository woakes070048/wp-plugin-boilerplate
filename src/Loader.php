<?php

namespace WPPluginBoilerplate;

class Loader
{
	protected array $actions = array();
	protected array $filters = array();

	public function action(
		string $hook,
		object $component,
		string $callback,
		int    $priority = 10,
		int    $accepted_args = 1
	): void
	{
		$this->actions[] = compact(
			'hook',
			'component',
			'callback',
			'priority',
			'accepted_args'
		);
	}

	public function filter(
		string $hook,
		object $component,
		string $callback,
		int    $priority = 10,
		int    $accepted_args = 1
	): void
	{
		$this->filters[] = compact(
			'hook',
			'component',
			'callback',
			'priority',
			'accepted_args'
		);
	}

	public function run(): void
	{
		foreach ($this->actions as $action) {
			add_action(
				$action['hook'],
				array($action['component'], $action['callback']),
				$action['priority'],
				$action['accepted_args']
			);
		}

		foreach ($this->filters as $filter) {
			add_filter(
				$filter['hook'],
				array($filter['component'], $filter['callback']),
				$filter['priority'],
				$filter['accepted_args']
			);
		}
	}
}

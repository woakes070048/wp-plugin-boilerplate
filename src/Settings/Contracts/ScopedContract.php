<?php

namespace WPPluginBoilerplate\Settings\Contracts;

interface ScopedContract
{
	/**
	 * site | network
	 */
	public static function scope(): string;
}

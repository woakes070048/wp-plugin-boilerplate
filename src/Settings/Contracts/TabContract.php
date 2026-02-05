<?php

namespace WPPluginBoilerplate\Settings\Contracts;

interface TabContract
{
	public function id(): string;

	public function label(): string;

	public function hasForm(): bool;

	public function hasActions(): bool;

	public function viewCapability(): string;

	public function manageCapability(): string;

	public function render(): void;
}

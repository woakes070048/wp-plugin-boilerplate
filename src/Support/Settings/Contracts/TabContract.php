<?php

namespace WPPluginBoilerplate\Support\Settings\Contracts;

interface TabContract
{
    public function id(): string;
    public function label(): string;

    public function hasForm(): bool;
    public function hasActions(): bool;

    /**
     * Capability required to view the tab
     */
    public function viewCapability(): string;

    /**
     * Capability required to modify settings
     */
    public function manageCapability(): string;

    public function render(): void;
}


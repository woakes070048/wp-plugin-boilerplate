<?php

namespace WPPluginBoilerplate\Settings\Fields\Types;

use WPPluginBoilerplate\Settings\Fields\Abstracts\AbstractField;

class TextareaField extends AbstractField
{
	public function render(string $optionKey): void
	{
		$this->openFieldWrapper();
		$rows        = $this->meta['rows'] ?? 5;
		$placeholder = $this->meta['placeholder'] ?? '';

		printf(
			'<textarea id="%s"
			name="%s"
			rows="%s"
			placeholder="%s"
			class="large-text">%s</textarea>',
			esc_attr($this->id($optionKey)),
			esc_attr($this->name($optionKey)),
			esc_attr($rows),
			esc_attr($placeholder),
			esc_textarea($this->value)
		);

		$this->description();
		$this->closeFieldWrapper();
	}

	protected function fieldClass(): string
	{
		return $this->meta['class'] ?? 'width';
	}

}

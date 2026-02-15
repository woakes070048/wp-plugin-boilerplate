<?php

namespace WPPluginBoilerplate\Settings\Fields\Types;

use WPPluginBoilerplate\Settings\Fields\Abstracts\AbstractField;

class EditorField extends AbstractField
{
	public function render(string $optionKey): void
	{
		$this->openFieldWrapper();

		$name  = $this->name($optionKey);
		$id    = $this->id($optionKey);
		$value = $this->value;

		$rows         = $this->meta['rows'] ?? 8;
		$mediaButtons = $this->meta['media_buttons'] ?? false;

		// TinyMCE-safe ID (no brackets allowed)
		$editor_id = sanitize_key(
			str_replace(['[', ']'], '_', $id)
		);

		wp_editor(
			$value,
			$editor_id,
			[
				'textarea_name' => $name,   // KEEP brackets here
				'textarea_rows' => $rows,
				'media_buttons' => $mediaButtons,
			]
		);

		$this->description();
		$this->closeFieldWrapper();
	}

	protected function fieldClass(): string
	{
		return $this->meta['class'] ?? 'width';
	}

}

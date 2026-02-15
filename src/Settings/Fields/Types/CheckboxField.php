<?php

namespace WPPluginBoilerplate\Settings\Fields\Types;

use WPPluginBoilerplate\Plugin;
use WPPluginBoilerplate\Settings\Fields\Abstracts\AbstractField;

class CheckboxField extends AbstractField
{
	public function render(string $optionKey): void
	{
		$this->openFieldWrapper();
		printf(
			'<label>
				<input type="checkbox" id="%s" name="%s" value="1" %s />
				%s
			</label>',
			esc_attr($this->id($optionKey)),
			esc_attr($this->name($optionKey)),
			checked($this->value, true, false),
			esc_html__('Enabled', Plugin::text_domain())
		);

		$this->description();
		$this->closeFieldWrapper();
	}
}

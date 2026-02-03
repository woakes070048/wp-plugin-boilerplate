<?php

namespace WPPluginBoilerplate\Support\Settings\Fields;

class FieldRenderer
{
    public static function render(string $optionKey, FieldDefinition $field, mixed $value): void {
        $name = $optionKey . '[' . $field->key . ']';
        $id   = $optionKey . '_' . $field->key;

        switch ($field->field) {

            /* -------------------------------------------------
             * Core HTML fields
             * ------------------------------------------------- */

            case 'text':
            case 'email':
            case 'url':
            case 'password':
            case 'hidden':
                self::input($field->field, $name, $id, $value);
                break;

            case 'textarea':
                self::textarea($name, $id, $value);
                break;

            case 'checkbox':
                self::checkbox($name, $id, $value);
                break;

            case 'number':
                self::number($name, $id, $value, $field->meta);
                break;

            case 'range':
                self::range($name, $id, $value, $field->meta);
                break;

            case 'select':
                self::select($name, $id, $value, $field->options);
                break;

            case 'multiselect':
                self::multiselect($name, $id, $value, $field->options);
                break;

            case 'radio':
                self::radio($name, $value, $field->options);
                break;

            case 'date':
            case 'time':
            case 'datetime-local':
                self::input($field->field, $name, $id, $value);
                break;

            /* -------------------------------------------------
             * WordPress-enhanced fields
             * ------------------------------------------------- */

            case 'media':
            case 'image':
            case 'file':
            case 'document':
            case 'audio':
            case 'video':
            case 'archive':
                self::media($name, $value, $field);
                break;

            case 'color':
                self::color($name, $id, $value);
                break;

            case 'editor':
                self::editor($name, $id, $value, $field->meta);
                break;

            default:
                throw new \InvalidArgumentException(
                    sprintf('Unsupported field type "%s".', $field->field)
                );
        }

        if (! empty($field->description)) {
            echo '<p class="description">';
            echo esc_html($field->description);
            echo '</p>';
        }
    }

    /* -------------------------------------------------
     * Core renderers
     * ------------------------------------------------- */

    protected static function input(string $type, string $name, string $id, mixed $value): void
    {
        printf(
            '<input type="%s" id="%s" name="%s" value="%s" class="regular-text" />',
            esc_attr($type),
            esc_attr($id),
            esc_attr($name),
            esc_attr($value)
        );
    }

    protected static function textarea(string $name, string $id, mixed $value): void
    {
        printf(
            '<textarea id="%s" name="%s" rows="5" class="large-text">%s</textarea>',
            esc_attr($id),
            esc_attr($name),
            esc_textarea($value)
        );
    }

    protected static function checkbox(string $name, string $id, mixed $value): void
    {
        printf(
            '<label>
                <input type="checkbox" id="%s" name="%s" value="1" %s />
                %s
            </label>',
            esc_attr($id),
            esc_attr($name),
            checked($value, true, false),
            esc_html__('Enabled', 'wp-plugin-boilerplate')
        );
    }

    protected static function number(string $name, string $id, mixed $value, array $meta): void
    {
        printf(
            '<input type="number" id="%s" name="%s" value="%s" min="%s" max="%s" />',
            esc_attr($id),
            esc_attr($name),
            esc_attr($value),
            esc_attr($meta['min'] ?? ''),
            esc_attr($meta['max'] ?? '')
        );
    }

    protected static function range(string $name, string $id, mixed $value, array $meta): void
    {
        printf(
            '<input type="range" id="%s" name="%s" value="%s" min="%s" max="%s" />',
            esc_attr($id),
            esc_attr($name),
            esc_attr($value),
            esc_attr($meta['min'] ?? 0),
            esc_attr($meta['max'] ?? 100)
        );
    }

    protected static function select(string $name, string $id, mixed $value, array $options): void
    {
        printf('<select id="%s" name="%s">', esc_attr($id), esc_attr($name));

        foreach ($options as $key => $label) {
            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr($key),
                selected($value, $key, false),
                esc_html($label)
            );
        }

        echo '</select>';
    }

    protected static function multiselect(string $name, string $id, mixed $value, array $options): void
    {
        $value = is_array($value) ? $value : [];

        printf('<select id="%s" name="%s[]" multiple>', esc_attr($id), esc_attr($name));

        foreach ($options as $key => $label) {
            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr($key),
                selected(in_array($key, $value, true), true, false),
                esc_html($label)
            );
        }

        echo '</select>';
    }

    protected static function radio(string $name, mixed $value, array $options): void
    {
        foreach ($options as $key => $label) {
            printf(
                '<label style="display:block;">
                    <input type="radio" name="%s" value="%s" %s />
                    %s
                </label>',
                esc_attr($name),
                esc_attr($key),
                checked($value, $key, false),
                esc_html($label)
            );
        }
    }

    /* -------------------------------------------------
     * WordPress-enhanced renderers
     * ------------------------------------------------- */

    protected static function media(string $name, mixed $value, FieldDefinition $field): void {
        $preview = '';

        if ($value) {
            if (
                $field->previewMode() === 'image' ||
                ($field->previewMode() === 'auto' && wp_attachment_is_image($value))
            ) {
                $preview = wp_get_attachment_image($value, 'thumbnail');
            } else {
                $file = get_attached_file($value);
                $preview = $file ? '<code>' . esc_html(basename($file)) . '</code>' : '';
            }
        }

        printf(
            '<div class="wppb-media-field">
            <input type="hidden" name="%s" value="%s" />

            <div class="wppb-media-preview">%s</div>

            <button type="button" class="button wppb-media-select" data-field="%s" data-mimes="%s">%s</button>

            <button type="button" class="button wppb-media-remove" %s>%s</button>
        </div>',
            esc_attr($name),
            esc_attr($value),
            $preview,
            esc_attr($field->field),
            esc_attr(wp_json_encode($field->allowedMimes())),
            esc_html__('Select', 'wp-plugin-boilerplate'),
            $value ? '' : 'disabled',
            esc_html__('Remove', 'wp-plugin-boilerplate')
        );
    }

    protected static function color(string $name, string $id, mixed $value): void
    {
        printf(
            '<input type="text" id="%s" name="%s" value="%s" class="wppb-color-field" />',
            esc_attr($id),
            esc_attr($name),
            esc_attr($value)
        );
    }

    protected static function editor(string $name, string $id, mixed $value, array $meta): void
    {
        wp_editor(
            $value,
            $id,
            [
                'textarea_name' => $name,
                'textarea_rows' => $meta['rows'] ?? 8,
                'media_buttons' => $meta['media_buttons'] ?? false,
            ]
        );
    }
}

<?php

namespace pxlrbt\Wordpress\SettingsPage;

class SettingsPage
{
    protected $slug;
    protected $title;
    protected $fields;
    protected $sections;

    public function __construct($slug, $title)
    {
        $this->slug = $slug;
        $this->title = $title;
        $this->fields = [];
        $this->sections = [];

        $this->addSection('general', 'Allgemein');

        add_action('admin_init', [$this, 'init']);
        add_action('admin_menu', [$this, 'registerMenu']);
        add_action('admin_enqueue_scripts', function(){ wp_enqueue_media(); });
    }

    public function init()
    {
        // Register settings and fields
        foreach ($this->fields as $field) {
            register_setting($this->slug, $field->slug, $field->args);

            add_settings_field(
                $field->slug,
                __( $field->name, 'mtw' ),
                [$this, 'fieldHtml'],
                $this->slug,
                ($field->section == null
                    ? $this->sections[$this->slug . '_general']->slug
                    : $field->section),
                    [
                        'slug' => $field->slug,
                        'label_for' => $field->slug
                    ]
            );
        }

        // Register sections
        foreach ($this->sections as $key => $section) {
            if (isset($section->fields) == false) {
                continue;
            }

            add_settings_section(
                $section->slug,
                __( $section->name, 'mtw' ),
                [$this, 'sectionHtml'],
                $this->slug
            );
        }
    }

    /**
     * Add a new section to page.
     */
    public function addSection($slug, $name, $args = [])
    {
        $section = new \stdClass;
        $section->slug = $this->slug . '_' . $slug;
        $section->name = $name;
        $section->args = $args;

        $this->sections[$section->slug] = $section;
    }

    /**
     * Add an field to the page and section
     * General section used if not specified
     */
    public function addField($slug, $name, $section = null, $args = [])
    {
        $field = new \stdClass;
        $field->slug = $this->slug . '_' . $slug;
        $field->name = $name;
        $field->args = $args;
        $field->section = ($section != null ? $this->slug . '_' . $section : null);

        $this->fields[$field->slug] = $field;
        $this->sections[$field->section]->fields[] = $field;
    }

    /**
     * Registers new menu for option page
     */
    public function registerMenu()
    {
        add_options_page(
            $this->title,
            $this->title,
            'manage_options',
            $this->slug,
            [$this, 'pageHtml']
        );
    }

    /*    HTML STUFF */
    /**
     * Print field html
     */
    public function fieldHtml($args)
    {
        $field = $this->fields[$args['slug']];
        $value = get_option($args['slug']);

        $type = isset($field->args['type']) ? $field->args['type'] : 'input';

        switch ($type) {
            case'editor':
                require __DIR__ . '/views/fields/editor.php';
                break;

            case'textarea':
                require __DIR__ . '/views/fields/textarea.php';
                break;

            case'checkbox':
                require __DIR__ . '/views/fields/checkbox.php';
                break;

            case'select':
                require __DIR__ . '/views/fields/select.php';
                break;

            default:
                require __DIR__ . '/views/fields/input.php';
        }
    }

    /**
     * Print sections html
     */
    public function sectionHtml($args){
        $section = $this->sections[$args['id']];

        if (isset($section->args['description'])) {
            echo '<p>' . $section->args['description'] . '</p>';
        }
    }

    /**
     * Print pages html
     */
    public function pageHtml()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        require __DIR__ . '/views/page.php';
    }
}

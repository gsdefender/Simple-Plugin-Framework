<?php

namespace theantichris\WpPluginFramework;

/**
 * Class SettingsField
 * @package theantichris\WpPluginFramework
 * @since 1.2.0
 */
class SettingsField
{
    /** @var  string */
    private $id; // TODO: Generated by gtter.
    /** @var  string */
    private $title;
    /** @var View */
    private $view;
    /** @var  mixed[] */
    private $args;
    /** @var  string */
    private $callback; // TODO: Display function handled by the View parameter.
    /** @var  string */
    private $page; // TODO: Will be supplied in the settings object.
    /** @var  string */
    private $section; // TODO: Will be supplied in the settings object.

    /**
     * @since 1.2.0
     * @param string $title
     * @param View $view
     * @param mixed[] $args
     * @param string $textDomain
     */
    public function __construct($title, View $view, $args = array(), $textDomain = '')
    {
        if (empty($title)) {
            wp_die(__('You did not specify a title for your settings field.', $textDomain));
        } elseif (empty($view)) {
            wp_die(__('You did not specify a view for your settings field.', $textDomain));
        } else {
            $this->title = $title;
            $this->view = $view;
            $this->args = $args;
        }
    }
} 
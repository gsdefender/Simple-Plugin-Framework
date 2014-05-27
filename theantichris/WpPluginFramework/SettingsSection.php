<?php

namespace theantichris\WpPluginFramework;

/**
 * Class SettingsSection
 * @package theantichris\WpPluginFramework
 * @since 1.2.0
 */
class SettingsSection
{
    /** @var string */
    private $title;
    /** @var View */
    private $view;

    /**
     * @since 1.2.0
     * @param string $title
     * @param View $view
     * @param string $textDomain
     */
    public function __construct($title, $view, $textDomain = '')
    {
        if (empty($title)) {
            wp_die(__('You did not specify a title for your settings section.', $textDomain));
        } elseif (empty($view)) {
            wp_die(__('You did not specify a view for your settings section.', $textDomain));
        } else {
            $this->title                    = $title;
            $this->view                     = $view;
            $this->view->view_data['title'] = $this->title;
        }
    }

    /**
     * @since 1.2.0
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @since 1.2.0
     * @return string
     */
    public function getId()
    {
        return sanitize_title($this->title);
    }

    /**
     * @since 1.2.0
     * @return void
     */
    public function display()
    {
        echo $this->view->render();
    }
} 
<?php

namespace theantichris\WpPluginFramework;

/**
 * Class Page
 *
 * A base class for creating and managing WordPress Dashboard pages.
 *
 * @package theantichris\WpPluginFramework
 *
 * @since 0.1.0
 */
abstract class Page
{
    /** @var string The text to be displayed in the title tags of the page when the menu is selected. */
    protected $title;
    /** @var View The View object responsible for rendering the page. */
    protected $view;
    /** @var string The capability required for this menu to be displayed to the user. */
    protected $capability = 'manage_options';
    /** @var string The icon for this page in the menu. */
    protected $menuIcon;
    /** @var int The position in the menu order this menu should appear. */
    protected $position;
    /** @var string The slug name for the parent menu (or the file name of a standard WordPress admin page). Use NULL if you want to create a page that doesn't appear in any menu. */
    protected $parentSlug;
    /** @var string */
    protected $textDomain;

    /**
     * Class constructor.
     *
     * @since 0.1.0
     *
     * @param string $title
     * @param View $view
     * @param string $textDomain
     */
    public function __construct($title, View $view, $textDomain = '')
    {
        $this->textDomain = $textDomain;

        if (empty($title)) {
            wp_die(__('You did not specify a title for your page.', $this->textDomain));
        } elseif (empty($view)) {
            wp_die(__('You did not specify a view for your page.', $this->textDomain));
        } else {
            $this->title                   = $title;
            $this->view                    = $view;
            $this->view->viewData['title'] = $this->title;
            $this->view->viewData['slug']  = $this->getSlug();
        }

        add_action('admin_menu', array($this, 'addPage'));
    }

    /**
     * Checks if the given capability is a valid WordPress capability using the Capability enum.
     *
     * @since 3.0.0
     *
     * @param string $capability
     * @return void
     */
    public function setCapability($capability)
    {
        if (Capability::isValid($capability)) {
            $this->capability = $capability;
        } else {
            wp_die(__("The capability set for the {$this->title} page is not a valid WordPress capability ({$capability}).", $this->textDomain));
        }
    }

    /**
     * Checks if $position is an integer and if it is sets the property.
     *
     * @since 3.0.0
     *
     * @param int|string $position
     * @return void
     */
    public function setPosition($position)
    {
        if (is_numeric($position)) {
            $this->position = intval($position);
        } else {
            wp_die(__("The menu position set for the {$this->title} page is not an integer ({$position}).", $this->textDomain));
        }
    }

    /**
     * Validates and sets the page's menu icon.
     *
     * @since 3.0.0
     *
     * @param string $icon
     * @return void
     */
    public function setMenuIcon($icon)
    {
        if (filter_var($icon, FILTER_VALIDATE_URL)) {
            $this->menuIcon = $icon;
        } else {
            wp_die(__("The URL specified for the {$this->title} page menu icon is not valid ({$icon}).", $this->textDomain));
        }
    }

    /**
     * Returns the $slug property.
     *
     * @since 0.1.0
     *
     * @return string
     */
    public function getSlug()
    {
        return sanitize_title($this->title);
    }

    /**
     * Adds the page to WordPress.
     *
     * @since 0.1.1
     *
     * @return void
     */
    abstract public function addPage();

    /**
     * Displays the HTML output of the page.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function display()
    {
        if (!current_user_can($this->capability)) {
            wp_die(__('You do not have sufficient permissions to access this page.', $this->textDomain));
        }

        $this->view->render();
    }
}

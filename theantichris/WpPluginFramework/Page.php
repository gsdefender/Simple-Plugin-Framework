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
    /** @var string User readable title for the page and menu item. */
    protected $title;
    /** @var string Unique ID for the page. */
    protected $slug;

    /**
     * Class constructor.
     *
     * @since 0.1.0
     *
     * @param PageArg $pageArg
     */
    public function __construct(PageArg $pageArg) {
        $this->title = $pageTitle;
        $this->slug = sanitize_title($pageTitle);

        $this->viewPath = $viewPath;

        if (!empty($capability)) {
            $this->capability = $capability;
        }

        if (!empty($menuIcon)) {
            $this->menuIcon = $menuIcon;
        }

        if (!empty($position)) {
            $this->position = $position;
        }

        if (!empty($viewData)) {
            $this->viewData = $viewData;
        }

        $this->viewData['title'] = $this->title;
        $this->viewData['slug'] = $this->slug;

        $this->parentSlug = $parentSlug;

        $this->textDomain = $textDomain;

        add_action('admin_menu', array($this, 'addPage'));
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
        return $this->slug;
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
     * Removes a page from WordPress.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function removePage()
    {
        remove_menu_page($this->slug);
    }

    /**
     * Displays the HTML output of the page.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function displayPage()
    {
        if (!current_user_can($this->capability)) {
            wp_die(__('You do not have sufficient permissions to access this page.', $this->textDomain));
        }

        $view = new View($this->viewPath, $this->viewData);

        echo $view->render();
    }
}

<?php

namespace theantichris\SPF;

/**
 * Class SubMenuPage
 *
 * A class for adding a sub menu page from the WordPress Dashboard. Extends Page.
 *
 * @package theantichris\SPF
 *
 * @since 0.1.0
 */
class SubMenuPage extends Page
{
    /** @var string|null The slug name for the parent menu (or the file name of a standard WordPress admin page). Use NULL if you want to create a page that doesn't appear in any menu. */
    private $parentSlug = null;

    /**
     * Calls the WordPress add_submenu_page() function.
     * Should not be called directly. It is only public so WordPress can call it.
     * @link http://codex.wordpress.org/Function_Reference/add_submenu_page
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function addPage()
    {
        $name = $this->getName();

        add_submenu_page($this->parentSlug, $name, $name, $this->capability, $this->getSlug(), array($this, 'display'));
    }

    /**
     * Sets the page's parent slug.
     *
     * @since 3.0.0
     *
     * @param string|null $slug The slug name for the parent menu (or the file name of a standard WordPress admin page). Use NULL if you want to create a page that doesn't appear in any menu.
     * @return SubMenuPage
     */
    public function setParentSlug($slug)
    {
        $this->parentSlug = $slug;

        return $this;
    }
}
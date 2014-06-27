# WordPress Plugin Framework

## Introduction

This is an OOP framework for making the development of WordPress plugins easier and more strongly typed.

Each WordPress Object in the framework handles the WordPress specific registration functions and ties those functions to the right hooks automatically so a use only needs to set up properties and create the object.

## Getting Started

### Composer

The package is hosted on [Packagist](https://packagist.org/packages/theantichris/wp-plugin-framework) and ready to be using with [Composer](https://getcomposer.org/). Just add package to your composer.json.

### Drop In

Move the files from the __theantichris/WpPluginFramework__ directory into your plugin and include them using `include` statements.

### Using the Framework in Your Plugin

The easiest way to start using the frame work is to create a class for your plugin and place the framework code in the classes constructor.


    class myPlugin
    {
        public function __construct()
        {
            // Place framework code in there.
        }
    }

    new myPlugin();

## Creating WordPress Objects

The framework contains classes for creating custom post types, taxonomies, pages, and settings. The basic flow for creating these objects is setting up the arguments class and instantiating the object.

### Custom Post Types

The CustomPostTypeArg class requires the plural display name of the post type upon construction. Optionally, you can pass in your text domain.

CustomPostTypeArg uses the name to automatically generate the slug and labels for the post type.

The rest of the properties for CustomPostTypeArg are setup to create a publicly facing post type but can be overridden using standard object notation.

The CustomPostType class requires an instance of the CustomPostTypeArg class which passes in the needed information.

The CustomPostType class constructor sets up the arguments for the [register_post_type()](http://codex.wordpress.org/Function_Reference/register_post_type) function and adds the function to the [init](http://codex.wordpress.org/Plugin_API/Action_Reference/init) hook. The frameworks checks if the post type exists before adding it.

    $postTypeArgs = new CustomPostTypeArg('My Posts');
    $myPostType = new CustomPostType($postTypeArgs);

### Taxonomies

The TaxonomyArg class requires the plural display name of the taxonomy upon construction. Optionally, you can pass in your text domain.

TaxonomyArg uses the name to automatically generate the slug and labels for the taxonomy.

The rest of the TaxonomyArg properties are setup to create a taxonomy for the post post type but can be overridden using the $postTypes property.

The Taxonomy class requires an instance of the TaxonomyArg class in order to be created.

The Taxonomy class constructor sets up the arguments for the [register_taxonomy()](http://codex.wordpress.org/Function_Reference/register_taxonomy) function and adds the function to the [init](http://codex.wordpress.org/Plugin_API/Action_Reference/init) hook. The frameworks checks if the taxonomy exists before adding it.

Terms can be added to the taxonomy by using the addTerms() method. It requires a single string or array of strings to be used as the terms. You can optionally supply the text domain.

    $taxonomyArgs = new TaxonomyArgs('Genre');
    $taxonomyArgs->postTypes = $myPostType->getSlug();
    $myTaxonomy = new Taxonomy($taxonomyArgs);
    $myTaxonomy->addTerms('punk');

### Pages

You can create new dashboard pages by using the MenuPage, ObjectPage, UtilityPage, SubMenuPage, and OptionsPage classes. All page classes inherit from the Page abstract class.

All page classes require a title and View when instantiated. Text domain can be provided, optionally.

The base constructor sets the parameters then ties the abstract addPage() method to the [admin_menu](http://codex.wordpress.org/Plugin_API/Action_Reference/admin_menu) hook. This addPage() method class the correct WordPress function to add that type of page. The base display() method is used as the display call back.

Setters are available for capability, menu icon, position. A setter for parent slug is available for SubMenuPage.

Capability is set to 'manage_options' by default but can be changed to any valid WordPress capability. The setCapability() method accepts the capability as a string.

An abstract class called Capability is included that can be used to easily pick a WordPress capability using code completion. Simply type `Capability::` and your IDE should give you a list of all valid capabilities.

    $page->setCapability(Capability::manage_options);

The setMenuIcon() method accepts a URL as a string. The setter will check if it is given a valid URL but does not check if the file exists.

    $page->setMenuIcon('http://placehold.it/15x15');

The setPosition() method accepts either an integer or numeric string. Both of these examples will do the same thing.

    $page->setPosition(100);
    $page->setPosition('100');

Setter methods can be chained.

    $page->setPosition(5)->setMenuIcon($iconUrl);

#### MenuPage

To add a top-level menu page use the MenuPage class. Calls the [add_menu_page()](http://codex.wordpress.org/Function_Reference/add_menu_page) function.

    $menuPage = new MenuPage('My Page', $myView);

#### ObjectPage

ObjectPage adds a top-level page on the Object level (Posts, Media, Links, Pages, Comments, etc.) Calls the [add_object_page()](http://codex.wordpress.org/Function_Reference/add_object_page) function.

    $objectPage = new ObjectPage('My Page', $myView);

#### UtilityPage

UtilityPage adds a top-level page on the Utility level (Appearance, Plugins, Users, Tools, Settings, etc.) Calls the [add_utility_page()](http://codex.wordpress.org/Function_Reference/add_utility_page) function.

    $utilityPage = new UtilityPage('My Page', $myView);

#### OptionsPage

OptionsPage adds a sub-men page under Settings. Class the [add_options_page](http://codex.wordpress.org/Function_Reference/add_options_page) function.

    $optionsPage = new OptionsPage('My Page', $myView);

#### SubMenuPage

SubMenuPage adds a page as a sub-menu item for another page. Calls the [add_submenu_page()](http://codex.wordpress.org/Function_Reference/add_submenu_page) function.

    $subPage = new SubMenuPage('My Sub Page', $myView);
    $subPage->setParentSlug($myPage->getSlug());

### Settings

The Settings part of the framework consists of three classes. Settings, SettingsSection, and SettingsField.

A SettingsField object represents a single settings field on the page. A SettingsSection object represents a section of SettingsField objects grouped together on the page. The Settings object manages the WordPress interactions and what page the settings are displayed on.

#### SettingsFields

Start by creating your fields. The SettingsField constructor requires the field title (string) and view (View). Prefix (string), text domain (string), and additional arguments (array) can be provided but are optional. Prefix is set to 'lwppfw' by default.

Field title will be converted into an ID for the field in the WordPress database by being processed through sanitize_title() and being prepended by the value of prefix.

You can add anything you would like to the fields view but it is recommended to only specify the form field tag and label.

    $field1 = new SettingsField('Field One', $viewView); // ID is lwppfw-field-one.
    $field2 = new SettingsField('Field Two', $viewView); // ID is lwppfw-field-two.

#### SettingsSection

After you have some fields defined you will want to create a section and add your fields to it.

The SettingsSection constructor requires a title (string), view (View), and optionally takes a text domain (string).

## View

The View class makes it simpler to display output from your plugin.

It allows you to take the code that displays the output and gives it its own file (the view). The view is separated from your logic keeping your code cleaner, easier to read, and easier to manage.

Create a directory in your project to hold all your view files is a good practice.

Use the View class' render() function to display the view and send any data the view needs to know about.

    $view = new View($viewFile, $viewData = null);
    $view->render();

$viewFile should contain the full path and file name of the view file to render.

$viewData is used to pass data to the view if needed. It is an associated array. To use the data in the view file use a variable with the name of the data's key in the array. For example `$viewData['example']` will be `$example` in the view.

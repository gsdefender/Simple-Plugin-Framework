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

The PageArg class requires the display title for the page and an instance of the View class. The View class is used by Page class' display() method to render the page. The text domain can be passed in optionally.

Capabilities, menu icon, and position can be set using standard object notation but are not required. Parent slug is only required for the SubMenuPage object and can be set using object notation.

The base constructor for all pages requires an instance of PageArg. The constructor sets the parameters up then ties the abstract addPage() method to the [admin_menu](http://codex.wordpress.org/Plugin_API/Action_Reference/admin_menu) hook. This addPage() method class the correct WordPress function to add that type of page. The base display() method is used as the display call back.

#### MenuPage

To add a top-level menu page use the MenuPage class. Calls the [add_menu_page()](http://codex.wordpress.org/Function_Reference/add_menu_page) function.

    $pageArg = new PageArg('My Page', $myView);
    $menuPage = new MenuPage($pageArg);

#### ObjectPage

ObjectPage adds a top-level page on the Object level (Posts, Media, Links, Pages, Comments, etc.) Calls the [add_object_page()](http://codex.wordpress.org/Function_Reference/add_object_page) function.

    $pageArg = new PageArg('My Page', $myView);
    $objectPage = new ObjectPage($pageArg);

#### UtilityPage

UtilityPage adds a top-level page on the Utility level (Appearance, Plugins, Users, Tools, Settings, etc.) Calls the [add_utility_page()](http://codex.wordpress.org/Function_Reference/add_utility_page) function.

    $pageArg = new PageArg('My Page', $myView);
    $utilityPage = new UtilityPage($pageArg);

#### OptionsPage

OptionsPage adds a sub-men page under Settings. Class the [add_options_page](http://codex.wordpress.org/Function_Reference/add_options_page) function.

    $pageArg = new PageArg('My Page', $myView);
    $optionsPage = new OptionsPage($pageArg);

#### SubMenuPage

SubMenuPage adds a page as a sub-menu item for another page. Calls the [add_submenu_page()](http://codex.wordpress.org/Function_Reference/add_submenu_page) function.

    $pageArg = new PageArg('My Sub Page', $myView);
    $pageArg->parentSlug = $myPage->getSlug();
    $subPage = new SubMenuPage($pageArg);

### Settings

The Settings class requires an instance of SettingsArg as a parameter.

SettingsArg requires the page slug that the settings will appear on, and instance of SettingsSection and a single instance or array of SettingsField.

The SettingsSection class requires the title for the settings section and the instance of View that will render the section. Text domain is optional.

The SettingsField class requires the title of the field and the instance of View that will render the field. Additional arguments, ID prefix, and text domain are optional. The prefix is set to 'lwppfw' by default.

The Settings class constructor ties the [add_settings_section()](http://codex.wordpress.org/Function_Reference/add_settings_section) to the [admin_init](http://codex.wordpress.org/Plugin_API/Action_Reference/admin_init) hook then ties [add_settings_field()](http://codex.wordpress.org/Function_Reference/add_settings_field) and [register_setting()](http://codex.wordpress.org/Function_Reference/register_setting) to admin_init for each field in SettingsArg.

    $section = new SettingsSection('My Settings', $sectionView);
    $field1 = new SettingsField('Field 1', $fieldView1);
    $field2 = new SettingsField('Field 2', $fieldView2);
    $settingsArg = new SettingsArg($myPage->getSlug(), $section, array($field, $field2));
    new Settings($settingsArg);

## View

The View class makes it simpler to display output from your plugin.

It allows you to take the code that displays the output and gives it its own file (the view). The view is separated from your logic keeping your code cleaner, easier to read, and easier to manage.

Create a directory in your project to hold all your view files is a good practice.

Use the View class' render() function to display the view and send any data the view needs to know about.

    $view = new View($viewFile, $viewData = null);
    $view->render();

$viewFile should contain the full path and file name of the view file to render.

$viewData is used to pass data to the view if needed. It is an associated array. To use the data in the view file use a variable with the name of the data's key in the array. For example `$viewData['example']` will be `$example` in the view.

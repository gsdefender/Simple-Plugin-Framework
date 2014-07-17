<?php

namespace theantichris\SPF;

/**
 * Class WordPressObject
 * @package theantichris\SPF
 * @since 3.0.0
 */
abstract class WordPressObject
{
    /** @var string The text domain used for i18n. */
    public static $textDomain = 'default';
    /** @var string Name or title of the WordPress object. */
    protected $name;

    /**
     * Takes a plural string and returns the singular version.     *
     * @link https://sites.google.com/site/chrelad/notes-1/pluraltosingularwithphp
     *
     * @since 3.0.0
     *
     * @param string $word Plural word to make singular.
     *
     * @return string
     */
    protected function makeSingular($word)
    {
        /** @var mixed[] $rules Rules for making a string singular. The key is the plural ending, the value is the singular ending. */
        $rules = array(
            'ss'  => false,
            'os'  => 'o',
            'ies' => 'y',
            'xes' => 'x',
            'oes' => 'o',
            'ves' => 'f',
            's'   => ''
        );

        /** @var string $key */
        foreach (array_keys($rules) as $key) {
            if (substr($word, (strlen($key) * -1)) != $key) {
                continue;
            }

            if ($key === false) {
                return $word;
            }

            return substr($word, 0, strlen($word) - strlen($key)) . $rules[$key];
        }

        return $word;
    }

    /**
     * Returns the object's $name property.
     *
     * @since 3.0.0
     *
     * @return string
     */
    public function getName(){
        return __($this->name, self::$textDomain);
    }

    /**
     * Passes a $name through WordPress' sanitize_title() method to create a slug.
     * @link http://codex.wordpress.org/Function_Reference/sanitize_title
     *
     * @since 3.0.0
     *
     * @return string
     */
    public function getSlug()
    {
        return sanitize_title($this->name);
    }
}
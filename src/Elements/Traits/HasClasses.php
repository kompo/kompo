<?php

namespace Kompo\Elements\Traits;

trait HasClasses
{
    /**
     * The element's classes string.
     *
     * @var array
     */
    //public $class = ''; //Commented to allow use in traits without conflict

    /**
     * Sets the class attribute of the element.
     * For multiple classes, use a space-separated string.
     *
     * @param string $classes The class attribute.
     *
     * @return mixed
     */
    public function class($classes = null)
    {
        if ($classes !== null) {
            $this->class = ($this->class() ? ($this->class().' ') : '').trim($classes);

            return $this;
        } else {
            return property_exists($this, 'class') ? $this->class : '';
        }
    }

    /**
     * Sets the class attribute for the input element of the field.
     * For multiple classes, use a space-separated string.
     *
     * @param string $classes The class attribute.
     *
     * @return mixed
     */
    public function inputClass($classes)
    {
        return $this->config(['inputClass' => $classes]);
    }

    /**
     * Adds classes to the element. TODO: replace with overwriteClass or smth...
     *
     * @param string $classes
     *
     * @return mixed
     */
    public function replaceClass($classes)
    {
        $this->class = trim($classes);

        return $this;
    }

    /**
     * Removes a class or space separated classes from the element.
     *
     * @param string $classes
     *
     * @return mixed
     */
    public function removeClass($classes)
    {
        $currentClasses = explode(' ', $this->class());
        $newClasses = explode(' ', $classes);

        return $this->replaceClass(implode(' ', array_diff($currentClasses, $newClasses)));
    }

    /**
     * Sets a specific col value when the element is used inside `Columns`. By default, the columns get equal widths. A 12 column grid system is used here (same as Bootstrap CSS). For example:
     * <php>Columns::form(
     *    Html::form('Column 1')->col('col-8'),
     *    Html::form('Column 2')->col('col-4')
     * )</php>.
     *
     * @param string $col The col attribute. Ex: `col-8`.
     *
     * @return self
     */
    public function col($col)
    {
        return $this->config(['col' => $col]);
    }

    //Optional if using tailwind - maybe document in a bundle instead of main docs
    //TODO: document
    public function color($colorScale = null, $opacity = null)
    {
        if ($colorScale) {
            $this->class('text-'.color().'-'.$colorScale);
        }

        if ($opacity) {
            $this->class('text-opacity-'.$opacity);
        }

        return $this;
    }

    //TODO: document
    public function bgColor($colorScale = null, $opacity = null)
    {
        
        if ($colorScale) {
            $this->class('bg-'.color().'-'.$colorScale);
        }

        if ($opacity) {
            $this->class('bg-opacity-'.$opacity);
        }

        return $this;
    }

    //TODO: document
    public function monoGradient($fromScale, $toScale)
    {
        return $this->class('from-'.color().'-'.$fromScale.' to-'.color().'-'.$toScale);
    }
}

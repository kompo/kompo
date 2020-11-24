<?php 

namespace Kompo\Komponents\Traits;

use Kompo\Core\IconGenerator;

trait LabelInfoComment 
{
    /**
     * This will remove the label.
     *
     * @return self
     */
    public function noLabel()
    {
        return $this->data([
            'noLabel' => true
        ]);
    }

    /**
     * Sets the class attribute of the elements' label.
     * For multiple classes, use a space-separated string.
     *
     * @param string $labelClass The label's space separated class attribute.
     * 
     * @return self
     */
    public function labelClass($labelClass)
    {
        return $this->data([
            'labelClass' => $labelClass
        ]);
    }

    /**
     * Adds a comment below the field. This is additional information to complement the label of the input.
     *
     * @param      string  $comment  The comment. Can be an HTML string too.
     *
     * @return     self   
     */
    public function comment($comment)
    {
        return $this->data(['comment' => $comment]);
    }

    /**
     * Adds a help text next to the label that appears on hover. By default, a question mark icon is used.
     *
     * @param      string  $hint   The information text that displays on hover.
     *
     * @return     self
     */
    public function hint($hint)
    {
        //TODO: info should be renamed hint() in the Front-end!!
        return $this->data(['info' => $hint]);
    }

    /**
     * Sets the hint text's icon or class icon. By default, it is the built-in question mark icon.
     * 
     * @param  string $iconClassOrHtml This is the icon HTML or class in &lt;i class="...">&lt;/i>
     * 
     * @return self
     */
    public function hintIcon($iconClassOrHtml)
    {
        return $this->data(['infoIcon' => IconGenerator::toHtml($iconClassOrHtml)]);
    }

    /**
     * Sets where the hint text's will show on hover. By default, it displays on top.
     *
     * @param      string  $infoPlacement  Accepted values are up, down, left, right
     *
     * @return     self    ( description_of_the_return_value )
     */
    public function hintPlacement($infoPlacement)
    {
        return $this->data(['infoPlacement' => $infoPlacement]);
    }

    /**
     * Sets the width of the hint text. By default, it's medium'.
     *
     * @param      string  $hintLength  Accepted values are small, medium, big, fit
     *
     * @return     self    ( description_of_the_return_value )
     */
    public function hintLength($hintLength)
    {
        return $this->data(['hintLength' => $hintLength]);
    }

}
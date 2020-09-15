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
     * @param      string  $info   The information text that displays on hover.
     *
     * @return     self
     */
    public function info($info)
    {
        //This method should be renamed hint()!!
        return $this->data(['info' => $info]);
    }

    /**
     * Sets the info text's icon or class icon. By default, it is the built-in question mark icon.
     * 
     * @param  string $iconClassOrHtml This is the icon HTML or class in &lt;i class="...">&lt;/i>
     * 
     * @return self
     */
    public function infoIcon($iconClassOrHtml)
    {
        return $this->data(['infoIcon' => IconGenerator::toHtml($iconClassOrHtml)]);
    }

    /**
     * Sets where the info text's will show on hover. By default, it displays on top.
     *
     * @param      string  $infoPlacement  Accepted values are top, bottom, left, right
     *
     * @return     self    ( description_of_the_return_value )
     */
    public function infoPlacement($infoPlacement)
    {
        return $this->data(['infoPlacement' => $infoPlacement]);
    }

}
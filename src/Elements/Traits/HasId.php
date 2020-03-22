<?php 

namespace Kompo\Elements\Traits;

trait HasId
{
    /**
     * The element's HTML id.
     *
     * @var string
     */
    public $id;

    /**
     * Sets the id attribute of the element.
     *
     * @param      string  $id     The id attribute.
     *
     * @return self
     */
    public function id($id)
    {
        $this->id = $id;

        return $this;
    }

}
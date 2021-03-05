<?php

namespace Kompo\Elements\Traits;

use Kompo\Core\KompoId;
use Kompo\Komposers\Komposer;

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
     * @param string $id The id attribute.
     *
     * @return self
     */
    public function id($id)
    {
        $this->id = $id;

        if ($this instanceof Komposer) {
            KompoId::setForKomposer($this, $this->id);
        }

        return $this;
    }
}

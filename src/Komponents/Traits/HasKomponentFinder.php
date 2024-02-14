<?php

namespace Kompo\Komponents\Traits;

use Kompo\Komponents\KomponentFinder;

trait HasKomponentFinder
{
    public function finder()
    {
        return new KomponentFinder($this);
    }

    /* SHORTCUTS */
    public function findByComponent($component)
    {
        return $this->finder()->byComponent($component)->find();
    }

    public function findByClass($class)
    {
        return $this->finder()->byClass($class)->find();
    }

    public function findById($id)
    {
        return $this->finder()->byId($id)->find();
    }

    public function findByStringContains($stringContains)
    {
        return $this->finder()->byStringContains($stringContains)->find();
    }

    public function countByComponent($component)
    {
        return $this->finder()->byComponent($component)->count();
    }

    public function countByClass($class)
    {
        return $this->finder()->byClass($class)->count();
    }

    public function countById($id)
    {
        return $this->finder()->byId($id)->count();
    }

    public function countByStringContains($stringContains)
    {
        return $this->finder()->byStringContains($stringContains)->count();
    }
}
<?php

namespace Kompo\Elements\Traits;

use Illuminate\Support\Str;
use Kompo\Routing\RouteFinder;

trait HasHref
{
    /**
     * The element's href.
     *
     * @var string
     */
    public $href = 'javascript:void(0)'; //TODO: should be empty by default

    /**
     * The element's hash, if any.
     *
     * @var string
     */
    public $hash;

    /**
     * The element's Html target attribute.
     *
     * @var string
     */
    public $target;

    /**
     * Sets the href attribute of a link.
     *
     * @param string     $route      The route name or uri.
     * @param array|null $parameters The route parameters (optional).
     *
     * @return self
     */
    public function href($route, $parameters = null)
    {
        $this->checkTurbo($route, $parameters);

        $this->href = RouteFinder::guessRoute($route, $parameters);
        
        $this->checkActive();

        if (method_exists($this, 'addCursorPointer')  ) {
            $this->addCursorPointer();
        }

        return $this;
    }

    /**
     * Sets the url of an Element using the Laravel `url()` helper function.
     *
     * @param string     $url        The route uri.
     * @param array|null $parameters The route parameters (optional).
     *
     * @return self
     */
    public function url($url, $parameters = null)
    {
        return $this->href(url($url), $parameters);
    }

    /**
     * Opens the link in a new tab, i.e. it sets target="_blank".
     *
     * @return self
     */
    public function inNewTab()
    {
        return $this->target('_blank');
    }

    /**
     * Adds a desired hash to the href of the element.
     * If parameter is null, a slugged version of the label will be calculated and added as a hash.
     * Ex: ->addHash('some-hash') will add href="/link#some-hash".
     *
     * @param string|null $hash The desired hash
     *
     * @return self
     */
    public function addHash($hash = null)
    {
        $this->hash = $hash ?: Str::slug(strip_tags($this->label), '-');

        if ($this->href != 'javascript:void(0)') {
            $this->href .= '#'.$this->hash;
        }

        return $this;
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function target($target)
    {
        $this->target = $target;

        return $this;
    }

    public function hasRoute()
    {
        return $this->href != 'javascript:void(0)';
    }

    //Active navigation methods
    public function checkActive()
    {
        $hrefWithoutHash = strtok($this->href, '#');
        
        if ($hrefWithoutHash == \Request::getSchemeAndHttpHost()) { //home page only active if matched completely
            $this->setActive(\Request::url() == $hrefWithoutHash? $this->getActiveClass() : '');
        } else {
            $this->setActive(substr(\Request::url(), 0, strlen($hrefWithoutHash)) == $hrefWithoutHash ? $this->getActiveClass() : '');
        }
    }

    protected function setActive($activeClass)
    {
        $this->config(['active' => $activeClass]);
    }

    //TODO: DOCUMENT
    public function activeClass($activeClass)
    {
        return $this->config([
            'activeClass' => $activeClass,
        ]);
    }

    protected function getActiveClass()
    {
        return $this->config('activeClass') ?: 'vlActive';
    }
}

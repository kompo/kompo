<?php

namespace Kompo\Tests\Feature\Routing;

trait _RouteParametersCommonTrait
{
    /*public $id = 'obj-id';

    protected $metaTags = [
        'title' => 'meta-title',
        'description' => 'meta description',
        'keywords' => 'key,word'
    ];*/

    public $store; //Had to expose it to see it in a route response
    public $parameters; //Had to expose it to see them in a route response
    public $param1;     //to check individually
    public $param2;     //to check individually

    public function commonCreated()
    {
        $this->id = 'obj-id';
        $this->metaTags = [
            'title'       => 'meta-title',
            'description' => 'meta description',
            'keywords'    => 'key,word',
        ];

        $this->store = $this->store();
        $this->parameters = $this->parameter(); //to check all
        $this->param1 = $this->parameter('param'); //to check individually
        $this->param2 = $this->parameter('opt'); //to check individually
    }
}

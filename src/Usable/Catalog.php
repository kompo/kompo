<?php

namespace Kompo;

use Kompo\Komposers\Catalog\CatalogBooter;
use Kompo\Komposers\Komposer;

abstract class Catalog extends Komposer
{
    /**
     * The card vue component name or Card class to display an item.
     * 
     * @var Kompo\Card|string
     */
    public $card;

    /**
     * The catalog's layout component.
     * 
     * @var string
     */
    public $layout = 'Horizontal';

    /**
     * The vue component to render the Catalog as a child.
     *
     * @var        string
     */
    public $component = 'FormCatalog'; //--> TODO: move to data

    public $query; //--> TODO: move to data
    
    public $filters; //--> TODO: move to data

    public $orderable = false;
    public $keyName = 'id';
    
    /**
     * Whether to display pagination links or not
     *
     * @var        boolean
     */
    public $hasPagination = true;

    /**
     * Whether to display pagination links above the cards
     *
     * @var        boolean
     */
    public $topPagination = true;

    /**
     * Whether to display pagination links below the cards.
     *
     * @var        boolean
     */
    public $bottomPagination = false;

    /**
     * Whether to align pagination links to the left or to the right.
     *
     * @var        boolean
     */
    public $leftPagination = false;

    /**
     * The pagination links style.
     *
     * @var        string
     */
    public $paginationStyle = 'Links';

    /**
     * The default message that displays when no items are found (or translation key if multi-language app).
     *
     * @var        string
     */
    public $noItemsFound = 'No items found';

    /**
     * The default number of items per page.
     *
     * @var        integer
     */
    public $perPage = 50;

    /**
     * The model's namespace used for filters display.
     *
     * @var string
     */
    public $model;

	/**
     * Constructs a Catalog
     * 
     * @param null|array $store (optional) Additional data passed to the komponent.
     *
     * @return self
     */
	public function __construct($store = [], $dontBoot = false)
	{
        if(!$dontBoot)
            CatalogBooter::bootForDisplay($this, $store);
	}

    /**
     * The overridable method to load the query that will be displayed in the catalog.
     *
     * @return Illuminate\Database\Eloquent\Builder|
     *         Illuminate\Database\Eloquent\Model|
     *         Illuminate\Database\Query\Builder|
     *         Illuminate\Database\Eloquent\Relations\Relation|
     *         Illuminate\Support\Collection|
     *         array
     */
    public function query()
    {
        if( $model = $this->model )
            return new $model();
    }

    /**
     * The method that contains the card information. 
     * Each item from the query will pass through this method and be transformed according to it's specs.
     *
     * @param mixed  $item  An item from the query
     *
     * @return array|Kompo\Card
     */
    public function card($item)
    {
        return [];
    }

    //Catalog filters
    public function top(){ return []; }
    public function bottom(){ return []; }
    public function left(){ return []; }
    public function right(){ return []; }

    /**
     * Get the filter/browse request's validation rules.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Assigns the page for browsing the catalog.
     *
     * @param  integer|null  $currentPage (optional) The current catalog page number.
     * 
     * @return integer
     */
    public function currentPage($currentPage = null)
    {
        return $this->_kompo('currentPage', $currentPage) ?: 1; //If null, we are on the first page.
    }



    /**
     * Shortcut method to render a Catalog into it's Vue component.
     *
     * @return string
     */
    public static function render($store = [])
    {
        return CatalogBooter::renderVueComponent(new static($store));
    }

}
<?php

namespace Kompo;

use Kompo\Komposers\Komposer;
use Kompo\Komposers\Query\QueryBooter;

abstract class Query extends Komposer
{
    /**
     * The card vue component name or Card class to display an item.
     *
     * @var Kompo\Card|string
     */
    public $card;

    /**
     * The query's layout component.
     *
     * @var string
     */
    public $layout = 'Horizontal';

    /**
     * The Vue komposer tag.
     *
     * @var string
     */
    public $vueKomposerTag = 'vl-query';

    /**
     * The Query results.
     */
    public $query;

    /**
     * The Query filter Komponents.
     */
    public $filters;

    /**
     * Make the query orderable with back-end support.
     *
     * @var bool
     */
    public $orderable = false;

    /**
     * When dealing with non-eloquent Queries, this specifies the primary key.
     *
     * @var string
     */
    public $keyName = 'id';

    /**
     * The headers of a Table Query.
     *
     * @var array
     */
    public $headers = [];

    /**
     * Whether to display pagination links or not.
     *
     * @var bool
     */
    public $hasPagination = true;

    /**
     * Whether to display pagination links above the cards.
     *
     * @var bool
     */
    public $topPagination = false;

    /**
     * Whether to display pagination links below the cards.
     *
     * @var bool
     */
    public $bottomPagination = true;

    /**
     * Whether to align pagination links to the left or to the right.
     *
     * @var bool
     */
    public $leftPagination = false;

    /**
     * The pagination links style. Values: Links|Showing|Scroll.
     *
     * @var string
     */
    public $paginationStyle = 'Links';

    /**
     * The default message that displays when no items are found (or translation key if multi-language app).
     *
     * @var string
     */
    public $noItemsFound = 'No items found';

    /**
     * The default number of items per page.
     *
     * @var int
     */
    public $perPage = 15;

    /**
     * The model's namespace used for filters during display phase.
     *
     * @var string
     */
    public $model;

    /**
     * Constructs a Query.
     *
     * @param null|array $store (optional) Additional data passed to the komponent.
     *
     * @return self
     */
    public function __construct(?array $store = [])
    {
        parent::__construct();

        $this->store($store);

        if (app('bootFlag')) {
            $this->boot();
        }
    }

    /**
     * The overridable method to load the query that will be displayed in the query.
     *
     * @return Illuminate\Database\Eloquent\Builder|
     *                                               Illuminate\Database\Eloquent\Model|
     *                                               Illuminate\Database\Query\Builder|
     *                                               Illuminate\Database\Eloquent\Relations\Relation|
     *                                               Illuminate\Support\Collection|
     *                                               array
     */
    public function query()
    {
        if ($model = $this->model) {
            return new $model();
        }
    }

    /**
     * The method that contains the card information.
     * Each item from the query will pass through this method and be transformed according to it's specs.
     *
     * @param mixed $item An item from the query
     *
     * @return array|Kompo\Card
     */
    /*public function card($item) //commented out to override with any parameter desired
    {
        return [];
    }*/

    //Query filters
    public function top()
    {
        return [];
    }

    public function bottom()
    {
        return [];
    }

    public function left()
    {
        return [];
    }

    public function right()
    {
        return [];
    }

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
     * Assigns the page for browsing the query.
     *
     * @param int|null $currentPage (optional) The current query page number.
     *
     * @return int
     */
    public function currentPage($currentPage = null)
    {
        return $this->_kompo('currentPage', $currentPage) ?: 1; //If null, we are on the first page.
    }

    /**
     * Shortcut method to render a Query into it's Vue component.
     *
     * @return string
     */
    public function renderNonStatic()
    {
        return QueryBooter::renderVueComponent($this);
    }

    /**
     * Shortcut method to boot a Query for display.
     *
     * @return string
     */
    public function bootNonStatic()
    {
        return QueryBooter::bootForDisplay($this);
    }
}

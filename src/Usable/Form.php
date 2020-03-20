<?php

namespace Kompo;

use Kompo\Core\ValidationManager;
use Kompo\Komposers\Form\FormBooter;
use Kompo\Komposers\Form\HasModel;
use Kompo\Komposers\Komposer;
use Kompo\Routing\Router;

abstract class Form extends Komposer
{
	use HasModel;

    public $component = 'Rows';
    public $menuComponent = 'Form';
    public $partial = 'VlForm';
    
    public $noMargins = false;

    protected $preventSubmit = false; //prevent submitting a form (emits only)
    protected $emitFormData = true;

    protected $submitTo = null; //if the route is simple (no parameters)
    protected $submitMethod = 'POST';
    //protected $failedAuthorizationMessage; //still active but removed so we can extend in Trait

    protected $redirectTo = null;
    protected $redirectMessage = 'Success! Redirecting...';


    /**
     * The model's namespace that the form links to.
     *
     * @var string
     */
    public $model;

    /**
     * Stores the form components.
     *
     * @var array
     */
    public $components = [];

	/**
     * Constructs a Form
     * 
     * @param null|int|string $modelKey (optional) The record's key or id in the DB table.
     * @param null|array $store (optional) Additional data passed to the komponent.
     *
     * @return self
     */
	public function __construct($modelKey = null, $store = [], $dontBoot = false)
	{
		if(!$dontBoot)
        	FormBooter::bootForDisplay(
                $this, 
                $modelKey, 
                $store,
                Router::getRouteParameters(request()),
                optional(request()->route())->uri(),
                optional(request()->route())->methods()[0]
            );
	}

    /**
     * Get the Components displayed in the form.
     *
     * @return array
     */
    public function components()
    {
        return [];
    }

    /**
     * Get the request's validation rules.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Get the Form's post url - Override to set.
     *
     * @return string
     */
    public function submitUrl()
    {
        return '';
    }

    /**
     * Prepares the components of the Form when included in another Komposer.
     *
     * @var array
     */
    public function prepareForDisplay($komposer)
    {
        ValidationManager::addRulesToKomposer($this->data('rules'), $komposer);

        if($komposer->noMargins ?? false)
            $this->noMargins();
    }

}

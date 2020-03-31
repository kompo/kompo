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

    /**
     * The vue component to render the Form.
     *
     * @var        string
     */
    public $component = 'Rows'; //--> TODO: move to data
    public $menuComponent = 'Form'; //--> TODO: move to data
    
    /**
     * Disable adding default margins for Form components.
     *
     * @var boolean
     */
    public $noMargins = false;

    /**
     * Prevent emitting the form data to it's closest parent.
     *
     * @var boolean
     */
    public $emitFormData = true;

    /**
     * Prevent submitting a form.
     *
     * @var boolean
     */
    protected $preventSubmit = false;

    /**
     * Custom submit route for quick use (if the route has no parameters)
     *
     * @var string
     */
    protected $submitTo; //if the route is simple (no parameters)

    /**
     * Custom submit method (POST|PUT) for quick use.
     *
     * @var string
     */
    protected $submitMethod = 'POST';

    /**
     * Custom redirect route for quick use (for simple route with no parameters).
     *
     * @var string
     */
    protected $redirectTo;

    /**
     * Default redirect message after successful submit (or translation key if multi-language app).
     *
     * @var string
     */
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
    public $components = [];  //--> TODO: move to data

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
        $this->_kompo('options', [
            'preventSubmit' => $this->preventSubmit,
            'submitTo' => $this->submitTo,
            'submitMethod' => $this->submitMethod,
            'redirectTo' => $this->redirectTo,
            'redirectMessage' => $this->redirectMessage
        ]);

		if(!$dontBoot)
        	FormBooter::bootForDisplay($this, $modelKey, $store);
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

    /**
     * Prepares the components of the Form when included in another Komposer.
     *
     * @var array
     */
    public function prepareForAction($komposer)
    {
        ValidationManager::addRulesToKomposer($this->data('rules'), $komposer);
    }


    /**
     * Shortcut method to render a Form into it's Vue component.
     *
     * @return string
     */
    public static function render($modelKey = null, $store = [])
    {
        return FormBooter::renderVueComponent(new static($modelKey, $store));
    }

}

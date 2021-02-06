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
     * The Vue komposer tag.
     *
     * @var string
     */
    public $vueKomposerTag = 'vl-form';

    /**
     * The Blade component to render the Form.
     *
     * @var string
     */
    public $bladeComponent = 'Form';
    
    /**
     * Disable adding default margins for Form komponents.
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
     * Default alert error message after a validation error (422) (use as translation key if multi-language app).
     * To deactivate, set to false, null or empty.
     *
     * @var null|string
     */
    protected $validationErrorAlert = 'Please correct the errors';

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
     * Stores the form komponents.
     *
     * @var array
     */
    public $komponents = [];
    
    /**
     * If you wish to reload the form after submit/saving the model, set to true.
     *
     * @var boolean
     */
    protected $refresh = false; //TODO: rename $refreshAfterSubmit which is clearer

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
            'validationErrorAlert' => $this->validationErrorAlert,
            'redirectTo' => $this->redirectTo,
            'redirectMessage' => $this->redirectMessage,
            'refresh' => $this->refresh
        ]);
        
        if(Router::shouldNotBeBooted()) return; //request has not been handled yet

		if(!$dontBoot)
        	FormBooter::bootForDisplay($this, $modelKey, $store);
	}

    /**
     * Get the Komponents displayed in the form.
     *
     * @return array
     */
    public function komponents()
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
     * Overriden method to set the Form's submit url.
     *
     * @return string
     */
    public function submitUrl()
    {
        return '';
    }

    /**
     * Prepares the komponents of the Form when included in another Komposer.
     *
     * @var array
     */
    public function prepareForDisplay($komposer)
    {
        ValidationManager::addRulesToKomposer($this->config('rules'), $komposer); 
    }

    /**
     * Prepares the komponents of the Form when included in another Komposer.
     *
     * @var array
     */
    public function prepareForAction($komposer)
    {
        if($komposer instanceOf self) //Cuz in Query filters, Forms would pass their rules to browse & sort actions
            ValidationManager::addRulesToKomposer($this->config('rules'), $komposer); 
    }


    /**
     * Shortcut method to render a Form into it's Vue component.
     *
     * @return string
     */
    public static function renderStatic($modelKey = null, $store = [])
    {
        return with(new static($modelKey, $store))->render();
    }

    /**
     * Shortcut method to render a Form into it's Vue component.
     *
     * @return string
     */
    public function renderNonStatic()
    {
        return FormBooter::renderVueComponent($this);
    }

    /**
     * Methods that can be called both statically or non-statically
     *
     * @return array
     */
    public static function duplicateStaticMethods()
    {
        return ['render'];
    }

    /**
     * Displays an encoded version of the Form.
     * Hides (or not) the public $model property before displaying or returning response.
     * Mostly, useful when echoing in blade for example.
     *
     * @return string
     */
    public function __toString()
    {
        if(
            (property_exists($this, 'hideModel') && $this->hideModel) || 
            (!property_exists($this, 'hideModel') && config('kompo.eloquent_form.hide_model_in_forms'))
        )
            unset($this->model);

        return json_encode($this);
    }

}

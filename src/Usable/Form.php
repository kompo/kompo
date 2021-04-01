<?php

namespace Kompo;

use Kompo\Core\ValidationManager;
use Kompo\Komposers\Form\FormBooter;
use Kompo\Komposers\Form\HasModel;
use Kompo\Komposers\Komposer;

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
     * @var bool
     */
    public $noMargins = false;

    /**
     * Prevent emitting the form data to it's closest parent.
     *
     * @var bool
     */
    public $emitFormData = true;

    /**
     * Prevent submitting a form.
     *
     * @var bool
     */
    protected $preventSubmit = false;

    /**
     * Custom submit route for quick use (if the route has no parameters).
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
     * @var bool
     */
    protected $refresh = false; //TODO: rename $refreshAfterSubmit which is clearer

    /**
     * Constructs a Form.
     *
     * @param null|int|string $modelKey (optional) The record's key or id in the DB table.
     * @param null|array      $store    (optional) Additional data passed to the komponent.
     *
     * @return self
     */
    public function __construct($modelKey = null, $store = [])
    {
        parent::__construct();

        $this->_kompo('options', [
            'preventSubmit'        => $this->preventSubmit,
            'submitTo'             => $this->submitTo,
            'submitMethod'         => $this->submitMethod,
            'validationErrorAlert' => $this->validationErrorAlert,
            'redirectTo'           => $this->redirectTo,
            'redirectMessage'      => $this->redirectMessage,
            'refresh'              => $this->refresh,
        ]);

        if (is_array($modelKey)) { //Allow permutation of arguments
            $newStore = $modelKey;
            $modelKey = is_array($store) ? null : $store;
            $store = $newStore;
        }

        $this->store($store);
        $this->modelKey($modelKey);

        if(KompoServiceProvider::$bootFlag)
            $this->boot();
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
        parent::prepareForDisplay($komposer);
        
        ValidationManager::addRulesToKomposer($this->config('rules'), $komposer);
    }

    /**
     * Prepares the komponents of the Form when included in another Komposer.
     *
     * @var array
     */
    public function prepareForAction($komposer)
    {
        parent::prepareForAction($komposer);

        if ($komposer instanceof self) { //Cuz in Query filters, Forms would pass their rules to browse & sort actions
            ValidationManager::addRulesToKomposer($this->config('rules'), $komposer);
        }
    }

    /**
     * Shortcut method to render a Form into it's Vue component.
     *
     * @return string
     */
    public static function renderStatic($modelKey = null, $store = [])
    {
        return static::boot($modelKey, $store)->render();
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
     * Shortcut method to boot a Form for display.
     *
     * @return string
     */
    public static function bootStatic($modelKey = null, $store = [])
    {
        return with(new static($modelKey, $store))->boot();
    }

    /**
     * Shortcut method to boot a Form for display.
     *
     * @return string
     */
    public function bootNonStatic()
    {
        return FormBooter::bootForDisplay($this);
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
        if (
            (property_exists($this, 'hideModel') && $this->hideModel) ||
            (!property_exists($this, 'hideModel') && config('kompo.eloquent_form.hide_model_in_forms'))
        ) {
            unset($this->model);
        }

        return parent::__toString();
    }
}

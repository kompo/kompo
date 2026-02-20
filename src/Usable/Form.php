<?php

namespace Kompo;

use Kompo\Core\AuthorizationGuard;
use Kompo\Core\ValidationManager;
use Kompo\Komponents\Form\FormDisplayer;
use Kompo\Komponents\Form\FormSubmitter;
use Kompo\Komponents\Form\HasModel;
use Kompo\Komponents\Komponent;
use Kompo\Komponents\KomponentManager;
use Kompo\Routing\RouteFinder;

abstract class Form extends Komponent
{
    use HasModel;

    /**
     * The Vue komponent tag.
     *
     * @var string
     */
    public $vueKomponentTag = 'vl-form';

    /**
     * The Blade component to render the Form.
     *
     * @var string
     */
    public $bladeComponent = 'Form';

    /**
     * Disable adding default margins for Form elements.
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
     * Include fields from nested Komponents in the form submission data.
     *
     * @var bool
     */
    public $nestedFields = false;

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
     * Checks if the model exists in DB <=> if Form is doing an INSERT/UPDATE operation.
     *
     * @var bool
     */
    public $modelExists = false;

    /**
     * Stores the form elements.
     *
     * @var array
     */
    public $elements = [];

    /**
     * If you wish to reload the form after submit/saving the model, set to true.
     *
     * @var bool
     */
    protected $refresh = false; //TODO: rename $refreshAfterSubmit which is clearer

    /**
     * If you wish to reload the form after submit/saving the model, set to true.
     *
     * @var bool
     */
    public $refreshAfterSubmit = false;

    /**
     * Constructs a Form.
     *
     * @param null|int|string $modelKey (optional) The record's key or id in the DB table.
     * @param null|array      $store    (optional) Additional data passed to the Komponent.
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

        if (app('bootFlag')) {
            $this->boot();
        }
    }

    /**
     * Get the elements displayed in the form.
     *
     * @return array|\Kompo\Elements\Element
     */
    public function render()
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
     * Prepares the elements of the Form when included in another Komponent.
     *
     * @var array
     */
    public function prepareForDisplay($komponent)
    {
        parent::prepareForDisplay($komponent);

        ValidationManager::addRulesToKomponent($this->config('rules'), $komponent);
    }

    /**
     * Prepares the elements of the Form when included in another Komponent.
     *
     * @var array
     */
    public function prepareForAction($komponent)
    {
        parent::prepareForAction($komponent);

        if ($komponent instanceof self) { //Cuz in Query filters, Forms would pass their rules to browse & sort actions
            ValidationManager::addRulesToKomponent($this->config('rules'), $komponent);
        }
    }

    /**
     * Calls a custom method on form submit.
     *
     * @return mixed
     */
    public function callCustomHandle()
    {
        return FormSubmitter::callCustomHandle($this);
    }

    /**
     * Calls the internal kompo eloquent saving mechanism on form submit.
     *
     * @return mixed
     */
    public function eloquentSave()
    {
        return FormSubmitter::eloquentSave($this);
    }

    /**
     * Set a redirect url after the form submit
     *
     * @param string $route
     * @param mixed  $parameters
     * 
     * @return mixed
     */
    public function kompoRedirectTo($route, $parameters = null)
    {
        return [
            'kompoRedirectTo' => RouteFinder::guessRoute($route, $parameters),
        ];
    }

    /**
     * Shortcut method to output the Komponent into it's HTML Vue tag.
     *
     * @return string
     */
    public static function toHtmlStatic($modelKey = null, $store = [])
    {
        return static::boot($modelKey, $store)->toHtml();
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
     * Initial boot of a Form Komponent for display.
     *
     * @return self
     */
    public function bootForDisplay($routeParams = null)
    {
        $this->parameter($routeParams ?: RouteFinder::getRouteParameters());

        $this->setModel($this->model);

        AuthorizationGuard::checkBoot($this, 'Display');

        FormDisplayer::displayElements($this);

        KomponentManager::booted($this);

        return $this;
    }

    /**
     * Subsequent boot of a Form Komponent for a later action (i.e. submit, Ajax request, etc...)
     *
     * @return self
     */
    public function bootForAction()
    {
        $this->setModel($this->model);

        AuthorizationGuard::checkBoot($this, 'Action');

        ValidationManager::addRulesToKomponent($this->rules(), $this);

        $this->prepareOwnElementsForAction($this->render()); //mainly to retrieve rules from fields

        return $this;
    }

    /**
     * Constructing a Form from an array of parameters
     *
     * @param  array  $info  The parameters
     *
     * @return  self
     */
    public static function constructFromArray($info)
    {
        return is_string($komponent = $info['kompoClass']) ? new $komponent($info['modelKey'], $info['store']) : $komponent;
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

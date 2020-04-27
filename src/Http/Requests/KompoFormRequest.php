<?php

namespace Kompo\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Kompo\Core\AuthorizationGuard;
use Kompo\Routing\Dispatcher;

class KompoFormRequest extends FormRequest
{
    /**
     * The request's Komposer.
     *
     * @var Kompo\Komposers\Komposer
     */
    protected $komposer;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->komposer = Dispatcher::bootForAction();

        return AuthorizationGuard::mainGate($this->komposer);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Gets the failed authorization message.
     *
     * @return     Illuminate\Auth\Access\AuthorizationException
     */
    protected function failedAuthorization()
    {
        if(method_exists($this->komposer, 'failedAuthorization'))
            return $this->komposer->failedAuthorization();

        if($this->komposer->getFailedAuthorizationMessage())
            throw new AuthorizationException($this->komposer->getFailedAuthorizationMessage());

        parent::failedAuthorization(); 
    }

}

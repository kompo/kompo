<?php

namespace Kompo\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Kompo\Core\AuthorizationGuard;
use Kompo\Core\ValidationManager;
use Kompo\Routing\Dispatcher;

class KompoFormRequest extends FormRequest
{
    /**
     * The request's Komponent.
     *
     * @var Kompo\Komponents\Komponent
     */
    protected $komponent;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->komponent = Dispatcher::bootKomponentForAction();

        return AuthorizationGuard::mainGate($this->komponent);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return ValidationManager::getRules($this->komponent);
    }
}

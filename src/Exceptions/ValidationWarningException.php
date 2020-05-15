<?php

namespace Kompo\Exceptions;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ValidationWarningException extends ValidationException
{
    /**
     * The status code to use for the response.
     * Retry with status code taken from: https://www.restapitutorial.com/httpstatuscodes.html
     * @var int
     */
    public $status = 449;

    public function __construct($message)
    {
        parent::__construct($message);

        $this->response = new Response($message, $this->status);
        //$this->errorBag = $errorBag;
        $this->validator = Validator::make([], []);
    }
    
}

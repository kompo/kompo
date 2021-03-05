<?php

namespace Kompo\Tests\Feature\Authorization;

use Illuminate\Auth\Access\AuthorizationException;
use Kompo\Core\KompoInfo;
use Kompo\Exceptions\NotFoundKompoActionException;
use Kompo\Http\Requests\KompoFormRequest;
use Kompo\Tests\EnvironmentBoot;

class SubmitAuthorizationTest extends EnvironmentBoot
{
    /** @test */
    public function submit_is_unauthorized_for_eloquent_forms()
    {
        $this->assert_unauthorized_submit(new _SubmitUnauthorizedEloquentForm());
    }

    /** @test */
    public function submit_is_unauthorized_for_handle_forms()
    {
        $this->assert_unauthorized_submit(new _SubmitUnauthorizedHandleForm());
    }

    /** @test */
    public function submit_cannot_be_handled_for_traditional_forms()
    {
        $this->expectException(NotFoundKompoActionException::class);

        $this->withoutExceptionHandling()->submit(new _SubmitUnauthorizedSubmitToForm());
    }

    /** @test */
    public function submit_unauthorized_in_controller_for_traditional_forms()
    {
        \Route::post('submit-route', function (KompoFormRequest $request) {});

        $this->expectException(AuthorizationException::class);

        $this->withoutExceptionHandling()->withHeaders(
            KompoInfo::arrayFromElement(new _SubmitUnauthorizedSubmitToForm())
        )->post('submit-route');
    }

    /** ------------------ PRIVATE --------------------------- */
    private function assert_unauthorized_submit($objClass)
    {
        $this->expectException(AuthorizationException::class);

        $this->withoutExceptionHandling()->submit($objClass);
    }
}

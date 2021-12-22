<?php

namespace Kompo\Tests\Feature\Routing;

use Kompo\Tests\EnvironmentBoot;

class KomponentRouteAsJsonTest extends EnvironmentBoot
{
    /** @test */
    public function boot_form_from_route_as_json()
    {
        $this->prepareRoute(_RouteParametersForm::class);

        $this->make_route_assertions()
            ->assertJson(['modelKey' => 1]);
    }

    /** @test */
    public function boot_query_from_route_as_json()
    {
        $this->prepareRoute(_RouteParametersQuery::class);

        $this->make_route_assertions();
    }

    /** @test */
    public function boot_menu_from_route_as_json()
    {
        $this->prepareRoute(_RouteParametersMenu::class);

        $this->make_route_assertions();
    }

    /** ------------------ PRIVATE --------------------------- */
    private function prepareRoute($objClass)
    {
        \Route::get('test/{id}', $objClass);
    }

    private function make_route_assertions()
    {
        return $this->get('test/1')->assertJson([
            'id'         => 'obj-id',
            'parameters' => ['id' => 1],
        ]);
    }
}

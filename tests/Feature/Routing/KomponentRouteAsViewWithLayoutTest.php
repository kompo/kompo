<?php

namespace Kompo\Tests\Feature\Routing;

use Kompo\Tests\EnvironmentBoot;

class KomponentRouteAsViewWithLayoutTest extends EnvironmentBoot
{
    /** @test */
    public function boot_form_from_route_as_view()
    {
        $this->prepareRoute(_RouteParametersForm::class);

        $this->make_route_assertions('vl-form')
            ->assertSee('"modelKey":"1"');
    }

    /** @test */
    public function boot_query_from_route_as_view()
    {
        $this->prepareRoute(_RouteParametersQuery::class);

        $this->make_route_assertions('vl-query');
    }

    /** @test */
    public function boot_menu_from_route_as_view()
    {
        $this->prepareRoute(_RouteParametersMenu::class);

        $this->make_route_assertions('vl-menu');
    }

    /** ------------------ PRIVATE --------------------------- */
    private function prepareRoute($komponentClass)
    {
        \Route::layout('kompo::app')->group(function () use ($komponentClass) {
            \Route::get('test/{id}', $komponentClass);
        });
    }

    private function make_route_assertions($vueComponent)
    {
        return $this->get('test/1')
            ->assertViewIs('kompo::view')
            ->assertViewHas('vueComponent')
            ->assertViewHas('metaTags', [
                'title'       => 'meta-title',
                'description' => 'meta description',
                'keywords'    => 'key,word',
            ])
            ->assertViewHas('layout', 'kompo::app')
            ->assertViewHas('section', 'content')
            ->assertSee($vueComponent.' :vkompo=')
            ->assertSee('"id":"obj-id"')
            ->assertSee('"parameters":{"id":"1"}')
            ->assertSee('meta-title')
            ->assertSee('meta description')
            ->assertSee('key,word');
    }
}

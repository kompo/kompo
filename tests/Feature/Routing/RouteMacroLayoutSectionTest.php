<?php

namespace Kompo\Tests\Feature\Routing;

use Kompo\Exceptions\RouteLayoutIncorrectlySetException;
use Kompo\Tests\EnvironmentBoot;

class RouteMacroLayoutSectionTest extends EnvironmentBoot
{
    protected $_form = _RouteParametersForm::class;

    /** @test */
    public function macros_work_when_used_in_route_group()
    {
        \Route::group(['layout' => 'kompo::app'], function () {
            \Route::get('test1', $this->_form); //yes
            \Route::get('test2', $this->_form)->section('content'); //yes
            \Route::get('test3', $this->_form)->section('inexistant-content'); //no
        });

        \Route::group(['layout' => 'test::layouts', 'section' => 'content1'], function () {
            \Route::get('test4', $this->_form); //yes
            \Route::get('test5', $this->_form)->section('content1'); //yes
            \Route::get('test6', $this->_form)->section('inexistant-content'); //no

            \Route::group(['layout' => 'custom', 'section' => 'inexistant-content'], function () {
                \Route::get('test7', $this->_form); //no
                \Route::get('test8', $this->_form)->section('content2'); //yes
            });
        });

        $this->make_assertions();
    }

    /** @test */
    public function macros_work_when_called_directly()
    {
        \Route::layout('kompo::app')->group(function () {
            \Route::get('test1', $this->_form); //yes
            \Route::get('test2', $this->_form)->section('content'); //yes
            \Route::get('test3', $this->_form)->section('inexistant-content'); //no
        });

        \Route::layout('test::layouts')->section('content1')->group(function () {
            \Route::get('test4', $this->_form); //yes
            \Route::get('test5', $this->_form)->section('content1'); //yes
            \Route::get('test6', $this->_form)->section('inexistant-content'); //no

            \Route::section('inexistant-content')->layout('custom')->group(function () {
                \Route::get('test7', $this->_form); //no
                \Route::get('test8', $this->_form)->section('content2'); //yes
            });
        });

        $this->make_assertions();
    }

    /** @test */
    public function layout_throws_error_when_called_on_a_route()
    {
        $this->expectException(RouteLayoutIncorrectlySetException::class);

        \Route::get('test9', $this->_form)->layout('test::layouts');
    }

    /** ------------------ PRIVATE --------------------------- */
    private function make_assertions()
    {
        $expectedText = 'obj-id';

        $this->view_assertions($this->get('test1'), 'kompo::app', 'content')
            ->assertSee($expectedText);
        $this->view_assertions($this->get('test2'), 'kompo::app', 'content')
            ->assertSee($expectedText);
        $this->view_assertions($this->get('test3'), 'kompo::app', 'inexistant-content')
            ->assertDontSee($expectedText); //Don't see

        $this->view_assertions($this->get('test4'), 'test::layouts', 'content1')
            ->assertSee($expectedText);
        $this->view_assertions($this->get('test5'), 'test::layouts', 'content1')
            ->assertSee($expectedText);
        $this->view_assertions($this->get('test6'), 'test::layouts', 'inexistant-content')
            ->assertDontSee($expectedText); //Don't see

        $this->view_assertions($this->get('test7'), 'test::layouts.custom', 'inexistant-content')
            ->assertDontSee($expectedText); //Don't see
        $this->view_assertions($this->get('test8'), 'test::layouts.custom', 'content2')
            ->assertSee($expectedText);
    }

    private function view_assertions($response, $expectedLayout, $expectedSection)
    {
        $response->assertViewIs('kompo::view')
            ->assertViewHas('vueComponent')
            ->assertViewHas('layout', $expectedLayout)
            ->assertViewHas('section', $expectedSection);

        return $response;
    }
}

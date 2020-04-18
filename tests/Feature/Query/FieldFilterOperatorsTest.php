<?php

namespace Kompo\Tests\Feature\Query;

use Kompo\Exceptions\FilterOperatorNotAllowedException;
use Kompo\Exceptions\NotFilterCapableException;
use Kompo\Input;
use Kompo\Interactions\Action;
use Kompo\Link;
use Kompo\Tests\EnvironmentBoot;

class FieldFilterOperatorsTest extends EnvironmentBoot
{
	/** @test */
	public function filtering_throws_exception_on_non_fields()
	{
		$this->expectException(NotFilterCapableException::class);

		Link::form()->filter();
	}

	/** @test */
	public function operator_not_allowed_throws_exception()
	{
		$this->expectException(FilterOperatorNotAllowedException::class);

		Input::form()->filter('RESEMBLES');
	}

	/** @test */
	public function all_allowed_operators_are_correctly_assigned()
	{
		foreach ($ops = Action::getAllowedOperators() as $operator) {
			$els[] = Input::form()->filter($operator);
		}

		foreach ($els as $key => $el) {
			$this->assertEquals($ops[$key], $el->data('filterOperator'));
		}
	}


}
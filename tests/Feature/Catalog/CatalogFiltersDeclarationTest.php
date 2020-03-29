<?php

namespace Kompo\Tests\Feature\Catalog;

use Kompo\Tests\EnvironmentBoot;

class CatalogFiltersDeclarationTest extends EnvironmentBoot
{
    public $filtersPlacement = [ 'top', 'left', 'bottom', 'right' ];

	/** @test */
	public function filters_and_subfilters_are_arrays()
	{
		$catalog = new _FiltersReturnTypes();

		$this->assertIsArray($catalog->filters);

		foreach ($this->filtersPlacement as $placement) {
			$this->assertArrayHasKey($placement, $catalog->filters);
			$this->assertIsArray($catalog->filters[$placement]);
		}
	}

	/** @test */
	public function null_components_are_removed_in_filters()
	{
		$catalog = new _FiltersReturnTypes();

		$this->assertCount(1, $catalog->filters['top']);
		$this->assertCount(1, $catalog->filters['right']);
		$this->assertCount(1, $catalog->filters['bottom']);
		$this->assertCount(0, $catalog->filters['left']);

	}

}
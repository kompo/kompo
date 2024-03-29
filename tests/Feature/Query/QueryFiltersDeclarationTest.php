<?php

namespace Kompo\Tests\Feature\Query;

use Kompo\Tests\EnvironmentBoot;

class QueryFiltersDeclarationTest extends EnvironmentBoot
{
    public $filtersPlacement = ['top', 'left', 'bottom', 'right'];

    /** @test */
    public function filters_and_subfilters_are_arrays()
    {
        $query = _FiltersReturnTypes::boot();

        $this->assertIsArray($query->filters);

        foreach ($this->filtersPlacement as $placement) {
            $this->assertArrayHasKey($placement, $query->filters);
            $this->assertIsArray($query->filters[$placement]);
        }
    }

    /** @test */
    public function null_elements_are_removed_in_filters()
    {
        $query = _FiltersReturnTypes::boot();

        $this->assertCount(1, $query->filters['top']);
        $this->assertCount(1, $query->filters['right']);
        $this->assertCount(1, $query->filters['bottom']);
        $this->assertCount(0, $query->filters['left']);
    }
}

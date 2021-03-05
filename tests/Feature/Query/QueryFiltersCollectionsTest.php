<?php

namespace Kompo\Tests\Feature\Query;

use Kompo\Tests\EnvironmentBoot;

class QueryFiltersCollectionsTest extends EnvironmentBoot
{
    protected $getFromArray = false;

    /** @test */
    public function filters_work_for_collection()
    {
        $this->_checkAllFilters(new _QueryCollection());
    }

    /** @test */
    public function filters_work_for_simple_array()
    {
        $this->_checkAllFilters(new _QueryArray());
    }

    /** @test */
    public function filters_work_for_assoc_array()
    {
        $this->_checkAllFilters(new _QueryAssocArray());
    }

    /** @test */
    public function filters_work_for_array_of_arrays()
    {
        $this->getFromArray = true;
        $this->_checkAllFilters(new _QueryArrayOfArrays());
    }

    /** @test */
    public function filters_work_for_array_of_objects()
    {
        $this->getFromArray = true; //because object gets decoded as array in JSON response
        $this->_checkAllFilters(new _QueryArrayOfObjs());
    }

    /** @test */
    public function edge_cases_filters_work_for_array_of_arrays()
    {
        $response = $this->browse(new _QueryArrayOfArrays(), ['non-existing' => 'rav'])->assertStatus(200);

        $items = $response->decodeResponseJson()['data'];
        $this->assertCount(0, $items);
    }

    /** @test */
    public function edge_cases_filters_work_for_array_of_objects()
    {
        $response = $this->browse(new _QueryArrayOfObjs(), ['non-existing' => 'rav'])->assertStatus(200);

        $items = $response->decodeResponseJson()['data'];
        $this->assertCount(0, $items);
    }

    /**** PRIVATE ****/

    private function _checkAllFilters($query)
    {
        $this->_checkFilter($query, ['input' => 'rav'], ['bravo']);
        $this->_checkFilter($query, ['select' => 'bravo'], ['bravo']);
        $this->_checkFilter($query, ['multiselect' => ['bravo', 'charlie']], ['bravo', 'charlie']);
        $this->_checkFilter($query, ['equal' => 'echo'], ['echo']); //Caps sensitive TODO: add ability to customize
        $this->_checkFilter($query, ['greater' => 'india'], ['india', 'juliett']);
        $this->_checkFilter($query, ['lower' => 'c'], ['alpha', 'bravo']);
        $this->_checkFilter($query, ['like' => 'ravo'], ['bravo']);
        $this->_checkFilter($query, ['startswith' => 'fox'], ['foxtrot']);
        $this->_checkFilter($query, ['endswith' => 'ho'], ['echo']);
        $this->_checkFilter($query, ['between' => ['c', 'e']], ['charlie', 'delta']);
        $this->_checkFilter($query, ['in' => ['alpha', 'bravo', 'something else']], ['alpha', 'bravo']);
    }

    private function _checkFilter($query, array $input, array $expected)
    {
        $response = $this->browse($query, $input)->assertStatus(200);

        $items = $response->decodeResponseJson()['data'];

        $this->assertCount(count($expected), $items);
        foreach ($items as $key => $item) {
            $value = $item['render']['komponents']['value'];

            if ($this->getFromArray) {
                $value = $value[array_key_first($input)];
            }

            $this->assertEquals($expected[$key], $value);
        }
    }
}

<?php

namespace Kompo\Tests\Feature\Select;

use Kompo\IconText;
use Kompo\Tests\EnvironmentBoot;
use Kompo\Tests\Models\File;
use Kompo\Tests\Models\Tag;
use Kompo\Tests\Models\User;

class SelectOptionsTransformationTest extends EnvironmentBoot
{
    /** @test */
    public function array_options_are_transformed_into_label_value_array()
    {
        $form = _SelectAttributeFillsForm::boot();

        $this->assert_array_options_are_transformed_into_label_value_array($form->options(), $form->elements);
    }

    /** @test */
    public function plucked_options_are_transformed_into_label_value_array()
    {
        factory(Tag::class, 6)->create();
        $form = _SelectAttributeFillsForm::boot(null, ['optionsMethod' => 'Tags']);

        $this->assert_array_options_are_transformed_into_label_value_array(Tag::pluck('name', 'id'), $form->elements);
    }

    /** @test */
    public function custom_label_options_are_transformed_into_label_value_array()
    {
        $form = _SelectAttributeFillsForm::boot(null, ['optionsMethod' => 'Cards']);

        foreach ($form->elements as $key => $element) {
            $options = $element->options;

            $this->assertCount(5, $options);
            $this->assertTrue($options[0]['label'] instanceof IconText);
            $this->assertEquals(1, $options[0]['value']);
            $this->assertEquals('Option 1', $options[0]['label']->elements['text']);
            $this->assertEquals(4, $options[3]['value']);
            $this->assertEquals('Option 4', $options[3]['label']->elements['text']);
        }
    }

    /** @test */
    public function options_are_loaded_from_relationships_with_optionsFrom()
    {
        factory(User::class, 4)->create();
        $users = User::get();
        $usersOrdered = User::orderBy('name')->get();
        $usersFiltered = User::where('name', '<', 'm')->get();
        $files = factory(File::class, 3)->create();
        $filesOrdered = File::orderBy('name')->get();
        $filesFiltered = File::where('name', '<', 'm')->get();

        $form = _SelectOptionsFromForm::boot();
        $opts = function ($index) use ($form) { return $form->elements[$index]->options; };

        //belongsTo
        $this->assert_relationships_options_are_loaded_ordered_or_filtered($users, $opts(0));
        $this->assert_relationships_options_are_loaded_ordered_or_filtered($usersOrdered, $opts(1));
        $this->assert_relationships_options_are_loaded_ordered_or_filtered($usersFiltered, $opts(2));
        //belongsToMany
        $this->assert_relationships_options_are_loaded_ordered_or_filtered($files, $opts(3));
        $this->assert_relationships_options_are_loaded_ordered_or_filtered($filesOrdered, $opts(4));
        $this->assert_relationships_options_are_loaded_ordered_or_filtered($filesFiltered, $opts(5));
        //morphTo
        $this->assert_relationships_options_are_loaded_ordered_or_filtered($users, $opts(6));
        $this->assert_relationships_options_are_loaded_ordered_or_filtered($usersOrdered, $opts(7));
        $this->assert_relationships_options_are_loaded_ordered_or_filtered($usersFiltered, $opts(8));
        //morphToMany
        $this->assert_relationships_options_are_loaded_ordered_or_filtered($files, $opts(9));
        $this->assert_relationships_options_are_loaded_ordered_or_filtered($filesOrdered, $opts(10));
        $this->assert_relationships_options_are_loaded_ordered_or_filtered($filesFiltered, $opts(11));
        //morphedByMany
        $this->assert_relationships_options_are_loaded_ordered_or_filtered($files, $opts(12));
        $this->assert_relationships_options_are_loaded_ordered_or_filtered($filesOrdered, $opts(13));
        $this->assert_relationships_options_are_loaded_ordered_or_filtered($filesFiltered, $opts(14));
    }

    /** @test */
    public function options_labels_are_transformed_with_closures()
    {
        factory(User::class, 4)->create();
        $users = User::orderBy('name')->get();

        $form = _SelectOptionsFromForm::boot();

        //Card
        $this->assertEquals(count($users), count($opts = $form->elements[15]->options));
        foreach ($opts as $key => $opt) {
            $this->assertEquals($users[$key]->id, $opt['value']);
            $this->assertTrue($opt['label'] instanceof IconText);
            $this->assertEquals(strtoupper($users[$key]->name), $opt['label']->elements['text']);
            $this->assertEquals('icon-location', $opt['label']->elements['icon']);
        }

        //Array
        $this->assertEquals(count($users), count($opts = $form->elements[16]->options));
        foreach ($opts as $key => $opt) {
            $this->assertEquals($users[$key]->id, $opt['value']);
            $this->assertCount(2, $opt['label']);
            $this->assertEquals(strtoupper($users[$key]->name), $opt['label']['text']);
            $this->assertEquals('icon-location', $opt['label']['icon']);
        }

        //Closure
        $this->assertEquals(count($users), count($opts = $form->elements[17]->options));
        foreach ($opts as $key => $opt) {
            $this->assertEquals($users[$key]->id, $opt['value']);
            $this->assertEquals(strtoupper($users[$key]->name), $opt['label']);
        }
    }

    /** ------------------ PRIVATE --------------------------- */
    private function assert_array_options_are_transformed_into_label_value_array($opts0, $formElements)
    {
        foreach ($formElements as $element) {
            $this->assertEquals(count($opts0), count($element->options));

            foreach ($element->options as $key => $opt) {
                $this->assertEquals($key + 1, $opt['value']);
                $this->assertEquals($opts0[$key + 1], $opt['label']);
            }
        }
    }

    private function assert_relationships_options_are_loaded_ordered_or_filtered($opts0, $opts1)
    {
        $this->assertEquals(count($opts0), count($opts1));

        foreach ($opts1 as $key => $opt) {
            $this->assertEquals($opts0[$key]->id, $opt['value']);
            $this->assertEquals($opts0[$key]->name, $opt['label']);
        }
    }
}

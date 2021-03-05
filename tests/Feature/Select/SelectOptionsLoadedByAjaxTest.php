<?php

namespace Kompo\Tests\Feature\Select;

use Kompo\Exceptions\KomposerMethodNotFoundException;
use Kompo\Tests\EnvironmentBoot;
use Kompo\Tests\Models\File;
use Kompo\Tests\Models\Obj;
use Kompo\Tests\Models\Tag;
use Kompo\Tests\Models\User;

class SelectOptionsLoadedByAjaxTest extends EnvironmentBoot
{
    /** @test */
    public function no_search_method_found_for_retrieving_options()
    {
        $this->expectException(KomposerMethodNotFoundException::class);

        $this->withoutExceptionHandling()->searchOptions(new _SelectAjaxOptionsForm(), null, 'Non existing Method');
    }

    /** @test */
    public function options_are_searched_through_ajax()
    {
        $tags = factory(Tag::class, 6)->create(); //factory ensures they are unique
        $tag = factory(Tag::class)->create(['name' => substr($tags[3]->name, 0, 30)]); // add another similar one

        //Found at least one
        $this->assert_correct_options_searched_through_ajax('anotherMethod', $tags[3], $tag);

        $this->assert_correct_options_searched_through_ajax('searchTags', $tags[3], $tag);

        $this->assert_correct_options_searched_through_ajax('searchTags_cast', $tags[3], $tag);

        //None found
        $this->searchOptions(new _SelectAjaxOptionsForm(), 'Impossible to find name', 'searchTags')
            ->assertStatus(200)
            ->assertJsonCount(0);
    }

    /** @test */
    public function db_values_correctly_loaded_in_select_options()
    {
        $tags = factory(Tag::class, 6)->create(); //factory ensures they are unique
        $users = factory(User::class, 6)->create();
        $file1 = factory(File::class)->create(['name' => 'p.pdf']);
        $file2 = factory(File::class)->create(['name' => 'b.pdf']);
        $files = factory(File::class, 10)->create();

        Obj::unguard();
        $obj = Obj::create([
            'tag'       => $tags[2]->id,
            'tags'      => json_encode([$tags[1]->id, $tags[4]->id]),
            'tags_cast' => [$tags[0]->id, $tags[3]->id],
        ]);
        $obj->belongsToPlain()->associate($users[0]);
        $obj->save();
        $obj->belongsToManyPlain()->sync([$file1->id, $file2->id]);

        $form = new _SelectAjaxOptionsEloquentForm(1);

        $opts = function ($index) use ($form) { return $form->komponents[$index]->options; };

        $this->assertCount(1, $opts(0));
        $this->assert_option_matches_model($tags[2], $opts(0)[0]);

        $this->assertCount(2, $opts(1));
        $this->assert_option_matches_model($tags[1], $opts(1)[0]);
        $this->assert_option_matches_model($tags[4], $opts(1)[1]);

        $this->assertCount(2, $opts(2));
        $this->assert_option_matches_model($tags[0], $opts(2)[0]);
        $this->assert_option_matches_model($tags[3], $opts(2)[1]);

        $this->assertCount(1, $opts(3));
        $this->assert_option_matches_model($users[0], $opts(3)[0]);

        $this->assertCount(2, $opts(4));
        $this->assert_option_matches_model($file1, $opts(4)[0]);
        $this->assert_option_matches_model($file2, $opts(4)[1]);

        $this->assertCount(1, $opts(5));
        $this->assert_option_matches_model($users[0], $opts(5)[0]);

        $this->assertCount(2, $opts(6));
        $this->assert_option_matches_model($file2, $opts(6)[0]);
        $this->assert_option_matches_model($file1, $opts(6)[1]);

        $this->assertCount(1, $opts(7));
        $this->assert_option_matches_model($file2, $opts(7)[0]);
    }

    /** @test */
    public function options_are_loaded_from_field_through_ajax()
    {
        $tags = factory(Tag::class, 2)->create(['category_id' => 1]);
        $tagsM = factory(Tag::class, 3)->create(['category_id' => 2]);
        $users = factory(User::class, 4)->create(['order' => 1]);
        $usersM = factory(User::class, 5)->create(['order' => 2]);
        $files = factory(File::class, 6)->create(['mime_type' => 'pdf']);
        $filesM = factory(File::class, 7)->create(['mime_type' => 'jpg']);

        //Found at least one
        $this->assert_correct_options_loaded_from_field('anotherMethod', 2, $tagsM);
        $this->assert_correct_options_loaded_from_field('searchTags', 2, $tagsM);
        $this->assert_correct_options_loaded_from_field('anotherMethod', 2, $tagsM);

        $this->assert_correct_options_loaded_from_field('getUsers', 2, $usersM);
        $this->assert_correct_options_loaded_from_field('searchFiles', 'jpg', $filesM);

        $this->assert_correct_options_loaded_from_field('getUsers', 2, $usersM);
        $this->assert_correct_options_loaded_from_field('searchFiles', 'jpg', $filesM);
        $this->assert_correct_options_loaded_from_field('searchFiles', 'jpg', $filesM);

        //None found
        $this->searchOptions(new _SelectAjaxOptionsFromFieldForm(), 'not a category id', 'searchTags')
            ->assertStatus(200)
            ->assertJsonCount(0);
    }

    /** ------------------ PRIVATE --------------------------- */
    private function assert_correct_options_searched_through_ajax($method, $tag1, $tag2)
    {
        $this->searchOptions(new _SelectAjaxOptionsForm(), substr($tag1->name, 0, 20), $method)
            ->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJson([
                [
                    'value' => $tag1->id,
                    'label' => $tag1->name,
                ],
                [
                    'value' => $tag2->id,
                    'label' => $tag2->name,
                ],
            ]);
    }

    private function assert_option_matches_model($model, $opt)
    {
        $this->assertEquals($model->id, $opt['value']);
        $this->assertEquals($model->name, $opt['label']);
    }

    private function assert_correct_options_loaded_from_field($method, $search, $matches)
    {
        $this->searchOptions(new _SelectAjaxOptionsFromFieldForm(), $search, $method)
            ->assertStatus(200)
            ->assertJsonCount(count($matches))
            ->assertJson(collect($matches)->map(function ($match) {
                return [
                    'value' => $match->id,
                    'label' => $match->name,
                ];
            })->all());
    }
}

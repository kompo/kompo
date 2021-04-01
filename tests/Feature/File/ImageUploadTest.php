<?php

namespace Kompo\Tests\Feature\File;

use Intervention\Image\Facades\Image;

class ImageUploadTest extends FileEnvironmentBoot
{
    public $modelPath = 'sqlite/objs';

    /** @test */
    public function image_upload_is_resized_for_web()
    {
        $this->compare_uploaded_image_widths('file', 3000, 2000); //validations pass because height is = 1px, so filesize<1kb
    }

    /** @test */
    public function image_upload_web_optimization_is_disabled()
    {
        $this->compare_uploaded_image_widths('image', 3000, 3000); //validations pass because height is = 1px, so filesize<1kb
    }

    /** @test */
    public function image_upload_has_thumbnail()
    {
        $this->compare_uploaded_image_widths('image_cast', 3000, 1500, true); //validations pass because height is = 1px, so filesize<1kb
    }

    /** @test */
    public function image_upload_size_validation_is_working()
    {
        $image1 = $this->createImage(null, 2000, 200); //fail
        $image2 = $this->createImage(null, 2000, 50);  //pass

        $r = $this->submit(_ImageUploadForm::boot(), [
            'image' => $image1,
        ])->assertStatus(422);

        $this->assertCount(1, $r['errors']);

        $r = $this->submit(_ImageUploadForm::boot(), [
            'images' => [$image1, $image2],
        ])->assertStatus(422);

        $this->assertCount(1, $r['errors']); //only 1 error of 2 inputs.
    }

    /************* PRIVATE ***************************/

    private function compare_uploaded_image_widths($column, $w0, $w1, $validateThumb = false)
    {
        $image = $this->createImage(null, $w0);
        $this->assertEquals($w0, Image::make($image)->width());

        $this->submit(_ImageUploadForm::boot(), [
            $column => $image,
        ])->assertStatus(201)
        ->assertJson([
            $column => $this->file_to_array($image, $column, true),
        ]);

        $this->assert_in_storage($image, $column);

        $uploaded = Image::make($this->get_from_storage($image, $column));

        $this->assertEquals($w1, $uploaded->width());

        if ($validateThumb) {
            $this->assert_thumb_in_storage($image, $column);

            $thumb = Image::make($this->get_thumb_from_storage($image, $column));

            $this->assertEquals(200, $thumb->width());
        }

        //To test deletion
        $image2 = $this->createImage(null, $w0);

        $this->submit(_ImageUploadForm::boot(1), [
            $column => $image2,
        ])->assertStatus(200)
        ->assertJson([
            $column => $this->file_to_array($image2, $column, true),
        ]);

        $this->assert_not_in_storage($image, $column);
        $this->assert_in_storage($image2, $column);

        if ($validateThumb) {
            $this->assert_thumb_not_in_storage($image, $column);
            $this->assert_thumb_in_storage($image2, $column);
        }
    }
}

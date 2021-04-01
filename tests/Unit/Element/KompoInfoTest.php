<?php

namespace Kompo\Tests\Unit\Element;

use Illuminate\Support\Facades\Crypt;
use Kompo\Core\KompoId;
use Kompo\Core\KompoInfo;
use Kompo\Tests\EnvironmentBoot;

class KompoInfoTest extends EnvironmentBoot
{
    /** @test */
    public function kompo_info_is_correctly_created_on_komposers()
    {
        $form = _SetElementIdForm::boot();

        $bootInfo = Crypt::decrypt(KompoInfo::getFromElement($form));
        $this->assertNotNull($bootInfo);
        $this->assertEquals(_SetElementIdForm::class, $bootInfo['kompoClass']);

        $form = _SetElementIdForm::boot();
        $bootInfo2 = Crypt::decrypt(KompoInfo::getFromElement($form));
        $this->assertNotNull($bootInfo2);
        $this->assertEquals(_SetElementIdForm::class, $bootInfo['kompoClass']);

        foreach ($bootInfo as $key => $value) {
            if ($key !== KompoId::$key) {
                $this->assertEquals($value, $bootInfo2[$key]);
            }
        }
    }
}

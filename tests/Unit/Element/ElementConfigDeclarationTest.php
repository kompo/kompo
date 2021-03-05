<?php

namespace Kompo\Tests\Unit\Element;

use Kompo\Core\KompoId;
use Kompo\Input;
use Kompo\Tests\EnvironmentBoot;

class ElementConfigDeclarationTest extends EnvironmentBoot
{
    /** @test */
    public function config_is_set_correctly_on_elements()
    {
        $el = Input::form('SomeLabel');

        $this->assertIsArray($el->config);
        $this->assertNotNull(KompoId::getFromElement($el));

        $el->config(['some-key' => 'some-value']);
        $this->assertIsArray($el->config);
        $this->assertEquals('some-value', $el->config['some-key']);

        $el->config([
            'some-key'    => 'another-value',
            'another-key' => ['txt1', 'txt2'],
        ]);
        $this->assertEquals('another-value', $el->config['some-key']);
        $this->assertIsArray($el->config['another-key']);
        $this->assertEquals('txt1', $el->config['another-key'][0]);
        $this->assertEquals('txt2', $el->config['another-key'][1]);

        $el->config([
            'another-key' => ['txt3'],
            0             => 2,
        ]);
        $this->assertEquals('another-value', $el->config['some-key']);
        $this->assertIsArray($el->config['another-key']);
        $this->assertCount(1, $el->config['another-key']);
        $this->assertEquals('txt3', $el->config['another-key'][0]);
        $this->assertEquals(2, $el->config[0]);
    }
}

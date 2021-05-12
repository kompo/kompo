<?php

namespace Kompo;

use Kompo\Interactions\Traits\ForwardsInteraction;
use Kompo\Interactions\Traits\HasInteractions;
use Kompo\Komponents\Layout;
use Kompo\Komponents\Traits\HasHref;

class Rows extends Layout
{
    use HasInteractions;
    use ForwardsInteraction;
    use HasHref;

    public $vueComponent = 'Rows';
    public $bladeComponent = 'Rows';

    public function mounted()
    {
    	//Temporary hack, but the idea is very interesting... Explore further (used in BanksUnitsView)
    	if ($icon = $this->config('icon')) {

    		$this->vueComponent = 'Flex';

    		$this->komponents = [
    			_Html($icon),
    			_Rows(
    				$this->komponents
    			)
    		];
    	}
    }
}

<?php

namespace Kompo;

use Kompo\Komponents\Block;

class Code extends Block
{
    public $vueComponent = 'Code';

    /**
     * Sets the number of spaces taken by a tab in the &lt;code> HTML tag.
     *
     * @param integer  $tabSize  The tab size in spaces. By default, it is 4.
     *
     * @return self
     */
    public function tabSize($tabSize)
    {
    	return $this->data([
    		'tabSize' => $tabSize
    	]);
    }
    
}

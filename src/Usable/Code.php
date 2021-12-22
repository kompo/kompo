<?php

namespace Kompo;

use Kompo\Elements\Block;

class Code extends Block
{
    public $vueComponent = 'Code';

    /**
     * Sets the number of spaces taken by a tab in the &lt;code> HTML tag.
     *
     * @param int $tabSize The tab size in spaces. By default, it is 4.
     *
     * @return self
     */
    public function tabSize($tabSize)
    {
        return $this->config([
            'tabSize' => $tabSize,
        ]);
    }

    /**
     * TODO: document AND bring in the syntax highlighting (with optional import)
     *
     * @param      <type>  $language  The language
     */
    public function language($language)
    {
        return $this->config([
            'codeLanguage' => $language,
        ]);
    }
}

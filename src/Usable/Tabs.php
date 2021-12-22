<?php

namespace Kompo;

use Kompo\Elements\Layout;
use Kompo\Elements\Traits\HasSelectedClass;

class Tabs extends Layout
{
    use HasSelectedClass;

    public $vueComponent = 'FormTabs';

    /**
     * Initializing the tabs element with some sensible defaults that can be easily overriden if needed
     *
     * @param      <type>  $label  The label
     */
    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->commonClass('border-b-2 px-4 py-2 mb-4 block'); //defaults can be overridden

        $this->selectedClass('border-gray-400 font-bold', 'border-transparent'); //defaults can be overridden

        $this->disabledClass('text-gray-400 cursor-not-allowed'); //defaults can be overridden
    }

    /** TODO ADD TO DOCUMENTATION
     * Force a certain tab to be active (set it by it's index starting from 0)
     *
     * @param      integer    $index  The index of the starting active tab
     *
     * @return self
     */
    public function activeTab($index = null)
    {
        return $this->config([
            'activeTab' => $index ?: 0,
        ]);
    }

    //TODO DOCUMENT
    public function disabledClass($disabledClass)
    {
        return $this->config([
            'disabledClass' => $disabledClass,
        ]);
    }

    //TODO DOCUMENT
    public function disableAfter($index)
    {
        collect($this->elements)->each(function($element, $key) use ($index) {
            if ($key >= $index) {
                $element->disabled();
            }
        });

        return $this;
    }
}

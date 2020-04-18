<?php 

namespace Kompo\Komponents\Traits;

trait CKEditorTrait
{
    protected function setDefaultToolbar()
    {
        $this->toolbar(array_merge(
            $this->formatToolbar(),
            $this->insertToolbar(),
            $this->actionsToolbar()
        ));
    }

    /**
     * Sets the available toolbar buttons. Check out <a target="_blank" href="https://ckeditor.com/docs/ckeditor5"><u>CKEditor's docs</u></a> for more options. For example:
     * <php>->toolbar([ 'bold', 'italic', 'underline'])</php>
     *
     * @param      array  $toolbar  An array of the toolbar buttons.
     */
    public function toolbar($toolbar)
    {
        $this->data([
            'toolbar' => $toolbar
        ]);
        return $this;
    }

    /**
     * Appends new buttons at the end of the current toolbar.
     *
     * @param      array  $toolbar  An array of the toolbar buttons.
     */
    public function appendToolbar($additionalToolbar)
    {
        return $this->toolbar(array_merge($this->data('toolbar'), $additionalToolbar));
    }

    /**
     * Prepends new buttons at the beginning of the current toolbar.
     *
     * @param      array  $toolbar  An array of the toolbar buttons.
     */
    public function prependToolbar($additionalToolbar)
    {
        return $this->toolbar(array_merge($additionalToolbar, $this->data('toolbar')));
    }


    private function formatToolbar()
    {
        return ['bold', 'italic', 'underline', 'alignment'];
    }

    private function insertToolbar()
    {
        return ['|', 'heading', 'bulletedList', 'numberedList', 'link', 'insertTable', 'blockQuote'];
    }

    private function actionsToolbar()
    {
        return ['|', 'undo', 'redo'];
    }
    
}
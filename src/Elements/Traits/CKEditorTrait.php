<?php

namespace Kompo\Elements\Traits;

use Kompo\Core\IconGenerator;

trait CKEditorTrait
{
    use \Kompo\Elements\Traits\FocusBlur;
    
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
     * <php>->toolbar([ 'bold', 'italic', 'underline'])</php>.
     *
     * @param array $toolbar An array of the toolbar buttons.
     *
     * @return self
     */
    public function toolbar($toolbar)
    {
        $this->config([
            'toolbar' => $toolbar,
        ]);

        return $this;
    }

    /**
     * Appends new buttons at the end of the current toolbar.
     *
     * @param array $additionalToolbar An array of the toolbar buttons.
     *
     * @return self
     */
    public function appendToolbar($additionalToolbar)
    {
        return $this->toolbar(array_merge($this->config('toolbar'), $additionalToolbar));
    }

    /**
     * Prepends new buttons at the beginning of the current toolbar.
     *
     * @param array $additionalToolbar An array of the toolbar buttons.
     *
     * @return self
     */
    public function prependToolbar($additionalToolbar)
    {
        return $this->toolbar(array_merge($additionalToolbar, $this->config('toolbar')));
    }

    /**
     * Adds the ability to tag/mention options in the editor by writing a character, with an optional Button trigger.
     *
     * @param string       $marker
     * @param array|string $feed              An array of options or a string method name if AJAX
     * @param int          $minimumCharacters
     * @param string|null  $icon              The icon for the mention
     * @param string|null  $itemName          The name attribute for the mention
     *
     * @return self
     */
    public function addMention($marker, $feed, $minimumCharacters = 0, $icon = null, $itemName = 'name', $itemType = null, $itemLabel = null)
    {
        $mentions = $this->config('mentions') ?: [];

        array_push($mentions, [
            'marker'            => $marker,
            'minimumCharacters' => $minimumCharacters,
            'iconClass'         => $icon,
            'iconHtml'          => IconGenerator::toHtml($icon),
            'itemType'          => $itemType,
            'itemLabel'         => $itemLabel ?: __($itemType),
            'initialFeed'       => !$icon ? $feed : $feed->map(
                fn ($item)      => static::mapMention($item, $marker, $icon, $itemName, $itemType)
            )->toArray(),
        ]);

        return $this->config([
            'mentions' => $mentions,
        ]);
    }

    /* PRIVATE METHODS - DEFAULT TOOLBARS */
    private function formatToolbar(): array
    {
        return ['bold', 'italic', 'underline', 'alignment'];
    }

    private function insertToolbar(): array
    {
        return ['|', 'heading', 'bulletedList', 'numberedList', 'link', 'insertTable', 'blockQuote'];
    }

    private function actionsToolbar(): array
    {
        return ['|', 'undo', 'redo'];
    }

    public static function mapMention($item, $marker, $icon, $itemName = 'name', $itemType = null)
    {
        return [
            'id'        => $marker.$item->{$itemName},
            'iconClass' => $icon,         //has to be a font icon (not svg)
            'text'      => $item->{$itemName}, //text is the key for the label
            'itemType'  => $itemType,
            'itemId'    => $item->id ?? null,
        ];
    }
}

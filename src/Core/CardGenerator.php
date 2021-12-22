<?php

namespace Kompo\Core;

use Kompo\Card;
use Kompo\Elements\Layout;
use Kompo\Rows;

class CardGenerator
{
    public static function getTransformedCards($komponent)
    {
        $komponent->query = $komponent->query->getPaginated();

        $komponent->query->getCollection()->transform(function ($item, $key) use ($komponent) {
            return [
                'attributes' => static::getItemAttributes($item, $komponent),
                'render'     => static::getItemCard($item, $key, $komponent),
            ];
        });
    }

    protected static function getItemAttributes($item, $komponent)
    {
        return static::isSpecialQueryLayout($komponent) ? 

            $item : 

            [
                'id' => static::attemptGetItemKeyName($item, $komponent->keyName)
            ];
    }

    protected static function attemptGetItemKeyName($item, $keyName)
    {
        try {
            return $item->{$keyName};
        } catch (\Throwable $e) {
            return;
        }
    }

    protected static function getItemCard($item, $key, $komponent)
    {
        $card = static::getCardDefaultFallback($item, $key, $komponent);

        if ($komponent->orderable) {
            $card->config([
                'item_id'    => $item->{$komponent->keyName},
                'item_order' => $item->{$komponent->orderable},
            ]);
        }

        return $card;
    }

    protected static function getCardDefaultFallback($item, $key, $komponent)
    {
        $shouldActivateBootFlag = !app('bootFlag'); //because a query's card in a parent card would deactivate it for next one

        $shouldActivateBootFlag && app()->instance('bootFlag', true);
        $card = method_exists($komponent, 'render') ? $komponent->render($item, $key) : [];
        $shouldActivateBootFlag && app()->instance('bootFlag', false);

        if (is_array($card)) {
            $defaultCard = $komponent->card ?: Card::class;

            return $defaultCard::form($card);
        } elseif (!($card instanceof Card) && !($card instanceof Layout)) {
            return Rows::form($card);
        } else {
            return $card;
        }
    }

    public static function isSpecialQueryLayout($komponent)
    {
        return in_array($komponent->layout, ['CalendarMonth', 'Kanban']);
    }
}

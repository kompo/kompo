<?php

namespace Kompo\Core;

use Kompo\Card;
use Kompo\Komponents\Layout;
use Kompo\Rows;

class CardGenerator
{
    public static function getTransformedCards($komposer)
    {
        $komposer->query = $komposer->query->getPaginated();

        $komposer->query->getCollection()->transform(function ($item, $key) use ($komposer) {
            return [
                'attributes' => static::getItemAttributes($item, $komposer),
                'render'     => static::getItemCard($item, $key, $komposer),
            ];
        });
    }

    protected static function getItemAttributes($item, $komposer)
    {
        return static::isSpecialQueryLayout($komposer) ? 
            $item : 
            ['id' => $item->{$komposer->keyName} ?? null];
    }

    protected static function getItemCard($item, $key, $komposer)
    {
        $card = static::getCardDefaultFallback($item, $key, $komposer);

        if ($komposer->orderable) {
            $card->config([
                'item_id'    => $item->{$komposer->keyName},
                'item_order' => $item->{$komposer->orderable},
            ]);
        }

        return $card;
    }

    protected static function getCardDefaultFallback($item, $key, $komposer)
    {
        $shouldActivateBootFlag = !app('bootFlag'); //because a query's card in a parent card would deactivate it for next one

        $shouldActivateBootFlag && app()->instance('bootFlag', true);
        $card = method_exists($komposer, 'card') ? $komposer->card($item, $key) : [];
        $shouldActivateBootFlag && app()->instance('bootFlag', false);

        if (is_array($card)) {
            $defaultCard = $komposer->card ?: Card::class;

            return $defaultCard::form($card);
        } elseif (!($card instanceof Card) && !($card instanceof Layout)) {
            return Rows::form($card);
        } else {
            return $card;
        }
    }

    public static function isSpecialQueryLayout($komposer)
    {
        return in_array($komposer->layout, ['CalendarMonth', 'Kanban']);
    }
}

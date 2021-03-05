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
                'attributes' => $item,
                'render'     => static::getItemCard($item, $key, $komposer),
            ];
        });
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
        $card = method_exists($komposer, 'card') ? $komposer->card($item, $key) : [];

        if (is_array($card)) {
            $defaultCard = $komposer->card ?: Card::class;

            return $defaultCard::form($card);
        } elseif (!($card instanceof Card) && !($card instanceof Layout)) {
            return Rows::form($card);
        } else {
            return $card;
        }
    }
}

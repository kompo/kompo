<?php 

namespace Kompo\Core;

use Kompo\Card;
use Kompo\Core\Util;

class CardGenerator
{
    public static function getTransformedCards($komposer)
    {
        $komposer->query = $komposer->query->getPaginated();

        $komposer->query->getCollection()->transform(function($item) use($komposer){

            return static::getItemCard($item, $komposer);

        });
    }


    protected static function getItemCard($item, $komposer)
    {
        $defaultItems = array_merge(
            $komposer->orderable ? [
                'id' => $item->{$komposer->keyName},
                'order' => $item->{$komposer->orderable}
            ] : []
        );

        $card = static::getCardDefaultFallback($item, $komposer);
        $card->komponents = array_merge($defaultItems, $card->komponents);
        return $card;
    }

    protected static function getCardDefaultFallback($item, $komposer)
    {
        $card = method_exists($komposer, 'card') ? $komposer->card($item) : [];

        if(is_array($card)){
            $defaultCard = $komposer->card ?: Card::class;
            return $defaultCard::form($card);
        }else{
            return $card;
        }
    }
}
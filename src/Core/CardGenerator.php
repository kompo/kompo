<?php 

namespace Kompo\Core;

use Kompo\Card;
use Kompo\Core\Util;
use Kompo\Komponents\Layout;
use Kompo\Rows;

class CardGenerator
{
    public static function getTransformedCards($komposer)
    {
        $komposer->query = $komposer->query->getPaginated();

        $komposer->query->getCollection()->transform(function($item, $key) use($komposer){

            return static::getItemCard($item, $key, $komposer);

        });
    }


    protected static function getItemCard($item, $key, $komposer)
    {
        $defaultItems = array_merge(
            $komposer->orderable ? [
                'id' => $item->{$komposer->keyName},
                'order' => $item->{$komposer->orderable}
            ] : []
        );

        $card = static::getCardDefaultFallback($item, $key, $komposer);
        $card->komponents = array_merge($defaultItems, $card->komponents);
        return $card;
    }

    protected static function getCardDefaultFallback($item, $key, $komposer)
    {
        $card = method_exists($komposer, 'card') ? $komposer->card($item, $key) : [];

        if(is_array($card)){
            $defaultCard = $komposer->card ?: Card::class;
            return $defaultCard::form($card);
        }else if(!($card instanceOf Card) && !($card instanceOf Layout)){
            return Rows::form($card);
        }else{
            return $card;
        }
    }
}
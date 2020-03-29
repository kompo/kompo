<?php 

namespace Kompo\Core;

use Kompo\Card;
use Kompo\Core\Util;

class CardPaginator
{
    public static function transformItems($catalog)
    {
        $catalog->query = $catalog->query->getPaginated();

        $catalog->query->getCollection()->transform(function($item) use($catalog){

            return static::getItemCard($item, $catalog);

        });
    }


    protected static function getItemCard($item, $catalog)
    {
        $defaultItems = array_merge(
            $catalog->orderable ? [
                'id' => $item->{$catalog->keyName},
                'order' => $item->{$catalog->orderable}
            ] : []
        );

        $card = static::getCardDefaultFallback($item, $catalog);
        $card->components = array_merge($defaultItems, $card->components);
        return $card;
    }

    protected static function getCardDefaultFallback($item, $catalog)
    {
        if(is_array($card = $catalog->card($item))){
            $defaultCard = $catalog->card ?: Card::class;
            return $defaultCard::form($catalog->card($item));
        }else{
            return $card;
        }
    }
}
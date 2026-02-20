<?php

namespace Kompo\Broadcasting;

trait BroadcastsKompoEvents
{
    public static function bootBroadcastsKompoEvents()
    {
        static::created(function ($model) {
            if (method_exists($model, 'kompoChannel')) {
                kompo_broadcast(
                    $model->kompoChannel(),
                    class_basename($model) . 'Created',
                    $model->kompoEventData()
                );
            }
        });

        static::updated(function ($model) {
            if (method_exists($model, 'kompoChannel')) {
                kompo_broadcast(
                    $model->kompoChannel(),
                    class_basename($model) . 'Updated',
                    $model->kompoEventData()
                );
            }
        });

        static::deleted(function ($model) {
            if (method_exists($model, 'kompoChannel')) {
                kompo_broadcast(
                    $model->kompoChannel(),
                    class_basename($model) . 'Deleted',
                    ['id' => $model->id]
                );
            }
        });
    }

    public function kompoEventData()
    {
        return $this->toArray();
    }
}

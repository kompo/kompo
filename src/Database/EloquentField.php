<?php

namespace Kompo\Database;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Kompo\Database\Lineage;
use Kompo\Database\NameParser;

class EloquentField
{
    /**
     * Gets all the potentially related models from a relationship.
     *
     * @param Illuminate\Database\Eloquent\Model $model
     * @param string  $name
     * @param string  $morphToModel
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getRelatedCandidates($model, $name, $morphToModel)
    {
        if(NameParser::isNested($name)){
            $model = Lineage::findOrFailRelationNoConstraints($model, NameParser::firstTerm($name))->getRelated();
            $name = NameParser::secondTerm($name);
            return static::getRelatedCandidates($model, $name, $morphToModel);
        }

        if($model->$name() instanceOf MorphTo){ //should be before Relation::noConstraints because it changes the related class...
            $newInstance = $model->newInstance();
            $newInstance->{$model->$name()->getMorphType()} = $morphToModel; //setting the related model
            $relationQuery = $newInstance->$name()->getBaseQuery();
            array_shift($relationQuery->wheres); //removing a join that stays after noConstraints for some reason...
            return $relationQuery->get();
        }

        $relation = Lineage::findOrFailRelationNoConstraints($model, $name);

        if($relation instanceOf BelongsToMany){
            $relationQuery = $relation->getBaseQuery();
            array_shift($relationQuery->joins); //removing a join that Laravel does not remove with noConstraints for some reason...
            return $relationQuery->get();
        }

        return $relation->get();
    }

}
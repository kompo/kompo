<?php

namespace Kompo\Core;

use Kompo\Database\NameParser;

class ValidationManager
{
    /**
     * Validates the incoming request with the komposer's rules.
     *
     * @param Kompo\Komposers\Komposer $komposer The komposer
     */
    public static function validateRequest($komposer)
    {
        request()->validate(static::getRules($komposer));
    }

    /**
     * Sets the field validation rules.
     *
     * @param <type> $field The field
     * @param <type> $rules The rules
     *
     * @return <type> ( description_of_the_return_value )
     */
    public static function setFieldRules($rules, $field)
    {
        $rules = is_array($rules) && array_key_exists($field->name, $rules) ? $rules : [$field->name => $rules];

        return static::setRules($rules, $field);
    }

    /**
     * Appends validation rules to the Komposer.
     *
     * @param array $rules The validation rules array.
     *
     * @return void
     */
    public static function addRulesToKomposer($rules, $komposer)
    {
        return static::setRules($rules, $komposer);
    }

    /**
     * Pushes field rules to the Komposer if they were set on the field directly.
     *
     * @param <type>                   $field    The field
     * @param Kompo\Komposers\Komposer $komposer The form
     */
    public static function pushCleanRulesToKomposer($field, $komposer)
    {
        if ($field->config('rules')) {
            static::addRulesToKomposer($field->config('rules'), $komposer);
        }

        static::cleanNestedNameRules($field, $komposer);
    }

    protected static function cleanNestedNameRules($field, $komposer)
    {
        Util::collect($field->name)->each(function ($name) use ($komposer) {
            $komposerRules = static::getRules($komposer);

            if (NameParser::isNested($name) && ($komposerRules[$name] ?? null)) {
                $komposerRules[RequestData::convert($name)] = $komposerRules[$name];
                unset($komposerRules[$name]);
                static::overwriteRules($komposerRules, $komposer);
            }
        });
    }

    /**
     * Gets the validation rules from a komposer or komponent.
     *
     * @param <type> $element The komposer or komponent
     *
     * @return array
     */
    public static function getRules($element)
    {
        return $element->config('rules') ?: [];
    }

    /**** PRIVATE ****/

    private static function setRules($rules, $el)
    {
        return $el->config([
            'rules' => static::mergeRules($rules, $el->config('rules') ?: []),
        ]);
    }

    private static function mergeRules($rules, $oldRules)
    {
        $results = [];
        foreach ($rules as $attribute => $validations) {
            $results[$attribute] = static::mergeAttribute($validations, $oldRules[$attribute] ?? []);
        }

        return array_replace($oldRules, $results);
    }

    private static function mergeAttribute($validations, $oldValidations = [])
    {
        return array_merge($oldValidations, is_string($validations) ? explode('|', $validations) : $validations);
    }

    private static function overwriteRules($rules, $el)
    {
        return $el->config([
            'rules' => $rules,
        ]);
    }
}

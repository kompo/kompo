<?php

namespace Kompo\Core;

use Kompo\Database\NameParser;
use Kompo\Exceptions\ValidationWarningException;

class ValidationManager
{
    /**
     * TODO DOCUMENT
     * A helper method you can call beforeSave() to ask the user for a confirmation that he truly accepts the form submit.
     * Useful when for example, you want to warn the user of a potential duplicate record in your database.
     *
     * @param  Kompo\Komponents\Komponent|string  $confirmationElements  The confirmation elements
     * @param  bool                            $condition             The condition (true or false) - check for duplicates for example
     *
     * @throws     \Kompo\Exceptions\ValidationWarningException  (description)
     */
    public static function confirmSubmit($confirmationElements, bool $condition)
    {
        if (!request('kompoConfirmed')) {
            if ($condition) {
                throw new ValidationWarningException($confirmationElements);
            }
        }

        //else the user has confirmed he really want's to submit
    }

    /**
     * Validates the incoming request with the komponent's rules.
     *
     * @param Kompo\Komponents\Komponent $komponent The komponent
     */
    public static function validateRequest($komponent)
    {
        request()->validate(static::getRules($komponent));
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
     * Appends validation rules to the Komponent.
     *
     * @param array $rules The validation rules array.
     *
     * @return void
     */
    public static function addRulesToKomponent($rules, $komponent)
    {
        return static::setRules($rules, $komponent);
    }

    /**
     * Pushes field rules to the Komponent if they were set on the field directly.
     *
     * @param <type>                   $field    The field
     * @param Kompo\Komponents\Komponent $komponent The form
     */
    public static function pushCleanRulesToKomponent($field, $komponent)
    {
        if ($field->config('rules')) {
            static::addRulesToKomponent($field->config('rules'), $komponent);
        }

        static::cleanNestedNameRules($field, $komponent);
    }

    protected static function cleanNestedNameRules($field, $komponent)
    {
        Util::collect($field->name)->each(function ($name) use ($komponent) {
            $komponentRules = static::getRules($komponent);

            if (NameParser::isNested($name) && ($komponentRules[$name] ?? null)) {
                $komponentRules[RequestData::convert($name)] = $komponentRules[$name];
                unset($komponentRules[$name]);
                static::overwriteRules($komponentRules, $komponent);
            }
        });
    }

    /**
     * Gets the validation rules from a komponent or element.
     *
     * @param <type> $element The komponent or element
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

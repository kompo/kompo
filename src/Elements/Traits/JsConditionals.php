<?php

namespace Kompo\Elements\Traits;

trait JsConditionals
{
    /**
     * Show element when field matches condition
     * Usage: ->jsShowWhen('payment_method', '==', 'credit_card')
     */
    public function jsShowWhen($fieldName, $operator, $value)
    {
        $fieldJson = json_encode($fieldName);
        $valueJson = json_encode($value);
        $operators = [
            '==' => '===',
            '!=' => '!==',
            '>' => '>',
            '<' => '<',
            '>=' => '>=',
            '<=' => '<=',
            'contains' => '.includes',
        ];
        $jsOp = $operators[$operator] ?? '===';

        $condition = $operator === 'contains'
            ? "val{$jsOp}({$valueJson})"
            : "val {$jsOp} {$valueJson}";

        return $this->config([
            'jsConditional' => [
                'type' => 'show',
                'field' => $fieldName,
                'condition' => $condition,
            ]
        ]);
    }

    /**
     * Hide element when field matches condition
     */
    public function jsHideWhen($fieldName, $operator, $value)
    {
        $fieldJson = json_encode($fieldName);
        $valueJson = json_encode($value);
        $operators = ['==' => '===', '!=' => '!==', '>' => '>', '<' => '<'];
        $jsOp = $operators[$operator] ?? '===';

        return $this->config([
            'jsConditional' => [
                'type' => 'hide',
                'field' => $fieldName,
                'condition' => "val {$jsOp} {$valueJson}",
            ]
        ]);
    }

    /**
     * Enable element when condition is met
     */
    public function jsEnableWhen($fieldName, $operator, $value)
    {
        $fieldJson = json_encode($fieldName);
        $valueJson = json_encode($value);
        $operators = ['==' => '===', '!=' => '!=='];
        $jsOp = $operators[$operator] ?? '===';

        return $this->config([
            'jsConditional' => [
                'type' => 'enable',
                'field' => $fieldName,
                'condition' => "val {$jsOp} {$valueJson}",
            ]
        ]);
    }

    /**
     * Disable element when condition is met
     */
    public function jsDisableWhen($fieldName, $operator, $value)
    {
        $fieldJson = json_encode($fieldName);
        $valueJson = json_encode($value);
        $operators = ['==' => '===', '!=' => '!=='];
        $jsOp = $operators[$operator] ?? '===';

        return $this->config([
            'jsConditional' => [
                'type' => 'disable',
                'field' => $fieldName,
                'condition' => "val {$jsOp} {$valueJson}",
            ]
        ]);
    }
}

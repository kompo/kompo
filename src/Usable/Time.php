<?php

namespace Kompo;

use Illuminate\Support\Carbon;
use Kompo\Elements\Field;

class Time extends Field
{
    public $vueComponent = 'Date';

    protected $savedAsDateTime = false;

    protected function initialize($label)
    {
        parent::initialize($label);

        $this->icon('icon-clock');

        $this->timeFormat(config('kompo.default_time_format') ?: 'H:i');

        $this->config([
            'enableTime' => true,
            'noCalendar' => true,
        ]);
    }

    /**
     * Sets a FlatPickr accepted time format. By default, it's 'H:i'.
     *
     * @param string $timeFormat The time format.
     *
     * @return self
     */
    public function timeFormat($timeFormat = 'H:i')
    {
        $this->config([
            'altFormat'  => $timeFormat,
            'dateFormat' => $timeFormat,
        ]);

        return $this;
    }

    /**
     * If your DB column is a DATETIME, use this method to convert it to that type.
     *
     * @return self
     */
    public function savedAsDateTime()
    {
        $this->savedAsDateTime = true;

        return $this;
    }

    public function setOutput($value, $key)
    {
        if (!is_null($value)) {
            $this->value(
                $value instanceof Carbon ? $value->format('H:i') : $value
            );
        }
    }

    private function validateDate($date, $format = 'Y-m-d')
    {
        $d = \DateTime::createFromFormat($format, $date);

        return $d && $d->format($format) === $date;
    }

    private function getTimeFormat()
    {
        return $this->config('altFormat') ?: 'H:i';
    }
}

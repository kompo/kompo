<?php 

namespace Kompo\Models\Traits;

trait EnumKompo
{
    public static function optionsWithLabels()
    {
        return collect(self::cases())->filter(fn($case) => $case->visibleInSelects())->mapWithKeys(fn($enum) => [
            $enum->value => $enum->label(),
        ]);
    }

    public function visibleInSelects()
    {
        return match ($this) {
            default => true,
        };
    }
}
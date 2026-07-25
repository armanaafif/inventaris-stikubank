<?php

namespace App\Support;

use Carbon\Carbon;

class InventoryFormatter
{
    public static function quantity($value): string
    {
        if ($value === null || $value === '') {
            return '0';
        }

        $formatted = number_format((float) $value, 2, '.', '');

        return rtrim(rtrim($formatted, '0'), '.');
    }

    public static function quantityWithUnit($value, ?string $unit): string
    {
        return trim(self::quantity($value) . ' ' . ($unit ?: ''));
    }

    public static function lateDays($returnDate, $returnedDate = null, ?string $status = null): int
    {
        if (!$returnDate) {
            return 0;
        }

        $due = Carbon::parse($returnDate)->startOfDay();
        $actual = $returnedDate ? Carbon::parse($returnedDate)->startOfDay() : now()->startOfDay();

        if ($status && !in_array($status, ['BORROWED', 'RETURNED'], true)) {
            return 0;
        }

        return $actual->gt($due) ? (int) $due->diffInDays($actual) : 0;
    }

    public static function lateLabel($returnDate, $returnedDate = null, ?string $status = null): string
    {
        $days = self::lateDays($returnDate, $returnedDate, $status);

        return $days > 0 ? $days . ' Hari' : 'Tepat Waktu';
    }
}

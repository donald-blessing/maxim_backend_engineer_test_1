<?php


declare(strict_types=1);


namespace App\Traits;

use Illuminate\Support\Str;

trait GeneralHelperTrait
{
    /**
     * @param $cm
     * @return string
     */
    public static function cm2feet($cm): string
    {
        $inches = $cm / 2.54;
        $feet = (int)($inches / 12);
        $inches %= 12;
        return $feet . "ft " . $inches . Str::plural('inch', $inches);
    }
}

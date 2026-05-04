<?php

namespace App\Helpers;

/**
 *
 */
class EnumHelper
{
    /**
     * Возвращает варианты исключая переданные
     *
     * @param array $exceptedCases Варианты для исключения
     *
     * @return array варианты
     */
    public static function except(array $cases, array $exceptedCases): array {
        foreach ($cases as $key => $case) {
            if (in_array($case, $exceptedCases, true)) {
                unset($cases[$key]);
            }
        }

        return array_values($cases);
    }
}

<?php

namespace App\Traits;

/**
 * Вспомогательный трейт для конвертации перечислений в массивы.
 */
trait EnumToArray {

    /**
     * Возвращает ассоциативный массив имён и значений перечисления
     *
     * @return array ассоциативный массив имён и значений перечисления
     */
    public static function array(): array {
        return array_combine(self::values(), self::names());
    }

    /**
     * Возвращает массив значений перечисления
     *
     * @return array Массив значений перечисления
     */
    public static function values(): array {
        return array_column(self::cases(), 'value');
    }

    /**
     * Возвращает массив имён перечисления
     *
     * @return array Массив имён перечисления
     */
    public static function names(): array {
        return array_column(self::cases(), 'name');
    }
}

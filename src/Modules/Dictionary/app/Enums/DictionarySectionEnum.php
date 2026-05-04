<?php

namespace Modules\Dictionary\Enums;

enum DictionarySectionEnum: int
{
    /** Базовые сведения */
    case BASIC_INFO = 1;
    /** Расширенная информация */
    case EXTENDED_INFO = 2;
    /** Этимология и история */
    case ETYMOLOGY_AND_HISTORY = 3;
    /** Поиск в выбранном словаре */
    case SEARCH_IN_DICTIONARY = 4;
    /** Сопоставление словарных данных */
    case DICTIONARY_DATA_COMPARISON = 5;

    /**
     * Возвращает человекочитаемое название раздела на русском языке.
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::BASIC_INFO => 'базовые сведения',
            self::EXTENDED_INFO => 'расширенная информация',
            self::ETYMOLOGY_AND_HISTORY => 'этимология и история',
            self::SEARCH_IN_DICTIONARY => 'поиск в выбранном словаре',
            self::DICTIONARY_DATA_COMPARISON => 'сопоставление словарных данных',
        };
    }

    /**
     * Возвращает все допустимые значения в виде массива строк.
     *
     * @return string[]
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

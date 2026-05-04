<?php

declare(strict_types=1);

/* STRINGS */

/**
 * Проверяет, пуста ли строка
 *
 * @param  string  $string  Проверяемая строка
 *
 * @return bool Признак, пуста ли строка
 */
function str_is_empty(string $string): bool
{
    return $string === '';
}

if (!function_exists('str_contains')) {
    /**
     * Определяет, содержит ли строка заданную подстроку
     *
     * @param  string  $haystack  Строка для поиска
     * @param  string  $needle  Подстрока для поиска в haystack
     *
     * @return bool Признак, содержит ли строка заданную подстроку
     */
    function str_contains(string $haystack, string $needle): bool
    {
        return strpos($haystack, $needle) !== false;
    }
}

/* ARRAYS */

/**
 * Проверяет, пустой ли массив
 *
 * @param  array  $array  Проверяемый массив
 *
 * @return bool Признак, пуст ли массив
 */
function array_is_empty(array $array): bool
{
    return $array === [];
}

if (!function_exists('array_key_first')) {
    /**
     * Получает первый ключ массива
     *
     * @param  array  $array  Массив
     *
     * @return int|string|null Первый ключ массива array, если он не пустой; null в противном случае.
     */
    function array_key_first(array $array)
    {
        if (array_is_empty($array)) {
            return null;
        }

        reset($array);

        return key($array);
    }
}

if (!function_exists('array_key_last')) {
    /**
     * Получает последний ключ массива
     *
     * @param  array  $array  Массив
     *
     * @return int|string|null Последний ключ массива array, если он не пустой; null в противном случае.
     */
    function array_key_last(array $array)
    {
        if (array_is_empty($array)) {
            return null;
        }

        end($array);

        return key($array);
    }
}

/* VARIABLES */

/**
 * Проверяет, является ли переменняа равной null
 *
 * @param  mixed  $value  Проверяемая переменная
 *
 * @return bool Возвращает true, если значение value равно null, или false в противном случае
 */
function var_is_null($value): bool
{
    return $value === null;
}

/**
 * Проверяет, является ли переменняа массивом
 *
 * @param  mixed  $value  Проверяемая переменная
 *
 * @return bool Возвращает true, если значение value является массивом, или false в противном случае
 */
function var_is_array($value): bool
{
    return is_array($value);
}

/**
 * Проверяет, является ли переменная булевой
 *
 * @param  mixed  $value  Проверяемая переменная
 *
 * @return bool Возвращает true, если value является переменной типа bool, или false в противном случае
 */
function var_is_bool($value): bool
{
    return is_bool($value);
}

/**
 * Проверяет, является ли переменная числом или строкой, содержащей число
 *
 * @param  mixed  $value  Проверяемая переменная
 *
 * @return bool Возвращает true, если value является числом или строкой, содержащей число или false в противном случае
 */
function var_is_numeric($value): bool
{
    return is_numeric($value);
}

/**
 * Проверяет, является ли переменная целым числом
 *
 * Чтобы проверить, является ли переменная числом или строкой, содержащей число (как поле ввода в форме, которое всегда является строкой), используйте var_is_numeric().
 *
 * @param  mixed  $value  Проверяемая переменная
 *
 * @return bool Возвращает true, если value является целым числом (int), или false в противном случае
 */
function var_is_int($value): bool
{
    return is_int($value);
}

/**
 * Проверяет, является ли переменная числом с плавающей точкой
 *
 * Чтобы проверить, является ли переменная числом или строкой, содержащей число (как поле ввода в форме, которое всегда является строкой), используйте var_is_numeric().
 *
 * @param  mixed  $value  Проверяемая переменная
 *
 * @return bool Возвращает true, если value является переменной типа float, false в противном случае
 */
function var_is_float($value): bool
{
    return is_float($value);
}

/**
 * Проверяет, является ли переменная объектом
 *
 * @param  mixed  $value  Проверяемая переменная
 *
 * @return bool Возвращает true, если value является объектом, или false в противном случае
 */
function var_is_object($value): bool
{
    return is_object($value);
}

/**
 * Проверяет, является ли переменная строкой
 *
 * @param  mixed  $value  Проверяемая переменная
 *
 * @return bool Возвращает true, если value является строкой, и false в противном случае
 */
function var_is_string($value): bool
{
    return is_string($value);
}

/**
 * Проверяет, является ли переменная ресурсом
 *
 * @param  mixed  $value  Проверяемая переменная
 *
 * @return bool Возвращает true, если value является ресурсом (resource), или false в противном случае
 */
function var_is_resource($value): bool
{
    return is_resource($value);
}

/**
 * Проверяет, является ли переменная скалярным значением
 * Скалярные переменные - это переменные с типами int, float, string и bool. Типы array, object, resource и null не являются скалярными.
 *
 * var_is_scalar() не считает переменные типа resource скалярными, так как ресурсы являются абстрактными типами данных,
 * которые в настоящее время основаны на целом типе. Не стоит полагаться на данную деталь реализации, так как она может измениться.
 *
 * var_is_scalar() не считает NULL скаляром.
 *
 * @param  mixed  $value  Проверяемая переменная
 *
 * @return bool Возвращает true, если value является скалярным значением, или false в противном случае
 */
function var_is_scalar($value): bool
{
    return is_scalar($value);
}

/**
 * Проверяет, что значение может быть вызвано как функция в текущей области видимости
 *
 * @param  mixed  $value  Значение для проверки
 * @param  bool  $syntax_only  Если равен true, функция только проверяет, что value может быть функцией или методом.
 *                              В этом случае будут отклоняться переменные, которые не являются ни строкой, ни массивом c корректной структурой для использования в качестве callback-функции.
 *                              Корректная структура массива предполагает наличие только двух элементов, первый из которых - объект или строка, а второй - только строка.
 * @param  string  $callable_name  Получает "вызываемое имя". В примере ниже это "someClass::someMethod".
 *                              Следует иметь в виду, что хотя запись someClass::SomeMethod() означает вызываемый статический метод, это не так.
 *
 * @return bool Возвращает true, если value может быть вызвана, или false в противном случае
 */
function var_is_callable(mixed $value, bool $syntax_only = false, string &$callable_name = null): bool
{
    return is_callable($value, $syntax_only, $callable_name);
}

/**
 * Проверяет, что содержимое переменной является счётным значением
 * Т.е. проверяет, что содержимое переменной массив (array) или объект, реализующий Countable
 *
 * @param  mixed  $value  Значение для проверки
 *
 * @return bool Возвращает true, если value счётная или false в противном случае
 */
function var_is_countable($value): bool
{
    return is_countable($value);
}

/**
 * Проверяет, является ли переменная итерируемой
 * Т.е. проверяет, соответствует ли содержимое переменной псевдотипу iterable,
 * то есть является ли она либо массивом (array), либо объектом, реализующим Traversable
 *
 * @param  mixed  $value  Переменная для проверки
 *
 * @return bool Возвращает true, если value итерируемая или false, если нет
 */
function var_is_iterable($value): bool
{
    return is_iterable($value);
}
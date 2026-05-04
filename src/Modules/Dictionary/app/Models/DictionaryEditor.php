<?php

namespace Modules\Dictionary\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель редактора словаря.
 *
 * Представляет связь между словарём и её редактором по имени.
 *
 * @property string $name Имя редактора
 * @property int $dictionary_id ID словарной статьи
 */
class DictionaryEditor extends Model
{
    /** @var string Указывает таблицу в схеме dictionary */
    protected $table = 'dictionary.dictionaries_editors';
    /** @var string[] Разрешённые для масс-присвоения атрибуты модели */
    protected $fillable = ['name', 'dictionary_id'];
}

<?php

namespace Modules\Dictionary\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель автора словаря.
 *
 * Представляет связь между словарём и её автором по имени.
 *
 * @property string $name Имя автора
 * @property int $dictionary_id ID словарной статьи
 */
class DictionaryAuthor extends Model
{
    /** @var string Указывает таблицу в схеме dictionary */
    protected $table = 'dictionary.dictionaries_authors';
    /** @var string[] Разрешённые для масс-присвоения атрибуты модели */
    protected $fillable = ['name', 'dictionary_id'];
}

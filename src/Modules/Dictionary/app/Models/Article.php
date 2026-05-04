<?php

namespace Modules\Dictionary\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

/**
 * Модель "Статья"
 *
 * @property int    $id                 Идентификатор
 * @property int    $dictionary_word_id Идентификатор слова словаря
 * @property string $writing            Написание слова
 * @property string $content            Содержимое статьи
 * @property string $content_cleared    Содержимое статьи (очищенное от тегов)
 * @property int    $number             Порядковый номер
 * @property bool   $is_link            Признак ссылки
 */
class Article extends Model
{

    use HasFactory;

    protected $table = 'dictionary.articles';
    protected $fillable = [
        'dictionary_word_id',
        'writing',
        'content',
        'content_cleared',
        'number',
        'is_link',
    ];

    public function dictionaryWord(): HasOne
    {
        return $this->hasOne(DictionaryWord::class, 'id', 'dictionary_word_id');
    }

    public function word(): HasOneThrough
    {
        return $this->through('dictionaryWord')->has('word');
    }

    public function dictionary(): HasOneThrough
    {
        return $this->through('dictionaryWord')->has('dictionary');
    }

    public function links(): HasMany
    {
        return $this->hasMany(Link::class, 'article_id', 'id');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class, 'article_id', 'id');
    }
}

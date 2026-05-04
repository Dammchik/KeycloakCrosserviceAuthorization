<?php

namespace Modules\Dictionary\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Модель "Слова словаря"
 *
 * @property int $id             Идентификатор
 * @property int $dictionary_id  Идентификатор словаря
 * @property int $word_id        Идентификатор слова
 */
class DictionaryWord extends Model
{

    use HasFactory;

    protected $table = 'dictionary.dictionary_words';
    protected $fillable = [
        'dictionary_id',
        'word_id',
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'dictionary_word_id', 'id');
    }

    public function word(): HasOne
    {
        return $this->hasOne(Word::class, 'id', 'word_id');
    }

    public function dictionary(): HasOne
    {
        return $this->hasOne(Dictionary::class, 'id', 'dictionary_id');
    }
}

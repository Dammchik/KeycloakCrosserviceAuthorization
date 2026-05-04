<?php

namespace Modules\Dictionary\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Модель "Слово"
 *
 * @property int    $id    Идентификатор
 * @property string $value Значение
 */
class Word extends Model
{

    use HasFactory;

    protected $table = 'dictionary.words';
    protected $fillable = [
        'value',
    ];

    public function dictionaryWords(): HasMany
    {
        return $this->hasMany(DictionaryWord::class);
    }

    public function dictionary(): BelongsToMany
    {
        return $this->belongsToMany(Dictionary::class, 'dictionary.dictionary_words', 'dictionary_id', 'word_id');
    }
}

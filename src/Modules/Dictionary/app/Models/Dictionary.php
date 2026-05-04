<?php

namespace Modules\Dictionary\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Сущность словаря
 *
 * @property int $id Уникальный идентификатор словаря
 * @property string $name Наименование словаря
 * @property string $short_name Краткое название словаря
 * @property string|null $alias Краткое наименование словаря
 * @property string $original_language Язык оригинала словаря
 * @property array $authors Массив авторов словаря
 * @property string|null $main_editor Главный редактор словаря
 * @property array|null $responsible_editors Массив ответственных редакторов
 * @property string $publication_years Год(ы) издания словаря
 * @property string|null $publishing_house Издательство, выпустившее словарь
 * @property string|null $publication_city Город издательства
 * @property string $dictionary_version Версия словаря
 * @property string $module_number Принадлежность к модулю
 * @property Carbon|null $created_at Дата и время создания записи
 * @property Carbon|null $updated_at Дата и время последнего обновления записи
 * *
 */
class Dictionary extends Model
{

    use HasFactory;
    /** @var string Указывает таблицу в схеме dictionary */
    protected $table = 'dictionary.dictionaries';
    /** @var string[] Разрешённые для масс-присвоения атрибуты модели */
    protected $fillable = [
        'name',
        'alias',
        'short_name',
        'original_language',
        'main_editor',
        'publication_years',
        'publishing_house',
        'publication_city',
        'dictionary_version',
        'module_number',
    ];

    /**
     * Определяет отношение "один ко многим" к таблице авторов словаря.
     *
     * @return HasMany<DictionaryAuthor>
     */
    public function dictionaryAuthors(): HasMany
    {
        return $this->hasMany(DictionaryAuthor::class, 'dictionary_id', 'id');
    }

    /**
     * Определяет отношение "один ко многим" к таблице редакторов словаря.
     *
     * @return HasMany<DictionaryEditor>
     */
    public function dictionaryEditors(): HasMany
    {
        return $this->hasMany(DictionaryEditor::class, 'dictionary_id', 'id');
    }

    /**
     * Определяет отношение "один ко многим" к таблице слов словаря.
     *
     * @return HasMany<DictionaryWord>
     */
    public function dictionaryWords(): HasMany
    {
        return $this->hasMany(DictionaryWord::class, 'dictionary_id', 'id');
    }

    public function words(): BelongsToMany
    {
        return $this->belongsToMany(Word::class, 'dictionary.dictionary_words', 'word_id', 'dictionary_id');
    }

    /**
     * Возвращает массив имён авторов, связанных со словарём.
     *
     * @return array<int, string>
     */
    public function getAuthorNames(): array
    {
        return $this->dictionaryAuthors->pluck('name')->all();
    }

    /**
     * Возвращает массив имён ответственных редакторов, связанных со словарём.
     *
     * @return array<int, string>
     */
    public function getEditorNames(): array
    {
        return $this->dictionaryEditors->pluck('name')->all();
    }
}

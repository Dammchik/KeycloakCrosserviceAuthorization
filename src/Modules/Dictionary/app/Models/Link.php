<?php

namespace Modules\Dictionary\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Модель "Ссылки"
 *
 * @property int    $id             Идентификатор
 * @property int    $article_id     Идентификатор статьи
 * @property string $writing        Написание слова
 * @property string $word           Слово (только буквы)
 */
class Link extends Model
{

    use HasFactory;

    protected $table = 'dictionary.links';
    protected $fillable = [
        'article_id',
        'writing',
        'word',
    ];

    public function article(): HasOne
    {
        return $this->hasOne(Article::class, 'id', 'article_id');
    }
}

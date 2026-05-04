<?php

namespace Modules\Dictionary\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Модель "Раздел"
 *
 * @property int    $id                Идентификатор
 * @property int    $article_id        Идентификатор статьи
 * @property int    $type              Тип секции
 * @property string $sections          Вложенные секции
 * @property int    $number            Порядковый номер
 * @property string $content           Содержимое раздела
 * @property string $content_cleared   Содержимое секции (очищенное от тегов)
 */
class Section extends Model
{

    use HasFactory;

    protected $table = 'dictionary.sections';
    protected $fillable = [
        'article_id',
        'type',
        'sections',
        'number',
        'content',
        'content_cleared',
    ];

    public function article(): HasOne
    {
        return $this->hasOne(Article::class, 'id', 'article_id');
    }

    public function sectionList()
    {
        return json_decode($this->sections);
    }
}

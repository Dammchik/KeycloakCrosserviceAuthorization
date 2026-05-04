<?php

namespace Modules\Dictionary\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Dictionary\Models\Article;
use Modules\Dictionary\Models\Dictionary;
use Modules\Dictionary\Models\DictionaryWord;
use Modules\Dictionary\Models\Link;
use Modules\Dictionary\Models\Section;
use Modules\Dictionary\Models\Word;

/**
 * Сервис для работы с данными модуля Dictionary
 */
class ImportDictionaryArticlesService
{
    /**
     * @param array      $data
     * @param Dictionary $dictionary
     */
    public function __construct(
        protected array      $data,
        protected Dictionary $dictionary)
    {
    }

    private array $wordMap = [];

    /**
     * @return string[]
     */
    public function handle(): array
    {
        DB::beginTransaction();

        foreach ($this->data as $item) {

            //ToDo в таблицах article и links согласовать поля writing (получение значений) с результатом работы парсера -

            if (var_is_array($item)) {
                $isLinc = $item['type'] === 'link';
                $word = Arr::get($item, 'word');

                $article = Article::query()->create([
                    'dictionary_word_id' => $this->getDictionaryWordId($word),
                    'writing' => Arr::get($item, 'value'), // todo см. выше
                    'content' => Arr::get($item, 'content'),
                    'content_cleared' => $this->tagsStringClearing(Arr::get($item, 'content')),
                    'number' => $this->getNextArticleNumber($word),
                    'is_link' => $isLinc,
                ]);

                if (isset($item['sections'])) {
                    $number = 1;

                    foreach ($item['sections'] as $section) {
                        Section::query()->create([
                            'article_id' => $article->id,
                            'type' => Arr::get($section, 'type'),
                            'sections' => json_encode(Arr::get($section, 'sections'), JSON_UNESCAPED_UNICODE),
                            'content' => Arr::get($section, 'content'),
                            'content_cleared' => $this->tagsStringClearing(Arr::get($section, 'content')),
                            'number' => $number,
                        ]);

                        $number++;
                    }
                }

                if (isset($item['links'])) {

                    foreach ($item['links'] as $link) {
                        Link::query()->create([
                            'article_id' => $article->id,
                            'writing' => Arr::get($link, 'value'), // todo см. выше
                            'word' => Arr::get($link, 'word'),
                        ]);
                    }
                }
            }
        }

        DB::commit();

        return ["импорт данных прошел успешно"];
    }

    /**
     * Очищает строку от тегов
     *
     * @param string $value
     *
     * @return string
     */
    public function tagsStringClearing(string $value): string
    {
        return strip_tags($value);
    }

    /**
     * @param string $word
     *
     * @return array
     */
    public function getWordParams(string $word): array
    {
        if (!array_key_exists($word, $this->wordMap)) {
            $this->initWordParams($word);
        }

        return $this->wordMap[$word];
    }

    /**
     * Возвращает идентификатор слова
     *
     * @param string $word
     *
     * @return int
     */
    private function getDictionaryWordId(string $word): int
    {
        return $this->getWordParams($word)['dictionary_word_id'];
    }

    /**
     * Формирует данные для статьи
     *
     * @param string $word
     */
    public function initWordParams(string $word): void
    {
        $wordModel = Word::query()->firstOrCreate([
            'value' => $word,
        ]);

        $dictionaryWord = DictionaryWord::query()->firstOrCreate([
            'dictionary_id' => $this->dictionary->id,
            'word_id' => $wordModel->id,
        ]);

        $this->wordMap[$word] = [
            'dictionary_word_id' => $dictionaryWord->id,
            'nextNumber' => $dictionaryWord->articles()->count() + 1,
        ];
    }

    /**
     * Возвращает номер для статьи и сохраняет следующий
     *
     * @param string $word
     *
     * @return int
     */
    private function getNextArticleNumber(string $word): int
    {
        $nextNumber = $this->getWordParams($word)['nextNumber'];
        $this->incrementWordNumber($word);

        return $nextNumber;
    }

    /**
     * Формирует номер для статьи
     *
     * @param string $word
     *
     * @return void
     */
    private function incrementWordNumber(string $word): void
    {
        $this->wordMap[$word]['nextNumber'] = $this->wordMap[$word]['nextNumber'] + 1;
    }
}

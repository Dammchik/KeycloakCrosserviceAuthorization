<?php

namespace Modules\Dictionary\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Modules\Dictionary\Models\Dictionary;
use Modules\Dictionary\Services\ImportDictionaryArticlesService;

class DictionaryDatabaseSeeder extends Seeder
{
    const string BASE_PATH = '/Modules/Dictionary/database/data';

    public int $limit;

    public function __construct()
    {
        $this->limit = (int)env('SEEDER_DICTIONARY_ARTICLE_LIMIT', -1);
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonFile = base_path(self::BASE_PATH . '/dictionary.json');
        $dictionaryList = json_decode(File::get($jsonFile), true);

        foreach ($dictionaryList as $dictionary) {
            $dictionary = Dictionary::query()->firstOrCreate([
                'name' => $dictionary['name'],
                'alias' => $dictionary['alias'],
            ]);

            $this->writingDataArticles($dictionary);
        }
    }

    /**
     * @param Dictionary $dictionary
     *
     * @return void
     */
    public function writingDataArticles(Dictionary $dictionary): void
    {
        $directoryPath = base_path(self::BASE_PATH . '/dictionaries/' . $dictionary->alias);

        if (!File::isDirectory($directoryPath)) {
            return;
        }

        $files = File::files($directoryPath);

        if ($this->limit >= 0) {
            $files = Arr::take($files, $this->limit);
        }

        foreach ($files as $file) {
            $filesPath = $directoryPath . '/' . $file->getFilename();

            $articles = json_decode(File::get($filesPath), true);

            (new ImportDictionaryArticlesService($articles, $dictionary))->handle();
        }
    }
}

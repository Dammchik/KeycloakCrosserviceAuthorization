<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const SCHEMA_NAME = 'dictionary';

    /**
     * Run the migrations.
     */
    public function up(): void {
        DB::statement('CREATE SCHEMA IF NOT EXISTS '.static::SCHEMA_NAME);

        Schema::create(static::SCHEMA_NAME.'.'.'dictionaries', function (Blueprint $table) {
            $table->comment('Словарь');

            $table->id()->comment('Идентификатор');
            $table->string('name', 512)->comment('Наименование словаря');
            $table->string('short_name', 255)->nullable()->comment('Краткое название');
            $table->string('alias', 30)->nullable()->comment('Краткое наименование словаря')->nullable();
            $table->string('original_language')->nullable()->comment('Язык оригинала');
            $table->string('main_editor')->nullable()->comment('Главный редактор')->nullable();
            $table->string('publication_years')->nullable()->comment('Год(ы) издания');
            $table->string('publishing_house')->nullable()->comment('Издательство')->nullable();
            $table->string('publication_city')->nullable()->comment('Город издательства')->nullable();
            $table->string('dictionary_version')->nullable()->comment('Версия словаря');
            $table->integer('module_number')->nullable()->comment('Принадлежность к модулю');
            $table->timestamps(6);
        });

        Schema::create(static::SCHEMA_NAME.'.'.'dictionaries_authors', function (Blueprint $table) {
            $table->comment('Авторы словарей');

            $table->id()->comment('Идентификатор');
            $table->string('name', 512)->comment('Наименование автора');
            $table->foreignId('dictionary_id')->constrained(static::SCHEMA_NAME.'.'.'dictionaries')->onDelete('cascade');
            $table->timestamps(6);
        });

        Schema::create(static::SCHEMA_NAME.'.'.'dictionaries_editors', function (Blueprint $table) {
            $table->comment('Редакторы словарей');

            $table->id()->comment('Идентификатор');
            $table->string('name', 512)->comment('Наименование редактора');
            $table->foreignId('dictionary_id')->constrained(static::SCHEMA_NAME.'.'.'dictionaries')->onDelete('cascade');
            $table->timestamps(6);
        });

        Schema::create(static::SCHEMA_NAME.'.'.'words', function (Blueprint $table) {
            $table->comment('Слово');

            $table->id()->comment('Идентификатор');
            $table->string('value', 100)->unique()->comment('Значение');
            $table->timestamps(6);
        });

        Schema::create(static::SCHEMA_NAME.'.'.'dictionary_words', function (Blueprint $table) {
            $table->comment('Слова словаря');

            $table->id()->comment('Идентификатор');
            $table->integer('dictionary_id')->comment('Идентификатор словаря');
            $table->integer('word_id')->comment('Идентификатор слова');
            $table->timestamps(6);

            $table->foreign('dictionary_id')
                ->references('id')->on('dictionary.dictionaries')
                ->onDelete('cascade');

            $table->foreign('word_id')
                ->references('id')->on('dictionary.words')
                ->onDelete('cascade');
        });

        Schema::create(static::SCHEMA_NAME.'.'.'articles', function (Blueprint $table) {
            $table->comment('Статья');

            $table->id()->comment('Идентификатор');
            $table->integer('dictionary_word_id')->comment('Идентификатор слова словаря');
            $table->string('writing', 255)->comment('Написание слова');
            $table->text('content')->comment('Содержимое статьи');
            $table->text('content_cleared')->comment('Содержимое статьи (очищенное от тегов)');
            $table->integer('number')->comment('Порядковый номер');
            $table->boolean('is_link')->default(false)->comment('Признак ссылки');
            $table->timestamps(6);

            $table->foreign('dictionary_word_id')
                ->references('id')->on('dictionary.dictionary_words')
                ->onDelete('cascade');
        });

        Schema::create(static::SCHEMA_NAME.'.'.'links', function (Blueprint $table) {
            $table->comment('Ссылки');

            $table->id()->comment('Идентификатор');
            $table->integer('article_id')->comment('Идентификатор статьи');
            $table->string('writing', 255)->comment('Написание слова');
            $table->string('word', 255)->comment('Слово (только буквы)');
            $table->timestamps(6);

            $table->foreign('article_id')
                ->references('id')->on('dictionary.articles')
                ->onDelete('cascade');
        });

        Schema::create(static::SCHEMA_NAME.'.'.'sections', function (Blueprint $table) {
            $table->comment('');

            $table->id()->comment('Идентификатор');
            $table->integer('article_id')->comment('Идентификатор статьи');
            $table->integer('type')->comment('Тип секции');
            $table->json('sections')->comment('Вложенные секции');
            $table->integer('number')->comment('Порядковый номер');
            $table->text('content')->comment('Содержимое секции');
            $table->text('content_cleared')->comment('Содержимое секции (очищенное от тегов)');
            $table->timestamps(6);

            $table->foreign('article_id')
                ->references('id')->on('dictionary.articles')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        DB::statement('DROP SCHEMA IF EXISTS '.static::SCHEMA_NAME.' CASCADE');
    }
};

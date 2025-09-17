<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreatePageTopicsTable extends Migration
{

    public function boot()
    {
        Schema::defaultStringLength(191);
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('page_topics')) {
            return;
        }
        Schema::create('page_topics', function (Blueprint $table) {
            $table->engine = 'MyISAM';

            $table->increments('id'); // int(6) AUTO_INCREMENT PRIMARY KEY

            $table->integer('page_worktype_id')->default(0);
            $table->integer('page_subject_id')->default(0);

            $table->string('page_topic_alias', 191)->index('idx_topic_alias');;
            $table->longText('page_topic_content');

            $table->string('page_topic_title', 400);
            $table->text('page_topic_description');
            $table->text('page_topic_keywords');

            $table->boolean('page_is_work')->default(0);

            $table->string('page_og_title', 400);
            $table->text('page_og_description');

            $table->string('page_topic_name', 400);
            $table->boolean('is_published')->default(1);

            $table->integer('site_id')->default(0);
            $table->integer('vuz_id')->default(0);

            $table->string('page_content_title', 400);
            $table->timestamp('ai_updated_date')->default(DB::raw('CURRENT_TIMESTAMP'))->nulluble();

            // индексы
            //$table->index('page_topic_alias', 'idx_topic_alias');
            $table->index(['page_worktype_id', 'page_subject_id', 'site_id', 'vuz_id'], 'idx_worktype_subject_site_vuz');
        });

        // Жёсткая вставка данных из дампа
        DB::table('page_topics')->insert([
            [
                'id' => 2,
                'page_worktype_id' => 9,
                'page_subject_id' => 0,
                'page_topic_alias' => 'kursovyie-rabotyi-na-zakaz-v-voroneje',
                'page_topic_content' => '',
                'page_topic_title' => 'Курсовые работы на заказ в Воронеже, цены. Заказать курсовую в Воронеже срочно',
                'page_topic_description' => 'Курсовая работа на заказ в Воронеже у профессиональных авторов по низкой цене и в сжатые сроки.',
                'page_topic_keywords' => 'курсовая работа,заказать,Воронеж,стоимость',
                'page_is_work' => 0,
                'page_og_title' => 'Напишем курсовую работу на заказ в Воронеже без плагиата и недорого.',
                'page_og_description' => 'Срочно нужна курсовая работа в Воронеже? Закажи у профессионалов своего дела.',
                'page_topic_name' => 'Курсовые работы на заказ в Воронеже по низким ценам',
                'is_published' => 1,
                'site_id' => 1,
                'vuz_id' => 0,
                'page_content_title' => 'Заказать курсовую работу или написать её самостоятельно?',
            ],
            [
                'id' => 10,
                'page_worktype_id' => 8,
                'page_subject_id' => 0,
                'page_topic_alias' => 'kontrolnaya-rabota-na-zakaz-v-voroneje',
                'page_topic_content' => '',
                'page_topic_title' => 'Решение контрольных работ на заказ в Воронеже. Сколько стоит контрольная работа в Воронеже?',
                'page_topic_description' => 'Поможем решить контрольную работу в Воронеже в срок от 1-го дня за 69 рублей.',
                'page_topic_keywords' => 'Контрольная работа,заказать,Воронеж,цена',
                'page_is_work' => 0,
                'page_og_title' => 'Решим контрольную работу в Воронеже быстро и недорого.',
                'page_og_description' => 'Срочно нужна контрольная работа в Воронеже? Решим за 69 рублей в срок от 1-го дня',
                'page_topic_name' => 'Решение контрольных работ на заказ в Воронеже по низким ценам',
                'is_published' => 1,
                'site_id' => 1,
                'vuz_id' => 0,
                'page_content_title' => 'Стоит ли заказывать контрольную работу или решить самому?',
            ],
            [
                'id' => 12221,
                'page_worktype_id' => 11,
                'page_subject_id' => 0,
                'page_topic_alias' => 'raschyotno-graficheskaya-rabota--rgr--na-zakaz-v-permi',
                'page_topic_content' => '',
                'page_topic_title' => 'Расчётно-графическая работа (ргр) на заказ в Перми',
                'page_topic_description' => 'Выполним расчётно-графическую работу (ргр) в Перми в срок от 3-х дней за 800 рублей.',
                'page_topic_keywords' => 'Расчётно-графическая работа (ргр),заказать,Пермь,цена',
                'page_is_work' => 0,
                'page_og_title' => 'Выполним расчётно-графическую работу (ргр) в Перми быстро и дёшево.',
                'page_og_description' => 'Срочно нужно выполнить расчётно-графическую работу (ргр) в Перми? Сделаем за 800 рублей в срок от 3-х дней',
                'page_topic_name' => 'Расчётно-графические работы на заказ в Перми по низким ценам',
                'is_published' => 1,
                'site_id' => 24,
                'vuz_id' => 0,
                'page_content_title' => 'Выполним расчётно-графические работы на заказ в Перми быстро, а также недорого',
            ],
            [
                'id' => 12222,
                'page_worktype_id' => 18,
                'page_subject_id' => 0,
                'page_topic_alias' => 'referat-na-zakaz-v-permi',
                'page_topic_content' => '',
                'page_topic_title' => 'Реферат на заказ в Перми',
                'page_topic_description' => 'Напишем реферат в Перми в срок от 1-го дня за 99 рублей.',
                'page_topic_keywords' => 'Реферат,заказать,Пермь,цена',
                'page_is_work' => 0,
                'page_og_title' => 'Напишем реферат в Перми быстро и дёшево.',
                'page_og_description' => 'Срочно нужно написать реферат в Перми? Сделаем за 99 рублей в срок от 1-го дня',
                'page_topic_name' => 'Рефераты на заказ в Перми по низким ценам',
                'is_published' => 1,
                'site_id' => 24,
                'vuz_id' => 0,
                'page_content_title' => 'Пишем рефераты под заказ в Перми не теряя времени и дёшево',
            ],
            [
                'id' => 511,
                'page_worktype_id' => 6,
                'page_subject_id' => 0,
                'page_topic_alias' => 'Online-test-na-zakaz-v-voroneje',
                'page_topic_content' => '',
                'page_topic_title' => 'Online-тесты на заказ в Воронеже. Сколько стоит Online-тест в Воронеже?',
                'page_topic_description' => 'Выполним online-тест в Воронеже в срок от 1-го дня за 1200 рублей.',
                'page_topic_keywords' => 'Online-тест,заказать,Воронеж,цена',
                'page_is_work' => 0,
                'page_og_title' => 'Выполним online-тест в Воронеже быстро и недорого.',
                'page_og_description' => 'Срочно нужно выполнить online-тест в Воронеже? Сделаем за 1200 рублей в срок от 1-го дня',
                'page_topic_name' => 'Online-тесты на заказ в Воронеже по низким ценам',
                'is_published' => 1,
                'site_id' => 1,
                'vuz_id' => 0,
                'page_content_title' => 'Лучше ли заказывать online-тест или выполнить самому?',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('page_topics');
    }
}
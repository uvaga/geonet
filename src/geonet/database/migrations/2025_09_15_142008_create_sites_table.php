<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('sites')) {
            return;
        }
        Schema::create('sites', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->increments('id'); // int(4) AUTO_INCREMENT PRIMARY KEY

            $table->string('site_domain', 191);
            $table->string('site_name', 191);
            $table->integer('city_id')->default(0);
            $table->string('company_address', 191)->nullable();
            $table->text('company_description')->nullable();
            $table->string('company_coord_x', 75)->nullable();
            $table->string('company_coord_y', 75)->nullable();
            $table->string('button_text', 191)->nullable();
            $table->string('why_we_text', 191)->nullable();
            $table->text('why_we_description')->nullable();
            $table->string('call_us_text', 191)->nullable();
            $table->string('how_it_works_text', 191)->nullable();
            $table->string('how_it_works_step1', 191)->nullable();
            $table->text('how_it_works_step1_descr')->nullable();
            $table->string('how_it_works_step2', 191)->nullable();
            $table->text('how_it_works_step2_descr')->nullable();
            $table->string('how_it_works_step3', 191)->nullable();
            $table->text('how_it_works_step3_descr')->nullable();
            $table->string('how_it_works_step4', 191)->nullable();
            $table->text('how_it_works_step4_descr')->nullable();
            $table->string('our_prices_text', 191)->nullable();
            $table->string('faq_text', 191)->nullable();
            $table->string('happy_students_text', 191)->nullable();
            $table->integer('happy_students_num')->default(0);
            $table->string('authors_kol_text', 191)->nullable();
            $table->integer('authors_kol_num')->default(0);
            $table->string('sredniy_bal_text', 191)->nullable();
            $table->string('sredniy_bal_num', 400)->nullable();
            $table->string('uniq_proc_text', 191)->nullable();
            $table->integer('uniq_proc_num')->default(0);
            $table->string('site_email', 100)->nullable();
            $table->integer('rand_subj')->default(0);
            $table->text('yandex_metrika')->nullable();
            $table->text('vuz_subj_content')->nullable();
            $table->integer('search_id')->default(0);
            $table->text('google_metrika')->nullable();
            $table->text('footer_text')->nullable();
            $table->integer('response_counts')->nullable();
            $table->integer('hmwk_id')->default(4704);
            $table->tinyInteger('yandex_webmaster_group_id')->nullable();
            $table->string('yandex_webmaster_host_id', 100)->nullable();

            // MyISAM не поддерживает timestamps, но для Laravel обычно добавляют:
            $table->timestamps();
        });

        // Жёсткая вставка данных из дампа
        DB::table('sites')->insert([
            [
                'id' => 1,
                'site_domain' => 'study-voronezh.ru',
                'site_name' => 'Квалифицированная Помощь Студентам Города Воронежа',
                'city_id' => 1,
            ],
            [
                'id' => 24,
                'site_domain' => 'kursovaja-diplom-perm.ru',
                'site_name' => 'Профессиональное Выполнение Студенческих Работ в Перми',
                'city_id' => 5,
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
        Schema::dropIfExists('sites');
    }
}

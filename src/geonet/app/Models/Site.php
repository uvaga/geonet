<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    /**
     * Название таблицы, связанной с моделью.
     *
     * @var string
     */
    protected $table = 'sites';

    /**
     * Первичный ключ таблицы.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Указывает, что идентификаторы автоинкрементные.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Тип первичного ключа.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Отключает автоматическое управление временными метками.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Атрибуты, которые можно массово назначать.
     *
     * @var array
     */
    protected $fillable = [
        'site_domain',
        'site_name',
        'city_id',
        'company_address',
        'company_description',
        'company_coord_x',
        'company_coord_y',
        'button_text',
        'why_we_text',
        'why_we_description',
        'call_us_text',
        'how_it_works_text',
        'how_it_works_step1',
        'how_it_works_step1_descr',
        'how_it_works_step2',
        'how_it_works_step2_descr',
        'how_it_works_step3',
        'how_it_works_step3_descr',
        'how_it_works_step4',
        'how_it_works_step4_descr',
        'our_prices_text',
        'faq_text',
        'happy_students_text',
        'happy_students_num',
        'authors_kol_text',
        'authors_kol_num',
        'sredniy_bal_text',
        'sredniy_bal_num',
        'uniq_proc_text',
        'uniq_proc_num',
        'site_email',
        'rand_subj',
        'yandex_metrika',
        'vuz_subj_content',
        'search_id',
        'google_metrika',
        'footer_text',
        'response_counts',
        'hmwk_id',
        'yandex_webmaster_group_id',
        'yandex_webmaster_host_id'
    ];

    /**
     * Атрибуты, которые должны быть приведены к определенным типам.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'city_id' => 'integer',
        'happy_students_num' => 'integer',
        'authors_kol_num' => 'integer',
        'uniq_proc_num' => 'integer',
        'rand_subj' => 'integer',
        'search_id' => 'integer',
        'response_counts' => 'integer',
        'hmwk_id' => 'integer',
        'yandex_webmaster_group_id' => 'integer'
    ];

    /**
     * Значения по умолчанию для атрибутов.
     *
     * @var array
     */
    protected $attributes = [
        'city_id' => 0,
        'happy_students_num' => 0,
        'authors_kol_num' => 0,
        'uniq_proc_num' => 0,
        'rand_subj' => 0,
        'search_id' => 0,
        'hmwk_id' => 4704
    ];

    /**
     * Отношение с моделью City
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    /**
     * Scope для поиска по домену
     */
    public function scopeWhereDomain($query, $domain)
    {
        return $query->where('site_domain', $domain);
    }

    /**
     * Scope для активных сайтов (с ненулевым city_id)
     */
    public function scopeActive($query)
    {
        return $query->where('city_id', '>', 0);
    }

    /**
     * Accessor для получения полного URL
     */
    public function getFullUrlAttribute()
    {
        return 'https://' . $this->site_domain;
    }

    /**
     * Accessor для форматирования номера счастливых студентов
     */
    public function getHappyStudentsNumFormattedAttribute()
    {
        return number_format($this->happy_students_num, 0, '', ' ');
    }

    /**
     * Accessor для форматирования количества авторов
     */
    public function getAuthorsKolNumFormattedAttribute()
    {
        return number_format($this->authors_kol_num, 0, '', ' ');
    }
}
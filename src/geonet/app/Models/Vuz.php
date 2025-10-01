<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vuz extends Model
{
    /**
     * Название таблицы, связанной с моделью.
     *
     * @var string
     */
    protected $table = 'vuz';

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
        'vuz_name',
        'vuz_short_name',
        'vuz_alias',
        'vuz_description',
        'city_id',
        'vuz_address',
        'vuz_phone',
        'vuz_site',
        'vuz_type',
        'vuz_name_to',
        'vuz_city_rating'
    ];

    /**
     * Атрибуты, которые должны быть приведены к определенным типам.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'city_id' => 'integer',
        'vuz_type' => 'integer',
        'vuz_city_rating' => 'integer'
    ];

    /**
     * Значения по умолчанию для атрибутов.
     *
     * @var array
     */
    protected $attributes = [
        'city_id' => 0,
        'vuz_type' => 1
    ];

    /**
     * Отношение с моделью City
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    /**
     * Scope для вузов определенного типа
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('vuz_type', $type);
    }

    /**
     * Scope для вузов в определенном городе
     */
    public function scopeInCity($query, $cityId)
    {
        return $query->where('city_id', $cityId);
    }

    /**
     * Scope для поиска по названию вуза
     */
    public function scopeWhereName($query, $name)
    {
        return $query->where('vuz_name', 'like', "%{$name}%")
                    ->orWhere('vuz_short_name', 'like', "%{$name}%");
    }

    /**
     * Scope для сортировки по рейтингу города
     */
    public function scopeOrderByCityRating($query, $direction = 'desc')
    {
        return $query->orderBy('vuz_city_rating', $direction);
    }

    /**
     * Accessor для получения полного URL сайта вуза
     */
    public function getVuzSiteUrlAttribute()
    {
        if (empty($this->vuz_site)) {
            return null;
        }

        // Добавляем http:// если его нет
        if (!preg_match("~^(?:f|ht)tps?://~i", $this->vuz_site)) {
            return 'http://' . $this->vuz_site;
        }

        return $this->vuz_site;
    }

    /**
     * Accessor для форматирования телефона
     */
    public function getVuzPhoneFormattedAttribute()
    {
        return preg_replace('/(\d{1})(\d{3})(\d{3})(\d{2})(\d{2})/', '+$1 ($2) $3-$4-$5', $this->vuz_phone);
    }

    /**
     * Accessor для получения названия в дательном падеже с fallback
     */
    public function getVuzNameToAttribute($value)
    {
        return $value ?: $this->vuz_name;
    }

    /**
     * Accessor для получения короткого описания
     */
    public function getShortDescriptionAttribute()
    {
        return str_limit(strip_tags($this->vuz_description), 200);
    }

    /**
     * Проверяет, есть ли у вуза сайт
     */
    public function hasWebsite()
    {
        return !empty($this->vuz_site);
    }

    /**
     * Проверяет, есть ли у вуза описание
     */
    public function hasDescription()
    {
        return !empty($this->vuz_description);
    }
}
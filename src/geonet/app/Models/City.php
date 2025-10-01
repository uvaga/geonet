<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    /**
     * Название таблицы, связанной с моделью.
     *
     * @var string
     */
    protected $table = 'cities';

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
        'city_name',
        'city_name_in',
        'city_name_on'
    ];

    /**
     * Атрибуты, которые должны быть приведены к определенным типам.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer'
    ];
}
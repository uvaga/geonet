<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageWorkType extends Model
{
    /**
     * Название таблицы, связанной с моделью.
     *
     * @var string
     */
    protected $table = 'page_worktypes';

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
        'page_worktype_name',
        'page_worktype_alias',
        'page_worktype_name_to',
        'page_worktype_name_what',
        'wt_price',
        'wt_srok',
        'wt_description',
        'wt_order',
        'page_worktype_many',
        'page_worktype_name_chego',
        'r_money_id'
    ];

    /**
     * Атрибуты, которые должны быть приведены к определенным типам.
     *
     * @var array
     */
    protected $casts = [
        'wt_price' => 'integer',
        'wt_order' => 'integer',
        'r_money_id' => 'integer',
        'id' => 'integer'
    ];

    /**
     * Значения по умолчанию для атрибутов.
     *
     * @var array
     */
    protected $attributes = [
        'wt_price' => 0,
        'wt_order' => 0,
        'r_money_id' => 0
    ];
}
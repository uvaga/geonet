<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class PageTopic extends Model
{
    protected $table = 'page_topics';

    protected $fillable = [
        'page_worktype_id',
        'page_subject_id',
        'page_topic_alias',
        'page_topic_content',
        'page_topic_title',
        'page_topic_description',
        'page_topic_keywords',
        'page_is_work',
        'page_og_title',
        'page_og_description',
        'page_topic_name',
        'is_published',
        'site_id',
        'vuz_id',
        'page_content_title',
        'ai_updated_date',
    ];

    public $timestamps = false; // если нет created_at/updated_at

    public function pageWorkType(): BelongsTo
    {
        return $this->belongsTo(PageWorkType::class, 'page_worktype_id');
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function vuz(): BelongsTo
    {
        return $this->belongsTo(Vuz::class, 'vuz_id');
    }

    /**
     * Метод для получения названия города через сайт
     * Добавлена проверка на существование отношений
     */
    public function getCityName()
    {
        // Проверяем, загружено ли отношение site и есть ли у site отношение city
        if ($this->relationLoaded('site') && $this->site && $this->site->relationLoaded('city')) {
            return $this->site->city->city_name ?? null;
        }

        // Если отношения не загружены, используем жадную загрузку
        return $this->loadMissing('site.city')->site->city->city_name ?? null;
    }

    public function getVuzShortName()
    {        
        if ($this->relationLoaded('vuz')) {
            return $this->vuz->vuz_short_name ?? null;
        }
        
        return $this->loadMissing('vuz')->vuz->vuz_short_name ?? null;
    }

    public function scopeGetOldAiUpdated($query, $days = 90)
    {
        $cutoff = Carbon::now()->subDays($days);

        return $query
            ->where(function($q) use ($cutoff) {
                $q->where('ai_updated_date', '<', $cutoff)
                  ->orWhereNull('ai_updated_date');
            })
            ->orderBy('ai_updated_date', 'asc') // NULL значения будут первыми
            ->limit(1);
    }

    /**
     * Дополнительные полезные методы
     */
    
    /**
     * Проверяет, опубликована ли тема
     */
    public function isPublished(): bool
    {
        return (bool) $this->is_published;
    }

    /**
     * Scope для опубликованных тем
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope для тем определенного сайта
     */
    public function scopeForSite($query, $siteId)
    {
        return $query->where('site_id', $siteId);
    }

    /**
     * Scope для тем с жадной загрузкой всех отношений
     */
    public function scopeWithAll($query)
    {
        return $query->with(['site.city', 'vuz', 'pageWorkType']);
    }

}

<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

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

    public function scopeOldAiUpdated($query, $days = 90)
    {
        $cutoff = Carbon::now()->subDays($days);

        return $query
            ->where('ai_updated_date', '<', $cutoff)
            ->orderBy('ai_updated_date', 'asc');           // потом даты по возрастанию
    }

}

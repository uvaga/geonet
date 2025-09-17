<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiRewriteLog extends Model
{
    protected $table = 'ai_rewrite_logs';

    protected $fillable = [
        'page_topic_id',
        'rewrite_time',
        'status',
    ];

    public function pageTopic()
    {
        return $this->belongsTo(PageTopic::class, 'page_topic_id');
    }
}

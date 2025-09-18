<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateAiRewriteLogsCharset extends Migration
{
    public function up()
    {
        // Меняем кодировку всей таблицы
        DB::statement('ALTER TABLE ai_rewrite_logs CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci');

        // Меняем конкретно колонку status
        DB::statement('ALTER TABLE ai_rewrite_logs MODIFY status TEXT CHARACTER SET utf8 COLLATE utf8_general_ci');
    }

    public function down()
    {
        // Откат обратно на utf8mb4_unicode_ci
        DB::statement('ALTER TABLE ai_rewrite_logs CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        DB::statement('ALTER TABLE ai_rewrite_logs MODIFY status TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    }
}

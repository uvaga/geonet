<?php

namespace App\Helpers;

class TextHelper
{
    public static function markdownToHtml(string $text): string
    {
        // Жирный (**текст**)
        $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);

        // Курсив (*текст*)
        $text = preg_replace('/(?<!\*)\*(?!\*)(.*?)\*(?<!\*)/', '<em>$1</em>', $text);

        // Подчёркнутый (__текст__)
        $text = preg_replace('/__(.*?)__/', '<u>$1</u>', $text);

        // Зачёркнутый (~~текст~~)
        $text = preg_replace('/~~(.*?)~~/', '<del>$1</del>', $text);

        return $text;
    }

    public static function textRewriteConvertTags(string $text): string
    {
        $text = str_replace(
            ['h1>', 'h2>', 'h3>'],
            'h6>',
            $text
        );
        $text = str_replace('<ul>', '<ul class="list-marked list offset-top-10">', $text);

        $text = self::markdownToHtml($text);

        return $text;
    }
}

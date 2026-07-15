<?php

namespace App\Support;

class HtmlSanitizer
{
    /**
     * Trainer ka HTML safe banao.
     * Ye certificate design ke liye hai — script/iframe/form/php sab nikal do.
     */
    public static function clean(string $html): string
    {
        // PHP tags — sabse pehle
        $html = str_replace(['<?php', '<?=', '<?', '?>'], '', $html);

        // khatarnak tags (content ke saath)
        $html = preg_replace(
            '#<\s*(script|iframe|object|embed|applet|form|link|meta|base|noscript)\b[^>]*>.*?<\s*/\s*\1\s*>#is',
            '',
            $html
        );

        // unke bache-khuche / self-closing versions
        $html = preg_replace(
            '#<\s*/?\s*(script|iframe|object|embed|applet|form|link|meta|base|noscript)\b[^>]*>#is',
            '',
            $html
        );

        // onclick / onerror / onload etc.
        $html = preg_replace('#\son[a-z-]+\s*=\s*"[^"]*"#i', '', $html);
        $html = preg_replace("#\son[a-z-]+\s*=\s*'[^']*'#i", '', $html);
        $html = preg_replace('#\son[a-z-]+\s*=\s*[^\s>]+#i', '', $html);

        // javascript: / vbscript: / data:text/html
        // NOTE: data:image/... allowed hai — logo/signature usi se aayega
        $html = preg_replace(
            '#(href|src|action|formaction)\s*=\s*["\']?\s*(javascript|vbscript|data\s*:\s*text\s*/\s*html)\s*:#i',
            '$1="#',
            $html
        );

        // CSS expression() / behavior:
        $html = preg_replace('#expression\s*\(#i', '', $html);
        $html = preg_replace('#behaviou?r\s*:#i', '', $html);

        return trim($html);
    }

    /**
     * Placeholders ki list — page pe dikhane ke liye.
     */
    public static function placeholders(): array
    {
        return [
            '{{student_name}}'   => 'Student ka naam',
            '{{course_name}}'    => 'Course ka title',
            '{{certificate_no}}' => 'HSBTE-2026-000001',
            '{{issue_date}}'     => 'Issue ki date',
            '{{trainer_name}}'   => 'Trainer ka naam',
            '{{department}}'     => 'Department',
            '{{enrollment_no}}'  => 'Student ka roll no.',
        ];
    }

    /**
     * Placeholder bharo — str_replace only, Blade NEVER.
     */
    public static function fill(string $html, array $data): string
    {
        return str_replace(array_keys($data), array_values($data), $html);
    }
}
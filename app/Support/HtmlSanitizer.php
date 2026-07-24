<?php

namespace App\Support;

class HtmlSanitizer
{
    /**
     * Make the trainer's HTML safe.
     * This is for the certificate design — strip out script/iframe/form/php entirely.
     */
    public static function clean(string $html): string
    {
        // PHP tags — first of all
        $html = str_replace(['<?php', '<?=', '<?', '?>'], '', $html);

        // dangerous tags (along with their content)
        $html = preg_replace(
            '#<\s*(script|iframe|object|embed|applet|form|link|meta|base|noscript)\b[^>]*>.*?<\s*/\s*\1\s*>#is',
            '',
            $html
        );

        // their leftover / self-closing versions
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
        // NOTE: data:image/... is allowed — the logo/signature comes through it
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
     * The list of placeholders — for display on the page.
     */
    public static function placeholders(): array
    {
        return [
            '{{student_name}}'   => 'Student name',
            '{{course_name}}'    => 'Course title',
            '{{certificate_no}}' => 'HSBTE-2026-000001',
            '{{issue_date}}'     => 'Issue date',
            '{{trainer_name}}'   => 'Trainer name',
            '{{department}}'     => 'Department',
            '{{enrollment_no}}'  => 'Student roll no.',
        ];
    }

    /**
     * Fill in the placeholders — str_replace only, Blade NEVER.
     */
    public static function fill(string $html, array $data): string
    {
        return str_replace(array_keys($data), array_values($data), $html);
    }
}
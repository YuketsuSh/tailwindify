<?php

namespace Azuriom\Plugin\Tailwindify\Helpers;

use Azuriom\Plugin\Tailwindify\Models\TailwindifyReplacement;

class BootstrapClassReplacer
{

    public static function replaceBootstrapClasses(string $content): string
    {
        $replacements = TailwindifyReplacement::where('status', 'validated')->get();

        foreach ($replacements as $replacement) {
            $content = preg_replace('/\b' . preg_quote($replacement->bootstrap_class, '/') . '\b/', $replacement->tailwind_class, $content);
        }

        return $content;
    }
}

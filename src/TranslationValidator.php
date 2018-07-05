<?php

namespace CrazyFactory\Translations;


class TranslationValidator
{
    public static function isValidKey(string $key): bool
    {
        $re = '/^[a-z0-9]*([._][a-z0-9]*)*$/m';

        return preg_match($re, $key);
    }

    public static function isValidValue(string $value): bool
    {
        $re = '/^[{<].*[>}]/m';

        return !preg_match($re, $value);
    }

    public static function isValidLocale(string $locale): bool
    {
        $languages = ['cs', 'de', 'en-GB', 'en-US', 'es', 'fr', 'hr', 'it', 'nb', 'nl', 'pl', 'pt', 'fi', 'sv'];

        return in_array($locale, $languages);
    }
}

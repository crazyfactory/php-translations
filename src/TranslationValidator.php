<?php

namespace CrazyFactory\Translations;


class TranslationValidator
{
    public static function isValidKey(string $key): bool {
        return true;
    }

    public static function isValidValue(string $value): bool {
        return true;
    }

    public static function isValidLocale(string $locale): bool {
        return true;
    }
}

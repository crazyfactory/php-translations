<?php

namespace CrazyFactory\Translations;

interface ITranslationValuesProvider
{
    public function findValues(?array $scopes, ?array $locales): array;
}

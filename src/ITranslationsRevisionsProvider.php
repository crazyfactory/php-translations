<?php

namespace CrazyFactory\Translations;

interface ITranslationsRevisionsProvider
{
    /**
     * retrieves a list of all matching revisions for a given combination of scopes, locales, states
     *
     * @param string[]|null $scopes
     * @param string[]|null $locales
     * @param string[]|null $states
     * @return array[]
     */
    public function findRevisions(?array $scopes, ?array $locales, ?array $states = null): array;
}

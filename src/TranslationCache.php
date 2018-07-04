<?php

namespace CrazyFactory\Translations;

class TranslationCache
{
    /** @var string $locale */
    protected $locale;

    /** @var string $fallbackLocale */
    protected $fallbackLocale;

    /** @var string $dir the cache directory */
    protected $dir;

    /** @var string[] $scopes list of already loaded scopes */
    protected $scopes = [];

    /** @var string[] $values list of all values */
    protected $values = [];

    /** @var ITranslationValuesProvider $valuesProvider */
    protected $valuesProvider;

    /**
     * TranslationCache constructor.
     * @param string $locale
     * @param null|string $fallbackLocale
     * @param string $dir
     * @param ITranslationValuesProvider $valuesProvider
     * @param TranslationValidator|null $validator
     */
    public function __construct(
        string $locale,
        ?string $fallbackLocale,
        string $dir
    )
    {
        if (!is_dir($dir))
        {
            throw new \InvalidArgumentException('Directory not found');
        }
        elseif (!TranslationValidator::isValidLocale($locale))
        {
            throw new \InvalidArgumentException('Invalid locale');
        }
        elseif ($fallbackLocale !== null && !TranslationValidator::isValidLocale($fallbackLocale))
        {
            throw new \InvalidArgumentException('Invalid fallback locale');
        }
        $this->locale = $locale;
        $this->fallbackLocale = $fallbackLocale;
        $this->dir = $dir;
    }

    /**
     * @param array $scopes
     * @return array
     */
    public function load(array $scopes): array
    {
        /*$this->scopes = $this->filterScopesToLoad($scopes);

        if (empty($scopes))
        {
            return [];
        }

        $hashScopes = md5(implode(',', $scopes));
        $filePath = $this->dir . '/'
            . $this->locale
            . ($this->fallbackLocale
                ? '_' . $this->fallbackLocale
                : '')
            . '_' . $hashScopes.'.php';

        $this->values = includeFile($filePath);
        if(!file_exists($filePath)){
            $this->values = include $filePath;
            return $this->values;
        }

        codecept_debug($this->values);

        return $this->values = include $filePath;*/
    }

    /**
     * @param array $scopes
     * @param array $locales
     * @return array
     */
    public function loadMerged(array $scopes, array $locales): array
    {

    }

    /**
     * @param string $key
     * @param null|string $default
     * @return null|string
     */
    public function get(string $key, ?string $default = null): ?string
    {
        return $this->values[ $key ] ?? $default;
    }

    /**
     * @param array $scopes
     * @return array
     */
    protected function filterScopesToLoad(array $scopes): array
    {
        return sort(array_unique($scopes));
    }

    /**
     * @param array $data
     * @param string $filePath
     * @return bool
     */
    protected function saveCacheFile(array $data, string $filePath): bool
    {

    }

    /**
     * @param array $data
     * @return string
     */
    protected function createCacheFileBody(array $data): string
    {

    }

    /**
     * @param string $scope
     * @param string $locale
     * @return array
     */
    protected function loadScope(string $scope, string $locale): array
    {
    }

}

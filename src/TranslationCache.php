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
        string $dir,
        ITranslationValuesProvider $valuesProvider
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
        $this->valuesProvider = $valuesProvider;
    }

    /**
     * @param array $scopes
     * @return array
     */
    public function load(array $scopes): array
    {
        $this->scopes = $this->filterScopesToLoad($scopes);

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
            . '_' . $hashScopes . '.php';

        if (file_exists($filePath))
        {
            codecept_debug($filePath);

            return $this->values = require $filePath;
        }

        $locales = $this->fallbackLocale
            ? [$this->locale, $this->fallbackLocale]
            : [$this->locale];

        $this->values = $this->loadMerged($scopes, $locales);
        $this->saveCacheFile($this->values, $filePath);

        return $this->values;
    }

    /**
     * @param array $scopes
     * @return array
     */
    protected function filterScopesToLoad(array $scopes): array
    {
        $scopes = array_unique($scopes);
        sort($scopes);

        return $scopes;
    }

    /**
     * @param array $scopes
     * @param array $locales
     * @return array
     */
    public function loadMerged(array $scopes, array $locales): array
    {
        $scopes = array_unique($scopes);
        $result = [];
        foreach ($locales as $locale)
        {
            foreach ($scopes as $scope)
            {
                $result = array_merge_recursive($result, $this->loadScope($scope, $locale));
            }
        }

        return $result;
    }

    /**
     * @param string $scope
     * @param string $locale
     * @return array
     */
    protected function loadScope(string $scope, string $locale): array
    {
        if (!TranslationValidator::isValidLocale($locale))
        {
            throw new \InvalidArgumentException('Invalid locale');
        }

        $hashScope = md5($scope);
        $filePath = $this->dir . '/'
            . $locale
            . '_' . $hashScope . '.php';

        if (!file_exists($filePath))
        {
            $values = $this->valuesProvider->findValues([$scope], [$locale]);
            $this->saveCacheFile($values, $filePath);
        }

        return require $filePath;
    }

    /**
     * @param array $data
     * @param string $filePath
     * @return bool
     */
    protected function saveCacheFile(array $data, string $filePath): bool
    {
        return file_put_contents($filePath, $this->createCacheFileBody($data));
    }

    /**
     * @param array $data
     * @return string
     */
    protected function createCacheFileBody(array $data): string
    {
        return '<?php return ' . var_export($data, true) . ';';
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

}

<?php

namespace CrazyFactory\Translations;

abstract class TranslationManagerBase implements ITranslationValuesProvider, ITranslationsRevisionsProvider
{
    protected $db;

    /**
     * TranslationManager constructor.
     * @param null $db
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * get translation by translation_id
     * @param int $id
     * @return array
     */
    public function get(int $id): array
    {
        $dataFromDB = $this->getRawGet($id);

        return $dataFromDB[ $id ] ?? [];
    }

    abstract protected function getRawGet(int $id);

    /**
     * get translation by key
     * @param string $key
     * @return array
     */
    public function getByKey(string $key): array
    {
        $dataFromDB = $this->getRawGetByKey($key);

        return $dataFromDB[ $key ] ?? [];
    }

    abstract protected function getRawGetByKey(string $key);

    /**
     * add translation
     * @param string $key
     * @param int|null $userId
     * @param null|string $scope
     * @param null|string $state
     * @return int|null
     */
    public function add(string $key, ?int $userId, ?string $scope = null, ?string $state = null): ?int
    {
        if (!TranslationValidator::isValidKey($key))
        {
            throw new \InvalidArgumentException('Invalid Key');
        }

        if ($state !== null && !TranslationValidator::isValidTranslationsState($state))
        {
            throw new \InvalidArgumentException('Invalid Translations State');
        }

        $translationId = $this->db->insert($key, $userId, $scope, $state);
        if ($translationId)
        {
            $this->addAction($translationId, $userId, $scope, $state);

            return $translationId;
        }
        else
        {
            return 0;
        }
    }

    /**
     * Saves all changes made to translations
     * @param int $translationId
     * @param int|null $userId
     * @param null|string $state
     * @param null|string $scope
     * @param null|string $key
     * @return int
     */
    public function addAction(
        int $translationId,
        ?int $userId,
        ?string $state,
        ?string $scope = null,
        ?string $key = null
    ): int
    {
        if ($key !== null && !TranslationValidator::isValidKey($key))
        {
            throw new \InvalidArgumentException('Invalid Key');
        }

        if ($state !== null && !TranslationValidator::isValidTranslationsState($state))
        {
            throw new \InvalidArgumentException('Invalid Translations State');
        }

        $translationActionId = $this->db->insert($translationId, $userId, $state, $scope, $key);

        return $translationActionId ?? 0;
    }

    /**
     * @param int $translationId
     * @param string $locale
     * @param int|null $userId
     * @param null|string $value
     * @param null|string $comment
     * @param null|string $state
     * @return int
     */
    public function addRevision(
        int $translationId,
        string $locale,
        ?int $userId,
        ?string $value,
        ?string $comment = null,
        ?string $state = null
    ): int
    {
        if (!TranslationValidator::isValidLocale($locale))
        {
            throw new \InvalidArgumentException('Invalid locale');
        }

        if ($value !== null && !TranslationValidator::isValidValue($value))
        {
            throw new \InvalidArgumentException('Invalid Value');
        }

        if ($state !== null && !TranslationValidator::isValidTranslationRevisionsState($state))
        {
            throw new \InvalidArgumentException('Invalid Translation Revisions State');
        }

        $translationRevisionId = $this->db->insert($translationId, $locale, $userId, $value, $comment, $state);
        if ($translationRevisionId)
        {
            $this->addRevisionAction($translationRevisionId, $userId, $state, $value, $comment);

            return $translationId;
        }
        else
        {
            return 0;
        }
    }

    /**
     * Saves all changes made to translation revision
     * @param int $translationRevisionId
     * @param int|null $userId
     * @param null|string $state
     * @param null|string $value
     * @param null|string $comment
     * @return int
     */
    public function addRevisionAction(
        int $translationRevisionId,
        ?int $userId,
        ?string $state,
        ?string $value,
        ?string $comment
    ): int
    {
        if ($value !== null && !TranslationValidator::isValidValue($value))
        {
            throw new \InvalidArgumentException('Invalid Value');
        }

        if ($state !== null && !TranslationValidator::isValidTranslationRevisionsState($state))
        {
            throw new \InvalidArgumentException('Invalid Translation Revisions State');
        }

        $translationRevisionActionId = $this->db->insert($translationRevisionId, $userId, $state, $value, $comment);

        return $translationRevisionActionId ?? 0;
    }

    /**
     * @param int $translationId
     * @param string $locale
     * @return array
     */
    public function getActiveRevision(int $translationId, string $locale): array
    {
        $dataFromDB = $this->getRawActiveRevision($translationId, $locale);

        return $dataFromDB ?? [];
    }

    abstract protected function getRawActiveRevision(int $translationId, string $locale);

    /**
     * @param int $translationId
     * @param array|null $locales
     * @param array|null $states
     * @return array
     */
    public function getRevisions(int $translationId, ?array $locales = null, ?array $states = null): array
    {
        $dataFromDB = $this->getRawRevisions($translationId, $locales, $states);

        return $dataFromDB ?? [];
    }

    abstract protected function getRawRevisions(int $translationId, ?array $locales = null, ?array $states = null);

    /**
     * @param int $translationRevisionId
     * @return array
     */
    public function getRevisionById(int $translationRevisionId): array
    {
        $dataFromDB = $this->getRawRevisionById($translationRevisionId);

        return $dataFromDB ?? [];
    }

    abstract protected function getRawRevisionById(int $translationRevisionId);

    /**
     * @param int $translationRevisionId
     * @return array
     */
    public function getRevisionActions(int $translationRevisionId): array
    {
        $dataFromDB = $this->getRawRevisionActions($translationRevisionId);;

        return $dataFromDB[ $translationRevisionId ]?? [];
    }

    abstract protected function getRawRevisionActions(int $translationRevisionId);

    /**
     * retrieves a list of translation which active.
     * @param array|null $scopes
     * @param array|null $locales
     * @return array
     */
    public function findValues(?array $scopes, ?array $locales): array
    {
        $dataFromDB = $this->getRawFindValue($scopes, $locales);

        $result = [];
        foreach ($dataFromDB as $row)
        {
            if (empty($result[ $row['key'] ]))
            {
                $result[ $row['key'] ] = [];
            }
            $result[ $row['key'] ][ $row['locale'] ] = $row['value'];
        }

        return $result ?? [];
    }

    abstract protected function getRawFindValue(?array $scopes, ?array $locales);

    /**
     * retrieves a list of translation as passed states argument.
     * @param array|null $scopes
     * @param array|null $locales
     * @param array|null $states
     * @return array
     */
    public function findRevisions(?array $scopes, ?array $locales, ?array $states = null): array
    {
        $dataFromDB = $this->getRawFindRevisions($scopes, $locales, $states);

        $result = [];
        foreach ($dataFromDB as $row)
        {
            if (empty($result[ $row['key'] ]))
            {
                $result[ $row['key'] ] = [];
            }
            $result[ $row['key'] ]['values'][ $row['locale'] ] = $row['value'];
            $result[ $row['key'] ]['states'][ $row['locale'] ] = $row['state'];
        }

        return $result;
    }

    abstract protected function getRawFindRevisions(?array $scopes, ?array $locales, ?array $states = null);
}

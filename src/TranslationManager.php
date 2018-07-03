<?php
namespace CrazyFactory\Translations;

abstract class TranslationManager implements ITranslationValuesProvider, ITranslationsRevisionsProvider
{
    abstract protected function getRawGet();

    abstract protected function getRawGetById();

    abstract protected function getRawActiveRevision();

    abstract protected function getRawRevisions();

    abstract protected function getRawRevisionById();

    abstract protected function getRawRevisionActions();

    abstract protected function getRawFindValue(?array $scopes, ?array $locales);

    abstract protected function getRawFindRevisions();

    protected $db;

    public function __construct($db = null)
    {
        $this->db = $db?? new MockDB();
    }

    // get translation by id or key (to look up scope and other columns). does not include value
    public function get(int $id): array
    {
        $dataFromDB = $this->getRawGet();

        return $dataFromDB[ $id ] ?? [];
    }

    public function getByKey(string $key): array
    {
        $dataFromDB = $this->getRawGetById();

        return $dataFromDB[ $key ] ?? [];
    }

    // add a new translation key, return the insert id, creates entry first, then uses addAction()
    public function add(string $key, ?int $userId, ?string $scope = null, ?string $state = null): ?int
    {
        $translationId = $this->db->update();
        if ($translationId)
        {
            $this->addAction($translationId, $userId, $scope);

            return $translationId;
        }
        else
        {
            return 0;
        }
    }

    // add a new revision for a given translation/locale combination (adds first, then uses addRevisionAction()
    public function addRevision(
        int $translationId,
        string $locale,
        ?int $userId,
        ?string $value,
        ?string $comment = null,
        ?string $state = null
    ): int
    {
        $translationRevisionId = $this->db->update();
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

    // get the currently active revision for a single translation/locale combination
    public function getActiveRevision(int $translationId, string $locale): array
    {
        $dataFromDB = $this->getRawActiveRevision();

        return $dataFromDB[ $translationId ][ $locale ]?? [];
    }

    public function getRevisions(int $translationId, ?array $locales = null, ?array $states = null): array
    {
        $dataFromDB = $this->getRawRevisions();

        return $dataFromDB[ $translationId ]?? [];
    }

    public function getRevisionById(int $translationRevisionId): array
    {
        $dataFromDB = $this->getRawRevisionById();

        return $dataFromDB[ $translationRevisionId ]?? [];
    }

    // returns a list of all action for this revision
    public function getRevisionActions(int $translationRevisionId): array
    {
        $dataFromDB = $this->getRawRevisionActions();;

        return $dataFromDB[ $translationRevisionId ]?? [];
    }

    // retrieves a list of all keys/values for the provided scopes and locale combinations.
    // returns { id:int, key:string, values: {[key: string]: string|null}}[]
    public function findValues(?array $scopes, ?array $locales): array
    {
        $dataFromDB = $this->getRawFindValue($scopes, $locales);

        $result = [];
        foreach ($dataFromDB as $row)
        {
            $data = [];
            if (empty($result[ $row['scope'] ]))
            {
                $result[ $row['scope'] ] = [];
                $data['id'] = $row['translation_id'];
                $data['key'] = $row['key'];
                $data['values'] = [$row['locale'] => $row['value']];
                $result[ $row['scope'] ][] = $data;
            }
            else
            {
                if (($index = array_search($row['translation_id'], array_column($result[ $row['scope'] ], 'id'))) > -1)
                {
                    $result[ $row['scope'] ][ $index ]['values'][ $row['locale'] ] = $row['value'];
                }
            }
        }

        return $result ?? [];
    }

    // retrieves a list of all matching revisions for a given combination of scopes, locales, states
    public function findRevisions(?array $scopes, ?array $locales, ?array $states = null): array
    {

        $dataFromDB = $this->getRawFindRevisions();

        $result = [];
        foreach ($scopes as $scope)
        {
            foreach ($locales as $locale)
            {
                $result[] = $dataFromDB[ $scope ][ $locale ];
            }
        }

        return $result;
    }

    // logging and state updates
    public function addAction(
        int $translationId,
        ?int $userId,
        ?string $state,
        ?string $scope = null,
        ?string $key = null
    ): int
    {
        $translationActionId = $this->db->update();

        return $translationActionId ?? 0;
    }

    public function addRevisionAction(
        int $translationRevisionId,
        ?int $userId,
        ?string $state,
        ?string $value,
        ?string $comment
    ): int
    {
        $translationRevisionActionId = $this->db->update();

        return $translationRevisionActionId ?? 0;
    }
}

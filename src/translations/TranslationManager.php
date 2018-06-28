<?php

class TranslationManager
{
    protected $db;

    public function __construct($db = null)
    {
        $this->db = $db?? new DBTest();
    }

    public static $translation = [
        [
            "translation_id" => 1,
            "key"            => "Piercing Jewellery-Deal of the day",

        ],
    ];

    // get translation by id or key (to look up scope and other columns). does not include value
    public function get(int $id): array
    {
        $dataFromDB = [
            1 => [
                [
                    'translation_revision_id' => 1,
                    'local'                   => 'en-GB',
                    'translation_id'          => 1,
                ],
                [
                    'translation_revision_id' => 2,
                    'local'                   => 'de',
                    'translation_id'          => 1,
                ],
                [
                    'translation_revision_id' => 3,
                    'local'                   => 'fr',
                    'translation_id'          => 1,
                ],
            ],
            2 => [
                [
                    'translation_revision_id' => 4,
                    'local'                   => 'en-GB',
                    'translation_id'          => 2,
                ],
                [
                    'translation_revision_id' => 5,
                    'local'                   => 'de',
                    'translation_id'          => 2,
                ],
                [
                    'translation_revision_id' => 6,
                    'local'                   => 'fr',
                    'translation_id'          => 2,
                ],
            ],
        ];

        return $dataFromDB[ $id ] ?? [];
    }

    public function getByKey(string $key): array
    {
        $dataFromDB = [
            'key 1' => [
                [
                    'translation_revision_id' => 1,
                    'local'                   => 'en-GB',
                    'translation_id'          => 1,
                ],
                [
                    'translation_revision_id' => 2,
                    'local'                   => 'de',
                    'translation_id'          => 1,
                ],
                [
                    'translation_revision_id' => 3,
                    'local'                   => 'fr',
                    'translation_id'          => 1,
                ],
            ],
            'key 2' => [
                [
                    'translation_revision_id' => 4,
                    'local'                   => 'en-GB',
                    'translation_id'          => 2,
                ],
                [
                    'translation_revision_id' => 5,
                    'local'                   => 'de',
                    'translation_id'          => 2,
                ],
                [
                    'translation_revision_id' => 6,
                    'local'                   => 'fr',
                    'translation_id'          => 2,
                ],
            ],
        ];

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
        $dataFromDB = [
            1 => ['en-GB' =>
                      [
                          'translation_revision_id' => 1,
                          'translation_id'          => 1,
                          'locale'                  => 'en-GB',
                          'value'                   => 'test value of en-GB language',
                          'state'                   => 'active',
                      ],
            ],
            2 => ['fr' =>
                      [
                          'translation_revision_id' => 1,
                          'translation_id'          => 2,
                          'locale'                  => 'fr',
                          'value'                   => 'test value of fr language',
                          'state'                   => 'active',
                      ],
            ],
        ];

        return $dataFromDB[ $translationId ][ $locale ]?? [];
    }

    //
    public function getRevisions(int $translationId, ?array $locales = null, ?array $states = null): array
    {
        $dataFromDB = [
            1 => [
                [
                    'translation_revision_id' => 1,
                    'translation_id'          => 1,
                    'locale'                  => 'en-GB',
                    'value'                   => 'test value of en-GB language',
                    'state'                   => 'active',
                ],
                [
                    'translation_revision_id' => 2,
                    'translation_id'          => 1,
                    'locale'                  => 'fr',
                    'value'                   => 'test value of fr language',
                    'state'                   => 'pending',
                ],
            ],
            2 => [
                [
                    'translation_revision_id' => 1,
                    'translation_id'          => 2,
                    'locale'                  => 'en-GB',
                    'value'                   => 'test value of en-GB language',
                    'state'                   => 'active',
                ],
                [
                    'translation_revision_id' => 2,
                    'translation_id'          => 2,
                    'locale'                  => 'fr',
                    'value'                   => 'test value of fr language',
                    'state'                   => 'pending',
                ],
            ],
        ];

        return $dataFromDB[ $translationId ]?? [];
    }

    public function getRevisionById(int $translationRevisionId): array
    {
        $dataFromDB = [
            1 => [
                [
                    'translation_revision_id' => 1,
                    'translation_id'          => 1,
                    'locale'                  => 'en-GB',
                    'value'                   => 'test value of en-GB language',
                    'state'                   => 'active',
                ],
            ],
            2 => [
                [
                    'translation_revision_id' => 1,
                    'translation_id'          => 1,
                    'locale'                  => 'fr',
                    'value'                   => 'test value of fr language',
                    'state'                   => 'active',
                ],
            ],
        ];

        return $dataFromDB[ $translationRevisionId ]?? [];
    }

    // returns a list of all action for this revision
    public function getRevisionActions(int $translationRevisionId): array
    {
        $dataFromDB = [
            1 => [
                [
                    'translation_revision_action_id' => 1,
                    'translation_revision_id'        => 1,
                    'user_id'                        => 1,
                    'create_at'                      => '0000-00-00',
                    'comment'                        => 'test comment',
                    'value'                          => 'test value',
                    'state'                          => 'active',
                ],
            ],
            2 => [
                [
                    'translation_revision_action_id' => 5,
                    'translation_revision_id'        => 2,
                    'user_id'                        => 1,
                    'create_at'                      => '0000-00-00',
                    'comment'                        => 'test comment',
                    'value'                          => 'test value',
                    'state'                          => 'pending',
                ],
            ],
        ];

        return $dataFromDB[ $translationRevisionId ]?? [];
    }

    // retrieves a list of all keys/values for the provided scopes and locale combinations.
    // returns { id:int, key:string, values: {[key: string]: string|null}}[]
    public function findValues(?array $scopes, ?array $locales): array
    {
        $dataFromDB = [
            'default' => [
                'de'    => [
                    'id'     => 1,
                    'key'    => 'de',
                    'values' => [
                        "ok"    => "oki",
                        "hello" => "halo",
                    ],
                ],
                'en-GB' => [
                    'id'     => 1,
                    'key'    => 'en-GB',
                    'values' => [
                        "ok"    => "ok",
                        "hello" => "hello",
                    ],
                ],
            ],
            'shop'    => [
                'de'    => [
                    'id'     => 1,
                    'key'    => 'de',
                    'values' => [
                        "test"    => "Prüfung",
                        "welcome" => "herzlich willkommen",
                    ],
                ],
                'en-GB' => [
                    'id'     => 1,
                    'key'    => 'en-GB',
                    'values' => [
                        "test"    => "test",
                        "welcome" => "welcome",
                    ],
                ],
            ],
        ];

        $result = [];
        foreach ($scopes as $scope)
        {
            $result[ $scope ] = [];
            foreach ($locales as $locale)
            {
                $result[$scope][] = $dataFromDB[ $scope ][ $locale ];
            }
        }

        return $result;

    }

    // retrieves a list of all matching revisions for a given combination of scopes, locales, states
    /* public function findRevisions(?array $scopes, ?array $locales): array
     {
         $dataFromDB = [
             'default' => [
                 'de'    => [
                     [
                         'key'   => 'ok',
                         'value' => 'oki',
                         'state' => 'pending',
                     ],
                     [
                         'key'   => 'test',
                         'value' => 'Prüfung',
                         'state' => 'active',
                     ],
                 ],
                 'en-GB' => [
                     [
                         'key'   => 'ok',
                         'value' => 'ok',
                         'state' => 'active',
                     ],
                     [
                         'key'   => 'test',
                         'value' => 'test',
                         'state' => 'active',
                     ],
                 ],
             ],
             'shop'    => [
                 'de'    => [
                     'id'     => 1,
                     'key'    => 'de',
                     'values' => [
                         "test"    => "Prüfung",
                         "welcome" => "herzlich willkommen",
                     ],
                 ],
                 'en-GB' => [
                     'id'     => 1,
                     'key'    => 'en-GB',
                     'values' => [
                         "test"    => "test",
                         "welcome" => "welcome",
                     ],
                 ],
             ],
         ];

         $result = [];
         foreach ($scopes as $scope)
         {
             foreach ($locales as $locale)
             {
                 $result[] = $dataFromDB[ $scope ][ $locale ];
             }
         }

         return $result;

     }*/

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

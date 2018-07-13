<?php

namespace CrazyFactory\Translations;

class MockData extends TranslationManagerBase
{
    /**
     * MockData constructor.
     * @param null $db
     */
    public function __construct($db)
    {
        parent::__construct($db);
    }

    /**
     * @param int $id
     * @return array
     */
    public function getRawGet(int $id): array
    {
        $dataFromDB = [
            1 => [
                [
                    'translation_revision_id' => 1,
                    'locale'                  => 'en-GB',
                    'translation_id'          => 1,
                    'scope'                   => 'shop',
                ],
                [
                    'translation_revision_id' => 2,
                    'locale'                  => 'de',
                    'translation_id'          => 1,
                    'scope'                   => 'shop',
                ],
                [
                    'translation_revision_id' => 3,
                    'locale'                  => 'fr',
                    'translation_id'          => 1,
                    'scope'                   => 'shop',
                ],
            ],
            2 => [
                [
                    'translation_revision_id' => 4,
                    'locale'                  => 'en-GB',
                    'translation_id'          => 2,
                    'scope'                   => 'default',
                ],
                [
                    'translation_revision_id' => 5,
                    'locale'                  => 'de',
                    'translation_id'          => 2,
                    'scope'                   => 'default',
                ],
                [
                    'translation_revision_id' => 6,
                    'locale'                  => 'fr',
                    'translation_id'          => 2,
                    'scope'                   => 'default',
                ],
            ],
        ];

        return $dataFromDB;
    }

    /**
     * @param string $key
     * @return array
     */
    public function getRawGetByKey(string $key): array
    {
        $dataFromDB = [
            'key 1' => [
                [
                    'translation_revision_id' => 1,
                    'locale'                  => 'en-GB',
                    'translation_id'          => 1,
                ],
                [
                    'translation_revision_id' => 2,
                    'locale'                  => 'de',
                    'translation_id'          => 1,
                ],
                [
                    'translation_revision_id' => 3,
                    'locale'                  => 'fr',
                    'translation_id'          => 1,
                ],
            ],
            'key 2' => [
                [
                    'translation_revision_id' => 4,
                    'locale'                  => 'en-GB',
                    'translation_id'          => 2,
                ],
                [
                    'translation_revision_id' => 5,
                    'locale'                  => 'de',
                    'translation_id'          => 2,
                ],
                [
                    'translation_revision_id' => 6,
                    'locale'                  => 'fr',
                    'translation_id'          => 2,
                ],
            ],
        ];

        return $dataFromDB;
    }

    /**
     * @param int $translationId
     * @param string $locale
     * @return array
     */
    public function getRawActiveRevision(int $translationId, string $locale): array
    {
        $dataFromDB = [
            1 => [
                'en-GB' => 'test value of en-GB language',
            ],
            2 => [
                'fr' => 'test value of fr language',
            ],
        ];

        return $dataFromDB[ $translationId ] ?? [];
    }

    /**
     * @param int $translationId
     * @param array|null $locales
     * @param array|null $states
     * @return array
     */
    public function getRawRevisions(int $translationId, ?array $locales = null, ?array $states = null): array
    {
        $dataFromDB = [
            1 => [
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
            ],
            2 => [
                1 => [
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
            ],
        ];

        return $dataFromDB[ $translationId ] ?? [];
    }

    /**
     * @param int $translationRevisionId
     * @return array
     */
    public function getRawRevisionById(int $translationRevisionId): array
    {
        $dataFromDB = [
            1 => [
                'translation_revision_id' => 1,
                'translation_id'          => 1,
                'locale'                  => 'en-GB',
                'value'                   => 'test value of en-GB language',
                'state'                   => 'active',
            ],
            2 => [
                'translation_revision_id' => 2,
                'translation_id'          => 1,
                'locale'                  => 'fr',
                'value'                   => 'test value of fr language',
                'state'                   => 'active',
            ],
        ];

        return $dataFromDB[ $translationRevisionId ] ?? [];
    }

    /**
     * @param int $translationRevisionId
     * @return array
     */
    public function getRawRevisionActions(int $translationRevisionId): array
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
                    'state'                          => 'activated',
                ],
                [
                    'translation_revision_action_id' => 1,
                    'translation_revision_id'        => 1,
                    'user_id'                        => 1,
                    'create_at'                      => '0000-00-00',
                    'comment'                        => 'test comment2',
                    'value'                          => 'test value2',
                    'state'                          => 'pending',
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

        return $dataFromDB;
    }

    public function getRawFindValue(?array $scopes, ?array $locales): array
    {
        $dataFromDB = [
            [
                'scope'          => 'default',
                'translation_id' => 1,
                'key'            => 'ok',
                'locale'         => 'de',
                'value'          => 'oki',
            ],
            [
                'scope'          => 'default',
                'translation_id' => 1,
                'key'            => 'ok',
                'locale'         => 'en-GB',
                'value'          => 'ok',
            ],
            [
                'scope'          => 'shop',
                'translation_id' => 2,
                'key'            => 'test',
                'locale'         => 'en-GB',
                'value'          => 'test',
            ],
            [
                'scope'          => 'shop',
                'translation_id' => 2,
                'key'            => 'test',
                'locale'         => 'de',
                'value'          => 'Prüfung',
            ],
        ];

        return $dataFromDB;
    }

    /**
     * @param array|null $scopes
     * @param array|null $locales
     * @param array|null $states
     * @return array
     */
    public function getRawFindRevisions(?array $scopes, ?array $locales, ?array $states = null): array
    {
        $dataFromDB = [
            [
                'scope'          => 'default',
                'translation_id' => 1,
                'key'            => 'ok',
                'locale'         => 'de',
                'value'          => 'oki',
                'state'          => 'pending',
            ],
            [
                'scope'          => 'default',
                'translation_id' => 1,
                'key'            => 'ok',
                'locale'         => 'en-GB',
                'value'          => 'ok',
                'state'          => 'activated',
            ],
            [
                'scope'          => 'shop',
                'translation_id' => 2,
                'key'            => 'test',
                'locale'         => 'en-GB',
                'value'          => 'test',
                'state'          => 'activated',
            ],
            [
                'scope'          => 'shop',
                'translation_id' => 2,
                'key'            => 'test',
                'locale'         => 'de',
                'value'          => 'Prüfung',
                'state'          => 'activated',
            ],
        ];

        return $dataFromDB;
    }
}

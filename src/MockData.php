<?php
namespace CrazyFactory\Translations;

class mockData extends TranslationManager
{
    public function getRawGet(): array
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

        return $dataFromDB;
    }

    public function getRawGetById(): array
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

        return $dataFromDB;
    }

    public function getRawActiveRevision(): array
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

        return $dataFromDB;
    }

    public function getRawRevisions(): array
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

        return $dataFromDB;
    }

    public function getRawRevisionById(): array
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

        return $dataFromDB;
    }

    public function getRawRevisionActions(): array
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

    public function getRawFindRevisions(): array
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

        return $dataFromDB;
    }
}

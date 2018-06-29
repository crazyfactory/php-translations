<?php
use Codeception\Stub;

require_once("tests/unit/MockData.php");
require_once("src/translations/MockDB.php");

class TranslationManagerTest extends \Codeception\Test\Unit
{
    // tests
    public function testGet()
    {
        $this->specify("It should return translation data by id", function($id, $expected)
        {
            $translationManager = new MockData();
            $result = $translationManager->get($id);
            verify($result)->equals($expected);
        }, [
            'examples' => [
                'Translate with translation_id = 1'                  => [
                    'id'       => 1,
                    'expected' => [
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
                ],
                'Translate with translation_id = 2'                  => [
                    'id'       => 2,
                    'expected' => [
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
                ],
                'Translate with translation_id that not exist in DB' => [
                    'id'       => 100,
                    'expected' => [],
                ],
            ],
        ]);
    }

    public function testGetByKey()
    {
        $this->specify("It should return translation data by key", function($key, $expected)
        {
            $translationManager = new mockData();
            $result = $translationManager->getByKey($key);
            verify($result)->equals($expected);
        }, [
            'examples' => [
                'Translate with `key` = test1'              => [
                    'key'      => 'key 1',
                    'expected' => [
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
                ],
                'Translate with `key` = test2'              => [
                    'id'       => 'key 2',
                    'expected' => [
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
                ],
                'Translate with `key` that not exist in DB' => [
                    'key'      => 'key-not-exist',
                    'expected' => [],
                ],
            ],
        ]);
    }

    public function testAdd()
    {
        $this->specify('It should return translationId value when record updated successfully.', function()
        {
            $mockDb = Stub::makeEmpty(MockDB::class, [
                'update' => 123456,
            ]);

            $mockTranslation = Stub::construct(
                mockData::class, [$mockDb], [
                'addAction' => $translationActionId = Codeception\Stub\Expected::once(function()
                {
                    return 1;
                }),
            ]);

            $result = $mockTranslation->add("Hello", 789);
            verify($result)->equals(123456);

            $translationActionId->getMatcher()->verify();
        });

        $this->specify('It should return 0 value when record updated failed.', function()
        {
            $mockDb = Stub::makeEmpty(MockDB::class, [
                'update' => 0,
            ]);

            $mockTranslation = Stub::construct(
                MockData::class, [$mockDb], [
                'addAction' => $translationActionId = Codeception\Stub\Expected::never(function()
                {
                    return 0;
                }),
            ]);

            $result = $mockTranslation->add("Hello", 789);
            verify($result)->equals(0);
            $translationActionId->getMatcher()->verify();
        });
    }

    public function testAddRevision()
    {
        $this->specify('It should return translationRevisionId value when record updated successfully.', function()
        {
            $mockDb = Stub::makeEmpty(MockDB::class, [
                'update' => 123456,
            ]);

            $mockTranslation = Stub::construct(
                MockData::class, [$mockDb], [
                'addRevisionAction' => $translationRevisionActionId = Codeception\Stub\Expected::once(function()
                {
                    return 123456;
                }),
            ]);

            $result = $mockTranslation->addRevision(123456, 'default', 789, 'Hello');
            verify($result)->equals(123456);

            $translationRevisionActionId->getMatcher()->verify();
        });

        $this->specify('It should return translationRevisionId = 0 value when record updated failed.', function()
        {
            $mockDb = Stub::makeEmpty(MockDB::class, [
                'update' => 0,
            ]);

            $mockTranslation = Stub::construct(
                MockData::class, [$mockDb], [
                'addAction' => $translationActionId = Codeception\Stub\Expected::never(function()
                {
                    return 0;
                }),
            ]);

            $result = $mockTranslation->addRevision(123456, 'default', 789, 'Hello');
            verify($result)->equals(0);
            $translationActionId->getMatcher()->verify();
        });

    }

    public function testGetActiveRevision()
    {
        $this->specify("return active translation revision", function($translationId, $locale, $expected)
        {
            $translationManager = new mockData();
            $result = $translationManager->getActiveRevision($translationId, $locale);
            verify($result)->equals($expected);
        }, [
            'examples' => [
                'return translation revision data of en-GB' => [
                    'translationId' => 1,
                    'locale'        => 'en-GB',
                    'expected'      => [
                        'translation_revision_id' => 1,
                        'translation_id'          => 1,
                        'locale'                  => 'en-GB',
                        'value'                   => 'test value of en-GB language',
                        'state'                   => 'active',
                    ],
                ],
                'return translation revision data of fr'    => [
                    'translationId' => 2,
                    'locale'        => 'fr',
                    'expected'      => [
                        'translation_revision_id' => 1,
                        'translation_id'          => 2,
                        'locale'                  => 'fr',
                        'value'                   => 'test value of fr language',
                        'state'                   => 'active',
                    ],
                ],
                'return empty array'                        => [
                    'translationId' => 3,
                    'locale'        => 'locale isnt exist',
                    'expected'      => [],
                ],
            ],
        ]);
    }

    public function testGetRevisions()
    {
        $this->specify("return translation revision", function($translationId, $locales, $states, $expected)
        {
            $translationManager = new mockData();
            $results = $translationManager->getRevisions($translationId);
            verify($results)->equals($expected);

        }, [
            'examples' => [
                'return all data' => [
                    'translationId' => 1,
                    'locales'       => 'en-GB',
                    'states'        => 'active',
                    'expected'      => [
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
                'return all data' => [
                    'translationId' => 2,
                    'locales'       => 'en-GB',
                    'states'        => 'active',
                    'expected'      => [
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
                'return all data' => [
                    'translationId' => 3,
                    'locales'       => 'en-GB',
                    'states'        => 'active',
                    'expected'      => [],
                ],
            ],
        ]);
    }

    public function testAddAction()
    {
        $this->specify('It should return translationActionId value when record updated successfully.', function()
        {
            $mockDb = Stub::makeEmpty(MockDB::class, [
                'update' => 123456,
            ]);

            $mockTranslation = new MockData($mockDb);

            $result = $mockTranslation->addAction(1, 789, 'pending');
            verify($result)->equals(123456);
        });

        $this->specify('It should return 0 value when record updated failed.', function()
        {
            $mockDb = Stub::makeEmpty(MockDB::class, [
                'update' => 0,
            ]);

            $mockTranslation = new MockData($mockDb);
            $result = $mockTranslation->addAction(1, 789, 'pending');
            verify($result)->equals(0);
        });
    }

    public function testAddRevisionAction()
    {
        $this->specify('It should return translationActionId value when record updated successfully.', function()
        {
            $mockDb = Stub::makeEmpty(MockDB::class, [
                'update' => 123456,
            ]);

            $mockTranslation = new MockData($mockDb);

            $result = $mockTranslation->addRevisionAction(1, 789, 'pending', "test-value", "test-comment");
            verify($result)->equals(123456);
        });

        $this->specify('It should return 0 value when record updated failed.', function()
        {
            $mockDb = Stub::makeEmpty(MockDB::class, [
                'update' => 0,
            ]);

            $mockTranslation = new MockData($mockDb);
            $result = $mockTranslation->addRevisionAction(1, 789, 'pending', "test-value", "test-comment");
            verify($result)->equals(0);
        });
    }

    public function testGetRevisionById()
    {
        $this->specify("Test", function($translationRevisionId, $expected)
        {
            $translationManager = new mockData();
            $results = $translationManager->getRevisionById($translationRevisionId);
            verify($results)->equals($expected);

        }, [
            'examples' => [
                'return all data where translation_revision_id =1'         => [
                    'translationRevisionId' => 1,
                    'expected'              => [
                        [
                            'translation_revision_id' => 1,
                            'translation_id'          => 1,
                            'locale'                  => 'en-GB',
                            'value'                   => 'test value of en-GB language',
                            'state'                   => 'active',
                        ],
                    ],
                ],
                'return all data where translation_revision_id =2'         => [
                    'translationRevisionId' => 2,
                    'expected'              => [
                        [
                            'translation_revision_id' => 1,
                            'translation_id'          => 1,
                            'locale'                  => 'fr',
                            'value'                   => 'test value of fr language',
                            'state'                   => 'active',
                        ],
                    ],
                ],
                'return empty array if translationRevisionId doesnt exist' => [
                    'translationRevisionId' => 3,
                    'expected'              => [],
                ],
            ],
        ]);
    }

    public function testGetRevisionActions()
    {
        $this->specify("Test", function($translationRevisionId, $expected)
        {
            $translationManager = new mockData();
            $results = $translationManager->getRevisionActions($translationRevisionId);
            verify($results)->equals($expected);

        }, [
            'examples' => [
                'return all data where translation_revision_id =1'         => [
                    'translationRevisionId' => 1,
                    'expected'              => [
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
                ],
                'return all data where translation_revision_id =2'         => [
                    'translationRevisionId' => 2,
                    'expected'              => [
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
                ],
                'return empty array if translationRevisionId doesnt exist' => [
                    'translationRevisionId' => 3,
                    'expected'              => [],
                ],
            ],
        ]);
    }

    public function testFindValues()
    {
        $this->specify("It should return data as passed argument.", function($scopes, $locales, $expected)
        {
            $translationManager = new mockData();
            $results = $translationManager->findValues($scopes, $locales);
            verify($results)->equals($expected);
        }, [
            'examples' => [
                'return translations where `scopes` = default and `locales` = de' => [
                    'scopes'   => ['default'],
                    'locales'  => ['de', 'en-GB'],
                    'expected' => [
                        'default' => [
                            [
                                'id'     => 1,
                                'key'    => 'ok',
                                'values' => [
                                    "en-GB" => "ok",
                                    "de"    => "oki",
                                ],
                            ],
                        ],
                        'shop'    => [
                            [
                                'id'     => 2,
                                'key'    => 'test',
                                'values' => [
                                    "en-GB" => "test",
                                    "de"    => "Prüfung",
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }
}

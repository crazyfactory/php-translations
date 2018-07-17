<?php

namespace CrazyFactory\Translations\Tests\Unit;

use Codeception\Stub\Expected;
use Codeception\Util\Stub;
use CrazyFactory\Translations\MockData;
use CrazyFactory\Translations\MockDB;
use CrazyFactory\Translations\Tests\Unit;

class TranslationManagerBaseTest extends Unit
{
    // tests
    public function testGet()
    {
        $this->specify("It should return translation data by id", function($id, $expected)
        {
            $translationManager = new MockData(new MockDB());
            $result = $translationManager->get($id);
            verify($result)->equals($expected);
        }, [
            'examples' => [
                'Translate with translation_id = 1'                  => [
                    'id'       => 1,
                    'expected' => [
                        'translation_id' => 1,
                        'key'            => 'ok',
                        'scope'          => 'shop',
                        'state'          => 'requested',
                    ],
                ],
                'Translate with translation_id = 2'                  => [
                    'id'       => 2,
                    'expected' => [
                        'translation_id' => 2,
                        'key'            => 'yes',
                        'scope'          => 'shop',
                        'state'          => 'active',
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
            $translationManager = new MockData(new MockDB());
            $result = $translationManager->getByKey($key);
            verify($result)->equals($expected);
        }, [
            'examples' => [
                'Translate with `key` = test1'              => [
                    'key'      => 'key 1',
                    'expected' => [
                        'translation_id' => 1,
                        'key'            => 'key 1',
                        'scope'          => 'shop',
                        'state'          => 'requested',
                    ],
                ],
                'Translate with `key` = test2'              => [
                    'id'       => 'key 2',
                    'expected' => [
                        'translation_id' => 2,
                        'key'            => 'key 2',
                        'scope'          => 'shop',
                        'state'          => 'active',
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
                'insert' => 123456,
            ]);

            $mockTranslation = Stub::construct(
                MockData::class, [$mockDb], [
                'addAction' => $translationActionId = Expected::once(function()
                {
                    return 1;
                }),
            ]);

            $result = $mockTranslation->add("hello", 789);
            verify($result)->equals(123456);

            $translationActionId->getMatcher()->verify();
        });

        $this->specify('It should return 0 value when record updated failed.', function()
        {
            $mockDb = Stub::makeEmpty(MockDB::class, [
                'insert' => 0,
            ]);

            $mockTranslation = Stub::construct(
                MockData::class, [$mockDb], [
                'addAction' => $translationActionId = Expected::never(function()
                {
                    return 0;
                }),
            ]);

            $result = $mockTranslation->add("hello", 789);
            verify($result)->equals(0);
            $translationActionId->getMatcher()->verify();
        });
    }

    public function testAddRevision()
    {
        $this->specify('It should return translationRevisionId value when record updated successfully.', function()
        {
            $mockDb = Stub::makeEmpty(MockDB::class, [
                'insert' => 123456,
            ]);

            $mockTranslation = Stub::construct(
                MockData::class, [$mockDb], [
                'addRevisionAction' => $translationRevisionActionId = Expected::once(function()
                {
                    return 123456;
                }),
            ]);

            $result = $mockTranslation->addRevision(123456, 'de', 789, 'hello ka');
            verify($result)->equals(123456);

            $translationRevisionActionId->getMatcher()->verify();
        });

        $this->specify('It should return translationRevisionId = 0 value when record updated failed.', function()
        {
            $mockDb = Stub::makeEmpty(MockDB::class, [
                'insert' => 0,
            ]);

            $mockTranslation = Stub::construct(
                MockData::class, [$mockDb], [
                'addAction' => $translationActionId = Expected::never(function()
                {
                    return 0;
                }),
            ]);

            $result = $mockTranslation->addRevision(123456, 'de', 789, 'hello');
            verify($result)->equals(0);
            $translationActionId->getMatcher()->verify();
        });

    }

    public function testGetActiveRevision()
    {
        $this->specify("return active translation revision", function($translationId, $locale, $expected)
        {
            $translationManager = new MockData(new MockDB());
            $result = $translationManager->getActiveRevision($translationId, $locale);
            verify($result)->equals($expected);
        }, [
            'examples' => [
                'return translation revision data of en-GB' => [
                    'translationId' => 1,
                    'locale'        => 'en-GB',
                    'expected'      => [
                        [
                            'translation_id'          => 1,
                            'translation_revision_id' => 1,
                            'local'                   => 'de',
                            'value'                   => 'Oki',
                        ],
                        [
                            'translation_id'          => 1,
                            'translation_revision_id' => 2,
                            'local'                   => 'en-GB',
                            'value'                   => 'Ok',
                        ],
                    ],
                ],
                'return translation revision data of fr'    => [
                    'translationId' => 2,
                    'locale'        => 'fr',
                    'expected'      => [
                        [
                            'translation_id'          => 2,
                            'translation_revision_id' => 4,
                            'local'                   => 'de',
                            'value'                   => 'Ja',
                        ],
                        [
                            'translation_id'          => 2,
                            'translation_revision_id' => 5,
                            'local'                   => 'en-GB',
                            'value'                   => 'Yes',
                        ],
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
            $translationManager = new MockData(new MockDB());
            $results = $translationManager->getRevisions($translationId);
            verify($results)->equals($expected);

        }, [
            'examples' => [
                'return all data' => [
                    'translationId' => 1,
                    'locales'       => 'en-GB',
                    'states'        => 'active',
                    'expected'      => [
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
                ],
                'return all data' => [
                    'translationId' => 2,
                    'locales'       => 'en-GB',
                    'states'        => 'active',
                    'expected'      => [
                        2 => [
                            [
                                'translation_revision_id' => 3,
                                'translation_id'          => 2,
                                'locale'                  => 'en-GB',
                                'value'                   => 'test value of en-GB language',
                                'state'                   => 'active',
                            ],
                            [
                                'translation_revision_id' => 4,
                                'translation_id'          => 2,
                                'locale'                  => 'fr',
                                'value'                   => 'test value of fr language',
                                'state'                   => 'pending',
                            ],
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
                'insert' => 123456,
            ]);

            $mockTranslation = new MockData($mockDb);

            $result = $mockTranslation->addAction(1, 789, 'requested');
            verify($result)->equals(123456);
        });

        $this->specify('It should return 0 value when record updated failed.', function()
        {
            $mockDb = Stub::makeEmpty(MockDB::class, [
                'insert' => 0,
            ]);

            $mockTranslation = new MockData($mockDb);
            $result = $mockTranslation->addAction(1, 789, 'requested');
            verify($result)->equals(0);
        });
    }

    public function testAddRevisionAction()
    {
        $this->specify('It should return translationActionId value when record updated successfully.', function()
        {
            $mockDb = Stub::makeEmpty(MockDB::class, [
                'insert' => 123456,
            ]);

            $mockTranslation = new MockData($mockDb);

            $result = $mockTranslation->addRevisionAction(1, 789, 'pending', "test value", "test comment");
            verify($result)->equals(123456);
        });

        $this->specify('It should return 0 value when record updated failed.', function()
        {
            $mockDb = Stub::makeEmpty(MockDB::class, [
                'insert' => 0,
            ]);

            $mockTranslation = new MockData($mockDb);
            $result = $mockTranslation->addRevisionAction(1, 789, 'pending', "test value", "test comment");
            verify($result)->equals(0);
        });
    }

    public function testGetRevisionById()
    {
        $this->specify("Test", function($translationRevisionId, $expected)
        {
            $translationManager = new MockData(new MockDB());
            $results = $translationManager->getRevisionById($translationRevisionId);
            verify($results)->equals($expected);

        }, [
            'examples' => [
                'return all data where translation_revision_id =1'         => [
                    'translationRevisionId' => 1,
                    'expected'              => [
                        'translation_revision_id' => 1,
                        'translation_id'          => 1,
                        'locale'                  => 'en-GB',
                        'value'                   => 'test value of en-GB language',
                        'state'                   => 'active',
                    ],
                ],
                'return all data where translation_revision_id =2'         => [
                    'translationRevisionId' => 2,
                    'expected'              => [
                        'translation_revision_id' => 2,
                        'translation_id'          => 1,
                        'locale'                  => 'fr',
                        'value'                   => 'test value of fr language',
                        'state'                   => 'active',
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
            $translationManager = new MockData(new MockDB());
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
            $translationManager = new MockData(new MockDB());
            $results = $translationManager->findValues($scopes, $locales);
            verify($results)->equals($expected);
        }, [
            'examples' => [
                'return translations where `scopes` = default and `locales` = de' => [
                    'scopes'   => ['default'],
                    'locales'  => ['de', 'en-GB'],
                    'expected' => [
                        'ok'   => [
                            "en-GB" => "ok",
                            "de"    => "oki",
                        ],
                        'test' => [
                            "en-GB" => "test",
                            "de"    => "Prüfung",
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testFindRevisions()
    {
        $this->specify("Revission", function($scopes, $locales, $states, $expected)
        {
            $translationManager = new MockData(new MockDB());
            $results = $translationManager->findRevisions($scopes, $locales, $states);
            verify($results)->equals($expected);
        }, [
            'examples' => [
                'return translations where `scopes` = default and `locales` = de' => [
                    'scopes'   => ['default'],
                    'locales'  => ['de', 'en-GB'],
                    'state'    => ['pending', 'activated'],
                    'expected' => [
                        'ok'   => [
                            'values' => [
                                "de"    => "oki",
                                "en-GB" => "ok",
                            ],
                            'states' => [
                                "de"    => "pending",
                                "en-GB" => "activated",
                            ],
                        ],
                        'test' => [
                            'values' => [
                                "de"    => "Prüfung",
                                "en-GB" => "test",
                            ],
                            'states' => [
                                "de"    => "activated",
                                "en-GB" => "activated",
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }
}

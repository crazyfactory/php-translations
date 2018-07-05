<?php

namespace CrazyFactory\Translations\Tests\Unit;

use Codeception\Util\Stub;
use CrazyFactory\Translations\ITranslationValuesProvider;
use CrazyFactory\Translations\Tests\Unit;
use CrazyFactory\Translations\TranslationCache;

class FakeValuesProvider implements ITranslationValuesProvider
{
    public function findValues(?array $scopes, ?array $locales): array
    {
        if ($scopes == ['shop'] && $locales == ['en-GB'])
        {
            return [
                'ok'  => [
                    "en-GB" => "ok",
                ],
                'yes' => [
                    "en-GB" => "yes",
                ],
            ];
        }
        else if ($scopes == ['shop'] && $locales == ['de'])
        {
            return [
                'ok'  => [
                    "de" => "oki",
                ],
                'yes' => [
                    "de" => "ja",
                ],
            ];
        }
        else if ($scopes == ['default'] && $locales == ['de'])
        {
            return [
                'no' => [
                    "de" => "nein",
                ],
            ];
        }

        return [];
    }
}

class TranslationCacheTest extends Unit
{
    //test
    public function testGet()
    {
        $this->specify("It should return value as passed arguments ", function($key, $default, $expected)
        {
            $tc = Stub::construct(TranslationCache::class,
                ['de', 'en-GB', $this->getCacheDir(), new FakeValuesProvider()],
                [
                    'values' => [
                        'ok'     => [
                            "de"    => "oki",
                            "en-GB" => "ok",
                        ],
                        'yes'    => [
                            "de"    => "Ja",
                            "en-GB" => "yes",
                        ],
                        'please' => [
                            "de" => "please",
                        ],
                    ],
                ]
            );

            $results = $tc->get($key, $default);
            verify($results)->equals($expected);
        }, [
            'examples' => [
                'get default value'                        => [
                    'key'      => 'key not exist',
                    'default'  => 'default value',
                    'expected' => 'default value',
                ],
                'get german translation'                   => [
                    'key'      => 'ok',
                    'default'  => null,
                    'expected' => 'oki',
                ],
                'get en-GB(fallback language) translation' => [
                    'key'      => 'please',
                    'default'  => null,
                    'expected' => 'please',
                ],
                'get empty string'                         => [
                    'key'      => 'test',
                    'default'  => null,
                    'expected' => '',
                ],
            ],
        ]);
    }

    protected function getCacheDir(): string
    {
        $cacheDir = dirname(__DIR__, 2) . '/_cache/translations';

        if (!file_exists($cacheDir))
        {
            mkdir($cacheDir, 0777, true);
        }

        return $cacheDir;
    }

    public function testLoadMerged()
    {
        $this->specify("It should return merge value as passed arguments", function($scopes, $locales, $expected)
        {
            $tc = Stub::construct(TranslationCache::class,
                ['de', 'en-GB', $this->getCacheDir(), new FakeValuesProvider()],
                [
                    'locale' => 'de',
                    'scopes' => ['default', 'shop'],
                ]
            );
            $results = $tc->loadMerged($scopes, $locales);
            verify($results)->equals($expected);
        }, [
            'examples' => [
                'get translation of de and en-GB language at shop scope' => [
                    'scopes'   => ['shop'],
                    'locale'   => ['de', 'en-GB'],
                    'expected' => [
                        'ok'  => [
                            "de"    => "oki",
                            "en-GB" => "ok",
                        ],
                        'yes' => [
                            "de"    => "ja",
                            "en-GB" => "yes",
                        ],
                    ],
                ],
                'get translation of de language at shop scope'           => [
                    'scopes'   => ['shop'],
                    'locale'   => ['de'],
                    'expected' => [
                        'ok'  => [
                            "de" => "oki",
                        ],
                        'yes' => [
                            "de" => "ja",
                        ],
                    ],
                ],
                'get empty array'                                        => [
                    'scopes'   => [],
                    'locale'   => [],
                    'expected' => [],
                ],
                'get empty array'                                        => [
                    'scopes'   => ['test'],
                    'locale'   => ['de'],
                    'expected' => [],
                ],
            ],
        ]);

        $this->specify('It should throw an exception when locales is not valid.', function($scopes, $locales)
        {

            $tc = new TranslationCache('de', 'en-GB', $this->getCacheDir(), new FakeValuesProvider());
            $tc->loadMerged($scopes, $locales);
        }, [
            'examples' => [
                'with empty locale' => [
                    'scopes' => ['test'],
                    'locale' => ['test'],
                ],
            ],
            'throws'   => \InvalidArgumentException::class,
        ]);
    }

    public function testLoad()
    {
        $this->specify("It should return translation as passed arguments", function($scopes, $expected)
        {
            $tc = Stub::construct(TranslationCache::class,
                ['de', 'en-GB', $this->getCacheDir(), new FakeValuesProvider()],
                [
                    'locale' => 'de',
                    'scopes' => ['default', 'shop'],
                ]
            );
            $results = $tc->load($scopes);
            verify($results)->equals($expected);
        }, [
            'examples' => [
                'get translation of de and en-GB language at shop scope'    => [
                    'scopes'   => ['shop'],
                    'expected' => [
                        'ok'  => [
                            "de"    => "oki",
                            "en-GB" => "ok",
                        ],
                        'yes' => [
                            "de"    => "ja",
                            "en-GB" => "yes",
                        ],
                    ],
                ],
                'get translation of de and en-GB language at default scope' => [
                    'scopes'   => ['default'],
                    'expected' => [
                        'no' => [
                            "de" => "nein",
                        ],
                    ],
                ],
                'get empty array'                                           => [
                    'scopes'   => [],
                    'locale'   => [],
                    'expected' => [],
                ],
            ],
        ]);
    }
}

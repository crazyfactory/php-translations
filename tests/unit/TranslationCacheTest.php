<?php

namespace CrazyFactory\Translations\Tests\Unit;

use Codeception\Test\Unit;
use Codeception\Util\Stub;
use CrazyFactory\Translations\ITranslationValuesProvider;
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
                    "de" => "ya",
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
                        'ok'     => 'oki',
                        'yes'    => 'ya',
                        'please' => 'please',
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
        $this->specify("It should removes already loaded scopes, falsy and duplicate scopes from the list ", function($scopes, $locales, $expected)
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
                            "de"    => "ya",
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
                            "de" => "ya",
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
                    'locale'   => ['test'],
                    'expected' => [],
                ],
            ],
        ]);
    }
}

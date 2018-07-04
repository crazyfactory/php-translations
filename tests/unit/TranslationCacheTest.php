<?php

namespace CrazyFactory\Translations\Tests\Unit;

use Codeception\Test\Unit;
use Codeception\Util\Stub;
use CrazyFactory\Translations\TranslationCache;

class TranslationCacheTest extends Unit
{
    //test
    public function testGet()
    {
        $this->specify("It should return value as passed arguments ", function($key, $default, $expected)
        {
            $tc = Stub::construct(TranslationCache::class,
                ['de', 'en-GB', $this->getCacheDir()],
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
}

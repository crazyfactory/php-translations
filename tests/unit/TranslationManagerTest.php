<?php
use Codeception\Lib\Di;
use Codeception\Stub;

require_once("src/translations/TranslationManager.php");
require_once("src/translations/DBTest.php");

class TranslationManagerTest extends \Codeception\Test\Unit
{
	// tests
	public function testGet()
	{
		$this->specify("It should return translation data by id", function($id, $expected)
		{
			$translationManager = new TranslationManager();
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
			$translationManager = new TranslationManager();
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
			$mockDb = Stub::makeEmpty(DBTest::class, [
				'update' => 123456,
			]);

			$mockTranslation = Stub::construct(
				TranslationManager::class, [$mockDb], [
				'addAction' => $translationActionId = Codeception\Stub\Expected::once(),
			]);

			$result = $mockTranslation->add("Hello", 123456);
			verify($result)->equals(123456);

			$translationActionId->getMatcher()->verify();
		});

		$this->specify('It should return 0 value when record updated failed.', function()
		{
			$mockDb = Stub::makeEmpty(DBTest::class, [
				'update' => 0,
			]);

			$mockTranslation = Stub::construct(
				TranslationManager::class, [$mockDb], [
				'addAction' => $translationActionId = Codeception\Stub\Expected::never(),
			]);


			$result = $mockTranslation->add("Hello", 1);
			verify($result)->equals(0);
			$translationActionId->getMatcher()->verify();
		});
	}

	public function testAddRevision()
	{

	}

	public function testGetActiveRevision()
	{
		$this->specify("return active translation revision", function($translationId, $locale, $expected)
		{
			$translationManager = new TranslationManager();
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

	/*public function testGetRevisions()
	{
		$this->specify("return translation revision", function($translationId, $locales, $states, $expected)
		{
			$translationManager = new TranslationManager();
			$results = $translationManager->getRevisions($translationId, $locales, $states);
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
					],
				],
			],

		]);
	}*/
}

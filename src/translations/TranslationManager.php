<?php

class TranslationManager
{
	// get translation by id or key (to look up scope and other columns). does not include value
	public function get(int $id): array
	{

	}

	public function getByKey(string $key): array
	{

	}

	// add a new translation key, return the insert id, creates entry first, then uses addAction()
	public function add(string $key, ?int $userId, ?string $scope = null, ?string $state = null): ?int
	{

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

	}

	// get the currently active revision for a single translation/locale combination
	public function getActiveRevision(int $translationId, string $locale): array
	{

	}

	//
	public function getRevisions(int $translationId, ?array $locales = null, ?array $states = null): array
	{

	}

	public function getRevisionById(int $translationRevisionId): array
	{

	}

	// returns a list of all action for this revision
	public function getRevisionActions(int $translationRevisionId): array
	{

	}

	// retrieves a list of all keys/values for the provided scopes and locale combinations.
	// returns { id:int, key:string, values: {[key: string]: string|null}}[]
	public function findValues(?array $scopes, ?array $locales): array
	{

	}

	// retrieves a list of all matching revisions for a given combination of scopes, locales, states
	public function findRevisions(?array $scopes, ?array $locales): array
	{

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

	}

	public function addRevisionAction(
		int $translationRevisionId,
		?int $userId,
		?string $state,
		?string $value,
		?string $comment
	): int
	{

	}
}

<?php
class AcceptanceTestsWorkCest
{
	public function acceptanceTestsWorks(AcceptanceTester $I)
	{
		$I->amOnPage('/tests/acceptance/test.php');
		$I->see('Acceptance Tests Working');
	}
}

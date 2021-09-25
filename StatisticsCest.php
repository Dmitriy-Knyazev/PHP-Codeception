<?php

namespace App\Tests\acceptance\JS\Statistics;

use App\Tests\acceptance\Helper\UserTools;
use App\Tests\AcceptanceTester;
use App\Tests\acceptance\Helper\Constants\UrlConstants;

/**
 * @link http://Тест
 */

class StatisticsCest
{
    public const EMAIL = 'Тест';

    public function statisticsTest(
        AcceptanceTester $tester,
        UserTools $userTools
    ): void
    {
        $tester->amOnPage('/тест');
        $userTools->setDisableRecaptchaCookie();
        $tester->fillField('_username', self::EMAIL);
        $tester->fillField('_password', 'Тест');
        $tester->click('#signin-button');
        $tester->wait(5);
        $tester->seeCurrentUrlEquals('/Тест');

        $tester->amOnPage('/Тест');
        $tester->wait(5);
        $tester->see('Тест');

        $date = date('Y-m-d');

        $beforeClicks = $tester->grabTextFrom(sprintf('//tr[contains(., \'%s\')]/td[2]', $date));
        $beforeHits = $tester->grabTextFrom(sprintf('//tr[contains(., \'%s\')]/td[3]', $date));

        $this->flow($tester);

        $tester->amOnUrl(sprintf('%s/Тест', UrlConstants::APP_URL));
        $tester->wait(5);
        $tester->see('Тест');

        $afterClicks = $tester->grabTextFrom(sprintf('//tr[contains(., \'%s\')]/td[2]', $date));
        $afterHits = $tester->grabTextFrom(sprintf('//tr[contains(., \'%s\')]/td[3]', $date));

        $clickAndHits = 1;

        $tester->seeArgumentsIsEquals($beforeClicks + $clickAndHits, $afterClicks);
        $tester->seeArgumentsIsEquals($beforeHits + $clickAndHits, $afterHits);
    }

    private function flow(AcceptanceTester $tester): void
    {
        $tester->amOnUrl(sprintf('%s/Тест', UrlConstants::REDIRECT_URL));
    }
}
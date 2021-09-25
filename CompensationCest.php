<?php

namespace App\Tests\acceptance\NoJS\Compensation;

use App\Tests\acceptance\Helper\Constants\UserConstants;
use App\Tests\acceptance\Helper\UserTools;
use App\Tests\AcceptanceTester;
use App\Tests\Helper\Acceptance;

/**
 * @link http://тест   "Ссылка на тест-кейс"
 */

class CompensationCest
{
    public function CompensationTest(
        AcceptanceTester $I,
        UserTools $userTools
    ): void
    {
        $userCredentials = UserConstants::USER_FOR_TEST_3;
        $userTools->login($userCredentials['username'], $userCredentials['password']);
        $userBalanceBefore = $userTools->getBalance();
        $userTools->logout();

        $userCredentials = UserConstants::USER_FOR_ADMIN;
        $userTools->login($userCredentials['username'], $userCredentials['password']);
        $I->amOnPage('/тест');
        $I->seeResponseCodeIs(200);
        $I->click('Добавить новый');
        $I->see('тест');
        $myprice = 76;
        $I->fillField('Сумма', $myprice);
        $I->fillField('тест', UserConstants::USER_FOR_TEST_3['id']);
        $comment = uniqid('Comment_', true);
        $I->fillField('Комментарий', $comment);
        $I->click('Создать и вернуться к списку');
        $I->see('Элемент создан успешно');
        $I->click(sprintf('//tr[contains(., \'%s\')]/td[14]/div/a[1]', $comment)); //Нажимаю кнопку "Провести"
        $I->see('переведена в статус "Подтвержден"');
        $userTools->logout();

        $userCredentials = UserConstants::USER_FOR_TEST_3;
        $userTools->login($userCredentials['username'], $userCredentials['password']);
        $userBalanceAfter = $userTools->getBalance();
        $I->seeArgumentsIsEquals($userBalanceBefore + $myprice, $userBalanceAfter);
    }

    public function CompensationDeclineTest(
        AcceptanceTester $I,
        UserTools $userTools
    ): void
    {
        $userCredentials = UserConstants::USER_FOR_TEST_3;
        $userTools->login($userCredentials['username'], $userCredentials['password']);
        $userBalanceBefore = $userTools->getBalance();
        $userTools->logout();

        $userCredentials = UserConstants::USER_FOR_ADMIN;
        $userTools->login($userCredentials['username'], $userCredentials['password']);
        $I->amOnPage('/тест');
        $I->seeResponseCodeIs(200);
        $I->click('Добавить новый');
        $I->see('Доллар США');
        $prise = 112;
        $I->fillField('Сумма', $prise);
        $I->fillField('тест', UserConstants::USER_FOR_TEST_3['id']);
        $comment = uniqid('Comment_', true);
        $I->fillField('Комментарий', $comment);
        $I->click('Создать и вернуться к списку');
        $I->see('Элемент создан успешно');
        $I->click(sprintf('//tr[contains(., \'%s\')]/td[14]/div/a[2]', $comment)); //Нажимаю кнопку "Отклонить"
        $I->see('переведена в статус "Отклонен"');
        $userTools->logout();

        $userCredentials = UserConstants::USER_FOR_TEST_3;
        $userTools->login($userCredentials['username'], $userCredentials['password']);
        $userBalanceAfter = $userTools->getBalance();
        $I->seeArgumentsIsEquals($userBalanceBefore, $userBalanceAfter);
    }

    public function CompensationExpectationTest(
        AcceptanceTester $I,
        UserTools $userTools
    ): void
    {
        $userCredentials = UserConstants::USER_FOR_TEST_3;
        $userTools->login($userCredentials['username'], $userCredentials['password']);
        $userBalanceBefore = $userTools->getBalance();
        $userTools->logout();

        $userCredentials = UserConstants::USER_FOR_ADMIN;
        $userTools->login($userCredentials['username'], $userCredentials['password']);
        $I->amOnPage('/тест');
        $I->seeResponseCodeIs(200);
        $I->click('Добавить новый');
        $I->see('Доллар США');
        $prise = 55;
        $I->fillField('Сумма', $prise);
        $I->fillField('тест', UserConstants::USER_FOR_TEST_3['id']);
        $comment = uniqid('Comment_', true);
        $I->fillField('Комментарий', $comment);
        $I->click('Создать и вернуться к списку');
        $I->see('Элемент создан успешно');
        $userTools->logout();

        $userCredentials = UserConstants::USER_FOR_TEST_3;
        $userTools->login($userCredentials['username'], $userCredentials['password']);
        $userBalanceAfter = $userTools->getBalance();
        $I->seeArgumentsIsEquals($userBalanceBefore, $userBalanceAfter);
    }
}
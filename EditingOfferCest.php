<?php

namespace App\Tests\acceptance\NoJS\Offer;

use App\Tests\acceptance\Helper\Constants\UserConstants;
use App\Tests\acceptance\Helper\Constants\OfferConstants;
use App\Tests\acceptance\Helper\UserTools;
use App\Tests\AcceptanceTester;

/**
 * @link http://Тест
 */
class EditingOfferCest
{
    public function editPriceTypeTest(
        AcceptanceTester $I,
        UserTools $userTools
    ): void
    {
        $userCredentials = UserConstants::USER_FOR_TESTS_ACCESS_TO_ADMIN;
        $userTools->login($userCredentials['username'], $userCredentials['password']);

        $I->amOnPage(sprintf('/Тест/%d/Тест', OfferConstants::OFFER_FOR_EDIT_PRICE_TYPE_TEST));
        $I->seeResponseCodeIs(200);

        $I->selectOption('(//select[contains(@name, \'priceType\')][1])', 'fc'); //Фиксированная комиссия
        $I->fillField('(//input[contains(@name, \'coefficient\')][1])', '0');
        $I->fillField('Тест', 'Тест'); //при открытии без js поле пустое
        $I->click('Сохранить');
        $I->see('Во время обновления элемента произошла ошибка');
        $I->see('This value should be greater than 0');

        $I->selectOption('(//select[contains(@name, \'priceType\')][1])', 'pr'); //Процент от ставки, коэффициент 0
        $I->fillField('Тест', 'Тест');
        $I->click('Сохранить');
        $I->see('Во время обновления элемента произошла ошибка');
        $I->see('This value should be greater than 0 and less than 1');

        $I->selectOption('(//select[contains(@name, \'priceType\')][1])', 'pr'); //Процент от ставки, коэффициент 1
        $I->fillField('(//input[contains(@name, \'coefficient\')][1])', '1.01');
        $I->fillField('Тест', 'Тест');
        $I->click('Сохранить');
        $I->see('Во время обновления элемента произошла ошибка');
        $I->see('This value should be greater than 0 and less than 1');
    }
}
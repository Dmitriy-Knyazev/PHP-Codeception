<?php

namespace App\Tests\acceptance\NoJS\Auto;

use App\Tests\acceptance\Helper\Constants\OfferConstants;
use App\Tests\acceptance\Helper\Constants\UserConstants;
use App\Tests\acceptance\Helper\UserTools;
use App\Tests\AcceptanceTester;

/**
 * @link http://Тест
 */
class CreateCest
{
    public function autoTest(
        AcceptanceTester $I,
        UserTools $userTools
    ): void
    {
        $userCredentials = UserConstants::USER_FOR_ADMIN;
        $userTools->login($userCredentials['username'], $userCredentials['password']);

        $I->amOnPage('/Тест');
        $I->seeResponseCodeIs(200);

        $I->click('Добавить новый');
        $I->fillField('Пользователи', UserConstants::USER_FOR_ADMIN['id']);
        $I->fillField('Тест', OfferConstants::OFFER_FOR_TEST['id']);
        $I->selectOption('Тест', 'Тест');
        $I->fillField('Процент подтверждения', '50');
        $comment = uniqid('Comment_', true);
        $I->fillField('Комментарий', $comment);
        $I->click('Создать и вернуться к списку');
        $I->see('Элемент создан успешно');

        $I->amOnPage('/Тест');
        $I->seeResponseCodeIs(200);

        $offer = $I->grabTextFrom(sprintf('//tr[contains(., \'%s\')]/td[7]', UserConstants::USER_FOR_ADMIN['username']));
        $I->seeArgumentsIsEquals(OfferConstants::OFFER_FOR_TEST['title'], $offer);

        $idApprove = $I->grabTextFrom(sprintf('//tr[contains(., \'%s\')]/td[2]', $comment));
        $I->click(sprintf('//tr[contains(., \'%s\')]/td[17]/div/a[2]', $comment));
        $I->seeInCurrentUrl(sprintf('/Тест/%s/Тест', $idApprove));

        $I->selectOption('Тест', 'Тест');
        $I->fillField('Тест', OfferConstants::OFFER_FOR_AUTOAPPROVE_TEST['id']);
        $I->click('Сохранить и вернуться к списку');
        $I->see('Элемент успешно обновлен');

        $I->click(sprintf('//tr[contains(., \'%s\')]/td[17]/div/a[3]', $comment));
        $I->see('Вы действительно хотите удалить выбранный элемент?');
        $I->click('Да, удалить');
        $I->see('Элемент успешно удален');
    }
}
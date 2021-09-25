<?php

namespace App\Tests\acceptance\NoJS\Postback;

use App\Tests\acceptance\Helper\ApiTools;
use App\Tests\acceptance\Helper\Constants\FlowConstants;
use App\Tests\acceptance\Helper\Constants\UrlConstants;
use App\Tests\acceptance\Helper\Constants\UserConstants;
use App\Tests\acceptance\Helper\LeadTools;
use App\Tests\acceptance\Helper\PostbackTools;
use App\Tests\acceptance\Helper\UserTools;
use App\Tests\AcceptanceTester;

/**
 * @link http://Тест
 */
class TestCest
{
    public function UserTest (
        AcceptanceTester $I,
        UserTools $userTools,
        LeadTools $leadTools,
        ApiTools $apiTools,
        PostbackTools $postback
    ): void
    {
        $sub1 = uniqid('Тест_', true);
        $tid = $this->makeRedirect($I, $sub1);
        $landing = $I->grabCurrentUrl();
        $name = uniqid('Тест ', true);
        $phone = sprintf('+39 012%d', random_int(1000000, 9999999));
        $country = 'it';
        $leadTools->createFromLanding($landing, $name, $phone, $country, $tid);

        $userCredentials = UserConstants::USER_FOR_ADMIN;
        $userTools->login($userCredentials['username'], $userCredentials['password']);

        $I->amOnPage(sprintf('/Тест/%s/Тест', UserConstants::ADVERT_FOR_TEST['id']));
        $I->see(sprintf('%s/Тест/%s?Тест={Тест}&Тест={Тест}', UrlConstants::POSTBACK_URL, UserConstants::ADVERT_FOR_TEST['Тест']));
        $I->amOnPage('/Тест');
        $leadId = $I->grabTextFrom(sprintf('//tr[contains(., \'%s\')]/td[2]', $sub1));
        $statusLead = $I->grabTextFrom(sprintf('//tr[contains(., \'%s\')]/td[6]', $sub1));
        $I->seeArgumentsIsEquals('pending', $statusLead);

        $postbackSend = $this->postbackSend($postback, $tid, $country, $leadId);

        $postbackCheck = $this->postbackCheck($I, $apiTools, $userTools, $tid, $leadId);
    }

    private function makeRedirect(AcceptanceTester $I, string $sub1): string
    {
        $I->amOnUrl(sprintf('%s/%s?Тест=%s', UrlConstants::REDIRECT_URL, FlowConstants::FLOW_WITH_HASH['Тест'], $sub1));
        return $I->grabFromCurrentUrl('Тест');
    }

    private function postbackSend(
        PostbackTools $postback,
        string $tid,
        string $country,
        string $leadId
    ): void
    {
        $postback->sendPostbackToLeadbitHash([
            'tid' => $tid,
            'status' => 'pending',
            'country' => $country,
            'uid' => $leadId,
        ],
            UserConstants::ADVERT_FOR_TEST['Тест']
        );

        $postback->sendPostbackToLeadbitHash([
            'tid' => $tid,
            'status' => 'pending',
            'country' => $country,
            'uid' => $leadId,
        ],
            UserConstants::RECL_FOR_TEST['Тест']
        );
    }

    private function postbackCheck(
        AcceptanceTester $I,
        ApiTools $apiTools,
        UserTools $userTools,
        string $tid,
        string $leadId
    ): void
    {
        $apiTools->runIncomingPostbackCommand();

        $userCredentials = UserConstants::USER_FOR_ADMIN;
        $userTools->login($userCredentials['username'], $userCredentials['password']);

        $I->amOnPage('/Тест');
        $status = $I->grabTextFrom(sprintf('//tr[contains(., \'%s\')]/td[6]/span', $tid));
        $I->seeArgumentsIsEquals('success', $status);

        $statusFail = $I->grabTextFrom(sprintf('//tr[contains(., \'%s\')]/td[6]/span', UserConstants::RECL_FOR_TEST['Тест']));
        $I->seeArgumentsIsEquals('failed', $statusFail);
        $I->see(sprintf('Тест: %s and Тест: %s', $leadId, UserConstants::RECL_FOR_TEST['id']));
    }
}
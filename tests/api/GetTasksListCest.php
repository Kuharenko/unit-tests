<?php

class GetTasksListCest
{
    public function _before(ApiTester $I)
    {
        $I->sendPost('/auth/login', [
            'email' => 'admin@admin.com',
            'password' => 'password'
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $token = implode('', $I->grabDataFromResponseByJsonPath('$.access_token'));
        $I->amBearerAuthenticated($token);
    }

    // tests
    public function tryToGetListOfTasks(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendGET('/tasks');

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $needleFields = ['id', 'title', 'description', 'user_id'];
        $items = \App\Task::paginate()->toArray()['data'];

        $generatedItems = [];

        foreach ($items as $key => $item) {
            foreach ($needleFields as $field) {
                $generatedItems[$key][$field] = $item[$field];
            }
        }

        $I->seeResponseContainsJson(['data' => $generatedItems]);
    }
}

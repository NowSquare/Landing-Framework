<?php

namespace Stripe;

class AccountTest extends TestCase
{
    private function managedAccountResponse($id)
    {
        return array(
            'id' => $id,
            'currencies_supported' => array('usd', 'aed', 'afn', '...'),
            'object' => 'account',
            'business_name' => 'Stripe.com',
            'bank_accounts' => array(
                'object' => 'list',
                'total_count' => 0,
                'has_more' => false,
                'url' => '/v1/accounts/' . $id . '/bank_accounts',
                'data' => array()
            ),
            'verification' => array(
                'fields_needed' => array(
                    'product_description',
                    'business_url',
                    'support_phone',
                    'bank_account',
                    'tos_acceptance.ip',
                    'tos_acceptance.date'
                ),
                'due_by' => null,
                'contacted' => false
            ),
            'tos_acceptance' => array(
                'ip' => null,
                'date' => null,
                'user_agent' => null
            ),
            'legal_entity' => array(
                'type' => null,
                'business_name' => null,
                'address' => array(
                    'line1' => null,
                    'line2' => null,
                    'city' => null,
                    'state' => null,
                    'postal_code' => null,
                    'country' => 'US'
                ),
                'first_name' => null,
                'last_name' => null,
                'additional_owners' => null,
                'verification' => array(
                    'status' => 'unverified',
                    'document' => null,
                    'details' => null
                )
            )
        );
    }

    private function deletedAccountResponse($id)
    {
        return array(
            'id' => $id,
            'deleted' => true
        );
    }

    public function testBasicRetrieve()
    {
        $this->mockRequest('GET', '/v1/account', array(), $this->managedAccountResponse('acct_ABC'));
        $account = Account::retrieve();
        $this->assertSame($account->id, 'acct_ABC');
    }

    public function testIDRetrieve()
    {
        $this->mockRequest('GET', '/v1/accounts/acct_DEF', array(), $this->managedAccountResponse('acct_DEF'));
        $account = Account::retrieve('acct_DEF');
        $this->assertSame($account->id, 'acct_DEF');
    }

    public function testCreate()
    {
        $this->mockRequest(
            'POST',
            '/v1/accounts',
            array('managed' => 'true'),
            $this->managedAccountResponse('acct_ABC')
        );
        $account = Account::create(array(
            'managed' => true
        ));
        $this->assertSame($account->id, 'acct_ABC');
    }

    public function testDelete()
    {
        $account = self::createTestAccount();

        $this->mockRequest(
            'DELETE',
            '/v1/accounts/' . $account->id,
            array(),
            $this->deletedAccountResponse('acct_ABC')
        );
        $deleted = $account->delete();
        $this->assertSame($deleted->id, $account->id);
        $this->assertTrue($deleted->deleted);
    }

    public function testReject()
    {
        $account = self::createTestAccount();

        $this->mockRequest(
            'POST',
            '/v1/accounts/' . $account->id . '/reject',
            array('reason' => 'fraud'),
            $this->deletedAccountResponse('acct_ABC')
        );
        $rejected = $account->reject(array('reason' => 'fraud'));
        $this->assertSame($rejected->id, $account->id);
    }

    public function testSaveLegalEntity()
    {
        $response = $this->managedAccountResponse('acct_ABC');
        $this->mockRequest('POST', '/v1/accounts', array('managed' => 'true'), $response);

        $response['legal_entity']['first_name'] = 'Bob';
        $this->mockRequest(
            'POST',
            '/v1/accounts/acct_ABC',
            array('legal_entity' => array('first_name' => 'Bob')),
            $response
        );

        $account = Account::create(array('managed' => true));
        $account->legal_entity->first_name = 'Bob';
        $account->save();

        $this->assertSame('Bob', $account->legal_entity->first_name);
    }

    public function testUpdateLegalEntity()
    {
        $response = $this->managedAccountResponse('acct_ABC');
        $this->mockRequest('POST', '/v1/accounts', array('managed' => 'true'), $response);

        $response['legal_entity']['first_name'] = 'Bob';
        $this->mockRequest(
            'POST',
            '/v1/accounts/acct_ABC',
            array('legal_entity' => array('first_name' => 'Bob')),
            $response
        );

        $account = Account::create(array('managed' => true));
        $account = Account::update($account['id'], array(
          'legal_entity' => array(
            'first_name' => 'Bob'
          )
        ));

        $this->assertSame('Bob', $account->legal_entity->first_name);
    }

    public function testCreateAdditionalOwners()
    {
        $request = array(
            'managed' => true,
            'country' => 'GB',
            'legal_entity' => array(
                'additional_owners' => array(
                    0 => array(
                        'dob' => array(
                            'day' => 12,
                            'month' => 5,
                            'year' => 1970,
                        ),
                        'first_name' => 'xgvukvfrde',
                        'last_name' => 'rtcyvubhy',
                    ),
                    1 => array(
                        'dob' => array(
                            'day' => 8,
                            'month' => 4,
                            'year' => 1979,
                        ),
                        'first_name' => 'yutreuk',
                        'last_name' => 'dfcgvhbjihmv',
                    ),
                ),
            ),
        );

        $acct = Account::create($request);
        $response = $acct->__toArray(true);

        $req_ao = $request['legal_entity']['additional_owners'];
        $resp_ao = $response['legal_entity']['additional_owners'];

        $this->assertSame($req_ao[0]['dob'], $resp_ao[0]['dob']);
        $this->assertSame($req_ao[1]['dob'], $resp_ao[1]['dob']);

        $this->assertSame($req_ao[0]['first_name'], $resp_ao[0]['first_name']);
        $this->assertSame($req_ao[1]['first_name'], $resp_ao[1]['first_name']);
    }

    public function testUpdateAdditionalOwners()
    {
        $response = $this->managedAccountResponse('acct_ABC');
        $this->mockRequest('POST', '/v1/accounts', array('managed' => 'true'), $response);

        $response['legal_entity']['additional_owners'] = array(array(
            'first_name' => 'Bob',
            'last_name' => null,
            'address' => array(
                'line1' => null,
                'line2' => null,
                'city' => null,
                'state' => null,
                'postal_code' => null,
                'country' => null
            ),
            'verification' => array(
                'status' => 'unverified',
                'document' => null,
                'details' => null
            )
        ));

        $this->mockRequest(
            'POST',
            '/v1/accounts/acct_ABC',
            array('legal_entity' => array('additional_owners' => array(array('first_name' => 'Bob')))),
            $response
        );

        $response['legal_entity']['additional_owners'][0]['last_name'] = 'Smith';
        $this->mockRequest(
            'POST',
            '/v1/accounts/acct_ABC',
            array('legal_entity' => array('additional_owners' => array(array('last_name' => 'Smith')))),
            $response
        );

        $response['legal_entity']['additional_owners'][0]['last_name'] = 'Johnson';
        $this->mockRequest(
            'POST',
            '/v1/accounts/acct_ABC',
            array('legal_entity' => array('additional_owners' => array(array('last_name' => 'Johnson')))),
            $response
        );

        $response['legal_entity']['additional_owners'][0]['verification']['document'] = 'file_123';
        $this->mockRequest(
            'POST',
            '/v1/accounts/acct_ABC',
            array('legal_entity' => array('additional_owners' => array(array('verification' => array('document' => 'file_123'))))),
            $response
        );

        $response['legal_entity']['additional_owners'][1] = array(
            'first_name' => 'Jane',
            'last_name' => 'Doe'
        );
        $this->mockRequest(
            'POST',
            '/v1/accounts/acct_ABC',
            array('legal_entity' => array('additional_owners' => array(1 => array('first_name' => 'Jane')))),
            $response
        );

        $account = Account::create(array('managed' => true));
        $account->legal_entity->additional_owners = array(array('first_name' => 'Bob'));
        $account->save();
        $this->assertSame(1, count($account->legal_entity->additional_owners));
        $this->assertSame('Bob', $account->legal_entity->additional_owners[0]->first_name);

        $account->legal_entity->additional_owners[0]->last_name = 'Smith';
        $account->save();
        $this->assertSame(1, count($account->legal_entity->additional_owners));
        $this->assertSame('Smith', $account->legal_entity->additional_owners[0]->last_name);

        $account['legal_entity']['additional_owners'][0]['last_name'] = 'Johnson';
        $account->save();
        $this->assertSame(1, count($account->legal_entity->additional_owners));
        $this->assertSame('Johnson', $account->legal_entity->additional_owners[0]->last_name);

        $account->legal_entity->additional_owners[0]->verification->document = 'file_123';
        $account->save();
        $this->assertSame('file_123', $account->legal_entity->additional_owners[0]->verification->document);

        $account->legal_entity->additional_owners[1] = array('first_name' => 'Jane');
        $account->save();
        $this->assertSame(2, count($account->legal_entity->additional_owners));
        $this->assertSame('Jane', $account->legal_entity->additional_owners[1]->first_name);
    }

    public function testLoginLinkCreation()
    {
        $accountId = 'acct_EXPRESS';
        $mockExpress = array(
            'id' => $accountId,
            'object' => 'account',
            'login_links' => array(
                'object' => 'list',
                'data' => array(),
                'has_more' => false,
                'url' =>  "/v1/accounts/$accountId/login_links"
            )
        );

        $this->mockRequest('GET', "/v1/accounts/$accountId", array(), $mockExpress);

        $mockLoginLink = array(
            'object' => 'login_link',
            'created' => 1493820886,
            'url' => "https://connect.stripe.com/$accountId/AAAAAAAA"
        );

        $this->mockRequest('POST', "/v1/accounts/$accountId/login_links", array(), $mockLoginLink);

        $account = Account::retrieve($accountId);
        $loginLink = $account->login_links->create();
        $this->assertSame('login_link', $loginLink->object);
        $this->assertSame('Stripe\LoginLink', get_class($loginLink));
    }

    public function testDeauthorize()
    {
        Stripe::setClientId('ca_test');

        $accountId = 'acct_test_deauth';
        $mockAccount = array(
            'id' => $accountId,
            'object' => 'account',
        );

        $this->mockRequest('GET', "/v1/accounts/$accountId", array(), $mockAccount);

        $this->mockRequest(
            'POST',
            '/oauth/deauthorize',
            array(
                'client_id' => 'ca_test',
                'stripe_user_id' => $accountId,
            ),
            array(
                'stripe_user_id' => $accountId,
            ),
            200,
            Stripe::$connectBase
        );

        $account = Account::retrieve($accountId);
        $account->deauthorize();

        Stripe::setClientId(null);
    }

    public function testStaticCreateExternalAccount()
    {
        $this->mockRequest(
            'POST',
            '/v1/accounts/acct_123/external_accounts',
            array('source' => 'btok_123'),
            array('id' => 'ba_123', 'object' => 'bank_account')
        );

        $externalAccount = Account::createExternalAccount(
            'acct_123',
            array('source' => 'btok_123')
        );

        $this->assertSame('ba_123', $externalAccount->id);
        $this->assertSame('bank_account', $externalAccount->object);
    }

    public function testStaticRetrieveExternalAccount()
    {
        $this->mockRequest(
            'GET',
            '/v1/accounts/acct_123/external_accounts/ba_123',
            array(),
            array('id' => 'ba_123', 'object' => 'bank_account')
        );

        $externalAccount = Account::retrieveExternalAccount(
            'acct_123',
            'ba_123'
        );

        $this->assertSame('ba_123', $externalAccount->id);
        $this->assertSame('bank_account', $externalAccount->object);
    }

    public function testStaticUpdateExternalAccount()
    {
        $this->mockRequest(
            'POST',
            '/v1/accounts/acct_123/external_accounts/ba_123',
            array('metadata' => array('foo' => 'bar')),
            array('id' => 'ba_123', 'object' => 'bank_account')
        );

        $externalAccount = Account::updateExternalAccount(
            'acct_123',
            'ba_123',
            array('metadata' => array('foo' => 'bar'))
        );

        $this->assertSame('ba_123', $externalAccount->id);
        $this->assertSame('bank_account', $externalAccount->object);
    }

    public function testStaticDeleteExternalAccount()
    {
        $this->mockRequest(
            'DELETE',
            '/v1/accounts/acct_123/external_accounts/ba_123',
            array(),
            array('id' => 'ba_123', 'deleted' => true)
        );

        $externalAccount = Account::deleteExternalAccount(
            'acct_123',
            'ba_123'
        );

        $this->assertSame('ba_123', $externalAccount->id);
        $this->assertSame(true, $externalAccount->deleted);
    }

    public function testStaticAllExternalAccounts()
    {
        $this->mockRequest(
            'GET',
            '/v1/accounts/acct_123/external_accounts',
            array(),
            array('object' => 'list', 'data' => array())
        );

        $externalAccounts = Account::allExternalAccounts(
            'acct_123'
        );

        $this->assertSame('list', $externalAccounts->object);
        $this->assertEmpty($externalAccounts->data);
    }

    public function testStaticCreateLoginLink()
    {
        $this->mockRequest(
            'POST',
            '/v1/accounts/acct_123/login_links',
            array(),
            array('object' => 'login_link', 'url' => 'https://example.com')
        );

        $loginLink = Account::createLoginLink(
            'acct_123'
        );

        $this->assertSame('login_link', $loginLink->object);
        $this->assertSame('https://example.com', $loginLink->url);
    }
}

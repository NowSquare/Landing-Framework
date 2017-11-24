<?php

namespace Stripe;

class CustomerTest extends TestCase
{
    public function testDeletion()
    {
        $customer = self::createTestCustomer();
        $customer->delete();

        $this->assertTrue($customer->deleted);
        $this->assertNull($customer['active_card']);
    }

    public function testSave()
    {
        $customer = self::createTestCustomer();

        $customer->email = 'gdb@stripe.com';
        $customer->save();
        $this->assertSame($customer->email, 'gdb@stripe.com');

        $stripeCustomer = Customer::retrieve($customer->id);
        $this->assertSame($customer->email, $stripeCustomer->email);

        Stripe::setApiKey(null);
        $customer = Customer::create(null, self::API_KEY);
        $customer->email = 'gdb@stripe.com';
        $customer->save();

        self::authorizeFromEnv();
        $updatedCustomer = Customer::retrieve($customer->id);
        $this->assertSame($updatedCustomer->email, 'gdb@stripe.com');
    }

    /**
     * @expectedException Stripe\Error\InvalidRequest
     */
    public function testBogusAttribute()
    {
        $customer = self::createTestCustomer();
        $customer->bogus = 'bogus';
        $customer->save();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testUpdateDescriptionEmpty()
    {
        $customer = self::createTestCustomer();
        $customer->description = '';
    }

    public function testUpdateDescriptionNull()
    {
        $customer = self::createTestCustomer(array('description' => 'foo bar'));
        $customer->description = null;

        $customer->save();

        $updatedCustomer = Customer::retrieve($customer->id);
        $this->assertSame(null, $updatedCustomer->description);
    }

    public function testUpdateMetadata()
    {
        $customer = self::createTestCustomer();

        $customer->metadata['test1'] = 'foo';
        $customer->metadata['test2'] = 'bar';
        $customer->save();

        $updatedCustomer = Customer::retrieve($customer->id);
        $this->assertSame(2, count($updatedCustomer->metadata));
        $this->assertSame('foo', $updatedCustomer->metadata['test1']);
        $this->assertSame('bar', $updatedCustomer->metadata['test2']);
    }

    public function testDeleteMetadata()
    {
        $customer = self::createTestCustomer();

        $customer->metadata = null;
        $customer->save();

        $updatedCustomer = Customer::retrieve($customer->id);
        $this->assertSame(0, count($updatedCustomer->metadata));
    }

    public function testUpdateSomeMetadata()
    {
        $customer = self::createTestCustomer();
        $customer->metadata['shoe size'] = '7';
        $customer->metadata['shirt size'] = 'XS';
        $customer->save();

        $customer->metadata['shoe size'] = '9';
        $customer->save();

        $updatedCustomer = Customer::retrieve($customer->id);
        $this->assertSame('XS', $updatedCustomer->metadata['shirt size']);
        $this->assertSame('9', $updatedCustomer->metadata['shoe size']);
    }

    public function testUpdateAllMetadata()
    {
        $customer = self::createTestCustomer();
        $customer->metadata['shoe size'] = '7';
        $customer->metadata['shirt size'] = 'XS';
        $customer->save();

        $customer->metadata = array('shirt size' => 'XL');
        $customer->save();

        $updatedCustomer = Customer::retrieve($customer->id);
        $this->assertSame('XL', $updatedCustomer->metadata['shirt size']);
        $this->assertFalse(isset($updatedCustomer->metadata['shoe size']));
    }

    /**
     * @expectedException Stripe\Error\InvalidRequest
     */
    public function testUpdateInvalidMetadata()
    {
        $customer = self::createTestCustomer();
        $customer->metadata = 'something';
        $customer->save();
    }

    public function testCancelSubscription()
    {
        $planID = 'gold-' . self::generateRandomString(20);
        self::retrieveOrCreatePlan($planID);

        $customer = self::createTestCustomer(
            array(
                'plan' => $planID,
            )
        );

        $customer->cancelSubscription(array('at_period_end' => true));
        $this->assertSame($customer->subscription->status, 'active');
        $this->assertTrue($customer->subscription->cancel_at_period_end);
        $customer->cancelSubscription();
        $this->assertSame($customer->subscription->status, 'canceled');
    }

    public function testCustomerAddCard()
    {
        $customer = $this->createTestCustomer();
        $createdCard = $customer->sources->create(array("card" => 'tok_visa'));
        $customer->save();

        $updatedCustomer = Customer::retrieve($customer->id);
        $updatedCards = $updatedCustomer->sources->all();
        $this->assertSame(count($updatedCards["data"]), 2);
    }

    public function testCustomerUpdateCard()
    {
        $customer = $this->createTestCustomer();
        $customer->save();

        $sources = $customer->sources->all();
        $this->assertSame(count($sources["data"]), 1);

        $card = $sources['data'][0];
        $card->name = "Jane Austen";
        $card->save();

        $updatedCustomer = Customer::retrieve($customer->id);
        $updatedCards = $updatedCustomer->sources->all();
        $this->assertSame($updatedCards["data"][0]->name, "Jane Austen");
    }

    public function testCustomerDeleteCard()
    {
        $customer = $this->createTestCustomer();
        $createdCard = $customer->sources->create(array("card" => 'tok_visa'));
        $customer->save();

        $updatedCustomer = Customer::retrieve($customer->id);
        $updatedCards = $updatedCustomer->sources->all();
        $this->assertSame(count($updatedCards["data"]), 2);

        $deleteStatus = $updatedCustomer->sources->retrieve($createdCard->id)->delete();
        $this->assertTrue($deleteStatus->deleted);
        $updatedCustomer->save();

        $postDeleteCustomer = Customer::retrieve($customer->id);
        $postDeleteCards = $postDeleteCustomer->sources->all();
        $this->assertSame(count($postDeleteCards["data"]), 1);
    }

    public function testCustomerAddSource()
    {
        self::authorizeFromEnv();

        $customer = $this->createTestCustomer();
        $createdSource = $customer->sources->create(array("source" => 'tok_visa'));
        $customer->save();

        $updatedCustomer = Customer::retrieve($customer->id);
        $updatedSources = $updatedCustomer->sources->all();
        $this->assertSame(count($updatedSources["data"]), 2);
    }

    public function testCustomerUpdateSource()
    {
        $customer = $this->createTestCustomer();
        $customer->save();

        $sources = $customer->sources->all();
        $this->assertSame(count($sources["data"]), 1);

        $source = $sources['data'][0];
        $source->name = "Jane Austen";
        $source->save();

        $updatedCustomer = Customer::retrieve($customer->id);
        $updatedSources = $updatedCustomer->sources->all();
        $this->assertSame($updatedSources["data"][0]->name, "Jane Austen");
    }

    public function testCustomerDeleteSource()
    {
        self::authorizeFromEnv();

        $customer = $this->createTestCustomer();
        $createdSource = $customer->sources->create(array("source" => 'tok_visa'));
        $customer->save();

        $updatedCustomer = Customer::retrieve($customer->id);
        $updatedSources = $updatedCustomer->sources->all();
        $this->assertSame(count($updatedSources["data"]), 2);

        $deleteStatus = $updatedCustomer->sources->retrieve($createdSource->id)->delete();
        $this->assertTrue($deleteStatus->deleted);
        $updatedCustomer->save();

        $postDeleteCustomer = Customer::retrieve($customer->id);
        $postDeleteSources = $postDeleteCustomer->sources->all();
        $this->assertSame(count($postDeleteSources["data"]), 1);
    }


    public function testStaticCreateSource()
    {
        $this->mockRequest(
            'POST',
            '/v1/customers/cus_123/sources',
            array('source' => 'tok_123'),
            array('id' => 'card_123', 'object' => 'card')
        );

        $source = Customer::createSource(
            'cus_123',
            array('source' => 'tok_123')
        );

        $this->assertSame('card_123', $source->id);
        $this->assertSame('card', $source->object);
    }

    public function testStaticRetrieveSource()
    {
        $this->mockRequest(
            'GET',
            '/v1/customers/cus_123/sources/card_123',
            array(),
            array('id' => 'card_123', 'object' => 'card')
        );

        $source = Customer::retrieveSource(
            'cus_123',
            'card_123'
        );

        $this->assertSame('card_123', $source->id);
        $this->assertSame('card', $source->object);
    }

    public function testStaticUpdateSource()
    {
        $this->mockRequest(
            'POST',
            '/v1/customers/cus_123/sources/card_123',
            array('metadata' => array('foo' => 'bar')),
            array('id' => 'card_123', 'object' => 'card')
        );

        $source = Customer::updateSource(
            'cus_123',
            'card_123',
            array('metadata' => array('foo' => 'bar'))
        );

        $this->assertSame('card_123', $source->id);
        $this->assertSame('card', $source->object);
    }

    public function testStaticDeleteSource()
    {
        $this->mockRequest(
            'DELETE',
            '/v1/customers/cus_123/sources/card_123',
            array(),
            array('id' => 'card_123', 'deleted' => true)
        );

        $source = Customer::deleteSource(
            'cus_123',
            'card_123'
        );

        $this->assertSame('card_123', $source->id);
        $this->assertSame(true, $source->deleted);
    }

    public function testStaticAllSources()
    {
        $this->mockRequest(
            'GET',
            '/v1/customers/cus_123/sources',
            array(),
            array('object' => 'list', 'data' => array())
        );

        $sources = Customer::allsources(
            'cus_123'
        );

        $this->assertSame('list', $sources->object);
        $this->assertEmpty($sources->data);
    }
}

<?php

namespace App\Model;

use PagarMe;

class SubscriptionPagarMe{
    
    protected $pagarMe;

    function __construct(string $key_api)
    {
        $this->pagarMe = new Pagarme\Client($key_api,
            [
                'headers' => ['v2' => '2017-07-17']
            ]
        );
    }

    /**
     * @param array $data
     * 
     * @return [type]
     */
    public function createPlan(array $data, int $charges = null){
        $plan = $this->pagarMe->plans()->create([
            'amount' => $data['amount'],
            'days' => '30',
            'name' => $data['name'],
            'trial_days' => 30,
            'payment_methods' => $data['payment_methods'],
            'charges' => $charges == null ? 0 : $charges
        ]);
        return $plan->id;
    }

    /**
     * @return [type]
     */
    public function getPlans(){
        return $this->pagarMe->plans()->getList();
    }
    /**
     * @param int $plan_id
     * 
     * @return [type]
     */
    public function getPlan(int $plan_id){
        return $this->pagarMe->plans()->get(['id' => $plan_id]);
    }

    /**
     * @param int $plan_id
     * @param array $data
     * 
     * @return [type]
     */
    public function updatePlan(int $plan_id, array $data){
        return $this->pagarMe->plans()->update([
            'id' => $plan_id,
            'name' => $data['name'],
            'trial_days' => $data['trial_days'],
        ]);
    }

    /**
     * @param int $plan_id
     * @param string $card_id
     * @param array $customer
     * 
     * @return [type]
     */
    public function createSubscriptions(int $plan_id, string $card_id, array $customer){
        return $this->pagarMe->subscriptions()->create([
            'plan_id' => $plan_id,
            'payment_method' => 'credit_card',
            'card_id' => $card_id,
            'postback_url' => 'https://61c07e03e83ea7bb9f3929668716eba2.m.pipedream.net',//definir qual sera a url para retorno das assinaturas
            'customer' => [
                'email' => $customer['email'],
                'name' => $customer['name'],
                'document_number' => $customer['document_number'],
                'address' => [
                    'street' => $customer['street'],
                    'street_number' => $customer['street_number'],
                    'complementary' => $customer['complementary'],
                    'neighborhood' => $customer['neighborhood'],
                    'zipcode' => $customer['zipcode']
                ],
                'phone' => [
                    'ddd' => $customer['ddd'],
                    'number' => $customer['number']
                ],
                'sex' => 'other',
                'born_at' => '1970-01-01',
            ],
        ]);
    }

    /**
     * @param int $subscription_id
     * 
     * @return [type]
     */
    public function getSubscription(int $subscription_id){
        return $this->pagarMe->subscriptions()->get(['id' => $subscription_id]);
    }

    /**
     * @return [type]
     */
    public function getSubscriptions(){
        return $this->pagarMe->subscriptions()->getList();
    }
    /**
     * @param int $subscription_id
     * @param array $options
     * 
     * @return [type]
     */
    public function updateSubscription(int $id, array $options){
        $update = array_merge(['id' => $id], $options);
        try {
            $result =  $this->pagarMe->subscriptions()->update($update);
            return $result;
        } catch (\Throwable $th) {
            return $th->getError;
        }
        
    }

    /**
     * @param int $subscription_id
     * 
     * @return [type]
     */
    public function cancelSubscription(int $subscription_id){
        return $this->pagarMe->subscriptions()->cancel(['id' => $subscription_id]);
    }

    /**
     * @param int $subscription_id
     * 
     * @return [type]
     */
    public function getSubscriptionTransation(int $subscription_id){
        return $this->pagarMe->subscriptions()->transactions(['subscription_id' => $subscription_id]);
    }

    /**
     * @param array $data
     * 
     * @return [type]
     */
    public function createCreditCard(array $data){
        $card = $this->pagarMe->cards()->create($data);
        return $card->id;
    }

}


?>
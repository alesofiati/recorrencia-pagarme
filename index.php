<?php
session_start();
use App\Model\SubscriptionPagarMe;

require_once __DIR__."/vendor/autoload.php";

$recorrencia = new SubscriptionPagarMe('SUA_CHAVE_API');
$action = $_REQUEST['action'];

if(isset($action)){
    switch ($action) {
        case 'create_card':
            $card = [
                'holder_name' => 'Yoda',
                'number' => '4242424242424242',
                'expiration_date' => '1225',
                'cvv' => '123'
            ];
            //card_ckhkkidpu05wm0m9tdu7no8ee
            $result = $recorrencia->createCreditCard($card);
            echo $result;
            //518036
            return;
        break;
        case 'get_plan':
            $result = $recorrencia->getPlan(518036);
            echo "<pre>";
            print_r($result);
            echo "</pre>";
        break;
        case 'create_plan':
            // obrigatorio ter esses 3 parametros para criar um plano
            //parametros opcionais
            //trial_days <-- dias de graça do produto, charges <-- numero de cobranças
            $plan = [
                'amount' => 15000,
                'name' => 'Plano Teste 17/11/2020',
                'payment_methods' => ['credit_card']
            ];
            $result = $recorrencia->createPlan($plan);
            echo $result;
            return;
            //517997
        break;
        case 'create_subscriptions':
            $costumer = [
                'name' => "Cliente de teste",
                'email' => "alexandresofiati@hotmail.com",
                'document_number' => '38639077010',
                'street' => 'Rua de teste',
                'street_number' => 's/n',
                'complementary' => 'Algum complemento',
                'neighborhood' => 'Bairro de teste',
                'zipcode' => '11850000',
                'ddd' => '013',
                'number' => '981081244'
            ];
            $result = $recorrencia->createSubscriptions(517997, 'card_ckhkkidpu05wm0m9tdu7no8ee', $costumer);
            echo "<pre>";
            var_dump($result);
            echo "</pre>";
            //10443220
            return;
        break;
        case 'cancel_subscription':
            $result = $recorrencia->cancelSubscription(536484);
            echo "<pre>";
            var_dump( $result );
            echo "<pre>";
            return;
            //517997
        break;
        //cancelSubscription
        default:
            echo "Não sei qual a sua ação";
            exit();
        break;
    }
}




?>
<?php

declare(strict_types=1);
namespace GuavaPay;

use GuavaPay\Config\CardConfig;
use GuavaPay\Config\DeviceConfig;
use GuavaPay\Entities\BalanceEntity;
use GuavaPay\Entities\NewOrderEntity;
use GuavaPay\Entities\OrderInfoEntity;
use GuavaPay\Entities\PaymentEntity;
use GuavaPay\Entities\RefundEntity;
use GuavaPay\Entities\VersionEntity;
use GuavaPay\Exception\GuavaClientException;
use GuavaPay\Exception\GuavaEcomException;

class EPG
{
    /**
     * Username
     * @var string username
     */
    private string $username;
    /**
     * Password
     * @var string password
     */
    private string $password;
    /**
     * Bank ID
     * @var string bank
     */
    private string $bank;
    /**
     * SID
     * @var string sid
     */
    private string $sid;

    private string $baseUrl = 'https://epg.guavapay.com';

    /**
     * __construct
     *
     * @param string $username
     * @param string $password
     * @param string $bank
     * @param string $sid
     *
     * @return void
     */
    public function __construct(string $username, string $password, string $bank, string $sid)
    {
        $this->username = $username;
        $this->password = $password;
        $this->bank = $bank;
        $this->sid = $sid;
    }

    /**
     * Create order on Electronic Payment Gateway (EPG).
     * @param string $orderNumber
     * @param int $amount
     * @param int $currency
     * @param string $returnUrl
     * @return NewOrderEntity
     * @throws GuavaClientException
     */
    public function createOrder(string $orderNumber, int $amount, int $currency, string $returnUrl) : NewOrderEntity
    {
        $request = $this->sendRequest('epg/rest/register.do', [
            'orderNumber' => $orderNumber,
            'amount' => $amount,
            'currency' => $currency,
            'returnUrl' => $returnUrl,
            'jsonParams' => json_encode(['request' => 'PAY', 'bank' => $this->bank, 'description' => 'PAY', 'sid' => $this->sid])
        ]);
        return new NewOrderEntity($request['orderId'], $request['formUrl']);
    }

    /**
     * This method initiates a refund or reversal process for a specific order.
     * @param string $orderNumber
     * @param int $amount
     * @return RefundEntity
     * @throws GuavaClientException
     */
    public function refundOrder(string $orderNumber, int $amount) : RefundEntity
    {
        $request = $this->sendRequest('epg/rest/refund.do', [
            'orderId' => $orderNumber,
            'amount' => $amount,
            'jsonParams' => json_encode(['request' => 'PAY', 'bank' => $this->bank, 'description' => 'PAY', 'sid' => $this->sid])
        ]);
        return new RefundEntity($request['errorCode']);
    }

    /**
     * Check the 3D Secure version supported by the card.
     * @param string $orderNumber
     * @param CardConfig $card
     * @param string|null $ip
     * @return VersionEntity
     * @throws GuavaClientException
     */
    public function check3dsVersion(string $orderNumber, CardConfig $card, string $ip = null) : VersionEntity
    {
        $ip = $ip ?? $_SERVER['REMOTE_ADDR'] ?? null;
        $request =  $this->sendRequest('epg/rest/check3dsversion.do', [
            'mdOrder' => $orderNumber,
            'pan' => $card->getPan(),
            '$CVC' => $card->getCvc(),
            '$EXPIRY' => $card->getExpiryYear() . $card->getExpiryMonth(),
            'TEXT' => $card->getCardHolder(),
            'ip' => $ip
        ]);
        return new VersionEntity($request['3ds']);
    }

    /**
     * Make a payment using specific card details.
     * @param string $orderNumber
     * @param CardConfig $card
     * @param string|null $ip
     * @return PaymentEntity
     * @throws GuavaClientException
     */
    public function paymentRequest(string $orderNumber, CardConfig $card, DeviceConfig $device = null, string $ip = null) : PaymentEntity
     {
         $ip = $ip ?? $_SERVER['REMOTE_ADDR'] ?? null;
         $requestData = [
             'MDORDER' => $orderNumber,
             '$PAN' => $card->getPan(),
             '$CVC' => $card->getCvc(),
             'YYYY' => $card->getExpiryYear(),
             'MM'   => $card->getExpiryMonth(),
             'TEXT' => $card->getCardHolder(),
             'ip' => $ip
         ];
         if($device instanceof DeviceConfig)
         {
             $requestData = array_merge($requestData, [
                'browserJavaScriptEnabled' => ($device->isBrowserJavaScriptEnabled() === true) ? 'true' : 'false',
                'browserJavaEnabled' => ($device->isBrowserJavaEnabled() === true) ? 'true' : 'false',
                'browserScreenColorDepth' => $device->getBrowserScreenColorDepth(),
                'browserTimeZone' => $device->getBrowserTimeZone(),
                'browserScreenWidth' => $device->getBrowserScreenWidth(),
                'browserScreenHeight' => $device->getBrowserScreenHeight(),
                'browserLanguage' => $device->getBrowserLanguage(),
             ]);
         }
         $request = $this->sendRequest('epg/rest/paymentorder.do', $requestData);
         return new PaymentEntity($request['info'], $request['acsUrl'], $request['cReq']);
     }

    /**
     * Check order status by EPG order ID.
     * This method retrieves the status of an order by its EPG (Electronic Payment Gateway) order ID.
     * @param string $orderNumber
     * @param string $code
     * @return OrderInfoEntity
     * @throws GuavaClientException
     */
    public function getOrderStatus(string $orderNumber, string $code) : OrderInfoEntity
    {
        $request = $this->sendRequest('transaction/' . $code . '/status', [
            'mdorder' => $orderNumber,
        ]);
        return new OrderInfoEntity($request['OrderId'], $request['Description'], $request['Amount'], $request['Currency'], $request['Fee'] ?? null, $request['Timestamp'], $request['status'], $request['order_status'], $request['provider'] ?? null, $request['Pan'] ?: null, $request['RRN'] ?? null, $request['Success'], $request['Auth'] ?? null);
    }

    /**
     * Check the balance of a specific currency on the merchant account.
     * @param int $currency
     * @param string $code
     * @return BalanceEntity
     * @throws GuavaClientException
     */
    public function getBalanceStatus(int $currency, string $code) : BalanceEntity
    {
        $request = $this->sendRequest('merchant/' . $code . '/balance', [
            'currency' => $currency,
        ]);
        return new BalanceEntity(floatval($request['available_amount']));
    }

    private function sendRequest(string $endpoint, array $data)
    {
        $data['userName'] = $this->username;
        $data['username'] = $this->username;
        $data['user']     = $this->username;
        $data['password'] = $this->password;
        $data['sid']      = $this->sid;
        $url = "$this->baseUrl/$endpoint?" . http_build_query($data);

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


        // Execute the request
        $response = curl_exec($ch);
        // Check for cURL errors
        if ($response === false) {
            $errorText = curl_error($ch);
            $errorCode = curl_errno($ch);
            curl_close($ch);
            throw new GuavaClientException($errorText, $errorCode);
        }
        return $this->parseResponse($response);
    }

    /**
     * @throws GuavaEcomException
     */
    private function parseResponse(string $response)
    {
        $result = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new GuavaEcomException('Response is not valid! Raw response: ' . $response);
        }
        $errorCode = isset($result['errorCode']) ? intval($result['errorCode']) : null;
        $errorMessage = $result['errorMessage'] ?? null;
        if($errorCode !== null && $errorCode !== 0)
        {
            throw new GuavaEcomException($errorMessage, $errorCode);
        }
        return $result;
    }
}

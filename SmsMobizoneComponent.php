<?php
namespace bl\cms\sms;

use yii\base\Component;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use yii\web\BadRequestHttpException;


/**
 * This is the component class for Mobizone API
 * https://mobizon.net.ua
 *
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class SmsMobizoneComponent extends Component
{
    /**
     * @var string $apiToken
     * For get API token you must register account at https://mobizon.net.ua
     * After this turn on API access on "API settings" panel section (https://mobizon.net.ua/panel) and get your token.
     */
    public $apiToken;

    /**
     * @var string $recipientPhoneNumber
     * The recipient of an SMS message.
     * This phone number must be in international format without plus.
     * Example: "380965550000".
     */
    public $recipientPhoneNumber;

    /**
     * @var string $defaultMessage
     * Text message.
     */
    public $smsText = '';

    /**
     * @var string $alphaName
     * This is your signature in SMS.
     * For use this property you must create alpha-name in your account (https://mobizon.net.ua/panel)
     * on "My signatures" panel section.
     * This property can be empty.
     */
    public $alphaName = '';


    private $apiServer = 'https://api.mobizon.com/service/';
    private $apiVersion = 'v1';
    private $httpMethod = 'POST';
    private $format = 'json';

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * This methid is using for calling to Mobizone API
     *
     * @param string $model
     * @param string $method
     * @param array $params
     *
     * @return array
     * @throws BadRequestHttpException
     */
    private function callMethod($model, $method, $params = [])
    {
        $data = ArrayHelper::merge([
            'apiKey' => $this->apiToken, 'output' => $this->format, 'api' => $this->apiVersion],
            $params
        );

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod($this->httpMethod)
            ->setUrl($this->apiServer . $model . '/' . $method)
            ->setData($data)
            ->send();
        if ($response->isOk) {
            return $response->data;
        }
        else throw new BadRequestHttpException();
    }

    /**
     * This method returns user account balance
     *
     * @return array
     */
    public function getBalance() {
        $balance = $this->callMethod('User', 'GetOwnBalance');
        return $balance['data']['balance'];
    }

    /**
     * This method sends one SMS.
     * Returns message id.
     *
     * @return integer
     * @throws BadRequestHttpException
     * @throws Exception
     */
    public function send()
    {

        if (empty($this->recipientPhoneNumber)) {
            throw new BadRequestHttpException();
        }

        $data = [
            'recipient' => $this->recipientPhoneNumber,
            'text' => $this->smsText,
            'from' => $this->alphaName
        ];

        $result = $this->callMethod('Message', 'SendSMSMessage', $data);

        if (!empty($result)) {
            return $result['data']['messageId'];
        }
        else throw new Exception();
    }

    /**
     * Sets API token
     *
     * @param string $token
     * @throws BadRequestHttpException
     */
    public function setApiToken($token) {
        if (!empty($token)) {
            $this->apiToken = $token;
        }
        else throw new BadRequestHttpException();
    }

    /**
     * Sets recipient phone number
     *
     * @param string $recipientPhoneNumber
     * @throws BadRequestHttpException
     */
    public function setRecipientPhoneNumber($recipientPhoneNumber) {
        if (!empty($recipientPhoneNumber)) {
            $this->recipientPhoneNumber = $recipientPhoneNumber;
        }
        else throw new BadRequestHttpException();
    }

    /**
     * Sets recipient phone number
     *
     * @param string $smsText
     * @throws BadRequestHttpException
     */
    public function setSmsText($smsText) {
        if (!empty($smsText)) {
            $this->smsText = $smsText;
        }
        else throw new BadRequestHttpException();
    }

    /**
     * Sets API token
     *
     * @param string $alphaName
     * @throws BadRequestHttpException
     */
    public function setAlphaName($alphaName) {
        if (!empty($alphaName)) {
            $this->alphaName = $alphaName;
        }
        else throw new BadRequestHttpException();
    }
}
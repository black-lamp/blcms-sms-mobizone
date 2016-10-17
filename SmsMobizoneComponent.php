<?php
namespace bl\cms\sms;

use yii\base\Component;

/**
 * This is the component class for Mobizone API
 * https://mobizon.net.ua
 *
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class SmsMobizoneComponent extends Component
{
    private $apiToken;
    private $apiServer = 'https://api.mobizon.com/';
    private $format = 'JSON';

    public $phoneNumber;
    public $defaultMessage;

    public function __construct($config)
    {
        $this->apiToken = $config['apiToken'];
        $this->apiServer = $config['apiServer'];
        $this->format = $config['format'];

        $this->phoneNumber = $config['phoneNumber'];
        $this->defaultMessage = $config['defaultMessage'];

        parent::__construct();
    }

    protected function callMethod($model, $method) {
        $data = [
            'apiKey' => $this->apiToken,
        ];

        $url = $this->apiServer . $model . '/' . $method;

        $post = json_encode($data);

        $result = file_get_contents($url, null, stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded;\r\n",
                'content' => $post,
            ]
        ]));

        return $result;
    }

    public function getBalance() {
        $balance = $this->callMethod('user', 'getownbalance');
        return $balance;
    }
}
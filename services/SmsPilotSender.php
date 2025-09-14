<?php

namespace app\services;

use Yii;
use app\models\SmsLog;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * SMSPilot SMS sender implementation
 */
class SmsPilotSender implements SmsSenderInterface
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $apiUrl = 'https://smspilot.ru/api.php';

    /**
     * @var Client
     */
    private $httpClient;

    /**
     * Constructor
     *
     * @param string $apiKey API key for SMSPilot
     */
    public function __construct(string $apiKey = null)
    {
        $this->apiKey = $apiKey ?: Yii::$app->params['smsPilotApiKey'] ?? 'эмулятор';
        $this->httpClient = new Client([
            'timeout' => 30,
            'connect_timeout' => 10,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function sendSms(string $phone, string $message): array
    {
        $response = [
            'success' => false,
            'status' => 'ERROR',
            'message' => 'Unknown error',
            'response' => null,
        ];

        try {
            if ($this->apiKey === 'эмулятор') {
                // Emulation mode
                $response = [
                    'success' => true,
                    'status' => 'EMULATED',
                    'message' => 'SMS sent in emulation mode',
                    'response' => [
                        'id' => uniqid('sms_'),
                        'phone' => $phone,
                        'message' => $message,
                        'cost' => 0,
                    ],
                ];
            } else {
                // Real API call
                $response = $this->sendRealSms($phone, $message);
            }

            // Log SMS attempt
            $this->logSms($phone, $message, $response);

        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'status' => 'ERROR',
                'message' => $e->getMessage(),
                'response' => null,
            ];

            $this->logSms($phone, $message, $response);
        }

        return $response;
    }

    /**
     * Send real SMS via SMSPilot API
     *
     * @param string $phone
     * @param string $message
     * @return array
     * @throws RequestException
     */
    private function sendRealSms(string $phone, string $message): array
    {
        $params = [
            'send' => $message,
            'to' => $phone,
            'apikey' => $this->apiKey,
            'format' => 'json',
        ];

        $response = $this->httpClient->get($this->apiUrl, [
            'query' => $params,
        ]);

        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);

        if (isset($data['error'])) {
            return [
                'success' => false,
                'status' => 'ERROR',
                'message' => $data['error']['description'] ?? 'API Error',
                'response' => $data,
            ];
        }

        return [
            'success' => true,
            'status' => 'SENT',
            'message' => 'SMS sent successfully',
            'response' => $data,
        ];
    }

    /**
     * Log SMS attempt to database
     *
     * @param string $phone
     * @param string $message
     * @param array $response
     */
    private function logSms(string $phone, string $message, array $response): void
    {
        $log = new SmsLog();
        $log->phone = $phone;
        $log->message = $message;
        $log->status = $response['status'];
        $log->response_json = json_encode($response['response']);
        $log->save();
    }
}


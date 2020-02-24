<?php
/**
 * Created by PhpStorm.
 * User: Arun
 * Date: 14.08.19
 * Time: 15:19
 */

namespace App\Repository\EmailService;


use App\Repository\Logging\Logging;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Log;


class Service
{
    public static $status_200 = 'Dispatched successfully.';
    public static $status_429 = 'Too many requests.';
    public static $status_5XX = 'Server Error.';
    public static $status_4XX = 'Client Error.';
    public static $status_default = 'Unable to process request.';

    public function sendEmail($user_id)
    {
        try {
            //guzzle client
            $client = new Client([
                'timeout' => 10,
                'connect_timeout' => 10,
                'http_errors' => false
            ]);

            /*
             * Send as JSON request
             */
            $web_request = $client->post(env('EMAIL_SERVICE_URL') .'/send-email', [
                RequestOptions::JSON => [
                    'user_id' => $user_id
                ]
            ]);

            // return suitable response
            return $this->handleResponseCode($web_request);

        } catch (Exception $exception) {
            activity()->log(Logging::$EMAIL_ERROR);
            Log::error('Error occurred while processing email service for ' . $user_id);
            return [
                'status' => false,
                'msg' => self::$status_default
            ];
        }
    }

    /**
     * @param $response_code
     * @return array
     */
    public function handleResponseCode($web_request)
    {
        $response_code = $web_request->getStatusCode();
        $response = json_decode($web_request->getBody()->getContents()) ?? null;
        $send_status = $response->success ?? null;

        switch ($response_code) {
            case ($response_code == 200 && $send_status):
                activity()->log(self::$status_200);
                return [
                    'status' => true,
                    'msg' => self::$status_200
                ];
                break;
            case 429:
                activity()->log(self::$status_429);
                return [
                    'status' => false,
                    'msg' => self::$status_429
                ];
                break;
            case ($response_code >= 500 && $response_code < 600):
                activity()->log(self::$status_5XX);
                return [
                    'status' => false,
                    'msg' => self::$status_5XX
                ];
                break;
            case ($response_code >= 400 && $response_code < 500):
                activity()->log(self::$status_4XX);
                return [
                    'status' => false,
                    'msg' => self::$status_4XX
                ];
                break;
            default:
                activity()->log(self::$status_default);
                return [
                    'status' => false,
                    'msg' => self::$status_default
                ];
        }
    }
}
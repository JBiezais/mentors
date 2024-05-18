<?php

namespace App\Mail;

use App\Service\OAuthService;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use ReflectionException;

trait SendEmailTrait
{
    /**
     * @throws ReflectionException
     * @throws GuzzleException
     * @throws Exception
     */
    public function sendEmail(): void
    {

        $accessToken = Cache::get('oAuthEmailToken');
        if(!$accessToken){
            $oauthService = new OAuthService();
            $accessToken = $oauthService->getAccessToken();
            Cache::put('oAuthEmailToken', $accessToken, 60);
        }

        $client = new Client();

        $htmlString = $this->buildMailMessage()->render();
        $reflection = new \ReflectionClass($htmlString);
        $property = $reflection->getProperty('html');
        $property->setAccessible(true);

        $response = $client->post('https://graph.microsoft.com/v1.0/users/mentors@rsu.lv/sendMail', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'message' => [
                    'subject' => $this->getSubject(),
                    'body' => [
                        'contentType' => 'HTML',
                        'content' => $property->getValue($htmlString),
                    ],
                    'toRecipients' => [
                        [
                            'emailAddress' => [
                                'address' => $this->data['email'],
                            ],
                        ],
                    ],
                ],
                'saveToSentItems' => 'false',
            ],
        ]);

        if ($response->getStatusCode() !== 202) {
            throw new \Exception('Email sending failed.');
        }
    }
}

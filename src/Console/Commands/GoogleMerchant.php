<?php

namespace Bulkmake\LaravelSocialPlay\Console\Commands;
use Illuminate\Support\Facades\Http;

use Illuminate\Console\Command;

class GoogleMerchant extends Command
{
    protected $signature = 'google merchant';
    protected $description = 'This is a google command for creating the post on google';

    public function __construct()
    {
        parent::__construct();
    }


    /* public function handle()
    {
        $this->info('Updating reviews from Google Reviews');
        $client = $this->getClient();

        $my_business_account = new \Google\Service\MyBusinessAccountManagement($client);
        $my_business_info = new \Google\Service\MyBusinessBusinessInformation($client);
        $account_id = $my_business_account->accounts->listAccounts()[0]->name;
        $location_id = $my_business_info->accounts_locations->listAccountsLocations($account_id, ['readMask' => 'name,title'])->getLocations()[0]->name;

        $url = "https://mybusiness.googleapis.com/v4/$account_id/$location_id/reviews?page_size=50&orderBy=updateTime%20desc"; //?pageSize=50&orderBy=rating&pageToken=1";
        $this->info($url);

        $httpClient = $client->authorize();
        $response = $httpClient->get($url);
        $reviews = json_decode((string)$response->getBody(), false, 5, JSON_THROW_ON_ERROR)->reviews;
        //$this->updateReviews($reviews);

        $url = "https://mybusiness.googleapis.com/v4/$account_id/$location_id/media/customers?page_size=200";
        $response = $httpClient->get($url);
        $media_items = json_decode((string)$response->getBody(), false, 5, JSON_THROW_ON_ERROR)->mediaItems;
        //$this->updateMediaItems($media_items);

        $this->info("Updated Successfully");
    } */

    public function handle()
    {
        $this->info('Updating reviews from Google Reviews');
        $client = $this->getClient();

        $my_business_account = new \Google\Service\MyBusinessAccountManagement($client);
        $my_business_info = new \Google\Service\MyBusinessBusinessInformation($client);
        $my_merchant = new \Google\Service\Merchant($client);
        $account_id = $my_business_account->accounts->listAccounts()[0]->name;
        $location_id = $my_business_info->accounts_locations->listAccountsLocations($account_id, ['readMask' => 'name,title'])->getLocations()[0]->name;

        $url = "https://mybusiness.googleapis.com/v4/$account_id/$location_id/reviews?page_size=50&orderBy=updateTime%20desc"; //?pageSize=50&orderBy=rating&pageToken=1";
        $url = "https://merchantapi.googleapis.com/products/v1beta/$account_id/products";
        $this->info($url);

        $httpClient = $client->authorize();
        $response = $httpClient->get($url);

        $jsonPath = base_path('merchent.json');
        file_put_contents($jsonPath, $response->getBody());

        $this->info("Updated Successfully");
    }

    private function getClient()
    {
        $credentials = base_path(config('social-play.google_client_secrets_json'));
        $tokenPath = base_path(config('social-play.google_auth_token_json'));

        $client = new Google_Client();
        $client->setAuthConfig($credentials);
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        $client->addScope("https://www.googleapis.com/auth/business.manage");
        $client->addScope("https://www.googleapis.com/auth/plus.business.manage");
        $client->addScope("https://www.googleapis.com/auth/content");

        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }
        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));

                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }
            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }
        return $client;
    }
}
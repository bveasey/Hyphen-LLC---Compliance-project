<?php

namespace App\Services;

use App\Http\Resources\AccountLookupResource;
use App\Models\Service;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Perimeter81Service
{
    public function __construct(private Service $service)
    {
    }

    public function lookupAccount(string $email): AccountLookupResource
    {
        $accessToken = $this->getAccessToken();
        $p81Response = $this->requestAccount($accessToken, $email);

        return $this->mapAccountLookupResponse($p81Response);
    }

    public function requestAccount(string $token, string $email): Response
    {
        return  Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ])
            ->get($this->service->base_url . '/users', [
                'page' => 1,
                'limit' => 100,
                'q' => json_encode(['email' => $email]),
                'qType' => 'partial',
            ]);
    }

    public function mapAccountLookupResponse(Response $response): AccountLookupResource
    {
        $perimeter81Response = json_decode($response->body());

        if ($perimeter81Response->itemsTotal) {
            $status = 'Active';
            if ($perimeter81Response->data[0]->terminated) {
                $status = 'Inactive';
            }

            return new AccountLookupResource([
                'service' => $this->service->name,
                'name' => $perimeter81Response->data[0]->firstName . ' ' . $perimeter81Response->data[0]->lastName,
                'email' => $perimeter81Response->data[0]->email,
                'status' => $status,
            ]);
        }

        if (!$perimeter81Response->itemsTotal) {
            return new AccountLookupResource([
                'service' => $this->service->name,
                'status' => 'Account not Found',
            ]);
        }

        Log::error('Perimeter 81 lookup failed', [
            'service' => $this->service,
            'response' => json_encode($perimeter81Response),
        ]);

        return new AccountLookupResource([
            'service' => $this->service->name,
            'error' => 'Lookup Failed',
        ]);
    }

    private function getAccessToken()
    {
        // Perimeter 81 requires you to request an access token which we cache
        // to reduce the number of times we have to make this request and
        // reduce the time spent making the call on concurrent requests

        $accessToken = Cache::get('perimeter81Token');

        if (!$accessToken) {
            $response = Http::acceptJson()
                ->post('https://api.perimeter81.com/api/v1/auth/authorize', [
                    'grantType' => 'api_key',
                    'apiKey' => $this->service->token,
                ]);

            $tokenData = json_decode($response->body())->data;

            // Cache expiration is set to 55min to ensure we request a new access
            // token before the access token expires on their side.
            Cache::put('perimeter81Token', $tokenData->accessToken, 3300);
            $accessToken = $tokenData->accessToken;
        }

        return $accessToken;
    }
}

<?php

namespace App\Services;

use App\Http\Resources\AccountLookupResource;
use App\Models\Service;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SlackService
{
    public function __construct(private Service $service)
    {
    }

    public function lookupAccount(string $email): AccountLookupResource
    {
        $slackResponse = $this->requestAccount($email);

        return $this->mapAccountLookupResponse($slackResponse);
    }

    public function requestAccount(string $email): Response
    {
        return  Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->service->token,
                'Accept' => 'application/json',
            ])
            ->get($this->service->base_url . '/users.lookupByEmail', [
                'email' => $email,
            ]);
    }

    public function mapAccountLookupResponse(Response $response): AccountLookupResource
    {
        $slackResponse = json_decode($response->body());

        if ($slackResponse->ok) {
            $status = 'Active';
            if ($slackResponse->user->deleted) {
                $status = 'Inactive';
            }

            return new AccountLookupResource([
                'service' => $this->service->name,
                'name' => $slackResponse->user->name,
                'email' => $slackResponse->user->profile->email,
                'status' => $status,
            ]);
        }

        if ($slackResponse->error === 'users_not_found') {
            return new AccountLookupResource([
                'service' => $this->service->name,
                'status' => 'Account not Found',
            ]);
        }

        Log::error('Slack lookup failed', [
            'service' => $this->service,
            'response' => json_encode($slackResponse),
        ]);

        return new AccountLookupResource([
            'service' => $this->service->name,
            'error' => 'Lookup Failed',
        ]);
    }
}

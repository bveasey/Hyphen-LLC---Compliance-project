<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountLookupRequest;
use App\Models\Service;

class AccountLookupController extends Controller
{
    public function lookupByEmail(AccountLookupRequest $request, Service $service)
    {
        $serviceClass = Service::MODEL_TYPE_MAP[$service->service_slug];

        return (new $serviceClass($service))->lookupAccount($request->email);
    }
}

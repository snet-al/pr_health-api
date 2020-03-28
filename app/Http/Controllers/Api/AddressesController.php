<?php

namespace App\Http\Controllers\Api;

use App\Address;
use App\Helpers\ApiCodes;
use App\Helpers\ApiContextConstants;
use App\Helpers\HttpStatusCodes;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiAddressesRequest;
use App\Http\Resources\AddressResource;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class AddressesController extends Controller
{
    use ApiTrait;

    public function index()
    {
        $addresses = auth()->user()->addresses;

        return $this->getAddresses(ApiContextConstants::COLLECTION, $addresses);
    }

    public function show($id)
    {
        $address = Address::find($id);

        if (! $address) {
            return $this->resourceNotFound(ApiCodes::getAddressNotFoundMessage());
        }

        return $this->getAddresses(ApiContextConstants::RESOURCE, $address);
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), ApiAddressesRequest::rules());

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $addressData = [
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'zip_code' => $request->zip_code,
            'user_id' => auth()->user()->id,
        ];

        $countAddresses = auth()->user()->addresses()->count();
        if ($countAddresses == 0) {
            $addressData['is_default'] = 1;
        }

        $address = Address::create($addressData);
        $address->fresh();

        return $address ? $this->getAddresses(ApiContextConstants::RESOURCE, $address) : $this->generalError();
    }

    public function update(Request $request, $id)
    {
        $address = Address::find($id);

        if (! $address) {
            return $this->resourceNotFound(ApiCodes::getAddressNotFoundMessage());
        }

        $validator = \Validator::make($request->all(), ApiAddressesRequest::rules());

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $address->update($request->all());

        return $address ? $this->getAddresses(ApiContextConstants::RESOURCE, $address) : $this->generalError();
    }

    public function makeDefault(Request $request, $id)
    {
        $address = Address::find($id);

        if (! $address) {
            return $this->resourceNotFound(ApiCodes::getAddressNotFoundMessage());
        }

        $validator = \Validator::make($request->all(), ['is_default' => ['required', 'boolean']]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $addresses = auth()->user()->addresses;
        foreach ($addresses as $tempAddress) {
            if ($tempAddress->is_default == 1) {
                $tempAddress->is_default = 0;
                $tempAddress->save();
            }
        }
        $address->update(['is_default' => $request->is_default]);

        return $address ? $this->getAddresses(ApiContextConstants::RESOURCE, $address) : $this->generalError();
    }

    public function destroy($id)
    {
        try {
            $addressToDelete = Address::find($id);
            Address::destroy($id);
            if ($addressToDelete->is_default == 1) {
                $address = Address::where('user_id', auth()->user()->id)->first();
                if ($address) {
                    $address->is_default = 1;
                    $address->save();
                }
            }

            return $this->restApiResponse([], ApiCodes::getSuccessMessage(), ApiCodes::SUCCESS, [], HttpStatusCodes::OK);
        } catch (\Exception $exception) {
            return $this->generalError();
        }
    }

    private function getAddresses($context, $address)
    {
        if ($context == ApiContextConstants::RESOURCE) {
            $data = [new AddressResource($address)];
        } else {
            $data = AddressResource::collection($address);
        }

        return $this->restApiResponse($data, ApiCodes::getSuccessMessage(), ApiCodes::SUCCESS, [], HttpStatusCodes::OK);
    }
}

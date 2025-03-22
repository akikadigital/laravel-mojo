<?php

use Akika\Mojo\Enums\Currency;
use Akika\Mojo\Enums\MobileNetwork;
use Akika\Mojo\Http\Integrations\CashoutClient;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

it('can initiateGlobalCashout', function () {
    $baseUrl = config('mojo.dev.cashout_url');
    Http::fake([
        "{$baseUrl}/Cashout/InitiateGlobalCashout" => Http::response(['status_code' => 1], 200),
    ]);

    $client = new CashoutClient;

    $data = [
        'transactionDate' => now(),
        'expiryDate' => now(),
        'payerName' => fake()->name(),
        'payerEmail' => fake()->email(),
        'payerMobile' => fake()->phoneNumber(),
        'payeeName' => fake()->name(),
        'payeeEmail' => fake()->email(),
        'payeeMobile' => fake()->phoneNumber(),
        'mobileNetwork' => fake()->randomElement(MobileNetwork::cases()),
        'currency' => Currency::GHS,
        'country' => fake()->countryCode(),
        'amount' => fake()->randomNumber(),
        'merchantRef' => fake()->lexify('?????'),
        'narration' => fake()->sentence(),
    ];

    $client->initiateGlobalCashout(...$data);

    $response = null;
    Http::assertSent(function (Request $request) use (&$response) {
        $response = $request->data();

        return $request['app_id'] == config('mojo.dev.app_id') &&
            $request['app_key'] == config('mojo.dev.app_key');
    });

    expect($response)->toHaveSnakeCaseKeys();

    $keys = collect(array_keys($data))->map(fn (string $key) => Str::snake($key))->toArray();

    expect($response)->toHaveKeys($keys);
});

<?php

use Akika\Mojo\Enums\Currency;
use Akika\Mojo\Enums\FeeTypeCode;
use Akika\Mojo\Enums\MobileNetwork;
use Akika\Mojo\Http\Integrations\CrossSwitchClient;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

it('can createMMPayment', function () {
    $baseUrl = config('mojo.dev.cross_switch_url');
    Http::fake([
        "{$baseUrl}/Interapi.svc/CreateMMPayment" => Http::response(['status_code' => 1], 200),
    ]);

    $client = new CrossSwitchClient;

    $data = [
        'mobile' => fake()->numerify('+2###########'),
        'mobileNetwork' => fake()->randomElement(MobileNetwork::cases()),
        'amount' => fake()->randomNumber(),
        'orderId' => fake()->numerify('INV#####'),
        'clientTimestamp' => now()->format('Y-M-D TH:i:s'),
        'name' => fake()->name(),
        'email' => fake()->email(),
        'voucherCode' => fake()->word(),
        'orderDesc' => fake()->sentence(),
        'currency' => Currency::GHS,
        'feetypecode' => FeeTypeCode::GENERALPAYMENT,
    ];

    $client->createMMPayment(...$data);

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

it('can getInvoiceStatus', function () {
    $baseUrl = config('mojo.dev.cross_switch_url');
    Http::fake([
        "{$baseUrl}/Interapi.svc/GetInvoiceStatus" => Http::response(['status_code' => 1], 200),
    ]);

    $client = new CrossSwitchClient;

    $orderId = fake()->numerify('#####');

    $client->getInvoiceStatus($orderId);

    $response = null;
    Http::assertSent(function (Request $request) use (&$response) {
        $response = $request->data();

        return $request['app_id'] == config('mojo.dev.app_id') &&
            $request['app_key'] == config('mojo.dev.app_key');
    });

    expect($response)->toHaveSnakeCaseKeys();
    expect($response)->toHaveKey('order_id', $orderId);
});

it('can getAccountProfile', function () {
    $baseUrl = config('mojo.dev.cross_switch_url');
    Http::fake([
        "{$baseUrl}/Interapi.svc/GetAccountProfile" => Http::response(['status_code' => 1], 200),
    ]);

    $client = new CrossSwitchClient;

    $network = fake()->randomElement(MobileNetwork::cases());
    $mobile = fake()->numerify('+2###########');

    $client->getAccountProfile($network, $mobile);

    $response = null;
    Http::assertSent(function (Request $request) use (&$response) {
        $response = $request->data();

        return $request['app_id'] == config('mojo.dev.app_id') &&
            $request['app_key'] == config('mojo.dev.app_key');
    });

    expect($response)->toHaveSnakeCaseKeys();
    expect($response)->toHaveKey('network', $network);
    expect($response)->toHaveKey('mobile', $mobile);
});

<?php

use Akika\Mojo\Enums\Currency;
use Akika\Mojo\Enums\FeeTypeCode;
use Akika\Mojo\Enums\MobileNetwork;
use Akika\Mojo\Http\Integrations\CrossSwitchClient;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

it('can createMMPayment', function () {
    config()->set('mojo.dev.app_id', 'TEST_APP_ID');
    config()->set('mojo.dev.app_key', 'TEST_APP_KEY');

    $baseUrl = config('mojo.dev.cross_switch_url');
    Http::preventStrayRequests();
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
        'feeTypeCode' => FeeTypeCode::GENERALPAYMENT,
    ];

    $client->createMMPayment(...$data);

    $response = null;
    Http::assertSent(function (Request $request) use (&$response) {
        $response = json_decode($request->body(), true);

        return $request['app_id'] == config('mojo.dev.app_id');
    });

    expect($response)->toHaveSnakeCaseKeys();
});

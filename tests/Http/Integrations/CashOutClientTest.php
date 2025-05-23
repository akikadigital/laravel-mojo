<?php

use Akika\Mojo\Enums\Currency;
use Akika\Mojo\Enums\MobileNetwork;
use Akika\Mojo\Http\Integrations\CashOutClient;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

it('can initiateGlobalCashout', function () {
    $baseUrl = config('mojo.dev.cashout_url');
    Http::fake([
        "{$baseUrl}/Cashout/InitiateGlobalCashout" => Http::response(['status_code' => 1], 200),
    ]);

    $client = new CashOutClient;

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

it('can bankTransfer', function () {
    $baseUrl = config('mojo.dev.cashout_url');
    Http::fake([
        "{$baseUrl}/Cashout/BankTransfer" => Http::response(['status_code' => 1], 200),
    ]);

    $client = new CashOutClient;

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
        'bankName' => fake()->word(),
        'bankBranchSortCode' => fake()->lexify('???'),
        'bankCode' => fake()->lexify('???'),
        'bankAccountNo' => fake()->numerify(str_repeat('#', 10)),
        'bankAccountTitle' => fake()->word(),
        'merchantRef' => fake()->lexify('?????'),
        'narration' => fake()->sentence(),
    ];

    $client->bankTransfer(...$data);

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

it('can getBankList', function () {
    $baseUrl = config('mojo.dev.cashout_url');
    Http::fake([
        "{$baseUrl}/Cashout/GetBankList" => Http::response(['status_code' => 1], 200),
    ]);

    $client = new CashOutClient;

    $countryCode = fake()->countryCode();

    $client->getBankList($countryCode);

    $response = null;
    Http::assertSent(function (Request $request) use (&$response) {
        $response = $request->data();

        return $request['app_id'] == config('mojo.dev.app_id') &&
            $request['app_key'] == config('mojo.dev.app_key');
    });

    expect($response)->toHaveSnakeCaseKeys();

    expect($response)->toHaveKey('country_code', $countryCode);
});

it('can getTxnStatus', function () {
    $baseUrl = config('mojo.dev.cashout_url');
    Http::fake([
        "{$baseUrl}/Cashout/GetTxnStatus" => Http::response(['status_code' => 1], 200),
    ]);

    $client = new CashOutClient;

    $merchantRef = fake()->word();

    $client->getTxnStatus($merchantRef);

    $response = null;
    Http::assertSent(function (Request $request) use (&$response) {
        $response = $request->data();

        return $request['app_id'] == config('mojo.dev.app_id') &&
            $request['app_key'] == config('mojo.dev.app_key');
    });

    expect($response)->toHaveSnakeCaseKeys();

    expect($response)->toHaveKey('merchant_ref', $merchantRef);
});

it('can checkAvailableBalance', function () {
    $baseUrl = config('mojo.dev.cashout_url');
    Http::fake([
        "{$baseUrl}/Cashout/CheckAvailableBalance" => Http::response(['status_code' => 1], 200),
    ]);

    $client = new CashOutClient;

    $currencyCode = fake()->randomElement(Currency::cases());

    $client->checkAvailableBalance($currencyCode);

    $response = null;
    Http::assertSent(function (Request $request) use (&$response) {
        $response = $request->data();

        return $request['app_id'] == config('mojo.dev.app_id') &&
            $request['app_key'] == config('mojo.dev.app_key');
    });

    expect($response)->toHaveSnakeCaseKeys();

    expect($response)->toHaveKey('currency_code', $currencyCode->value);
});

it('can validateBankAccount', function () {
    $baseUrl = config('mojo.dev.cashout_url');
    Http::fake([
        "{$baseUrl}/Cashout/ValidateBankAccount" => Http::response(['status_code' => 1], 200),
    ]);

    $client = new CashOutClient;

    $data = [
        'bankName' => fake()->word(),
        'bankBranchSortCode' => fake()->lexify('???'),
        'bankCode' => fake()->lexify('???'),
        'bankAccountNo' => fake()->numerify(str_repeat('#', 10)),
        'bankAccountTitle' => fake()->word(),
    ];
    $client->validateBankAccount(...$data);

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

it('can getAccountProfile', function () {
    $baseUrl = config('mojo.dev.cashout_url');
    Http::fake([
        "{$baseUrl}/Cashout/GetAccountProfile" => Http::response(['status_code' => 1], 200),
    ]);

    $client = new CashOutClient;

    $mobile = fake()->phoneNumber();
    $network = fake()->randomElement(MobileNetwork::cases());

    $client->getAccountProfile($mobile, $network);

    $response = null;
    Http::assertSent(function (Request $request) use (&$response) {
        $response = $request->data();

        return $request['app_id'] == config('mojo.dev.app_id') &&
            $request['app_key'] == config('mojo.dev.app_key');
    });

    expect($response)->toHaveSnakeCaseKeys();

    expect($response)->toHaveKey('mobile', $mobile);
    expect($response)->toHaveKey('network', $network->value);
});

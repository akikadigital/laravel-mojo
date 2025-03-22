<?php

namespace Akika\Mojo\Http\Integrations;

use Akika\Mojo\Enums\Currency;
use Akika\Mojo\Enums\EnvironmentType;
use Akika\Mojo\Enums\FeeTypeCode;
use Akika\Mojo\Enums\MobileNetwork;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class CrossSwitchClient
{
    private string $baseUrl;

    private string $appId;

    private string $appKey;

    public function __construct()
    {
        $environment = EnvironmentType::tryFrom(config('mojo.environment'));
        $isLiveEnvironment = $environment === EnvironmentType::LIVE;

        $this->baseUrl = $isLiveEnvironment
            ? config('mojo.live.cross_switch_url')
            : config('mojo.dev.cross_switch_url');

        $this->appId = $isLiveEnvironment
            ? config('mojo.live.app_id')
            : config('mojo.dev.app_id');

        $this->appKey = $isLiveEnvironment
            ? config('mojo.live.app_key')
            : config('mojo.dev.app_key');
    }

    public function http(string $path, array $body): Response
    {
        return Http::post("{$this->baseUrl}/Interapi.svc{$path}", [
            ...$body,
            'app_id' => $this->appId,
            'app_key' => $this->appKey,
        ]);
    }

    /**
     * @param  string  $mobile  (Required) Customer Mobile number
     * @param  MobileNetwork  $mobileNetwork  (Required) Telco Code
     * @param  float  $amount  (Required) Deposit Amount. Thousand
     *                         separators should not be part of format
     * @param  string  $orderId  (Required) Merchant Transaction Reference Number.
     * @param  ?string  $clientTimestamp  (Optional) Transaction Date and time in
     *                                    following format
     *                                    YYYY-MM-SS Thh:mm:ss.sss
     * @param  ?string  $name  (Optional) Customer Full name
     * @param  ?string  $email  (Optional) Customer Email
     * @param  ?string  $voucherCode  (Optional) Telco Voucher code generated
     *                                via Telco Application i.e.
     *                                Vodafone via USSD *110#. For
     *                                Vodafone it is also optional
     * @param  string  $orderDesc  (Required) Description or detail of order
     * @param  ?Currency  $currency  (Required) Payment Currency Cod (GHS)
     * @param  ?FeeTypeCode  $feeTypeCode  (Optional) Fee Type that customer wants to pay
     */
    public function createMMPayment(
        string $mobile,
        MobileNetwork $mobileNetwork,
        float $amount,
        string $orderId,
        ?string $clientTimestamp,
        ?string $name,
        ?string $email,
        ?string $voucherCode,
        ?string $orderDesc,
        ?Currency $currency = Currency::GHS,
        ?FeeTypeCode $feeTypeCode = FeeTypeCode::GENERALPAYMENT,
    ): Response {
        // Sha1 checksum of following parameters app_id + app_key + Client_timestamp + order_id + Amount
        $signature = $clientTimestamp
            ? sha1($this->appId.$this->appKey.$clientTimestamp.$orderId)
            : null;

        return $this->http('/CreateMMPayment', [
            'client_timestamp' => $clientTimestamp,
            'name' => $name,
            'mobile_network' => $mobileNetwork->value,
            'mobile' => $mobile,
            'email' => $email,
            'feetypecode' => $feeTypeCode?->value,
            'currency' => $currency->value,
            'amount' => $amount,
            'voucher_code' => $voucherCode,
            'order_id' => $orderId,
            'order_desc' => $orderDesc,
            'signature' => $signature,
        ]);
    }
}

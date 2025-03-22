<?php

namespace Akika\Mojo\Http\Integrations;

use Akika\Mojo\Enums\Currency;
use Akika\Mojo\Enums\EnvironmentType;
use Akika\Mojo\Enums\MobileNetwork;
use Carbon\Carbon;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class CashoutClient
{
    private string $baseUrl;

    private string $appId;

    private string $appKey;

    public function __construct()
    {
        $environment = EnvironmentType::tryFrom(config('mojo.environment'));
        $isLiveEnvironment = $environment === EnvironmentType::LIVE;

        $this->baseUrl = $isLiveEnvironment
            ? config('mojo.live.cashout_url')
            : config('mojo.dev.cashout_url');

        $this->appId = $isLiveEnvironment
            ? config('mojo.live.app_id')
            : config('mojo.dev.app_id');

        $this->appKey = $isLiveEnvironment
            ? config('mojo.live.app_key')
            : config('mojo.dev.app_key');
    }

    private function http(string $path, array $body): Response
    {
        return Http::post("{$this->baseUrl}/Cashout{$path}", [
            ...$body,
            'app_id' => $this->appId,
            'app_key' => $this->appKey,
        ]);
    }

    /**
     * @param  Carbon  $transactionDate  (Required) Transaction Date
     * @param  Carbon  $expiryDate  (Required) Transaction Date
     * @param  string  $payerName  (Required) Payer/Sender Name
     * @param  ?string  $payerEmail  (Optional) Payer/Sender Email
     * @param  string  $payerMobile  (Required) Payer/Sender Mobile
     * @param  string  $payeeName  (Required) Receipient/Receiver Name
     * @param  ?string  $payeeEmail  (Optional) Receipient/Receiver Email
     * @param  string  $payeeMobile  (Required) Receipient/Receiver Mobile
     * @param  MobileNetwork  $mobileNetwork  (Required) Mobile network to use
     * @param  Currency  $currency  (Required) ISO 4217 currency
     * @param  ?string  $country  (Optional) ISO 3166-1 alpha-3 country code
     * @param  float  $amount  (Required) Transacted Amount
     * @param  string  $merchantRef  (Required) Merchant transaction reference
     * @param  ?string  $narration  (Optional) Narration for transaction
     * @param  string  $transactionType  (Required) Defaults to `local`
     *
     * ---
     * This will create a new MOMO Cashout Transaction
     */
    public function initiateGlobalCashout(
        Carbon $transactionDate,
        Carbon $expiryDate,
        string $payerName,
        ?string $payerEmail,
        string $payerMobile,
        string $payeeName,
        ?string $payeeEmail,
        string $payeeMobile,
        MobileNetwork $mobileNetwork,
        Currency $currency,
        string $country,
        float $amount,
        string $merchantRef,
        ?string $narration,
        string $transactionType = 'local',
    ): Response {
        return $this->http('/InitiateGlobalCashout', [
            'transaction_date' => $transactionDate->format('Y-m-d H:i:s'),
            'expiry_date' => $expiryDate->format('Y-m-d H:i:s'),
            'payer_name' => $payerName,
            'payer_email' => $payerEmail,
            'payer_mobile' => $payerMobile,
            'payee_name' => $payeeName,
            'payee_email' => $payeeEmail,
            'payee_mobile' => $payeeMobile,
            'mobile_network' => $mobileNetwork,
            'currency' => $currency,
            'country' => $country,
            'amount' => $amount,
            'merchant_ref' => $merchantRef,
            'narration' => $narration,
            'transaction_type' => $transactionType,
        ]);
    }

    /**
     * @param  Carbon  $transactionDate  (Required) Transaction Date
     * @param  Carbon  $expiryDate  (Required) Transaction Date
     * @param  string  $payerName  (Required) Payer/Sender Name
     * @param  ?string  $payerEmail  (Optional) Payer/Sender Email
     * @param  string  $payerMobile  (Required) Payer/Sender Mobile
     * @param  string  $payeeName  (Required) Receipient/Receiver Name
     * @param  ?string  $payeeEmail  (Optional) Receipient/Receiver Email
     * @param  string  $payeeMobile  (Required) Receipient/Receiver Mobile
     * @param  MobileNetwork  $mobileNetwork  (Required) Mobile network to use
     * @param  Currency  $currency  (Required) ISO 4217 currency
     * @param  ?string  $country  (Optional) ISO 3166-1 alpha-3 country code
     * @param  float  $amount  (Required) Transacted Amount
     * @param  string  $bankName  (Required) Destination bank name
     * @param  string  $bankBranchSortCode  (Required) Destination bank branch code
     * @param  string  $bankCode  (Required) The bank code of the destination bank.
     * @param  string  $bankAccountNo  (Required) Account number of the beneficiary.
     * @param  string  $bankAccountTitle  (Required) Account title of the beneficiary
     *                                    in case of BAT (bank account transfer)
     * @param  string  $merchantRef  (Required) Merchant transaction reference
     * @param  ?string  $narration  (Optional) Narration for transaction
     * @param  string  $transactionType  (Required) Defaults to `local`
     *
     * ---
     * This will create a new BAT (Bank Account Transfer) Transactions.
     */
    public function bankTransfer(
        Carbon $transactionDate,
        Carbon $expiryDate,
        string $payerName,
        ?string $payerEmail,
        string $payerMobile,
        string $payeeName,
        ?string $payeeEmail,
        string $payeeMobile,
        MobileNetwork $mobileNetwork,
        Currency $currency,
        string $country,
        float $amount,
        string $bankName,
        string $bankBranchSortCode,
        string $bankCode,
        string $bankAccountNo,
        string $bankAccountTitle,
        string $merchantRef,
        ?string $narration,
        string $transactionType = 'local',
    ): Response {
        return $this->http('/BankTransfer', [
            'transaction_date' => $transactionDate->format('Y-m-d H:i:s'),
            'expiry_date' => $expiryDate->format('Y-m-d H:i:s'),
            'payer_name' => $payerName,
            'payer_email' => $payerEmail,
            'payer_mobile' => $payerMobile,
            'payee_name' => $payeeName,
            'payee_email' => $payeeEmail,
            'payee_mobile' => $payeeMobile,
            'mobile_network' => $mobileNetwork,
            'currency' => $currency,
            'country' => $country,
            'amount' => $amount,
            'bank_name' => $bankName,
            'bank_branch_sort_code' => $bankBranchSortCode,
            'bank_code' => $bankCode,
            'bank_account_no' => $bankAccountNo,
            'bank_account_title' => $bankAccountTitle,
            'merchant_ref' => $merchantRef,
            'narration' => $narration,
            'transaction_type' => $transactionType,
        ]);
    }

    /**
     * @param  string  $countryCode  (Required) ISO 3166-1 alpha-3 country code (GHA, NG)
     *
     * ---
     * Allowed bank listing
     */
    public function getBankList(string $countryCode): Response
    {
        return $this->http('/GetBankList', [
            'country_code' => $countryCode,
        ]);
    }
}

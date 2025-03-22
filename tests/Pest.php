<?php

use Akika\Mojo\Tests\TestCase;
use Illuminate\Support\Facades\Http;

uses(TestCase::class)
    ->beforeEach(function () {
        config()->set('mojo.dev.app_id', 'TEST_APP_ID');
        config()->set('mojo.dev.app_key', 'TEST_APP_KEY');

        Http::preventStrayRequests();
    })
    ->in(__DIR__);

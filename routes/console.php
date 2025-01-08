<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Telegram\Bot\Laravel\Facades\Telegram;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Artisan::command('telegram:set-webhook', function () {
    Telegram::setWebhook(['url' => 'https://lara-autodown.test:82/telegram/webhook']);
    $this->comment('Webhook set successfully!');
});


<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\NotificationController;

class TestTwilioCommand extends Command
{
    protected $signature = 'twilio:test {phone} {message}';
    protected $description = 'Test Twilio WhatsApp integration';

    public function handle()
    {
        $phone = $this->argument('phone');
        $message = $this->argument('message');

        $notificationController = new NotificationController();
        $result = $notificationController->sendWhatsAppNotification($phone, $message);

        if ($result) {
            $this->info("WhatsApp sent successfully to {$phone}");
        } else {
            $this->error("Failed to send WhatsApp to {$phone}");
        }
    }
}

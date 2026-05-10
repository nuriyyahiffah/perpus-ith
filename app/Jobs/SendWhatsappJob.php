<?php

namespace App\Jobs;

use App\Helpers\WhatsappHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendWhatsappJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $phone;
    protected $message;

    public function __construct($phone, $message)
    {
        $this->phone = $phone;
        $this->message = $message;
    }

    public function handle()
    {
        try {
            Log::info("QUEUE WA KIRIM", [
                'phone' => $this->phone
            ]);

            $response = WhatsappHelper::sendNotif($this->phone, $this->message);

            Log::info("QUEUE WA RESPONSE", [
                'response' => $response
            ]);

        } catch (\Exception $e) {
            Log::error("QUEUE WA ERROR: " . $e->getMessage());
        }
    }
}
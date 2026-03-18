<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $sid;
    protected $token;
    protected $from;

    public function __construct()
    {
        $this->sid = config('services.twilio.sid');
        $this->token = config('services.twilio.token');
        $this->from = config('services.twilio.whatsapp_from');
    }

    /**
     * Envía un mensaje de WhatsApp a un número específico usando Twilio
     *
     * @param string $phone
     * @param string $message
     * @return bool
     */
    public function sendMessage(string $phone, string $message): bool
    {
        // Limpiar el número de teléfono (solo dígitos)
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Twilio requires format whatsapp:+<number>
        $to = "whatsapp:+" . $phone;
        $from = "whatsapp:" . $this->from;

        if (empty($this->sid) || empty($this->token) || empty($this->from)) {
            Log::info("SIMULACIÓN WHATSAPP TWILIO - Mensaje a {$to}: {$message}");
            return true;
        }

        try {
            $twilio = new Client($this->sid, $this->token);
            
            $messageResponse = $twilio->messages->create(
                $to,
                [
                    "from" => $from,
                    "body" => $message
                ]
            );

            Log::info("WhatsApp enviado vía Twilio a {$to}. SID: " . $messageResponse->sid);
            return true;

        } catch (\Exception $e) {
            Log::error("Excepción al enviar WhatsApp vía Twilio a {$to}: " . $e->getMessage());
            return false;
        }
    }
}

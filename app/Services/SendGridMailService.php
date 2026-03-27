<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendGridMailService
{
    /**
     * Send an email via SendGrid HTTP API.
     *
     * @param string $toEmail
     * @param string $toName
     * @param string $subject
     * @param string $html
     * @param array  $attachments  [['content' => base64, 'filename' => 'x.pdf', 'type' => 'application/pdf'], ...]
     */
    public static function send(string $toEmail, string $toName, string $subject, string $html, array $attachments = []): bool
    {
        try {
            $apiKey = config('services.sendgrid.api_key');

            if (!$apiKey) {
                Log::warning('SENDGRID_API_KEY not configured, skipping email to ' . $toEmail);
                return false;
            }

            $payload = [
                'personalizations' => [
                    [
                        'to' => [['email' => $toEmail, 'name' => $toName]],
                        'subject' => $subject,
                    ]
                ],
                'from' => [
                    'email' => 'mangukiyabhavdeep007@gmail.com',
                    'name' => 'FrontStore Team',
                ],
                'content' => [
                    ['type' => 'text/html', 'value' => $html],
                ],
            ];

            // Add attachments if any
            if (!empty($attachments)) {
                $payload['attachments'] = $attachments;
            }

            $response = Http::withToken($apiKey)
                ->timeout(15)
                ->post('https://api.sendgrid.com/v3/mail/send', $payload);

            if ($response->successful()) {
                Log::info('Email sent to ' . $toEmail . ' via SendGrid: ' . $subject);
                return true;
            } else {
                Log::error('SendGrid email failed for ' . $toEmail . ': ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('SendGrid email exception for ' . $toEmail . ': ' . $e->getMessage());
            return false;
        }
    }
}

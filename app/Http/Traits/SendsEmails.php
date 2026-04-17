<?php

namespace App\Http\Traits;

use App\Services\EmailService;
use App\Models\User;
use Illuminate\Http\Request;

trait SendsEmails
{
    /**
     * Get EmailService instance
     * 
     * @return EmailService
     */
    protected function emailService()
    {
        return app(EmailService::class);
    }

    /**
     * Get language ID from request header with fallback
     * Priority: Request header > User language > Session > Default (English)
     * 
     * @param Request|null $request Request object
     * @param User|null $user User model
     * @return int Language ID
     */
    protected function getLanguageFromRequest($request = null, $user = null)
    {
        return $this->emailService()->getLanguageFromRequest($request, $user);
    }

    /**
     * Send email using template
     * 
     * @param string|array $to Recipient email address(es)
     * @param int $templateId Email template ID
     * @param array $variables Variables to replace in template
     * @param array $additionalData Additional data to pass to email view
     * @param int|null $languageId Language ID for template translation (if null, will use request header/user/session)
     * @param bool $queue Whether to queue the email (default: true)
     * @param string|null $queueName Queue name (default: 'verify_email')
     * @return bool Success status
     */
    protected function sendEmail(
        $to,
        $templateId,
        $variables = [],
        $additionalData = [],
        $languageId = null,
        $queue = true,
        $queueName = 'verify_email'
    ) {
        return $this->emailService()->sendEmail(
            $to,
            $templateId,
            $variables,
            $additionalData,
            $languageId,
            $queue,
            $queueName
        );
    }

    /**
     * Send email with custom content (without template)
     * 
     * @param string|array $to Recipient email address(es)
     * @param string $subject Email subject
     * @param string $content Email content (HTML)
     * @param array $additionalData Additional data to pass to email view
     * @param bool $queue Whether to queue the email (default: true)
     * @param string|null $queueName Queue name (default: 'verify_email')
     * @return bool Success status
     */
    protected function sendCustomEmail(
        $to,
        $subject,
        $content,
        $additionalData = [],
        $queue = true,
        $queueName = 'verify_email'
    ) {
        return $this->emailService()->sendCustomEmail(
            $to,
            $subject,
            $content,
            $additionalData,
            $queue,
            $queueName
        );
    }
}


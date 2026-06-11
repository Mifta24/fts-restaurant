<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class FreeAnalysisResultUser extends Mailable
{
    public function __construct(
        public array $lead,
        public ?array $analysis = null,
        public string $appLocale = 'id',
    ) {}

    public function build(): static
    {
        $name = trim((string) ($this->lead['name'] ?? 'Unknown'));
        $store = trim((string) ($this->lead['store'] ?? ''));
        $label = $store !== '' ? $store : $name;

        $subject = trans('landing.form.email_user_subject', ['label' => $label], $this->appLocale);

        return $this
            ->locale($this->appLocale)
            ->subject($subject)
            ->text('emails.free-analysis-result-user');
    }
}

<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class FreeAnalysisLeadSubmitted extends Mailable
{
    public function __construct(
        public array $lead,
        public ?array $analysis = null,
        public ?string $analysisError = null,
    ) {}

    public function build(): static
    {
        $name = trim((string) ($this->lead['name'] ?? 'Unknown'));
        $store = trim((string) ($this->lead['store'] ?? ''));
        $label = $store !== '' ? $store : $name;

        return $this
            ->subject('New Free Analysis Request - '.$label)
            ->replyTo((string) $this->lead['email'], $name)
            ->text('emails.free-analysis-lead-submitted');
    }
}

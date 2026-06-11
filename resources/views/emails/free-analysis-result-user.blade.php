{{ __('landing.form.email_user_greeting', ['name' => $lead['name'] ?? 'Unknown']) }}

{{ __('landing.form.email_user_intro') }}

---

@if (is_array($analysis))
{{ __('landing.form.ai_title') }}
{{ data_get($analysis, 'title', '') }}

@if (filled(data_get($analysis, 'summary')))
{{ data_get($analysis, 'summary') }}

@endif
@foreach (data_get($analysis, 'sections', []) as $section)
{{ data_get($section, 'heading', '') }}
@foreach (data_get($section, 'items', []) as $item)
- {{ $item }}
@endforeach

@endforeach
@else
{{ __('landing.form.email_user_no_analysis') }}

@endif
---

{{ __('landing.form.email_user_note') }}

{{ __('landing.form.email_user_closing') }}

FTS Team

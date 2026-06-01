New free analysis request

Lead
- Locale: {{ $lead['locale'] ?? '-' }}
- Name: {{ $lead['name'] ?? '-' }}
- Store: {{ filled($lead['store'] ?? null) ? $lead['store'] : '-' }}
- Email: {{ $lead['email'] ?? '-' }}
- Website: {{ filled($lead['store_url'] ?? null) ? $lead['store_url'] : '-' }}
- Instagram: {{ filled($lead['instagram_url'] ?? null) ? $lead['instagram_url'] : '-' }}
- Google Maps: {{ filled($lead['gmap_url'] ?? null) ? $lead['gmap_url'] : '-' }}

Message
{{ $lead['message'] ?? '-' }}

@if (is_array($analysis))
AI draft
Title: {{ data_get($analysis, 'title', '-') }}
Summary: {{ filled(data_get($analysis, 'summary')) ? data_get($analysis, 'summary') : '-' }}

@foreach (data_get($analysis, 'sections', []) as $section)
{{ data_get($section, 'heading', 'Section') }}
@foreach (data_get($section, 'items', []) as $item)
- {{ $item }}
@endforeach

@endforeach
@elseif (filled($analysisError))
AI draft status
{{ $analysisError }}
@else
AI draft status
No AI draft was generated.
@endif

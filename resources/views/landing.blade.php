@php
    $meta = __('landing.meta');
    $schema = __('landing.schema');
    $localeSlug = $localeSlug ?? 'id';
    $localeLinks = ['id' => '/id', 'en' => '/en', 'jp' => '/jp'];
    $waUrl = config('services.whatsapp.url', 'https://wa.me/');
    $caseHeadings = __('landing.cases.headings');
    $structuredData = [
        '@context' => 'https://schema.org',
        '@type' => 'ProfessionalService',
        'name' => $schema['name'],
        'description' => $schema['description'],
        'url' => $schema['url'],
        'areaServed' => ['ID', 'Global'],
        'serviceType' => 'Restaurant Digital Marketing',
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ $meta['lang'] }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="format-detection" content="telephone=no">
<meta name="robots" content="index, follow, max-image-preview:large">
<title>{{ $meta['title'] }}</title>
<meta name="description" content="{{ $meta['description'] }}">
<meta name="keywords" content="{{ $meta['keywords'] }}">
<meta name="author" content="FTS">
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $meta['og_title'] }}">
<meta property="og:description" content="{{ $meta['og_description'] }}">
<meta property="og:locale" content="{{ $meta['og_locale'] }}">
@foreach ($localeLinks as $slug => $path)
<link rel="alternate" hreflang="{{ $slug === 'jp' ? 'ja' : $slug }}" href="{{ url($path) }}">
@endforeach

@vite(['resources/css/app.css', 'resources/js/app.js'])
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="{{ $meta['font_url'] }}" rel="stylesheet">
<style>
  @keyframes ctaPulse { 0%,100%{box-shadow:0 14px 38px rgba(201,169,97,.42)} 50%{box-shadow:0 18px 50px rgba(212,175,55,.65)} }
  @keyframes livePulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.5;transform:scale(.85)} }
  @keyframes whatsappPulse { 0%,100%{box-shadow:0 10px 28px rgba(37,211,102,.45)} 50%{box-shadow:0 16px 40px rgba(37,211,102,.7)} }
  @keyframes ripple { 0%{transform:scale(1);opacity:.6} 100%{transform:scale(1.7);opacity:0} }
  @keyframes urgencySweep { 0%{transform:translateX(-100%)} 100%{transform:translateX(100%)} }
  body { font-family: {{ $meta['lang'] === 'ja' ? "'Noto Sans JP'" : "'Inter'" }}, sans-serif; line-height: 1.7; -webkit-font-smoothing: antialiased; }
  h1,h2,h3,h4 { white-space: normal; word-break: keep-all; overflow-wrap: normal; word-spacing: normal; letter-spacing: normal; hyphens: none; }
  .gold-underline { position: relative; display: inline-block; }
  .gold-underline::after { content:""; position:absolute; left:0; right:0; bottom:2px; height:6px; background:#d4af37; opacity:.28; z-index:-1; }
  .urgency-sweep::before { content:""; position:absolute; inset:0; background:linear-gradient(90deg, transparent, rgba(212,175,55,.18), transparent); animation:urgencySweep 3s linear infinite; }
  html { scroll-behavior: smooth; }
  .section-label::before { content:"-- "; color:#c9a961; }
  .section-label::after { content:" --"; color:#c9a961; }
  @media (max-width: 768px) { body { padding-bottom: 88px; } }
</style>
<script type="application/ld+json">
{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
</head>

<body class="bg-white text-slate-800">
<header class="sticky top-0 z-50 bg-white/92 backdrop-blur-md border-b border-fts-cream-dark">
  <div class="max-w-7xl mx-auto flex items-center justify-between gap-4 px-6 py-4">
    <a href="{{ url('/') }}" class="flex items-center gap-3 no-underline">
      <span class="font-serif text-2xl font-bold text-fts-green-900 tracking-widest">F<span class="text-fts-gold-400">T</span>S</span>
      <span class="hidden md:inline-block text-[0.7rem] tracking-widest text-slate-500 font-bold border-l border-fts-cream-dark pl-3">{{ __('landing.brand.tagline') }}</span>
    </a>
    <div class="flex items-center gap-3">
      <span class="hidden lg:inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-fts-gold-50 border border-fts-gold-400 text-fts-gold-700 text-xs font-bold">
        <span class="w-2 h-2 bg-fts-gold-400 rounded-full animate-live-pulse"></span>{{ __('landing.shared.limited') }}
      </span>
      <nav class="hidden sm:flex items-center gap-1 rounded-full bg-fts-cream px-1 py-1 text-xs font-black text-fts-green-900" aria-label="{{ __('landing.language.label') }}">
        @foreach (__('landing.language.items') as $slug => $label)
          <a href="{{ url($localeLinks[$slug]) }}" class="px-3 py-1.5 rounded-full {{ $localeSlug === $slug ? 'bg-white shadow-sm text-fts-gold-700' : 'hover:bg-white/70' }}">{{ $label }}</a>
        @endforeach
      </nav>
      <a href="#analysis" class="bg-gradient-to-br from-fts-gold-400 to-fts-gold-500 text-fts-green-950 px-5 py-2.5 rounded-full font-black text-sm shadow-md hover:shadow-gold transition-all whitespace-nowrap">{{ __('landing.shared.primary_cta') }}</a>
    </div>
  </div>
</header>

<div class="urgency-sweep relative bg-gradient-to-r from-fts-green-950 to-fts-green-900 text-white py-3 text-center text-sm font-bold border-b-2 border-fts-gold-400 overflow-hidden">
  <div class="relative z-10 inline-flex items-center gap-3 flex-wrap justify-center px-4">
    <span class="w-2.5 h-2.5 bg-red-500 rounded-full animate-live-pulse shadow-lg shadow-red-500/50"></span>
    <span>{!! __('landing.urgency.main') !!}</span>
    <span class="hidden sm:inline-block w-px h-4 bg-white/30"></span>
    <span class="hidden sm:inline">{{ __('landing.urgency.side') }}</span>
  </div>
</div>

<section class="relative overflow-hidden bg-fts-cream py-20 md:py-28">
  <div class="absolute inset-0 opacity-50" style="background-image: linear-gradient(rgba(15,107,81,0.05) 1px, transparent 1px), linear-gradient(90deg, rgba(15,107,81,0.05) 1px, transparent 1px); background-size: 56px 56px;"></div>
  <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-br from-transparent to-fts-green-100/30 pointer-events-none"></div>
  <div class="relative max-w-7xl mx-auto px-6 grid lg:grid-cols-[1.05fr_1fr] gap-12 items-center">
    <div class="min-w-0">
      <span class="inline-flex items-center gap-2 bg-gradient-to-br from-fts-green-900 to-fts-green-700 text-fts-gold-400 px-4 py-2 rounded-full text-xs font-bold tracking-wider mb-5 shadow-md">✦ {{ __('landing.hero.badge') }}</span>
      <h1 class="font-black text-fts-green-900 leading-tight mb-5 text-[2rem] md:text-[2.4rem] lg:text-[2.8rem]">{!! __('landing.hero.title') !!}</h1>
      <p class="text-fts-green-900 font-black text-base md:text-lg mb-6 inline-flex items-center gap-2"><span class="text-fts-gold-400 text-xl">✦</span>{!! __('landing.hero.subtitle') !!}</p>
      <div class="flex flex-wrap gap-2 mb-6">
        @foreach (__('landing.hero.chips') as $index => $chip)
          <span class="inline-block {{ $index === 0 ? 'bg-fts-green-50 text-fts-green-900 border-fts-green-200' : 'bg-fts-gold-50 text-fts-gold-700 border-fts-gold-400' }} font-bold text-xs md:text-sm px-4 py-2 rounded-full border">{{ $chip }}</span>
        @endforeach
      </div>
      <h2 class="text-fts-green-700 font-bold text-lg md:text-xl mb-6">{!! __('landing.hero.lead') !!}</h2>
      <p class="text-slate-600 leading-relaxed mb-4">{!! __('landing.hero.intro') !!}</p>
      <ul class="space-y-2 mb-7">
        @foreach (__('landing.hero.bullets') as $bullet)
          <li class="flex items-start gap-3"><span class="text-fts-gold-400 font-black mt-0.5">✓</span><span class="text-slate-700">{!! $bullet !!}</span></li>
        @endforeach
      </ul>
      <p class="text-slate-600 leading-relaxed mb-7">{!! __('landing.hero.body') !!}</p>
      <div class="bg-white border-l-4 border-fts-gold-400 px-6 py-5 mb-8 rounded-r-xl shadow-sm"><p class="leading-relaxed">{!! __('landing.hero.note') !!}</p></div>
      <div class="inline-flex items-center gap-3 bg-red-50 border-2 border-red-400 rounded-xl px-5 py-3 mb-7">
        <span class="w-9 h-9 bg-red-500 text-white rounded-full flex items-center justify-center font-black animate-live-pulse">!</span>
        <div class="text-left leading-tight"><strong class="block text-red-700 font-black text-sm">{{ __('landing.hero.spots_title') }}</strong><span class="text-slate-600 text-xs">{{ __('landing.hero.spots_body') }}</span></div>
      </div>
      <div class="space-y-3">
        <div class="border-l-4 border-fts-gold-400 pl-4 py-1 space-y-1.5">
          <p class="text-fts-green-900 font-bold text-base md:text-lg leading-relaxed">{!! __('landing.hero.cta_title') !!}</p>
          <p class="text-slate-600 text-sm md:text-base leading-relaxed">{{ __('landing.hero.cta_body') }}</p>
        </div>
        <p class="text-sm text-slate-500 font-bold">⏱ {{ __('landing.shared.time_note') }}</p>
        <div class="flex flex-col sm:flex-row gap-3">
          <a href="#analysis" class="inline-flex items-center justify-center gap-2 bg-gradient-to-br from-fts-gold-400 via-fts-gold-500 to-fts-gold-400 text-fts-green-950 px-8 py-5 rounded-full font-black text-base md:text-lg shadow-gold hover:-translate-y-1 transition-all animate-cta-pulse">{{ __('landing.shared.primary_cta') }} <span>→</span></a>
          <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 bg-[#25D366] text-white px-7 py-5 rounded-full font-bold shadow-lg hover:bg-[#1ebe5a] hover:-translate-y-0.5 transition-all">@include('partials.whatsapp-icon') {{ __('landing.shared.whatsapp_cta') }}</a>
        </div>
        <p class="text-xs text-slate-500 pt-2">{{ __('landing.hero.disclaimer') }}</p>
      </div>
      <div class="flex flex-wrap gap-2 mt-8">
        @foreach (__('landing.hero.trust_badges') as $badge)
          <span class="bg-white text-fts-green-900 text-xs font-bold px-4 py-2 rounded-full border border-fts-green-100 shadow-sm">{{ $badge }}</span>
        @endforeach
      </div>
    </div>
    <div class="relative">
      <div class="relative rounded-3xl overflow-hidden shadow-green-lg">
        <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1200&q=80" alt="{{ __('landing.hero.image_alt') }}" class="w-full h-[420px] md:h-[540px] object-cover">
        <div class="absolute inset-0 bg-gradient-to-br from-fts-green-900/20 to-transparent"></div>
        <div class="absolute bottom-5 left-5 right-5 sm:right-auto bg-white/95 backdrop-blur-md rounded-xl p-4 shadow-green-lg flex items-center gap-3 border border-fts-gold-100">
          <div class="w-11 h-11 rounded-full bg-gradient-to-br from-fts-gold-400 to-fts-gold-500 flex items-center justify-center text-fts-green-950 font-black text-lg shrink-0">↑</div>
          <div><strong class="block text-fts-green-900 font-black text-sm">{{ __('landing.hero.stat_title') }}</strong><span class="text-xs text-slate-500">{{ __('landing.hero.stat_body') }}</span></div>
        </div>
      </div>
      <div class="hidden md:block absolute -bottom-7 -right-7 w-32 h-32 border-[3px] border-fts-gold-400 rounded-3xl -z-10"></div>
    </div>
  </div>
</section>

<section class="bg-fts-cream-dark py-12 border-y border-fts-cream-dark">
  <div class="max-w-6xl mx-auto px-6 text-center">
    <p class="text-slate-500 text-xs tracking-widest font-bold mb-6">{{ __('landing.businesses.label') }}</p>
    <div class="flex flex-wrap justify-center gap-3">
      @foreach (__('landing.businesses.items') as $item)
        <span class="bg-white text-fts-green-900 px-5 py-2.5 rounded-full border border-fts-cream-dark text-sm font-bold shadow-sm">{{ $item }}</span>
      @endforeach
    </div>
  </div>
</section>

<section class="bg-fts-green-50 py-24 md:py-32">
  <div class="max-w-6xl mx-auto px-6">
    <div class="text-center mb-12">
      <span class="section-label inline-block text-fts-gold-400 font-serif italic tracking-widest text-sm font-semibold mb-3">{{ __('landing.problems.label') }}</span>
      <h2 class="text-fts-green-900 font-black text-3xl md:text-4xl mb-5 leading-tight">{!! __('landing.problems.title') !!}</h2>
      <p class="text-slate-600 leading-relaxed max-w-2xl mx-auto">{!! __('landing.problems.body') !!}</p>
    </div>
    <div class="grid md:grid-cols-2 gap-4 max-w-3xl mx-auto">
      @foreach (__('landing.problems.items') as $item)
        <div class="bg-white border-l-4 border-fts-gold-400 rounded-xl p-6 shadow-sm flex items-start gap-4">
          <span class="w-7 h-7 rounded-full bg-fts-green-900 text-fts-gold-400 flex items-center justify-center text-sm font-black shrink-0 mt-0.5">✓</span>
          <p class="leading-relaxed">{!! $item !!}</p>
        </div>
      @endforeach
    </div>
    <div class="bg-white border-2 border-fts-gold-400 rounded-2xl p-8 max-w-3xl mx-auto mt-10 text-center shadow-sm">
      <p class="text-fts-green-900 leading-relaxed">{!! __('landing.problems.callout') !!}</p>
    </div>
  </div>
</section>

<section class="bg-white py-24 md:py-32">
  <div class="max-w-6xl mx-auto px-6">
    <div class="text-center mb-14">
      <span class="section-label inline-block text-fts-gold-400 font-serif italic tracking-widest text-sm font-semibold mb-3">{{ __('landing.solutions.label') }}</span>
      <h2 class="text-fts-green-900 font-black text-3xl md:text-4xl mb-5 leading-tight">{!! __('landing.solutions.title') !!}</h2>
      <p class="text-slate-600 leading-relaxed max-w-2xl mx-auto">{!! __('landing.solutions.body') !!}</p>
    </div>
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
      @foreach (__('landing.solutions.items') as $item)
        <div class="bg-fts-cream rounded-2xl p-8 shadow-sm hover:shadow-green-lg hover:-translate-y-1 transition-all border-t-4 {{ $item['gold'] ? 'border-fts-gold-400' : 'border-fts-green-900' }}">
          <div class="font-serif italic text-5xl text-fts-gold-400 leading-none mb-4">{{ str_pad((string) ($loop->index + 1), 2, '0', STR_PAD_LEFT) }}</div>
          <h3 class="text-fts-green-900 font-black text-lg mb-3">{{ $item['title'] }}</h3>
          <p class="text-slate-600 text-sm leading-relaxed">{{ $item['body'] }}</p>
        </div>
      @endforeach
    </div>
    <div class="text-center mt-12"><a href="#analysis" class="inline-flex items-center gap-2 bg-gradient-to-br from-fts-gold-400 via-fts-gold-500 to-fts-gold-400 text-fts-green-950 px-10 py-5 rounded-full font-black text-base md:text-lg shadow-gold hover:-translate-y-1 transition-all animate-cta-pulse">{{ __('landing.shared.primary_cta') }} <span>→</span></a></div>
  </div>
</section>

<section class="bg-fts-green-50 py-24 md:py-32">
  <div class="max-w-6xl mx-auto px-6">
    <div class="text-center mb-14">
      <span class="section-label inline-block text-fts-gold-400 font-serif italic tracking-widest text-sm font-semibold mb-3">{{ __('landing.services.label') }}</span>
      <h2 class="text-fts-green-900 font-black text-3xl md:text-4xl mb-5 leading-tight">{!! __('landing.services.title') !!}</h2>
      <div class="max-w-3xl mx-auto bg-white border-2 border-fts-gold-400 rounded-2xl p-6 md:p-7 shadow-sm">
        <p class="text-fts-green-900 font-black text-base md:text-lg leading-relaxed">{!! __('landing.services.intro') !!}</p>
        <p class="text-slate-600 text-sm mt-3 leading-relaxed">{!! __('landing.services.intro_body') !!}</p>
      </div>
      <div class="max-w-3xl mx-auto bg-[#25D366]/5 border border-[#25D366]/30 rounded-2xl p-6 md:p-7 mt-5 shadow-sm text-left">
        <p class="text-fts-green-900 font-black text-sm md:text-base mb-3 flex items-center gap-2">@include('partials.whatsapp-icon', ['size' => 20, 'color' => '#25D366']) {{ __('landing.services.wa_title') }}</p>
        <p class="text-slate-600 text-sm leading-relaxed mb-3">{!! __('landing.services.wa_body_1') !!}</p>
        <p class="text-slate-600 text-sm leading-relaxed">{!! __('landing.services.wa_body_2') !!}</p>
      </div>
    </div>
    <div class="grid md:grid-cols-2 gap-6 max-w-4xl mx-auto">
      @foreach (__('landing.services.items') as $item)
        <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-green-lg transition-all flex gap-5 items-start">
          <div class="w-16 h-16 rounded-full bg-gradient-to-br from-fts-gold-400 to-fts-gold-500 text-fts-green-950 flex items-center justify-center font-serif font-black text-xl shrink-0 shadow-md">{{ ['I','II','III','IV'][$loop->index] }}</div>
          <div><h3 class="text-fts-green-900 font-black text-lg mb-2">{{ $item['title'] }}</h3><p class="text-fts-gold-700 font-bold text-sm mb-2 italic">{{ $item['tagline'] }}</p><p class="text-slate-600 text-sm leading-relaxed">{{ $item['body'] }}</p></div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<section class="bg-white py-24 md:py-32">
  <div class="max-w-4xl mx-auto px-6">
    <div class="text-center mb-14">
      <span class="section-label inline-block text-fts-gold-400 font-serif italic tracking-widest text-sm font-semibold mb-3">{{ __('landing.process.label') }}</span>
      <h2 class="text-fts-green-900 font-black text-3xl md:text-4xl mb-5 leading-tight">{!! __('landing.process.title') !!}</h2>
      <p class="text-slate-600 leading-relaxed">{{ __('landing.process.body') }}</p>
    </div>
    <div class="space-y-4">
      @foreach (__('landing.process.items') as $item)
        <div class="bg-fts-cream rounded-2xl p-7 shadow-sm flex items-center gap-6 hover:translate-x-1 transition-all">
          <div class="w-16 h-16 rounded-full bg-fts-green-900 text-fts-gold-400 flex items-center justify-center font-serif font-black text-lg shrink-0">{{ str_pad((string) ($loop->index + 1), 2, '0', STR_PAD_LEFT) }}</div>
          <div><h3 class="text-fts-green-900 font-black mb-1">{{ $item['title'] }} @if($item['meta'])<span class="text-xs text-fts-gold-700 font-bold ml-2 italic">{{ $item['meta'] }}</span>@endif</h3><p class="text-slate-600 text-sm">{{ $item['body'] }}</p></div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<section class="bg-fts-green-50 py-24 md:py-32">
  <div class="max-w-6xl mx-auto px-6">
    <div class="text-center mb-14">
      <span class="section-label inline-block text-fts-gold-400 font-serif italic tracking-widest text-sm font-semibold mb-3">{{ __('landing.cases.label') }}</span>
      <h2 class="text-fts-green-900 font-black text-3xl md:text-4xl mb-5 leading-tight">{!! __('landing.cases.title') !!}</h2>
      <p class="text-slate-600 leading-relaxed max-w-xl mx-auto">{{ __('landing.cases.body') }}</p>
    </div>
    <div class="grid md:grid-cols-3 gap-6">
      @foreach (__('landing.cases.items') as $item)
        <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-green-lg hover:-translate-y-1 transition-all">
          <img src="{{ $item['image'] }}" alt="{{ $item['alt'] }}" class="w-full h-48 object-cover">
          <div class="p-7">
            <span class="inline-block bg-fts-green-900 text-fts-gold-400 px-3 py-1 rounded-full text-xs font-bold tracking-wide mb-4">{{ $item['tag'] }}</span>
            <p class="text-fts-gold-700 text-xs font-black tracking-wider mb-1">{{ $caseHeadings['challenge'] }}</p><p class="text-slate-700 text-sm mb-3">{{ $item['challenge'] }}</p>
            <p class="text-fts-gold-700 text-xs font-black tracking-wider mb-1">{{ $caseHeadings['approach'] }}</p><p class="text-slate-700 text-sm mb-3">{{ $item['approach'] }}</p>
            <p class="text-fts-gold-700 text-xs font-black tracking-wider mb-1">{{ $caseHeadings['result'] }}</p><p class="bg-gradient-to-br from-fts-green-900 to-fts-green-700 text-white px-4 py-3 rounded-lg font-bold text-sm">{!! $item['result'] !!}</p>
            <p class="text-xs text-slate-500 mt-3 leading-relaxed">※ {{ __('landing.cases.note') }}</p>
          </div>
        </div>
      @endforeach
    </div>
    <p class="text-center text-xs text-slate-500 mt-8 leading-relaxed bg-fts-cream-dark border border-fts-cream-dark rounded-xl px-6 py-4 max-w-2xl mx-auto">※ {!! __('landing.cases.disclaimer') !!}</p>
  </div>
</section>

<section class="bg-white py-24 md:py-32">
  <div class="max-w-4xl mx-auto px-6">
    <div class="text-center mb-10">
      <span class="section-label inline-block text-fts-gold-400 font-serif italic tracking-widest text-sm font-semibold mb-3">{{ __('landing.promo.label') }}</span>
      <h2 class="text-fts-green-900 font-black text-3xl md:text-4xl mb-5 leading-tight">{!! __('landing.promo.title') !!}</h2>
      <p class="text-slate-600 leading-relaxed max-w-2xl mx-auto">{!! __('landing.promo.body') !!}</p>
    </div>
    <div class="bg-gradient-to-br from-fts-gold-50 via-white to-fts-gold-50 border-2 border-dashed border-fts-gold-400 rounded-2xl p-8 md:p-10">
      <div class="grid md:grid-cols-3 gap-6 mb-8">
        @foreach (__('landing.promo.items') as $item)
          <div class="text-center"><div class="w-14 h-14 mx-auto rounded-full bg-fts-green-900 text-fts-gold-400 flex items-center justify-center font-serif font-black text-lg mb-3">{{ $loop->iteration }}</div><h4 class="text-fts-green-900 font-black mb-2 text-sm">{{ $item['title'] }}</h4><p class="text-slate-600 text-xs">{{ $item['body'] }}</p></div>
        @endforeach
      </div>
      <div class="text-center"><a href="#analysis" class="inline-flex items-center gap-2 bg-gradient-to-br from-fts-gold-400 via-fts-gold-500 to-fts-gold-400 text-fts-green-950 px-10 py-5 rounded-full font-black text-base md:text-lg shadow-gold hover:-translate-y-1 transition-all animate-cta-pulse">{{ __('landing.shared.primary_cta') }} <span>→</span></a><p class="text-xs text-slate-500 mt-4 font-bold">⏱ {{ __('landing.shared.time_note') }}</p></div>
    </div>
  </div>
</section>

<section id="contact" class="relative bg-gradient-to-br from-fts-green-900 to-fts-green-700 text-white py-28 md:py-32 overflow-hidden">
  <div class="absolute inset-0 pointer-events-none" style="background: radial-gradient(circle at 18% 28%, rgba(212,175,55,0.16), transparent 42%), radial-gradient(circle at 82% 72%, rgba(212,175,55,0.13), transparent 42%);"></div>
  <div class="relative max-w-4xl mx-auto px-6">
    <div class="text-center mb-12">
      <span class="section-label inline-block text-fts-gold-400 font-serif italic tracking-widest text-sm font-semibold mb-3">{{ __('landing.contact.label') }}</span>
      <h2 class="text-white font-black text-3xl md:text-4xl mb-6 leading-tight">{!! __('landing.contact.title') !!}</h2>
      <p class="text-white/90 leading-relaxed max-w-2xl mx-auto">{!! __('landing.contact.body') !!}</p>
    </div>
    <div class="grid md:grid-cols-2 gap-4 max-w-3xl mx-auto mb-12">
      @foreach (__('landing.contact.risk') as $item)
        <div class="bg-white/10 backdrop-blur-md border border-fts-gold-400/30 rounded-xl p-5 flex items-start gap-3"><span class="text-fts-gold-400 text-2xl leading-none mt-0.5">✓</span><div><strong class="block text-fts-gold-400 font-black text-sm mb-1">{{ $item['title'] }}</strong><span class="text-white/80 text-xs leading-relaxed">{{ $item['body'] }}</span></div></div>
      @endforeach
    </div>
    <div id="analysis" class="scroll-mt-28 relative bg-white text-slate-800 rounded-3xl p-8 md:p-12 max-w-2xl mx-auto shadow-2xl border-t-8 border-fts-gold-400">
      <div id="analysisLoadingOverlay" class="hidden absolute inset-0 z-10 bg-white/92 backdrop-blur-sm rounded-3xl flex-col items-center justify-center gap-5 px-8 text-center">
        <svg class="w-12 h-12 text-fts-gold-500 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
        <div>
          <p class="font-black text-fts-green-900 text-lg mb-1">{{ __('landing.form.loading_title') }}</p>
          <p class="text-slate-500 text-sm">{{ __('landing.form.loading_body') }}</p>
        </div>
      </div>
      <h3 class="text-fts-green-900 font-black text-xl md:text-2xl text-center mb-2">{{ __('landing.form.title') }}</h3>
      <p class="text-center text-slate-500 text-sm mb-7">{{ __('landing.form.subtitle') }}</p>
      @if (session('status'))
        <div class="mb-6 rounded-xl border border-fts-green-200 bg-fts-green-50 px-4 py-3 text-sm font-bold text-fts-green-900">{{ session('status') }}</div>
      @endif
      @if (session('ai_analysis'))
        @php($analysis = session('ai_analysis'))
        <div class="mb-6 overflow-hidden rounded-2xl border border-fts-green-200 bg-fts-green-50 text-sm text-fts-green-950">
          <div class="border-b border-fts-green-200 bg-white px-5 py-4">
            <p class="text-[11px] font-black uppercase tracking-widest text-fts-gold-700">{{ __('landing.form.ai_title') }}</p>
            <p class="mt-1 text-lg font-black leading-tight">{{ is_array($analysis) ? data_get($analysis, 'title', __('landing.form.ai_title')) : __('landing.form.ai_title') }}</p>
            @if (is_array($analysis) && filled(data_get($analysis, 'summary')))
              <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ data_get($analysis, 'summary') }}</p>
            @endif
          </div>
          @if (is_array($analysis))
            <div class="divide-y divide-fts-green-200">
              @foreach (data_get($analysis, 'sections', []) as $section)
                <div class="px-5 py-4">
                  <p class="mb-3 font-black text-fts-green-900">{{ data_get($section, 'heading') }}</p>
                  <ul class="space-y-2">
                    @foreach (data_get($section, 'items', []) as $item)
                      <li class="flex gap-2 leading-relaxed text-slate-700"><span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-fts-gold-400"></span><span>{{ $item }}</span></li>
                    @endforeach
                  </ul>
                </div>
              @endforeach
            </div>
          @else
            <div class="whitespace-pre-line px-5 py-4 leading-relaxed">{{ $analysis }}</div>
          @endif
          <p class="mx-5 mb-4 border-t border-fts-green-200 pt-3 text-xs text-fts-green-900/75">{{ __('landing.form.ai_note') }}</p>
        </div>
      @endif
      @if (session('ai_analysis_error'))
        <div class="mb-6 rounded-xl border border-fts-gold-400 bg-fts-gold-50 px-4 py-3 text-sm font-bold text-fts-gold-700">{{ session('ai_analysis_error') }}</div>
      @endif
      @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</div>
      @endif
      <p class="text-center mb-7"><span class="inline-block bg-fts-gold-50 border border-fts-gold-400 text-fts-gold-700 text-xs font-black rounded-full px-3 py-1.5">⏱ {{ __('landing.form.quota') }}</span></p>
      <form action="{{ route('landing.contact') }}" method="post" class="space-y-5">
        @csrf
        <input type="hidden" name="locale" value="{{ $localeSlug }}">
        <div><label for="name" class="block text-fts-green-900 font-bold text-sm mb-2">{{ __('landing.form.name') }} <span class="text-fts-gold-700 text-xs bg-fts-gold-50 px-2 py-0.5 rounded ml-1">{{ __('landing.form.required') }}</span></label><input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="{{ __('landing.form.name_placeholder') }}" required autocomplete="name" class="w-full px-4 py-4 bg-fts-cream border border-fts-cream-dark rounded-lg focus:outline-none focus:border-fts-green-900 focus:bg-white focus:ring-4 focus:ring-fts-green-900/10 transition-all"></div>
        <div><label for="store" class="block text-fts-green-900 font-bold text-sm mb-2">{{ __('landing.form.store') }} <span class="text-slate-400 text-xs ml-1">({{ __('landing.form.optional') }})</span></label><input type="text" id="store" name="store" value="{{ old('store') }}" placeholder="{{ __('landing.form.store_placeholder') }}" class="w-full px-4 py-4 bg-fts-cream border border-fts-cream-dark rounded-lg focus:outline-none focus:border-fts-green-900 focus:bg-white focus:ring-4 focus:ring-fts-green-900/10 transition-all"></div>
        <div><label for="email" class="block text-fts-green-900 font-bold text-sm mb-2">{{ __('landing.form.email') }} <span class="text-fts-gold-700 text-xs bg-fts-gold-50 px-2 py-0.5 rounded ml-1">{{ __('landing.form.required') }}</span></label><input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="{{ __('landing.form.email_placeholder') }}" required autocomplete="email" class="w-full px-4 py-4 bg-fts-cream border border-fts-cream-dark rounded-lg focus:outline-none focus:border-fts-green-900 focus:bg-white focus:ring-4 focus:ring-fts-green-900/10 transition-all"></div>
        <div class="bg-fts-green-50 border border-fts-green-100 rounded-xl p-5 space-y-4">
          <p class="text-fts-green-900 font-bold text-sm flex items-center gap-2"><span class="text-fts-gold-400">🔍</span>{{ __('landing.form.accuracy_title') }} <span class="text-slate-400 font-normal text-xs">({{ __('landing.form.optional') }})</span></p>
          <p class="text-xs text-slate-500 -mt-2">{{ __('landing.form.accuracy_body') }}</p>
          <div><label for="store_url" class="block text-fts-green-900 font-bold text-xs mb-1.5">{{ __('landing.form.store_url') }}</label><input type="url" id="store_url" name="store_url" value="{{ old('store_url') }}" placeholder="{{ __('landing.form.store_url_placeholder') }}" class="w-full px-3.5 py-3 bg-white border border-fts-cream-dark rounded-lg text-sm focus:outline-none focus:border-fts-green-900 focus:ring-4 focus:ring-fts-green-900/10 transition-all"></div>
          <div><label for="instagram_url" class="block text-fts-green-900 font-bold text-xs mb-1.5">{{ __('landing.form.instagram_url') }}</label><input type="url" id="instagram_url" name="instagram_url" value="{{ old('instagram_url') }}" placeholder="{{ __('landing.form.instagram_url_placeholder') }}" class="w-full px-3.5 py-3 bg-white border border-fts-cream-dark rounded-lg text-sm focus:outline-none focus:border-fts-green-900 focus:ring-4 focus:ring-fts-green-900/10 transition-all"></div>
          <div><label for="gmap_url" class="block text-fts-green-900 font-bold text-xs mb-1.5">{{ __('landing.form.gmap_url') }}</label><input type="url" id="gmap_url" name="gmap_url" value="{{ old('gmap_url') }}" placeholder="{{ __('landing.form.gmap_url_placeholder') }}" class="w-full px-3.5 py-3 bg-white border border-fts-cream-dark rounded-lg text-sm focus:outline-none focus:border-fts-green-900 focus:ring-4 focus:ring-fts-green-900/10 transition-all"></div>
        </div>
        <div><label for="message" class="block text-fts-green-900 font-bold text-sm mb-2">{{ __('landing.form.message') }} <span class="text-fts-gold-700 text-xs bg-fts-gold-50 px-2 py-0.5 rounded ml-1">{{ __('landing.form.required') }}</span></label><textarea id="message" name="message" rows="5" placeholder="{{ __('landing.form.message_placeholder') }}" required class="w-full px-4 py-4 bg-fts-cream border border-fts-cream-dark rounded-lg focus:outline-none focus:border-fts-green-900 focus:bg-white focus:ring-4 focus:ring-fts-green-900/10 transition-all resize-y">{{ old('message') }}</textarea></div>
        <label class="flex items-start gap-3 text-sm text-slate-600"><input type="checkbox" name="consent" required class="mt-1 w-5 h-5 accent-fts-green-900 shrink-0"><span>{!! __('landing.form.consent') !!}</span></label>
        <button id="analysisSubmitBtn" type="submit" data-loading-text="{{ __('landing.form.button_loading') }}" class="w-full bg-gradient-to-br from-fts-gold-400 via-fts-gold-500 to-fts-gold-400 text-fts-green-950 py-5 rounded-full font-black text-base md:text-lg shadow-gold hover:-translate-y-1 transition-all disabled:opacity-60 disabled:cursor-not-allowed disabled:translate-y-0">{{ __('landing.form.button') }}</button>
        <p class="text-center text-xs text-slate-500 leading-relaxed">{!! __('landing.form.footnote') !!}</p>
      </form>
    </div>
    <div class="text-center mt-10 text-white/90"><p class="mb-3">💬 {!! __('landing.contact.info') !!}</p><p class="text-xs text-white/70">{{ __('landing.contact.info_small') }}</p></div>
    <div class="bg-white/10 backdrop-blur-md border-l-4 border-fts-gold-400 rounded-r-xl p-7 md:p-8 max-w-2xl mx-auto mt-10">
      <p class="font-black text-fts-gold-400 mb-3">📌 {{ __('landing.contact.last_title') }}</p>
      <ul class="space-y-2 text-sm text-white/90 leading-relaxed">@foreach (__('landing.contact.last_items') as $item)<li>{{ $loop->iteration }}. {!! $item !!}</li>@endforeach</ul>
      <p class="text-xs text-white/70 mt-4 pt-4 border-t border-white/20">※ {{ __('landing.contact.last_note') }}</p>
    </div>
    <div class="max-w-3xl mx-auto mt-14 text-center">
      <p class="text-white text-lg md:text-2xl font-black leading-relaxed mb-3">{!! __('landing.contact.closing_title') !!}</p>
      <p class="text-white/85 text-sm md:text-base leading-relaxed mb-8">{{ __('landing.contact.closing_body') }}</p>
      <a href="#analysis" onclick="document.getElementById('name').focus();" class="inline-flex items-center gap-2 bg-gradient-to-br from-fts-gold-400 via-fts-gold-500 to-fts-gold-400 text-fts-green-950 px-9 py-5 rounded-full font-black text-base md:text-lg shadow-gold hover:-translate-y-1 transition-all animate-cta-pulse">{{ __('landing.shared.primary_cta') }} <span>→</span></a>
      <p class="text-xs text-white/70 mt-4 font-bold">⏱ {{ __('landing.shared.time_note') }}</p>
    </div>
  </div>
</section>

<footer class="bg-fts-green-950 text-slate-300 py-14 text-center">
  <div class="max-w-6xl mx-auto px-6">
    <div class="font-serif text-2xl font-bold text-fts-gold-400 tracking-widest mb-2">F<span class="text-white">T</span>S</div>
    <p class="text-xs text-slate-500 tracking-widest mb-6">{{ __('landing.brand.footer_tagline') }}</p>
    <div class="flex flex-wrap justify-center gap-x-6 gap-y-2 mb-6 text-sm">@foreach (__('landing.footer.links') as $link)<span class="text-slate-400">{{ $link }}</span>@endforeach</div>
    <p class="text-xs text-slate-500">{{ __('landing.footer.copyright') }}</p>
  </div>
</footer>

<a href="{{ $waUrl }}" target="_blank" rel="noopener" aria-label="{{ __('landing.shared.whatsapp_cta') }}" class="fixed bottom-6 right-6 md:bottom-8 md:right-8 z-[99] w-14 h-14 md:w-16 md:h-16 bg-[#25D366] hover:bg-[#1ebe5a] text-white rounded-full flex items-center justify-center shadow-2xl hover:-translate-y-1 transition-all animate-whatsapp-pulse group">
  <span class="absolute inset-[-4px] rounded-full border-2 border-[#25D366] opacity-0 animate-[ripple_2.4s_ease-out_infinite] pointer-events-none"></span>
  <span class="hidden lg:block absolute right-[76px] top-1/2 -translate-y-1/2 bg-fts-green-950 text-white px-4 py-2 rounded-full text-sm font-bold whitespace-nowrap shadow-lg pointer-events-none after:content-[''] after:absolute after:right-[-6px] after:top-1/2 after:-translate-y-1/2 after:border-[6px] after:border-transparent after:border-l-fts-green-950">{{ __('landing.shared.whatsapp_cta') }}</span>
  @include('partials.whatsapp-icon', ['size' => 28])
</a>

<div class="md:hidden fixed bottom-0 left-0 right-0 z-[98] bg-white/97 backdrop-blur-md border-t-2 border-fts-gold-400 shadow-2xl px-3 py-3">
  <a href="#analysis" class="block w-full bg-gradient-to-br from-fts-gold-400 via-fts-gold-500 to-fts-gold-400 text-fts-green-950 text-center py-4 rounded-full font-black text-base shadow-gold">{{ __('landing.shared.primary_cta') }} →</a>
</div>

<script>
(function () {
  var form = document.querySelector('#analysis form');
  if (!form) return;

  form.addEventListener('submit', function () {
    var btn = document.getElementById('analysisSubmitBtn');
    var overlay = document.getElementById('analysisLoadingOverlay');

    if (btn) {
      btn.disabled = true;
      btn.textContent = btn.dataset.loadingText || btn.textContent;
    }

    if (overlay) {
      overlay.classList.remove('hidden');
      overlay.classList.add('flex');
    }
  });
})();
</script>
</body>
</html>

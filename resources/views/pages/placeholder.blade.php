@extends('layouts.app', [
    'title' => $title ?? 'Page',
    'headerTitle' => $headerTitle ?? 'Page',
])

@section('content')
    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center">
        <h2 class="text-2xl font-semibold text-slate-900">{{ $pageTitle ?? 'Page en préparation' }}</h2>
        <p class="mt-3 text-sm text-slate-500">Cette section est prête pour accueillir vos prochains écrans.</p>
    </div>
@endsection

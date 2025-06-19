@extends('layouts.app')

@section('content')
@php
    $steps = [
        ['label' => 'Form Submitted by Client', 'icon' => 'heroicon-o-pencil-square'],
        ['label' => 'Lead Sent to SharpSpring CRM', 'icon' => 'heroicon-o-paper-airplane'],
        ['label' => 'Lead Detected by our Server', 'icon' => 'heroicon-o-server'],
        ['label' => 'AI Generates Tailored Response', 'icon' => 'heroicon-o-cpu-chip'],
        ['label' => 'Reply Sent Back via SharpSpring', 'icon' => 'heroicon-o-inbox-arrow-down'],
        ['label' => 'Client Replies via Email', 'icon' => 'heroicon-o-envelope-open'],
        ['label' => 'Booking Link Offered (if ready)', 'icon' => 'heroicon-o-calendar'],
    ];
@endphp

<div
    x-data="{
        step: 0,
        advance() {
            if (this.step < {{ count($steps) - 1 }}) {
                setTimeout(() => {
                    this.step++;
                    this.advance();
                }, 1500);
            }
        }
    }"
    x-init="advance"
    class="space-y-6 max-w-xl mb-10"
>
    <h2 class="text-2xl font-bold text-askews mb-4">How we handles the enquiry</h2>

    @foreach ($steps as $index => $step)
        <div 
            class="flex space-x-4 transition-all duration-500"
            x-bind:class="{ 'opacity-50': step < {{ $index }} }"
        >
            <x-dynamic-component :component="$step['icon']" class="w-6 h-6 text-askews" />
            <div class="text-base md:text-lg font-medium">{{ $step['label'] }}</div>
            <div class="ml-auto text-green-600 font-bold" x-show="{{ $index }} < step" x-transition>&check;</div>
        </div>
    @endforeach

    <div class="text-center mt-6" x-show="step < {{ count($steps) - 1 }}">
        <p class="text-gray-600 italic animate-pulse">Processing step <span x-text="step + 1"></span> of {{ count($steps) }}...</p>
    </div>
</div>

@foreach ($conversation as $entry)
    <div class="{{ $entry['role'] === 'assistant' ? 'bg-gray-100' : 'bg-askews text-white' }} p-4 my-2 rounded">
        <strong>{{ ucfirst($entry['role']) }}:</strong><br>
        @if($entry['role'] === 'assistant')
            @php
                preg_match('/^Subject:\s*(.*?)\s*(?:\r\n|\n|\r)/i', $entry['message'], $subjectMatch);
                $subject = $subjectMatch[1] ?? null;
                $body = $subject ? trim(str_replace($subjectMatch[0], '', $entry['message'])) : $entry['message'];
            @endphp

            @if($subject)
                <p class="font-bold text-gray-800 mb-2">Subject: {{ $subject }}</p>
            @endif
            <p>{{ $body }}</p>
        @else
            {{ $entry['message'] }}
        @endif
    </div>
@endforeach



<form action="/reply" method="POST" class="mt-4">
    @csrf
    <textarea name="reply" placeholder="Your reply..." class="border p-2 w-full rounded" required></textarea>
    <button type="submit" class="bg-askews text-white px-4 py-2 mt-2 rounded">Send Reply</button>
</form>
@endsection

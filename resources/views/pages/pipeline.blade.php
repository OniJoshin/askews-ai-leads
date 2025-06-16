@extends('layouts.app')

@section('content')
<div 
    x-data="{
        step: 0,
        steps: [
            { label: 'Form Submitted by Client', icon: '📝' },
            { label: 'Lead Sent to SharpSpring CRM', icon: '📤' },
            { label: 'Lead Detected by Askews Server', icon: '🖥️' },
            { label: 'AI Generates Tailored Response', icon: '🤖' },
            { label: 'Reply Sent Back via SharpSpring', icon: '📩' },
            { label: 'Client Replies via Email', icon: '📥' },
            { label: 'Booking Link Offered (if ready)', icon: '📅' }
        ],
        init() {
            this.advance();
        },
        advance() {
            if (this.step < this.steps.length - 1) {
                setTimeout(() => {
                    this.step++;
                    this.advance();
                }, 1500);
            }
        }
    }"
    x-init="init"
    class="space-y-6 max-w-xl mx-auto"
>
    <h2 class="text-2xl font-bold text-center mb-4">How Askews Legal Handles Your Enquiry</h2>

    <template x-for="(item, index) in steps" :key="index">
        <div class="flex items-center space-x-4 transition-all duration-500" :class="{ 'opacity-50': index > step }">
            <div class="text-3xl" x-text="item.icon"></div>
            <div class="text-base md:text-lg font-medium" x-text="item.label"></div>
            <div class="ml-auto text-green-600 font-bold" x-show="index < step" x-transition>&check;</div>
        </div>
    </template>

    <div class="text-center mt-6" x-show="step < steps.length - 1">
        <p class="text-gray-600 italic animate-pulse">Processing: <span x-text="steps[step].label"></span>...</p>
    </div>

    <div x-show="step === steps.length - 1" class="text-center mt-6" x-transition>
        <a href="https://askewslegal.co.uk/consultation-booking"
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded inline-block transition">
            Book a Consultation
        </a>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<form action="/submit" method="POST"
      x-data="{
          type: '',
          department: '',
          showQuote() {
              return this.department === 'Domestic Conveyancing';
          },
          showAffordability() {
              const individualDepts = [
                  'Private Family (Divorce and Financial)',
                  'Dispute Resolution',
                  'Wills Probate & Trusts',
                  'Employment - Individual',
                  'Miscellaneous'
              ];
              return this.type === 'business' || individualDepts.includes(this.department);
          }
      }"
      class="space-y-4 bg-white p-6 shadow rounded"
>
    @csrf

    <input type="text" name="name" placeholder="Your Name" required class="border p-2 w-full rounded">
    <input type="email" name="email" placeholder="Your Email" required class="border p-2 w-full rounded">

    <select name="type" x-model="type" required class="border p-2 w-full rounded">
        <option value="">Are you...</option>
        <option value="individual">Individual Client</option>
        <option value="business">Business Client</option>
    </select>

    <select name="department" x-model="department" required class="border p-2 w-full rounded">
        <option value="">Which legal department?</option>

        <template x-if="type === 'individual'">
            <optgroup label="Individual Departments">
                <option value="Domestic Conveyancing">Domestic Conveyancing</option>
                <option value="Private Family (Divorce and Financial)">Private Family (Divorce and Financial)</option>
                <option value="Private Family Children/Injunctions">Private Family Children/Injunctions</option>
                <option value="Local Authority and Children">Local Authority and Children</option>
                <option value="Dispute Resolution">Dispute Resolution</option>
                <option value="Wills Probate & Trusts">Wills Probate &amp; Trusts</option>
                <option value="Criminal">Criminal</option>
                <option value="Employment - Individual">Employment - Individual</option>
                <option value="Notary">Notary</option>
                <option value="Miscellaneous">Miscellaneous</option>
            </optgroup>
        </template>

        <template x-if="type === 'business'">
            <optgroup label="Business Departments">
                <option value="Company Law">Company Law</option>
                <option value="Commercial Property">Commercial Property</option>
                <option value="Dispute Resolution">Dispute Resolution</option>
                <option value="Criminal">Criminal</option>
                <option value="Employment - Business">Employment - Business</option>
                <option value="Miscellaneous">Miscellaneous</option>
            </optgroup>
        </template>
    </select>

    <!-- Quote field (Domestic Conveyancing only) -->
    <div x-show="showQuote()" class="transition-all">
        <select name="quote" class="border p-2 w-full rounded">
            <option value="">Do you require a quote?</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
    </div>

    <!-- Affordability field (specific departments or business) -->
    <div x-show="showAffordability()" class="transition-all">
        <select name="affordability" class="border p-2 w-full rounded">
            <option value="">Are you able to fund your enquiry privately?</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
    </div>

    <textarea name="message" placeholder="Brief description..." class="border p-2 w-full rounded" required></textarea>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Submit</button>
</form>
@endsection

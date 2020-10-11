@extends('welcome')
@section('title') Transferwise Integration Example @endsection
@section('content')

<h2 class="text-2xl md:text-4xl font-sans text-center font-bold">Transferwise Integration Example</h2>
<p class="text-sm font-sans text-center pt-2 pb-10 font-medium text-gray-400 mx-4"><b class="text-black"> Note: </b> This is just a demo, so we used limited & static data.</p>

@if (session()->has('message'))

            <div class="text-blue-300 mt-4">
                {{ session()->get('message') }}
            </div>
        @endif

        @if(count($errors) > 0)

            <div class="text-red-600 mt-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

<div class="leading-loose">
  <form action="{{ route('transferwise.store') }}" method="POST" id="payment-form" class="max-w-xl m-2 p-10 bg-white rounded shadow-xl">
    @csrf
      <p class="text-gray-800 font-medium">Recepient Details</p>
    <div class="">
      <label class="block text-sm text-gray-00" for="name">Recepient Name</label>
      <input class="w-full px-5 py-1 text-gray-700 bg-gray-200 rounded" id="name" name="name" type="text" required="" placeholder="Enter Recepient Name" aria-label="name">
    </div>
    <div class="mt-5">
      <label class="block text-sm text-gray-00" for="ac_no">Account Number</label>
      <input class="w-full px-5 py-1 text-gray-700 bg-gray-200 rounded" id="ac_no" name="ac_no" type="number" min="0" required="" placeholder="Enter A/C No." aria-label="ac_no">
    </div>

    <div class="mt-5">
      <label class="block text-sm text-gray-00" for="ac_type">Account Type</label>
      <input class="w-full px-5 py-1 text-gray-700 bg-gray-200 rounded" id="ac_type" name="ac_type" type="text" required="" placeholder="Checking/Savings" aria-label="ac_type">
    </div>

    <div class="mt-5">
      <label class="block text-sm text-gray-00" for="amount">Payout Amount</label>
      <input class="w-full px-5 py-1 text-gray-700 bg-gray-200 rounded" id="amount" name="amount" min="0" type="number" required="" placeholder="Enter Amount to be paid" aria-label="amount">
    </div>

    <div class="pt-8 mt-4">
      <button class="px-4 py-1 text-white font-light tracking-wider bg-gray-900 rounded" type="submit">Submit</button>
    </div>
  </form>
</div>

@include('extra')

@endsection

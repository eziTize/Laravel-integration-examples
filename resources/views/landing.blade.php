@extends('welcome')
@section('title') Laravel Integration Examples @endsection
@section('content')

<h2 class="text-2xl md:text-4xl font-sans text-center pb-10 font-bold">Laravel Integration Examples</h2>

	<div class="flex flex-col md:flex-row justify-center pt-8 sm:justify-start sm:pt-0 space-y-5 md:space-y-0 md:space-x-5 w-full">

		<a type="button" href="{{route('twilio.home')}}" class="flex items-center justify-between w-full md:w-1/3 border-orange-800 border-2 rounded-lg z-40 shadow-lg p-4 focus:outline-none">
              <img  src="https://www.vectorlogo.zone/logos/twilio/twilio-ar21.svg" class="h-16 md:h-24 w-auto text-gray-700">

              <i class="text-2xl md:text-xl fas fa-arrow-right pl-2 text-gray-800"> </i>
        </a>

        <a type="button" href="{{route('stripe.home')}}" class="flex items-center justify-between w-full md:w-1/3 border-orange-800 border-2 rounded-lg z-40 shadow-lg p-4 focus:outline-none">
              <img src="https://www.vectorlogo.zone/logos/stripe/stripe-ar21.svg" class="h-16 md:h-24 w-auto text-gray-700">

              <i class="text-2xl md:text-xl fas fa-arrow-right pl-2 text-gray-800"> </i>
        </a>

        <a type="button" href="{{route('transferwise.create')}}" class="flex items-center justify-between w-full md:w-1/3 border-orange-800 border-2 rounded-lg z-40 shadow-lg p-4 focus:outline-none">
              <img  src="https://www.vectorlogo.zone/logos/transferwise/transferwise-ar21.svg" class="h-16 md:h-24 w-auto text-gray-700">

              <i class="text-2xl md:text-xl fas fa-arrow-right pl-2 text-gray-800"> </i>
        </a>
    </div>
@endsection
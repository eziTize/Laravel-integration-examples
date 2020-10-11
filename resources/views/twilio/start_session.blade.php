@extends('welcome')
@section('title') Twilio Video Integration Example @endsection
@section('content')
<div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-center pt-8 sm:justify-start sm:pt-0 text-2xl md:text-4xl font-sans font-medium">
                  Twilio Video Integration Example
                </div>

                    <div class="mx-auto">
                        <div class="p-6">
                            <button
                                class="w-full p-5 bg-indigo-800 text-xl overflow-hidden shadow sm:rounded-lg text-center focus:outline-none font-sans"
                                href="{{ route('tw.session.create') }}" 
                                onclick="event.preventDefault();
                                Swal.fire({
                                    title: 'Start a Video Session?',
                                    showCancelButton: true,
                                    //confirmButtonColor: 'blue',
                                    confirmButtonText: `Yes`,
                                    cancelButtonText: `Cancel`,
                                  }).then((result) => {
                                    if (result.isConfirmed) {
                                      document.getElementById('start_session_form').submit()
                                      }
                                  });">Create Room</button>
                                <form id="start_session_form" action="{{ route('tw.session.create') }}" method="POST" style="display: none;">
                                  @csrf
                                </form>
                        </div>
                    </div>
                @include('extra')
            </div>
@endsection
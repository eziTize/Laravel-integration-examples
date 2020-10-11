@extends('welcome')
@section('title') Twilio Integration Example @endsection
@section('content')
            <div class="container mx-auto p-4 flex items-center space-x-32">
             <div class="my-32">
                 @if(session()->has('room_session_created'))
                <!--Modal-->
                      <div class="overflow-hidden px-3 overflow-y-auto fixed inset-0 outline-none focus:outline-none justify-center items-center flex bg-black bg-opacity-50">
                                    <div class="relative my-6 mx-auto container w-full md:w-2/3 lg:w-1/2">
                                      <!--content-->
                                     <div class="border-0 rounded-lg shadow-lg relative flex flex-col w-full outline-none bg-gray-300 focus:outline-none">
                                      <!--header-->
                                      <div class="p-5 border-b border-solid border-gray-300 rounded-lg">
                                      <h3 class="text-2xl font-semibold text-black text-center font-sans my-2">
                                        Session Created
                                      </h3>
                                      <p class="text-indigo-500 my-2 text-lg text-center font-medium break-all"> <b class="text-teal-600"> Session Link: </b> <a href="{{ session()->get('room_session_created.roomLink') }}">{{ session()->get('room_session_created.roomLink') }}</a> </p>
                                      <p class="text-indigo-500 mt-1 text-lg text-center font-medium break-all"> <b class="text-teal-600"> Session Pass: </b> {{ session()->get('room_session_created.roomPass') }} </p>   
                                  </div>
                              </div>
                           </div>
                      </div>

                      <div class="fixed top-0 container mx-auto flex justify-center my-8 mt-auto pt-8 pb-">
                        <a href="{{route('twilio.home')}}"> <button class="border border-indigo-500 text-indigo-500 block rounded-sm font-bold py-2 px-6 mr-2 flex items-center hover:bg-indigo-500 hover:text-white">
                           <i class="fas fa-arrow-left pr-2"></i>Back
                        </button>
                       </a>
                    </div>
                 @endif        
                </div>
            </div>
@endsection

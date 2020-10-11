<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Twilio Video Integration Example</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Packages -->
        <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">

        <!-- Icons -->
        <script src="https://kit.fontawesome.com/5810dc9d6a.js" crossorigin="anonymous"></script>

        <!-- JQuery -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

        <!-- Sweet Alert -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

        <!-- Scripts -->

<script src="//media.twiliocdn.com/sdk/js/video/v1/twilio-video.min.js"></script>


<script src="{{ asset('js/app.js') }}" defer></script>

<script>

  $(function() {
    jQuery.fn.extend({
        hidden: function(state) {
            return this.each(function() {
                this.hidden = state;
            });
        }
    });
    
    $('button[id="tglMute"]').hidden(false);
    $('button[id="tglUnmute"]').hidden(true);
    $('button[id="tglVidMute"]').hidden(false);
    $('button[id="tglVidUnmute"]').hidden(true);
    $('div[id="loading-wrapper"]').hidden(false);

  });

    Twilio.Video.createLocalTracks({
       audio: true,
       video: true,
       //tracks: []
    }).then(function(localTracks) {
       return Twilio.Video.connect('{{ $accessToken }}', {
           name: '{{ $roomName }}',
           tracks: localTracks,
           //video: true,
           //tracks: []
       });
    }).then(function(room) {
       console.log('Successfully joined a Room: ', room.name);

       room.participants.forEach(participantConnected);

       var previewContainer = document.getElementById(room.localParticipant.sid);
       if (!previewContainer || !previewContainer.querySelector('video')) {
           LocalparticipantConnected(room.localParticipant);
       }

       room.on('participantConnected', function(participant) {
           console.log('Joining: ' , participant.identity);
           participantConnected(participant);
       });

       room.on('participantDisconnected', function(participant) {
           console.log('Disconnected: ',  participant.identity );
           participantDisconnected(participant);
       });

       room.on('disconnected', function(room) {
                // Detach the local media elements
                    room.localParticipant.tracks.forEach(track => {
                    const attachedElements = track.detach();
                    track.stop();
                    attachedElements.forEach(element => element.remove());

                    console.log('disconnected');
                });
         });

       

    $('#tglMute').on('click', () => {
      $('button[id="tglMute"]').hidden(true);
      $('button[id="tglUnmute"]').hidden(false);
      room.localParticipant.audioTracks.forEach(function (audioTrack) {
       audioTrack.disable();
       });
        Swal.fire({
          position: 'top-right',
          icon: 'info',
          title: 'Audio Muted',
          showConfirmButton: false,
          timer: 1000
          });
      });

    $('#tglUnmute').on('click', () => {
      $('button[id="tglMute"]').hidden(false);
      $('button[id="tglUnmute"]').hidden(true);
       room.localParticipant.audioTracks.forEach(function (audioTrack) {
       audioTrack.enable();
       });
        Swal.fire({
          position: 'top-right',
          icon: 'success',
          title: 'Audio Unmuted',
          showConfirmButton: false,
          timer: 1000
          });
      });

    $('#tglVidMute').on('click', () => {
      $('button[id="tglVidMute"]').hidden(true);
      $('button[id="tglVidUnmute"]').hidden(false);
      room.localParticipant.videoTracks.forEach(function (videoTrack) {
       videoTrack.disable();
       });
        Swal.fire({
          position: 'top-right',
          icon: 'info',
          title: 'Video Disabled',
          showConfirmButton: false,
          timer: 1000
          });
      });

    $('#tglVidUnmute').on('click', () => {
      $('button[id="tglVidMute"]').hidden(false);
      $('button[id="tglVidUnmute"]').hidden(true);
       room.localParticipant.videoTracks.forEach(function (videoTrack) {
       videoTrack.enable();
       });
        Swal.fire({
          position: 'top-right',
          icon: 'success',
          title: 'Video Enabled',
          showConfirmButton: false,
          timer: 1000
          });
      });

    $('#disconnectRoom').on('click', () => {
                Swal.fire({
                title: 'Leave Session?',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: `Yes`,
                cancelButtonText: `Cancel`,
              }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                  Swal.fire({
                    position: 'center',
                    title: 'Leaving Session!',
                    html: 'Leaving Session in <b></b> milliseconds.',
                    timer: 1800,
                    timerProgressBar: true,
                    onBeforeOpen: () => {
                        Swal.showLoading()
                        timerInterval = setInterval(() => {
                          const content = Swal.getContent()
                          if (content) {
                            const b = content.querySelector('b')
                            if (b) {
                              b.textContent = Swal.getTimerLeft()
                            }
                          }
                        }, 100)
                      },
                      onClose: () => {
                        clearInterval(timerInterval)
                      }
                  });
                  room.disconnect();
                  window.location.href = "{{ url('/').'/twilio' }}";
             }
                
              })   
            });
    });
    // additional functions will be added after this point

    function LocalparticipantConnected(participant) {
   console.log('Participant "%s" connected', participant.identity);

       const div = document.createElement('div');
       //div.id = participant.sid;
       //div.setAttribute("style", "float: left; margin: 10px;");
       //div.innerHTML = "<div class='absolute top-0 right-0' style='clear:both;'>" +participant.identity+ "</div>";

       participant.tracks.forEach(function(track) {
           LocaltrackAdded(div, track)
       });

       participant.on('trackAdded', function(track) {
           LocaltrackAdded(div, track)
       });
       participant.on('trackRemoved', trackRemoved);

       document.getElementById('media-div').appendChild(div);
    }

    function participantConnected(participant) {
   console.log('Participant "%s" connected', participant.identity);

       const div = document.createElement('div');
      //div.id = participant.sid;
     //div.setAttribute("style", "float: left; margin: 10px;");
     //div.innerHTML = "<div style='clear:both;'>" +participant.identity+ "</div>";

       participant.tracks.forEach(function(track) {
           trackAdded(div, track)
       });

       participant.on('trackAdded', function(track) {
           trackAdded(div, track)
       });
       participant.on('trackRemoved', trackRemoved);

       document.getElementById('media-div').appendChild(div);
    }

    function participantDisconnected(participant) {
       console.log('Participant "%s" disconnected', participant.identity);

       participant.tracks.forEach(trackRemoved);
       document.getElementById(participant.sid).remove("video")[0];
    }

  function trackAdded(div, track) {
       div.appendChild(track.attach());
       var video = div.getElementsByTagName("video")[0];
       if (video) {
           video.setAttribute("style", "");
           video.setAttribute("class", "object-center absolute z-20 inset-0 h-full w-full");
           video.style.transform = 'scale(-1, 1)';
           $('div[id="loading-wrapper"]').hidden(true);

       }
    }


  function LocaltrackAdded(div, track) {
       div.appendChild(track.attach());
       var video = div.getElementsByTagName("video")[0];
       if (video) {
           video.setAttribute("style", "max-width:160px; max-height: 160px;");
           video.setAttribute("class", "object-center z-40 h-18 w-20 md:h-auto md:w-auto absolute top-0 right-0");
           video.setAttribute("id", "localVid");
           video.style.transform = 'scale(-1, 1)';
           $('div[id="loading-wrapper"]').hidden(true);
       }
    }

    function trackRemoved(track) {
       track.detach().forEach( function(element) { element.remove() });
    };

</script>

</head>
<body>
        <!-- Start -->
<div class="flex flex-row h-full">
  <!-- Sidebar -->
      <nav class="bg-gray-900 w-16 md:w-20  justify-between flex flex-col ">
        <div class="mt-10">
          <a href="">
            <img
              src="https://images.squarespace-cdn.com/content/v1/562146dae4b018ac1df34d5f/1450121865864-MEC4C6RJL76VS7H51PUB/ke17ZwdGBToddI8pDm48kAf-OpKpNsh_OjjU8JOdDKBZw-zPPgdn4jUwVcJE1ZvWQUxwkmyExglNqGp0IvTJZUJFbgE-7XRK3dMEBRBhUpzAFzFJoCInLPKyj9AG8yKe7-Q2aFvP177fkO9TY_-rz5WoqqTEZpmj4yDEOdwKV68/person-placeholder.jpg?format=1000w"
              class="rounded-full w-10 h-10 mb-3 mx-auto"
            />
          </a>
          <div class="mt-8 md:mt-10">
            <ul>
              <li class="mb-6">
                <button class="focus:outline-none" id="tglMute">
                  <span>
                  <i class="px-6 md:px-8 mb-1 md:mb-2 lg:mb-4 text-xl md:text-2xl fas fa-microphone-alt text-green-500 hover:text-green-700"></i>
                  </span>
                </button>

                <button class="focus:outline-none" id="tglUnmute">
                  <span>
                  <i class="px-6 md:px-8 mb-1 md:mb-2 lg:mb-4 text-xl md:text-2xl fas fa-microphone text-gray-500 hover:text-gray-700"></i>
                  </span>
                </button>
              </li>
            <!--  <li class="mb-6">
                <a href="#">
                  <span>
                  <i class="px-6 mb-1 md:mb-2 lg:mb-4 text-xl md:text-2xl fas fa-phone-slash text-red-500 hover:text-red-700"></i>
                  </span>
                </a>
              </li> -->
              <li class="mb-6">
                 <button id="tglVidMute" class="focus:outline-none px-1">
                  <span>
                  <i class="px-4 md:px-6 mb-1 md:mb-2 lg:mb-4 text-xl md:text-2xl fas fa-video text-indigo-500 hover:text-indigo-700"></i>
                  </span>
                </button>
                 <button id="tglVidUnmute" class="focus:outline-none px-1">
                  <span>
                  <i class="px-4 md:px-6 mb-1 md:mb-2 lg:mb-4 text-xl md:text-2xl fas fa-video text-gray-500 hover:text-gray-700"></i>
                  </span>
                </button>
              </li>
              <li class="mb-6">
                <button id="disconnectRoom" class="focus:outline-none">
                  <span class="px-1">
                  <i class="px-4 md:px-5 mb-1 md:mb-2 lg:mb-4 text-xl md:text-2xl fas fa-power-off fill-current text-red-600 hover:text-red-800"></i>
                  </span>
                </button>
              </li>
            </ul>
          </div>
        </div>
        <div class="mb-4">
          
        </div>
      </nav>
  <div class="flex items-center justify-center px-3 md:px-5 py-5 text-gray-700 bg-gray-800 h-screen w-screen overflow-y-hidden">
    
    <!-- Content -->
    <div id="media-div" class="md:mx-auto relative bg-black flex lg:flex-row flex-col items-center justify-center space-x-0 lg:space-x-20 md:px-18 w-full h-full">
        <div id="loading-wrapper">
              <div class="text-3xl text-white font-bold" id="loading-text">LOADING...</div>
              <div id="loading-content"></div>
        </div>
    </div>
              

      <App> </App>



    </div>
   </div>

    </body>
</html>



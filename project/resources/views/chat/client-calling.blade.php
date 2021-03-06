@extends('layouts.anovey')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
@endpush
@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.webrtc.ecl.ntt.com/skyway-4.4.4.js"></script>
@endpush

@section('content')
    @include('components.modals.call-screen')
    <div id="modal-content" class="md:w-2/4 w-4/5 rounded-2xl">
        <button id="modal-close"
            class="ml-auto block text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
            <span class="sr-only">Close menu</span>
            <!-- Heroicon name: outline/x -->
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <div class="modal-inner" id="call-review-modal">
            @include('components.modals.call-review')
        </div>
    </div>
    @push('scripts_bottom')
        <script src="{{ asset('js/modal.js') }}"></script>
        <script>
            (async function main() {
                //センタリングを実行する関数
                function centeringModalSyncer() {

                    //画面(ウィンドウ)の幅、高さを取得
                    var w = $(window).width();
                    var h = $(window).height();

                    // コンテンツ(#modal-content)の幅、高さを取得
                    // jQueryのバージョンによっては、引数[{margin:true}]を指定した時、不具合を起こします。
                    var cw = $("#modal-content").outerWidth();
                    var ch = $("#modal-content").outerHeight();

                    //センタリングを実行する
                    $("#modal-content").css({
                        "left": ((w - cw) / 2) + "px",
                        "top": ((h - ch) / 2) + "px"
                    });
                }

                const Peer = window.Peer;
                const localVideo = document.getElementById('js-local-stream')
                const localId = '{{ $loginUserPeerId }}'
                const callTrigger = document.getElementById('js-call-trigger')
                const closeTrigger = document.getElementById('js-close-trigger')
                const remoteVideo = document.getElementById('js-remote-stream')
                const remoteId = '{{ $partnerUserPeerId }}'
                const sdkSrc = document.querySelector('script[src*=skyway]')
                const callingTime = document.getElementById('calling-time')
                const localStream = await navigator.mediaDevices
                    .getUserMedia({
                        audio: true,
                        video: false,
                    })
                    .catch(console.error)
                // Render local stream
                localVideo.muted = true
                localVideo.srcObject = localStream
                localVideo.playsInline = true
                await localVideo.play().catch(console.error)
                const peer = new Peer('{{ $loginUserPeerId }}', {
                    key: '{{ $skyway_key }}',
                    debug: 3,
                })
                peer.once('open', id => localId)
                // Register callee handler
                peer.on('call', mediaConnection => {
                    closeTrigger.type = 'button'
                    let timer
                    const startTime = new Date()
                    // タイマー開始
                    startTimer()

                    function startTimer() {
                        timer = setInterval(showSecond, 1000)
                    }

                    // 秒数表示
                    function showSecond() {

                        let nowTime = new Date()

                        var elapsedTime = (nowTime - startTime) / 1000
                        let min = Math.floor(elapsedTime / 60)
                        let rem = Math.floor(elapsedTime) % 60
                        var str = `${min}:${rem}`

                        callingTime.innerHTML = str
                        if (Math.floor(elapsedTime) % 10 === 0) {
                            const callingTime = function() {
                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                            'content'),
                                    },
                                    url: `/chat/call/{{ $call->id }}/calling-time`,
                                    type: 'POST',
                                    data: {
                                        calling_time: elapsedTime,
                                    },
                                })
                                return false;
                            }
                            callingTime()
                        }
                        if (elapsedTime >= 540) {
                            callingTime.style.color = '#ff0000'
                        }
                        if (elapsedTime >= 600) {
                            mediaConnection.close(true)
                        }
                    }
                    mediaConnection.answer(localStream)
                    mediaConnection.on('stream', async stream => {
                        // Render remote stream for callee
                        remoteVideo.srcObject = stream
                        remoteVideo.playsInline = true
                        await remoteVideo.play().catch(console.error)
                    })
                    mediaConnection.once('close', () => {
                        remoteVideo.srcObject.getTracks().forEach(track => track.stop())
                        remoteVideo.srcObject = null
                        clearInterval(timer)

                        const finishCall = function() {
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                        'content'),
                                },
                                url: `/chat/call/{{ $call->id }}/finish`,
                                type: 'POST',
                                data: {
                                    call_id: {{ $call->id }},
                                },
                            })
                            return false;
                        }
                        finishCall()

                        $(".modal-inner").hide()
                        $("#call-review-modal").show()
                        $("body").append('<div id="modal-overlay"></div>')
                        $("#modal-overlay").fadeIn("slow")

                        $("#modal-content").fadeIn("slow")
                        centeringModalSyncer()
                    })
                    closeTrigger.addEventListener('click', () => mediaConnection.close(true))
                })
                peer.on('error', console.error)
            })()
        </script>
    @endpush
@endsection

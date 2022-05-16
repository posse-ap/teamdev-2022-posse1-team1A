@extends('layouts.anovey')

@section('content')
    @include('components.user-header')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/chatMain.css') }}">
        <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    @endpush
    @push('scripts')
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    @endpush
    <div class="wrapper container mx-auto pl-4 mb-0 pb-0 bg-white font-normal">
        <div class='flex flex-wrap'>
            <div class="w-full lg:w-1/4 border-r border-zinc-300 side_nav">
                <ul class="mt-5 pt-5">
                    <a href="{{ $isClientChat ? route('chat.client_chat_list') : route('chat.respondent_chat_list') }}"
                        class="mt-5 pt-5">チャット一覧に戻る ></a>
                    <li class="mt-3">トークを退出する ></li>
                </ul>
            </div>
            <div class="w-full lg:w-3/4 box-content relative">
                <div class="p-5  bg-amber-100 text-xl flex justify-between">
                    <div class="font-bold">
                        {{ $partnerUserName }}
                    </div>
                    <div>
                        @if ($isReserved)
                            <button
                                class="bg-indigo-400 hover:bg-blue-700 text-white font-bold py-1 px-5 rounded ml-2 modal-open"
                                id="call-start">
                                <span class="px-3">
                                    通話
                                </span>
                            </button>
                        @else
                            <button disabled class="bg-gray-400 text-white font-bold py-1 px-5 rounded ml-2">
                                <span class="px-3">
                                    {{-- TODO:通話で10min経過してからモーダル表示に切り替える --}}
                                    通話
                                </span>
                            </button>
                        @endif
                        <button
                            class="bg-yellow-400 hover:bg-gray-800 text-white font-bold py-1 px-4 rounded ml-2 modal-open"
                            id="schedule-registration">
                            日程登録
                        </button>
                    </div>
                </div>
                <div class="cards mx-3 overflow-scroll">
                    <div id="scroll-inner">
                        @if ($isClientChat)
                            <div
                                class="fixed absolute right-0 card m-5 mr-8 p-3 bg-amber-100 w-72 rounded-md drop-shadow-md ml-auto">
                                日程が決まりましたら、<br>
                                日程登録ボタンを押してください。<br>
                                リマインドメールが送信されます。
                            </div>
                        @endif
                        <div class="spacer h-28"></div>
                        @foreach ($chatRecords as $key => $chatRecord)
                            @if ($chatRecord->user_id == $loginUserId)
                                <div class="card flex items-center justify-end">
                                    <div class="icon w-14 h-14">
                                    </div>
                                    <div class="time mt-auto mb-5">
                                        <span class="text-slate-400 block">{{ $chatRecord->date }}</span>
                                    </div>
                                    <div class="m-5 p-3 max-w-max bg-slate-100 rounded-full">
                                        {{ $chatRecord->comment }}
                                    </div>
                                </div>
                                {{-- チャットボット発言用の分岐 --}}
                            @elseif ($chatRecord->user_id == 3)
                                <div class="card flex items-center">
                                    <div class="icon w-14 h-14">
                                        <img src="{{ asset('img/anovey.png') }}" alt="">
                                    </div>
                                    <div class="m-5 p-3 max-w-max rounded-md border-2 rounded-full mr-1 bg-sky-100">
                                        {{ $chatRecord->comment }}
                                    </div>
                                    <div class="time mt-auto mb-5">
                                        <span class="text-slate-400">{{ $chatRecord->date }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="card flex items-center h-max">
                                    <div class="icon w-14 h-14">
                                        <img src="{{ asset($partnerUserIcon) }}" alt="依頼者ユーザーのアイコン">
                                    </div>
                                    <div class="m-5 p-3 max-w-max rounded-md border-2 rounded-full mr-1">
                                        {{ $chatRecord->comment }}
                                    </div>
                                    <div class="time mt-auto mb-5">
                                        @if ($chatRecord->is_read)
                                            <span class="text-slate-400 block text-right">既読</span>
                                        @endif
                                        <span class="text-slate-400">{{ $chatRecord->date }}</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <form action="" method="POST" class="flex items-center">
                    @csrf
                    <input type="hidden" value="{{ $chatRoomId }}" name="chatRoomId">
                    <input type="text" class="block m-5 bg-slate-100 rounded-full w-full" name="comment">
                    <div class="icon w-28">
                        <button class="bg-gray-400 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded">送信</button>
                    </div>
                </form>
                <div id="modal-content" class="md:w-2/4 w-4/5 rounded-2xl">
                    {{-- TODO:閉じるボタンをちゃんとデザインする --}}
                    <button id="modal-close">閉じる</button>
                    <div class="modal-inner" id="schedule-registration-modal">
                        @include('components.modals.schedule_registration')
                    </div>
                    <div class="modal-inner" id="call-start-modal">
                        @include('components.modals.call-start')
                    </div>
                    <div class="modal-inner" id="ten-minute-announce-modal">
                        @include('components.modals.ten-minute-announce')
                    </div>
                    {{-- TODO:電話終了ボタンを押したら表示される↓ --}}
                    {{-- TODO: モーダルの外をクリックしても離脱させない仕組み必要--}}
                    <div class="modal-inner" id="call-review-modal">
                        @include('components.modals.call-review')
                    </div>
                </div>
            </div>
        </div>

        <!-- Using utilities: -->
        <button class="block bg-slate-600 hover:bg-slate-500 text-white font-bold py-2 px-4 rounded mx-auto mt-5">
            相談を受けつけない
        </button>
        {{-- <div class="mx-auto">
        </div> --}}
    </div>
    @push('scripts_bottom')
        <script>
            let target = document.getElementById('scroll-inner');
            target.scrollIntoView(false);
        </script>
        <script src="{{ asset('js/modal.js') }}"></script>
    @endpush
@endsection

@extends('layouts.anovey')

@section('content')
    @include('components.user-header')

    <main>
        <section class="pt-5 mb-20">
            <div class="wrapper container mx-auto px-4 mb-5 pb-5 min-h-screen">
                <div class="flex justify-between items-center">
                    <div class="flex items-center py-4 overflow-y-auto whitespace-nowrap font-thin text-sm">
                        <a href="{{ route('user_index') }}" class="text-gray-600">
                            トップ
                        </a>
                        <span class="mx-2 md:mx-5 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                        <p class="text-gray-600">
                            依頼者チャット一覧
                        </p>
                    </div>
                    <div class="bg-gray-700 py-2 px-3 w-min rounded-full h-min font-normal hidden sm:block">
                        <div class="flex items-center">
                            <div class="w-5 h-5 mr-1">
                                <img src="{{ asset('img/ticket-white.png') }}" alt="チケットアイコン" class="w-full h-full">
                            </div>
                            <p class="text-white whitespace-nowrap text-sm sm:text-sm">チケット所持数：{{ $ticket_counts }}枚</p>
                        </div>
                    </div>
                    <div class="bg-gray-700 py-2 px-3 w-min rounded-full h-min font-normal sm:hidden">
                        <div class="flex items-center">
                            <div class="w-5 h-5 mr-1">
                                <img src="{{ asset('img/ticket-white.png') }}" alt="チケットアイコン" class="w-full h-full">
                            </div>
                            <p class="text-white whitespace-nowrap text-sm sm:text-sm">{{ $ticket_counts }}</p>
                        </div>
                    </div>
                </div>
                <div class="text-center mb-5">
                    <h1 class="text-lg lg:text-2xl mb-3">
                        <span class="text-blue text-base lg:text-lg inline-block md:mb-1">Client chat</span><br>
                        依頼者チャット一覧
                    </h1>
                    <p class="text-sm font-thin">あなたが回答者に相談を依頼しているページです。</p>
                </div>
                <div class="cards mb-5 pb-5">
                    @if (count($client_chats) === 0)
                        <p class="text-base">進行中のチャットはありません。</p>
                    @else
                        @foreach ($client_chats as $client_chat)
                            <a class="block cursor-pointer mb-3 p-2 duration-300 bg-blue-50"
                                href="{{ route('chat.index', ['chat_id' => $client_chat->id]) }}">
                                <div class="flex justify-between items-center">
                                    <div
                                        class="lg:flex items-center justify-between sm:py-4 sm:pl-2 sm:pr-4 w-full flex-grow-0">
                                        <div class="flex items-center">
                                            <div
                                                class="w-11 h-11 md:w-16 md:h-16 flex-shrink-0 rounded-full overflow-hidden object-cover mr-3">
                                                <img class="w-full h-full object-cover"
                                                    src="{{ asset($client_chat->respondent_user->icon) }}" />
                                            </div>
                                            <div class="flex align-center">
                                                <div>
                                                    <p class="lg:mb-3">
                                                        <span
                                                            class="text-base lg:text-xl mr-1">{{ $client_chat->respondent_user->nickname }}
                                                        </span>
                                                        <span class="font-thin text-sm">
                                                            {{ $client_chat->respondent_user->company }}</span>
                                                        <span class="font-thin text-sm">
                                                            {{ $client_chat->respondent_user->department }}</span>
                                                    </p>
                                                    <p class="text-xs font-normal">
                                                        @isset($client_chat->last_message->comment)
                                                            {{ Str::limit($client_chat->last_message->comment, 30) }}
                                                        @endisset
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        @isset($client_chat->interview_schedule)
                                            <div class="ml-auto mt-3 lg:mt-0">
                                                <p class="text-xs">相談日程：
                                                    {{ $client_chat->interview_schedule ? "{$client_chat->interview_schedule->schedule->format('Y/m/d H:i')}" : '' }}
                                                </p>
                                            </div>
                                        @endisset
                                    </div>
                                    @if ($client_chat->number_of_unread_items() !== 0)
                                        <div class="w-6 h-6 rounded-full bg-blue-600 flex-shrink-0">
                                            <p class="w-full h-auto text-white font-normal text-center">
                                                {{ $client_chat->number_of_unread_items() }}</p>
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
        </section>
    </main>
    @include('components.user-footer')
@endsection

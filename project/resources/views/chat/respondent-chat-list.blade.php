@extends('layouts.anovey')

@section('content')
    @include('components.user-header')

    <main>
        <section class="pt-5 mb-20">
            <div class="wrapper container mx-auto px-4 mb-5 pb-5 min-h-screen">
                <div>
                    <div class="flex items-center py-4 mx-auto overflow-y-auto whitespace-nowrap font-thin text-sm">
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
                            回答者チャット一覧
                        </p>
                    </div>
                </div>
                <div class="text-center mb-10">
                    <h1 class="text-lg lg:text-2xl mb-3">
                        <span class="text-yellow-500 text-base lg:text-lg inline-block md:mb-1">Respondent chat</span><br>
                        回答者チャット一覧
                    </h1>
                    <p class="text-sm font-thin">依頼者からの相談を受けるページです。</p>
                </div>
                <div class="cards mb-5 pb-5">
                    @if (count($respondent_chats) === 0)
                        <p class="text-base">進行中のチャットはありません。</p>
                    @else
                        @foreach ($respondent_chats as $respondent_chat)
                            <a class="block cursor-pointer mb-3 p-2 duration-300 @if ($user->is_search_target == true) bg-blue-50 @else bg-gray-50 text-gray-400 pointer-events-none @endif"
                                href="{{ route('chat.index', ['chat_id' => $respondent_chat->id]) }}">
                                <div class="flex justify-between items-center">
                                    <div
                                        class="lg:flex items-center justify-between sm:py-4 sm:pl-2 sm:pr-4 w-full flex-grow-0">
                                        <div class="flex items-center">
                                            <div
                                                class="w-11 h-11 md:w-16 md:h-16 flex-shrink-0 rounded-full overflow-hidden object-cover mr-3">
                                                @if ($user->is_search_target == true)
                                                    <img class="w-full h-full object-cover"
                                                        src="{{ asset($respondent_chat->client_user->icon) }}" />
                                                @else
                                                    <img class="w-full h-full object-cover opacity-50"
                                                        src="{{ asset($respondent_chat->client_user->icon) }}" />
                                                @endif
                                            </div>
                                            <div class="flex align-center">
                                                <div>
                                                    <p class="lg:mb-3 text-base lg:text-xl">
                                                        {{ $respondent_chat->client_user->nickname }}</p>
                                                    <p class="text-xs font-normal">
                                                        {{ $respondent_chat->last_message->comment }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        @isset($respondent_chat->interview_schedule)
                                            <div class="ml-auto mt-3">
                                                <p class="text-xs">相談日程
                                                    {{ $respondent_chat->interview_schedule ? "#{$respondent_chat->interview_schedule->schedule}~" : '' }}
                                                </p>
                                            </div>
                                        @endisset
                                    </div>
                                    <div
                                        class="w-6 h-6 rounded-full flex-shrink-0 @if ($user->is_search_target == true) bg-blue-600 @else bg-gray-400 text-gray-400 pointer-events-none @endif">
                                        <p class="w-full h-auto text-white font-normal text-center">
                                            {{ $respondent_chat->number_of_unread_items() }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="fixed bottom-0 md:bottom-10 w-full">
                @if ($user->is_search_target)
                    <form action="{{ route('chat.reception_stop') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full md:w-min block bg-slate-500 hover:bg-slate-600 text-white whitespace-nowrap font-bold py-4 md:py-2 px-28 rounded mx-auto">
                            相談を受けつけない
                        </button>
                    </form>
                @else
                    <p class="text-blue-400 text-center mb-1">＼現在、相談受付停止中です／</p>
                    <form action="{{ route('chat.reception_start') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full md:w-min block bg-slate-500 hover:bg-slate-600 text-white whitespace-nowrap font-bold py-4 md:py-2 px-28 md:rounded mx-auto">
                            相談を受けつける
                        </button>
                    </form>
                @endif
            </div>
        </section>
    </main>
@endsection

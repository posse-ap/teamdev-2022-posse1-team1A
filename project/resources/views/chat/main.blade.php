@extends('layouts.anovey')

@section('content')
    @include('components.user-header')
    <style>
        body {
            background-color: rgb(226 232 240);
        }
        .cards {
          height: calc( 100vh - 230px );;
        }
        @media screen and (max-width: 1024px) {
          .side_nav {
            display: none;
          }
          .container {
            padding-left: 0;
          }
        }
    </style>
    <div class="wrapper container mx-auto pl-4 mb-0 pb-0 bg-white font-normal">
        <div class='flex flex-wrap'>
            <div class="w-full lg:w-1/4 border-r border-zinc-300 side_nav">
                <ul class="mt-5 pt-5">
                    <li class="mt-5 pt-5">チャット一覧に戻る ></li>
                    <li class="mt-3">トークを退出する ></li>
                    <li class="mt-3">通報 ></li>
                </ul>
            </div>
            <div class="w-full lg:w-3/4 box-content relative">
                <div class="p-5  bg-amber-100 text-xl flex justify-between">
                    <div class="font-bold">
                        {{$respondent_user->name}}
                    </div>
                    <div class="">
                      @if ($isReserved)
                        <button class="bg-indigo-400 hover:bg-blue-700 text-white font-bold py-1 px-5 rounded ml-2">
                          <a href="" class="px-3">
                            通話
                          </a>
                        </button>
                        @else
                          <button disabled class="bg-gray-400 text-white font-bold py-1 px-5 rounded ml-2">
                            <span href="" class="px-3">
                              通話
                            </span>
                          </button>
                      @endif
                      <button class="bg-yellow-400 hover:bg-gray-800 text-white font-bold py-1 px-4 rounded ml-2">
                        <a href="">
                          日程登録
                        </a>
                      </button>
                    </div>
                </div>
                <div class="cards mx-3 overflow-scroll">
                  <div id="scroll-inner">
                    <div class="fixed absolute right-0 card m-5 mr-8 p-3 bg-amber-100 w-72 rounded-md drop-shadow-md ml-auto">
                      日程が決まりましたら、<br>
                      日程登録ボタンを押してください。<br>
                      リマインドメールが送信されます。
                    </div>
                    <div class="spacer h-28"></div>
                    @foreach ($chatRecords as $key => $chatRecord)
                      @if ($chatRecord->user_id == $client_user->id)
                        <div class="card flex items-center justify-end">
                          <div class="icon w-14 h-14">
                            {{-- <img src="{{ asset('img/menIcon.png') }}" alt=""> --}}
                          </div>
                          <div class="time mt-auto mb-5">
                            <span class="text-slate-400 block">{{$chatRecord->date}}</span>
                          </div>
                          <div class="m-5 p-3 max-w-max bg-slate-100 rounded-full">
                            {{$chatRecord->comment}}
                          </div>
                        </div>
                      @elseif ($chatRecord->user_id == 3)
                        <div class="card flex items-center">
                          <div class="icon w-14 h-14">
                            <img src="{{ asset('img/anovey.png') }}" alt="">
                          </div>
                          <div class="m-5 p-3 max-w-max rounded-md border-2 rounded-full mr-1 bg-sky-100">
                            {{$chatRecord->comment}}
                          </div>
                          <div class="time mt-auto mb-5">
                            <span class="text-slate-400">{{$chatRecord->date}}</span>
                          </div>
                        </div>
                      @else
                        <div class="card flex items-center h-max">
                          <div class="icon w-14 h-14">
                            @if ($chatRecord->user_id != $chatRecords[$key + 1]->user_id)
                              <img src="{{ asset('img/menIcon.png') }}" alt="">
                            @endif
                            {{-- <img src="{{ asset($respondent_user_icon) }}" alt=""> --}}
                            {{-- 画像登録機能が完成されたらでいいかな? --}}
                          </div>
                          <div class="m-5 p-3 max-w-max rounded-md border-2 rounded-full mr-1">
                            {{$chatRecord->comment}}yyy
                          </div>
                          <div class="time mt-auto mb-5">
                            @if ($chatRecord->is_read)
                              <span class="text-slate-400 block text-right">既読</span>
                            @endif
                            <span class="text-slate-400">{{$chatRecord->date}}</span>
                          </div>
                        </div>
                      @endif
                    @endforeach
                  </div>
                </div>
                <form action="{{ route('chat.post') }}" method="POST"  class="flex items-center ">
                  @csrf
                  <input type="hidden" value="{{ $chatRoomId}}" name="chatRoomId">
                  <input type="hidden" value="{{ $client_user->id}}" name="user_id">
                  <input type="text" class="block m-5 p-3 bg-slate-100 rounded-full w-full" name="comment">
                  <div class="icon w-28">
                    <button class="bg-gray-400 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">送信</button>
                  </div>
                </form>
                <div class="flex items-center ">
                </div>
            </div>
            <script>
              let target = document.getElementById('scroll-inner');
              target.scrollIntoView(false);
            </script>
        @endsection
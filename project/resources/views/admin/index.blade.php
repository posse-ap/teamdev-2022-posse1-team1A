@extends('layouts.anovey')

@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
@endpush

@section('content')
    <main class="bg-grey-100 w-full">
        <div class="flex">
            @include('components.admin-bar')

            <div class="w-full mx-5">
                <h1 class="text-5xl py-12 text-blue-800">各種指標</h1>
                <div class="flex flex-row flex-wrap">
                    <div class="mx-5 bg-white block w-1/4 py-5 shadow-2xl rounded-lg mb-5">
                        <h2 class="text-blue-800 text-lg ml-5">総会員数</h2>
                        <div class="flex">
                            <nav class="flex px-12 items-end text-center ml-auto mr-auto my-4">
                                <p class="text-blue-800 text-6xl font-bold">{{ $total_count_users }}</p>
                                <p class="text-blue-800 ml-2 text-2xl">名</p>
                            </nav>
                        </div>
                    </div>
                    <div class="mx-5 bg-white block w-1/4 py-5 shadow-2xl rounded-lg mb-5">
                        <h2 class="text-blue-800 text-lg ml-5">総マッチ数</h2>
                        <div class="flex">
                            <nav class="flex px-12 items-end text-center ml-auto mr-auto my-4">
                                <p class="text-blue-800 text-6xl font-bold">{{ round($total_count_matched) }}</p>
                                <p class="text-blue-800 ml-2 text-2xl">回</p>
                            </nav>
                        </div>
                    </div>
                    <div class="mx-5 bg-white block w-1/4 py-5 shadow-2xl rounded-lg mb-5">
                        <h2 class="text-blue-800 text-lg ml-5">総通話数</h2>
                        <div class="flex">
                            <nav class="flex px-12 items-end text-center ml-auto mr-auto my-4">
                                <p class="text-blue-800 text-6xl font-bold">{{ $total_count_call }}</p>
                                <p class="text-blue-800 ml-2 text-2xl">回</p>
                            </nav>
                        </div>
                    </div>
                    <div class="mx-5 bg-white block w-1/4 py-5 shadow-2xl rounded-lg mb-5">
                        <h2 class="text-blue-800 text-lg ml-5">平均通話時間</h2>
                        <div class="flex">
                            <nav class="flex px-12 items-end text-center ml-auto mr-auto my-4">
                                <p class="text-blue-800 text-6xl font-bold">{{ round($avg_calling_time / 60, 1) }}</p>
                                <p class="text-blue-800 ml-2 text-2xl">分</p>
                            </nav>
                        </div>
                    </div>
                    <div class="mx-5 bg-white block w-1/4 py-5 shadow-2xl rounded-lg mb-5">
                        <h2 class="text-blue-800 text-lg ml-5">日程調整失敗数</h2>
                        <div class="flex">
                            <nav class="flex px-12 items-end text-center ml-auto mr-auto my-4">
                                <p class="text-blue-800 text-6xl font-bold">{{ $total_count_cancelled }}</p>
                                <p class="text-blue-800 ml-2 text-2xl">回</p>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    {{-- </html> --}}
    @endsection

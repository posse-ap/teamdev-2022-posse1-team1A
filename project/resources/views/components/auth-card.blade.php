<div class="flex flex-col sm:justify-center items-center pt-20 bg-gray-100">
    <div class="flex ml-auto mr-auto">
        <img src="{{ asset('img/logo-black.png') }}" alt="logo-black.png">
        <p class="flex ml-2 text-xl items-center"><b>Anovey<b></p>
    </div>
    
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        {{ $slot }}
    </div>
</div>

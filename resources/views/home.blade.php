<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>

    @if (session("msg"))
    <div class="pt-5">
        <x-alert color="blue">{{ session("msg") }}</x-alert-primary>
    </div>
    @endif
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($blogs["contents"] as $blog)
                        <div class="border border-gray-300 rounded-lg">
                            <img src="{{ $blog['eyecatch']['url'] }}"
                                class="w-full h-48 object-cover mb-4 rounded-t-lg">
                            <div class="p-4 text-center">
                                <h1 class="text-xl mb-2">{{ $blog["title"] }}</h1>
                                <p class="text-base mb-4">{{ $blog["details"] }}</p>
                                <p class="text-base mb-4">¥{{ $blog["price"] }}</p>
                                <form action="{{ route('cartadd') }}" method="post" class="text-center">
                                    @csrf
                                    <input type="hidden" name="blogData" value="{{ json_encode($blog) }}">
                                    @if ($blog['isInCart'] === 1)
                                        <x-secondary-button type="submit" disabled>
                                            {{'購入済み'}}
                                        </x-secondary-button>
                                    @elseif ($blog["isInCart"] === 2)
                                        <x-secondary-button type="submit" disabled>
                                            {{'カートに追加済み'}}
                                        </x-secondary-button>
                                    @elseif ($blog["isInCart"] === 0)
                                        <x-secondary-button type="submit">
                                            {{'カートに追加' }}
                                        </x-secondary-button>
                                    @endif
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
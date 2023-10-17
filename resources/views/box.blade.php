<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Box') }}
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
                    @if (empty($blogs))
                    <p>※ Boxは空です</p>
                    @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($blogs as $blog)
                        <div class="border border-gray-300 rounded-lg">
                            <img src="{{ $blog['eyecatch']['url'] }}"
                                class="w-full h-48 object-cover mb-4 rounded-t-lg">
                            <div class="p-4">
                                <h1 class="text-xl mb-2">{{ $blog["title"] }}</h1>
                                <p class="text-base mb-4">{{ $blog["details"] }}</p>
                                <form action="{{ route('blog', $blog['id']) }}" method="post" class="text-center">
                                    @csrf
                                    <x-primary-button>閲覧する</x-primary-button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
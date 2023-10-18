<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('購入成功!') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p>ご購入ありがとうございます！</p>
                    <form action="{{ route('box') }}" method="get">
                        @csrf
                        <x-primary-button class="mt-5">Boxを見る</x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

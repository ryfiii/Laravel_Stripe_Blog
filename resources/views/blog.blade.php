@vite(["resources/css/blog.scss"])
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Blog') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg my-8 mx-auto p-6 max-w-2xl">
                <div class="text-gray-700">
                    <div class="mb-4">
                        <p class="text-gray-500 text-center py-12 text-xl">{{ $blog["createdAt"] }}</p>
                        <h1 class="font-semibold text-center pb-12 text-4xl">{{ $blog["title"] }}</h1>
                    </div>
                    <div class="mb-6">
                        <img src="{{ $blog['eyecatch']['url'] }}"
                            class="w-9/12 h-auto rounded-lg shadow-md object-cover mx-auto" />
                    </div>
                    <div class="blog p-9 mx-auto" style="max-width: 800px;">
                        {!! $blog["content"] !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

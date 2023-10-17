@php
    $total = 0;
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (empty($cart))
                        <p>※ カートは空です</p>
                    @else
                        <table class="min-w-full bg-white">
                            <tr>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Image</th>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Details</th>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"></th>
                            </tr>
                            @foreach ($cart as $item)
                                <tr>
                                    <td class="py-2 px-4 border-b border-gray-200"><img src="{{ $item["image"] }}" class="w-20 h-20 object-cover"></td>
                                    <td class="py-2 px-4 border-b border-gray-200">{{ $item["title"] }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200">{{ $item["details"] }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200">¥{{ $item["price"] }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200">
                                        <form action="/cartdelete" method="post">
                                            @csrf
                                            <input type="hidden" name="item_id" value="{{ $item['id'] }}">
                                            <button type=submit><i class="fa-solid fa-trash text-blue-400 text-lg"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @php
                                    $total += $item['price']
                                @endphp
                                @if ($loop->last)
                                    <tr>
                                        <td class="py-2 px-4 border-b border-gray-200 font-bold">合計</td>
                                        <td class="py-2 px-4 border-b border-gray-200"></td>
                                        <td class="py-2 px-4 border-b border-gray-200"></td>
                                        <td class="py-2 px-4 border-b border-gray-200 font-bold">¥{{ $total }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                        <div class="flex justify-center items-center mt-5">
                            <form action="{{ route('create-checkout-session') }}" method="post">
                                @csrf
                                <x-primary-button>購入</x-primary-button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

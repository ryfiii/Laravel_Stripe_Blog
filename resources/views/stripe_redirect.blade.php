@php
    $total = 0;
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('リダイレクト') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p>決済画面を開いています...</p>
                </div>
            </div>
        </div>
    </div>
<script src="https://js.stripe.com/v3/"></script>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', (event) => {
        redirectToCheckout();
    });

    async function redirectToCheckout() {
        // Stripeオブジェクトを初期化
        const stripe = Stripe('{{ config("services.stripe.public_key") }}');  // Stripe公開キーを指定

        const sessionId = "{{ session('checkout_id') }}";

        // Stripe Checkoutページにリダイレクト
        const result = await stripe.redirectToCheckout({ sessionId });
        if (result.error) {
            // リダイレクトに失敗した場合、エラーメッセージを表示
            alert(result.error.message);
        }
    }
</script>
</x-app-layout>

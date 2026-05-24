@extends('layouts.frontend')

@section('content')
<div class="py-12 sm:py-16">
    <div class="page-shell">
        <div class="text-center">
            <span class="section-kicker">Checkout</span>
            <h2 class="section-title mt-4">Complete your order</h2>
            <p class="mx-auto mt-3 max-w-2xl text-base leading-8 text-slate-600">Confirm your delivery details and choose the payment method that suits you best.</p>
        </div>

        <div class="mt-8 grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="card p-8">
                <h3 class="text-xl font-bold text-darkblue-700">Delivery information</h3>
                <form action="{{ route('checkout.process') }}" method="POST" class="mt-6 space-y-5">
                    @csrf
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-darkblue-700">Delivery address</label>
                        <input type="text" name="address" required class="input-field" placeholder="123 Street Name, Colombo">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-darkblue-700">Phone number</label>
                        <input type="text" name="phone" value="{{ auth()->user()->phone }}" required class="input-field">
                    </div>

                    <div class="pt-4">
                        <h3 class="text-xl font-bold text-darkblue-700">Payment method</h3>
                        <div class="mt-4 space-y-3">
                            <label class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 transition hover:border-primary-200 hover:bg-primary-50">
                                <input type="radio" name="payment_method" value="card" checked class="text-primary-500 focus:ring-primary-500">
                                <span class="flex-1 font-semibold text-darkblue-700">Credit/Debit Card</span>
                                <i class="fas fa-cc-visa text-lg text-slate-400"></i>
                            </label>
                            <label class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 transition hover:border-primary-200 hover:bg-primary-50">
                                <input type="radio" name="payment_method" value="cash" class="text-primary-500 focus:ring-primary-500">
                                <span class="flex-1 font-semibold text-darkblue-700">Cash on Delivery</span>
                                <i class="fas fa-money-bill-wave text-lg text-slate-400"></i>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary w-full py-4 text-base">Place order</button>
                </form>
            </div>

            <div class="space-y-5">
                <div class="card p-8">
                    <h3 class="text-xl font-bold text-darkblue-700">Your order</h3>
                    @php
                        $cartItems = \App\Models\CartItem::where('user_id', auth()->id())->with('menuItem')->get();
                        $subtotal = $cartItems->sum(function($item) { return $item->menuItem->price * $item->quantity; });
                        $total = $subtotal + 250;
                    @endphp
                    <div class="mt-6 space-y-4">
                        @foreach($cartItems as $item)
                            <div class="flex items-center justify-between gap-4 rounded-2xl bg-slate-50 px-4 py-3">
                                <div>
                                    <p class="font-semibold text-darkblue-700">{{ $item->quantity }}x {{ $item->menuItem->name }}</p>
                                    <p class="text-sm text-slate-500">Rs. {{ number_format($item->menuItem->price, 0) }} each</p>
                                </div>
                                <p class="font-bold text-primary-500">Rs. {{ number_format($item->menuItem->price * $item->quantity, 0) }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 space-y-3 border-t pt-4 text-sm text-slate-600">
                        <div class="flex justify-between"><span>Subtotal</span><span>Rs. {{ number_format($subtotal, 0) }}</span></div>
                        <div class="flex justify-between"><span>Delivery fee</span><span>Rs. 250</span></div>
                        <div class="flex justify-between text-base font-bold text-darkblue-700"><span>Total</span><span class="text-primary-500">Rs. {{ number_format($total, 0) }}</span></div>
                    </div>
                </div>

                <div class="glass-panel p-6">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-info-circle text-primary-500"></i>
                        <p class="text-sm leading-7 text-slate-600">Your order will be prepared immediately after confirmation. Estimated delivery time is 30–45 minutes.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

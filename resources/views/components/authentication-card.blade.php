<div class="min-h-screen px-4 py-10 sm:px-6 lg:px-8">
    <div class="mx-auto grid w-full max-w-5xl overflow-hidden rounded-[34px] border border-white/70 bg-white/95 shadow-[0_28px_90px_-30px_rgba(44,62,80,0.38)] backdrop-blur md:grid-cols-[0.95fr_1.05fr]">
        <div class="flex flex-col justify-between bg-[linear-gradient(180deg,#0f172a,#1e3a5f)] p-8 text-white">
            <div>
                <p class="inline-flex items-center rounded-full bg-white/10 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.35em] text-orange-200">
                    Hot Dish
                </p>

                <div class="mt-6 flex items-center gap-3">
                    {{ $logo }}
                    <div>
                        <p class="text-sm font-semibold text-orange-200">Secure access</p>
                        <p class="text-lg font-bold">A refined ordering experience</p>
                    </div>
                </div>

                <h1 class="mt-8 text-3xl font-black tracking-tight sm:text-4xl">
                    Sign in with clarity, confidence, and style.
                </h1>
                <p class="mt-4 text-sm leading-7 text-slate-100/90">
                    Save your cart, track orders, and move through checkout with a polished, reliable workflow designed for modern food ordering.
                </p>

                <div class="mt-8 space-y-3">
                    <div class="rounded-[24px] bg-white/10 p-4 backdrop-blur">
                        <p class="text-sm font-semibold text-orange-200">Fast checkout</p>
                        <p class="mt-2 text-sm leading-6 text-slate-100/90">Return to your cart and complete orders in just a few taps.</p>
                    </div>
                    <div class="rounded-[24px] bg-white/10 p-4 backdrop-blur">
                        <p class="text-sm font-semibold text-orange-200">Protected sessions</p>
                        <p class="mt-2 text-sm leading-6 text-slate-100/90">Jetstream-powered authentication keeps access secure and easy to manage.</p>
                    </div>
                </div>
            </div>

            <div class="mt-10 rounded-[24px] border border-white/10 bg-white/5 px-4 py-4 backdrop-blur">
                <p class="text-[11px] font-bold uppercase tracking-[0.35em] text-slate-200/80">Why customers choose Hot Dish</p>
                <div class="mt-3 grid gap-3 sm:grid-cols-3">
                    <div>
                        <p class="text-lg font-black text-white">8</p>
                        <p class="text-sm text-slate-200/80">signature menu items</p>
                    </div>
                    <div>
                        <p class="text-lg font-black text-white">24/7</p>
                        <p class="text-sm text-slate-200/80">secure account access</p>
                    </div>
                    <div>
                        <p class="text-lg font-black text-white">1.0x</p>
                        <p class="text-sm text-slate-200/80">smooth checkout flow</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6 sm:p-8 lg:p-10">
            {{ $slot }}
        </div>
    </div>
</div>

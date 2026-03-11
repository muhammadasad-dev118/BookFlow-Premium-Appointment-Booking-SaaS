<x-layouts.dashboard :title="'Billing'">
    <div class="mb-6"><h2 class="text-xl font-bold text-white">Billing & Subscription</h2><p class="text-sm text-slate-400 mt-1">Manage your plan and payments</p></div>

    @if(request('success'))
        <div class="mb-6 p-4 glass-card border-l-4 border-green-500 text-green-400 text-sm">🎉 Your subscription has been activated!</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        @foreach($plans as $plan)
            <div class="glass-card p-6 relative {{ $subscription && $subscription->stripe_price === $plan['lookup_key'] ? 'border-amber-500/50' : '' }}">
                @if($subscription && $subscription->stripe_price === $plan['lookup_key'])
                    <div class="absolute top-4 right-4"><span class="badge badge-success">Current Plan</span></div>
                @endif
                <h3 class="text-base font-bold text-white mb-1">{{ $plan['name'] }}</h3>
                <p class="text-xl font-bold text-amber-400">${{ $plan['price'] }}<span class="text-xs font-normal text-slate-400">/mo</span></p>

                <ul class="mt-4 space-y-2 mb-6">
                    @foreach($plan['features'] as $f)
                        <li class="flex items-center gap-2 text-sm text-slate-300">
                            <svg class="w-4 h-4 text-green-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            {{ $f }}
                        </li>
                    @endforeach
                </ul>

                @if(!$subscription || $subscription->stripe_price !== $plan['lookup_key'])
                    <form method="POST" action="{{ route('billing.subscribe') }}">
                        @csrf
                        <input type="hidden" name="price_id" value="{{ $plan['lookup_key'] }}">
                        
                        @if(!$plan['lookup_key'])
                            <div class="mb-4 p-2 bg-red-500/10 border border-red-500/20 text-red-400 text-xs rounded">
                                ⚠️ This plan is not configured yet. Please add the Price ID to your .env file.
                            </div>
                            <button type="button" class="btn-primary w-full opacity-50 cursor-not-allowed" disabled>Subscribe</button>
                        @else
                            <button type="submit" class="btn-primary w-full">{{ $subscription ? 'Switch to ' . $plan['name'] : 'Subscribe' }}</button>
                        @endif
                    </form>
                @endif
            </div>
        @endforeach
    </div>

    @if($tenant->stripe_id)
        <div class="text-center">
            <form method="POST" action="{{ route('billing.portal') }}">
                @csrf
                <button type="submit" class="btn-secondary">Manage Billing & Invoices →</button>
            </form>
        </div>
    @endif
</x-layouts.dashboard>

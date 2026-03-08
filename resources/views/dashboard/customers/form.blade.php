<x-layouts.dashboard :title="$customer ? 'Edit Customer' : 'Add Customer'">
    <div class="max-w-2xl">
        <h2 class="text-xl font-bold text-white mb-6">{{ $customer ? 'Edit Customer' : 'Add Customer' }}</h2>
        <div class="glass-card p-6">
            <form method="POST" action="{{ $customer ? route('customers.update', $customer) : route('customers.store') }}" class="space-y-5">
                @csrf @if($customer) @method('PUT') @endif
                <div><label class="form-label">Full Name</label><input type="text" name="name" class="form-input" value="{{ old('name', $customer?->name) }}" required></div>
                <div><label class="form-label">Email</label><input type="email" name="email" class="form-input" value="{{ old('email', $customer?->email) }}" required></div>
                <div><label class="form-label">Phone</label><input type="text" name="phone" class="form-input" value="{{ old('phone', $customer?->phone) }}"></div>
                <div class="flex items-center gap-3 pt-2"><button type="submit" class="btn-primary">{{ $customer ? 'Update' : 'Add' }}</button><a href="{{ route('customers.index') }}" class="btn-secondary">Cancel</a></div>
            </form>
        </div>
    </div>
</x-layouts.dashboard>

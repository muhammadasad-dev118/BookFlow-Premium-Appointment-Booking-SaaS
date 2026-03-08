<x-layouts.dashboard :title="$staffMember ? 'Edit Staff' : 'Add Staff'">
    <div class="max-w-2xl">
        <h2 class="text-xl font-bold text-white mb-6">{{ $staffMember ? 'Edit Staff Member' : 'Add Staff Member' }}</h2>
        <div class="glass-card p-6">
            <form method="POST" action="{{ $staffMember ? route('staff.update', $staffMember) : route('staff.store') }}" class="space-y-5">
                @csrf @if($staffMember) @method('PUT') @endif
                <div><label class="form-label">Full Name</label><input type="text" name="name" class="form-input" value="{{ old('name', $staffMember?->name) }}" required></div>
                <div><label class="form-label">Email</label><input type="email" name="email" class="form-input" value="{{ old('email', $staffMember?->email) }}" required></div>
                <div><label class="form-label">Phone</label><input type="text" name="phone" class="form-input" value="{{ old('phone', $staffMember?->phone) }}"></div>
                <div><label class="flex items-center gap-3 cursor-pointer"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" class="w-4 h-4 rounded bg-slate-800 border-slate-600 text-amber-500 focus:ring-amber-500/30" {{ old('is_active', $staffMember?->is_active ?? true) ? 'checked' : '' }}><span class="text-sm text-slate-300">Active</span></label></div>
                <div class="flex items-center gap-3 pt-2"><button type="submit" class="btn-primary">{{ $staffMember ? 'Update' : 'Add' }}</button><a href="{{ route('staff.index') }}" class="btn-secondary">Cancel</a></div>
            </form>
        </div>
    </div>
</x-layouts.dashboard>

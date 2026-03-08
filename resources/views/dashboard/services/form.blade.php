<x-layouts.dashboard :title="$service ? 'Edit Service' : 'New Service'">
    <div class="max-w-2xl">
        <h2 class="text-xl font-bold text-white mb-6">{{ $service ? 'Edit Service' : 'New Service' }}</h2>
        <div class="glass-card p-6">
            <form method="POST" action="{{ $service ? route('services.update', $service) : route('services.store') }}" class="space-y-5">
                @csrf @if($service) @method('PUT') @endif
                <div><label class="form-label">Service Name</label><input type="text" name="name" class="form-input" value="{{ old('name', $service?->name) }}" required></div>
                <div><label class="form-label">Description</label><textarea name="description" class="form-input" rows="3">{{ old('description', $service?->description) }}</textarea></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="form-label">Duration (minutes)</label><input type="number" name="duration_minutes" class="form-input" value="{{ old('duration_minutes', $service?->duration_minutes) }}" min="5" required></div>
                    <div><label class="form-label">Price ($)</label><input type="number" name="price" class="form-input" step="0.01" value="{{ old('price', $service?->price) }}" min="0" required></div>
                </div>
                <div><label class="flex items-center gap-3 cursor-pointer"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" class="w-4 h-4 rounded bg-slate-800 border-slate-600 text-amber-500 focus:ring-amber-500/30" {{ old('is_active', $service?->is_active ?? true) ? 'checked' : '' }}><span class="text-sm text-slate-300">Active</span></label></div>
                <div class="flex items-center gap-3 pt-2"><button type="submit" class="btn-primary">{{ $service ? 'Update' : 'Create' }} Service</button><a href="{{ route('services.index') }}" class="btn-secondary">Cancel</a></div>
            </form>
        </div>
    </div>
</x-layouts.dashboard>

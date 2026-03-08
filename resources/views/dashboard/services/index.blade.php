<x-layouts.dashboard :title="'Services'">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-white">Services</h2>
            <p class="text-sm text-slate-400 mt-1">Manage the services you offer</p>
        </div>
        <a href="{{ route('services.create') }}" class="btn-primary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Service
        </a>
    </div>
    <div class="glass-card p-4 mb-6">
        <form method="GET" class="flex items-center gap-4"><input type="text" name="search" value="{{ request('search') }}" placeholder="Search services..." class="form-input flex-1"><button type="submit" class="btn-secondary">Search</button></form>
    </div>
    <div class="glass-card overflow-hidden">
        <table class="dash-table">
            <thead><tr><th>Name</th><th>Duration</th><th>Price</th><th>Bookings</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($services as $s)
                    <tr>
                        <td class="font-medium text-white">{{ $s->name }}</td>
                        <td>{{ $s->duration_minutes }} min</td>
                        <td>${{ number_format($s->price, 2) }}</td>
                        <td>{{ $s->appointments_count }}</td>
                        <td><span class="badge {{ $s->is_active ? 'badge-success' : 'badge-danger' }}">{{ $s->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td class="flex items-center gap-2">
                            <a href="{{ route('services.edit', $s) }}" class="text-xs text-amber-400 hover:text-amber-300">Edit</a>
                            <form method="POST" action="{{ route('services.destroy', $s) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="text-xs text-red-400 hover:text-red-300">Delete</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-slate-500 py-8">No services yet</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-white/5">{{ $services->links() }}</div>
    </div>
</x-layouts.dashboard>

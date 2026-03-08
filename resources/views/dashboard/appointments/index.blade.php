<x-layouts.dashboard :title="'Appointments'">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-white">Appointments</h2>
            <p class="text-sm text-slate-400 mt-1">Manage your bookings and schedule</p>
        </div>
        <a href="{{ route('appointments.create') }}" class="btn-primary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            New Appointment
        </a>
    </div>

    <!-- Filters -->
    <div class="glass-card p-4 mb-6">
        <form method="GET" class="flex items-center gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search client or service..." class="form-input flex-1">
            <select name="status" class="form-input w-40" onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="btn-secondary">Filter</button>
        </form>
    </div>

    <!-- Table -->
    <div class="glass-card overflow-hidden">
        <table class="dash-table">
            <thead>
                <tr><th>Client</th><th>Service</th><th>Staff</th><th>Date & Time</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($appointments as $apt)
                    <tr>
                        <td class="font-medium text-white">{{ $apt->customer?->name ?? 'Walk-in' }}</td>
                        <td>{{ $apt->service?->name ?? '—' }}</td>
                        <td>{{ $apt->staff?->name ?? '—' }}</td>
                        <td>{{ \Carbon\Carbon::parse($apt->start_time)->format('M d, Y · h:i A') }}</td>
                        <td><span class="badge {{ $apt->status === 'confirmed' ? 'badge-success' : ($apt->status === 'cancelled' ? 'badge-danger' : ($apt->status === 'completed' ? 'badge-info' : 'badge-warning')) }}">{{ ucfirst($apt->status) }}</span></td>
                        <td class="flex items-center gap-2">
                            <a href="{{ route('appointments.edit', $apt) }}" class="text-xs text-amber-400 hover:text-amber-300">Edit</a>
                            <form method="POST" action="{{ route('appointments.destroy', $apt) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="text-xs text-red-400 hover:text-red-300">Delete</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-slate-500 py-8">No appointments found</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-white/5">{{ $appointments->links() }}</div>
    </div>
</x-layouts.dashboard>

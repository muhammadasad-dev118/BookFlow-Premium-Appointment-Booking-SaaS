<x-layouts.dashboard :title="'Staff'">
    <div class="flex items-center justify-between mb-6">
        <div><h2 class="text-xl font-bold text-white">Staff</h2><p class="text-sm text-slate-400 mt-1">Manage your team members</p></div>
        <a href="{{ route('staff.create') }}" class="btn-primary flex items-center gap-2"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>Add Staff</a>
    </div>
    <div class="glass-card overflow-hidden">
        <table class="dash-table">
            <thead><tr><th>Name</th><th>Email</th><th>Bookings</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($staff as $s)
                    <tr>
                        <td class="font-medium text-white">{{ $s->name }}</td>
                        <td>{{ $s->email }}</td>
                        <td>{{ $s->appointments_count }}</td>
                        <td><span class="badge {{ $s->is_active ? 'badge-success' : 'badge-danger' }}">{{ $s->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td class="flex items-center gap-2">
                            <a href="{{ route('staff.edit', $s) }}" class="text-xs text-amber-400 hover:text-amber-300">Edit</a>
                            <form method="POST" action="{{ route('staff.destroy', $s) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="text-xs text-red-400 hover:text-red-300">Delete</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-slate-500 py-8">No staff added yet</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-white/5">{{ $staff->links() }}</div>
    </div>
</x-layouts.dashboard>

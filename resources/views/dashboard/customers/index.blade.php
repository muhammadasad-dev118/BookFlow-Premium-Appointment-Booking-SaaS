<x-layouts.dashboard :title="'Customers'">
    <div class="flex items-center justify-between mb-6">
        <div><h2 class="text-xl font-bold text-white">Customers</h2><p class="text-sm text-slate-400 mt-1">Manage your client database</p></div>
        <a href="{{ route('customers.create') }}" class="btn-primary flex items-center gap-2"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>Add Customer</a>
    </div>
    <div class="glass-card p-4 mb-6"><form method="GET" class="flex items-center gap-4"><input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email..." class="form-input flex-1"><button type="submit" class="btn-secondary">Search</button></form></div>
    <div class="glass-card overflow-hidden">
        <table class="dash-table">
            <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Bookings</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($customers as $c)
                    <tr>
                        <td class="font-medium text-white">{{ $c->name }}</td>
                        <td>{{ $c->email }}</td>
                        <td>{{ $c->phone ?? '—' }}</td>
                        <td>{{ $c->appointments_count }}</td>
                        <td class="flex items-center gap-2">
                            <a href="{{ route('customers.edit', $c) }}" class="text-xs text-amber-400 hover:text-amber-300">Edit</a>
                            <form method="POST" action="{{ route('customers.destroy', $c) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="text-xs text-red-400 hover:text-red-300">Delete</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-slate-500 py-8">No customers yet</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-white/5">{{ $customers->links() }}</div>
    </div>
</x-layouts.dashboard>

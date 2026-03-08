<x-layouts.dashboard :title="'Manage Tenants'">
    <div class="mb-6"><h2 class="text-xl font-bold text-white">Businesses</h2><p class="text-sm text-slate-400 mt-1">All registered businesses on the platform</p></div>
    <div class="glass-card p-4 mb-6"><form method="GET" class="flex items-center gap-4"><input type="text" name="search" value="{{ request('search') }}" placeholder="Search businesses..." class="form-input flex-1"><button type="submit" class="btn-secondary">Search</button></form></div>
    <div class="glass-card overflow-hidden">
        <table class="dash-table">
            <thead><tr><th>Business</th><th>Slug</th><th>Users</th><th>Staff</th><th>Appointments</th><th>Created</th></tr></thead>
            <tbody>
                @foreach($tenants as $t)
                    <tr>
                        <td class="font-medium text-white">{{ $t->name }}</td>
                        <td class="text-slate-400">{{ $t->slug }}</td>
                        <td>{{ $t->users_count }}</td>
                        <td>{{ $t->staff_count }}</td>
                        <td>{{ $t->appointments_count }}</td>
                        <td>{{ $t->created_at->format('M d, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-white/5">{{ $tenants->links() }}</div>
    </div>
</x-layouts.dashboard>

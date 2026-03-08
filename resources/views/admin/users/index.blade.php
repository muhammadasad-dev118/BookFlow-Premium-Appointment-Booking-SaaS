<x-layouts.dashboard :title="'Manage Users'">
    <div class="mb-6"><h2 class="text-xl font-bold text-white">Users</h2><p class="text-sm text-slate-400 mt-1">All registered users on the platform</p></div>
    <div class="glass-card p-4 mb-6"><form method="GET" class="flex items-center gap-4"><input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email..." class="form-input flex-1"><button type="submit" class="btn-secondary">Search</button></form></div>
    <div class="glass-card overflow-hidden">
        <table class="dash-table">
            <thead><tr><th>Name</th><th>Email</th><th>Businesses</th><th>Joined</th></tr></thead>
            <tbody>
                @foreach($users as $u)
                    <tr>
                        <td class="font-medium text-white">{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>{{ $u->tenants_count }}</td>
                        <td>{{ $u->created_at->format('M d, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-white/5">{{ $users->links() }}</div>
    </div>
</x-layouts.dashboard>

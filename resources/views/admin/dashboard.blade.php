<x-layouts.dashboard :title="'Admin Dashboard'">
    <div class="mb-6"><h2 class="text-xl font-bold text-white">Platform Overview</h2><p class="text-sm text-slate-400 mt-1">Super Admin Dashboard</p></div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        <div class="glass-card stat-gradient-1 p-5 animate-in">
            <p class="text-2xl font-bold text-white">{{ $totalTenants }}</p>
            <p class="text-xs text-slate-400 mt-1">Total Businesses</p>
        </div>
        <div class="glass-card stat-gradient-2 p-5 animate-in delay-1">
            <p class="text-2xl font-bold text-white">{{ $totalUsers }}</p>
            <p class="text-xs text-slate-400 mt-1">Total Users</p>
        </div>
        <div class="glass-card stat-gradient-3 p-5 animate-in delay-2">
            <p class="text-2xl font-bold text-white">{{ $totalAppointments }}</p>
            <p class="text-xs text-slate-400 mt-1">Total Appointments</p>
        </div>
    </div>

    <!-- Recent -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <div class="glass-card">
            <div class="px-6 py-4 border-b border-white/5"><h3 class="text-sm font-semibold text-white">Recent Businesses</h3></div>
            <table class="dash-table">
                <thead><tr><th>Name</th><th>Slug</th><th>Created</th></tr></thead>
                <tbody>
                    @foreach($recentTenants as $t)
                        <tr>
                            <td class="font-medium text-white">{{ $t->name }}</td>
                            <td class="text-slate-400">{{ $t->slug }}</td>
                            <td>{{ $t->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="glass-card">
            <div class="px-6 py-4 border-b border-white/5"><h3 class="text-sm font-semibold text-white">Recent Users</h3></div>
            <table class="dash-table">
                <thead><tr><th>Name</th><th>Email</th><th>Joined</th></tr></thead>
                <tbody>
                    @foreach($recentUsers as $u)
                        <tr>
                            <td class="font-medium text-white">{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>{{ $u->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.dashboard>

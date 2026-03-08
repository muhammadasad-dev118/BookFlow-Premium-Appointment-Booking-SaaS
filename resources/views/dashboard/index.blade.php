<x-layouts.dashboard :title="'Dashboard'">
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <!-- Bookings -->
        <div class="glass-card stat-gradient-1 p-5 animate-in delay-1">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                </div>
                <span class="text-xs font-medium text-green-400 bg-green-400/10 px-2 py-1 rounded-full">+12%</span>
            </div>
            <p class="text-2xl font-bold text-white">{{ number_format($totalBookings) }}</p>
            <p class="text-xs text-slate-400 mt-1">Total Bookings</p>
        </div>

        <!-- Revenue -->
        <div class="glass-card stat-gradient-2 p-5 animate-in delay-2">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-green-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs font-medium text-green-400 bg-green-400/10 px-2 py-1 rounded-full">+8%</span>
            </div>
            <p class="text-2xl font-bold text-white">${{ number_format($revenue, 2) }}</p>
            <p class="text-xs text-slate-400 mt-1">Total Revenue</p>
        </div>

        <!-- Clients -->
        <div class="glass-card stat-gradient-3 p-5 animate-in delay-3">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                </div>
                <span class="text-xs font-medium text-blue-400 bg-blue-400/10 px-2 py-1 rounded-full">+5%</span>
            </div>
            <p class="text-2xl font-bold text-white">{{ number_format($activeClients) }}</p>
            <p class="text-xs text-slate-400 mt-1">Active Clients</p>
        </div>

        <!-- Staff -->
        <div class="glass-card stat-gradient-4 p-5 animate-in delay-4">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-purple-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $staffCount }}</p>
            <p class="text-xs text-slate-400 mt-1">Staff Members</p>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">
        <!-- Revenue Chart -->
        <div class="glass-card p-6 lg:col-span-2 animate-in">
            <h3 class="text-sm font-semibold text-white mb-4">Revenue Trend</h3>
            <canvas id="revenueChart" height="130"></canvas>
        </div>

        <!-- Donut Chart -->
        <div class="glass-card p-6 animate-in">
            <h3 class="text-sm font-semibold text-white mb-4">Bookings by Service</h3>
            <canvas id="serviceChart" height="200"></canvas>
        </div>
    </div>

    <!-- Upcoming Appointments -->
    <div class="glass-card animate-in">
        <div class="flex items-center justify-between px-6 py-4 border-b border-white/5">
            <h3 class="text-sm font-semibold text-white">Upcoming Appointments</h3>
            <a href="{{ route('appointments.index') }}" class="text-xs text-amber-400 hover:text-amber-300 font-medium">View all →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Service</th>
                        <th>Staff</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($upcomingAppointments as $apt)
                        <tr>
                            <td class="font-medium text-white">{{ $apt->customer?->name ?? 'Walk-in' }}</td>
                            <td>{{ $apt->service?->name ?? '—' }}</td>
                            <td>{{ $apt->staff?->name ?? '—' }}</td>
                            <td>{{ \Carbon\Carbon::parse($apt->start_time)->format('M d, Y · h:i A') }}</td>
                            <td>
                                @php $status = $apt->status ?? 'pending'; @endphp
                                <span class="badge {{ $status === 'confirmed' ? 'badge-success' : ($status === 'cancelled' ? 'badge-danger' : 'badge-warning') }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-slate-500 py-8">No upcoming appointments</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Revenue Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            const gradient = revenueCtx.createLinearGradient(0, 0, 0, 250);
            gradient.addColorStop(0, 'rgba(245, 158, 11, 0.3)');
            gradient.addColorStop(1, 'rgba(245, 158, 11, 0)');

            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: @json($revenueLabels),
                    datasets: [{
                        label: 'Revenue',
                        data: @json($revenueData),
                        borderColor: '#f59e0b',
                        backgroundColor: gradient,
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#f59e0b',
                        pointBorderColor: '#0f172a',
                        pointBorderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { color: 'rgba(148,163,184,0.06)' }, ticks: { color: '#64748b', font: { size: 11 } } },
                        y: { grid: { color: 'rgba(148,163,184,0.06)' }, ticks: { color: '#64748b', font: { size: 11 }, callback: v => '$' + v } }
                    }
                }
            });

            // Service Donut
            const serviceCtx = document.getElementById('serviceChart').getContext('2d');
            const serviceData = @json($serviceStats);
            new Chart(serviceCtx, {
                type: 'doughnut',
                data: {
                    labels: serviceData.map(s => s.name),
                    datasets: [{
                        data: serviceData.map(s => s.appointments_count),
                        backgroundColor: ['#f59e0b', '#22c55e', '#3b82f6', '#a855f7', '#ef4444'],
                        borderColor: '#1e293b',
                        borderWidth: 3,
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '65%',
                    plugins: {
                        legend: { position: 'bottom', labels: { color: '#94a3b8', font: { size: 11 }, padding: 12 } }
                    }
                }
            });
        });
    </script>
    @endpush
</x-layouts.dashboard>

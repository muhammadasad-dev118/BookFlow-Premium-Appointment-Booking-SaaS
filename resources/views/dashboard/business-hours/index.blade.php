<x-layouts.dashboard :title="'Business Hours'">
    <div class="flex items-center justify-between mb-6">
        <div><h2 class="text-xl font-bold text-white">Business Hours</h2><p class="text-sm text-slate-400 mt-1">Set working hours for your team</p></div>
        <a href="{{ route('business-hours.create') }}" class="btn-primary flex items-center gap-2"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>Add Hours</a>
    </div>
    @php $days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday']; @endphp
    @foreach($businessHours as $staffId => $hours)
        <div class="glass-card mb-4 overflow-hidden">
            <div class="px-6 py-3 border-b border-white/5"><h4 class="text-sm font-semibold text-white">{{ $hours->first()->staff?->name ?? 'Unknown' }}</h4></div>
            <table class="dash-table">
                <thead><tr><th>Day</th><th>Start</th><th>End</th><th>Actions</th></tr></thead>
                <tbody>
                    @foreach($hours as $h)
                        <tr>
                            <td class="font-medium text-white">{{ $days[$h->day_of_week] ?? $h->day_of_week }}</td>
                            <td>{{ $h->start_time }}</td>
                            <td>{{ $h->end_time }}</td>
                            <td class="flex items-center gap-2">
                                <a href="{{ route('business-hours.edit', $h) }}" class="text-xs text-amber-400 hover:text-amber-300">Edit</a>
                                <form method="POST" action="{{ route('business-hours.destroy', $h) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="text-xs text-red-400 hover:text-red-300">Delete</button></form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
    @if($businessHours->isEmpty())
        <div class="glass-card p-8 text-center text-slate-500">No business hours configured yet</div>
    @endif
</x-layouts.dashboard>

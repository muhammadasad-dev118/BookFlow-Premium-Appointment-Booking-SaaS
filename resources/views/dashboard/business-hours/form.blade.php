<x-layouts.dashboard :title="$businessHour ? 'Edit Hours' : 'Add Hours'">
    @php $days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday']; @endphp
    <div class="max-w-2xl">
        <h2 class="text-xl font-bold text-white mb-6">{{ $businessHour ? 'Edit Business Hours' : 'Add Business Hours' }}</h2>
        <div class="glass-card p-6">
            <form method="POST" action="{{ $businessHour ? route('business-hours.update', $businessHour) : route('business-hours.store') }}" class="space-y-5">
                @csrf @if($businessHour) @method('PUT') @endif
                <div><label class="form-label">Staff Member</label>
                    <select name="staff_id" class="form-input" required>
                        <option value="">Select staff...</option>
                        @foreach($staff as $s)
                            <option value="{{ $s->id }}" {{ old('staff_id', $businessHour?->staff_id) == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="form-label">Day</label>
                    <select name="day_of_week" class="form-input" required>
                        @foreach($days as $i => $d)
                            <option value="{{ $i }}" {{ old('day_of_week', $businessHour?->day_of_week) == $i ? 'selected' : '' }}>{{ $d }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="form-label">Start Time</label><input type="time" name="start_time" class="form-input" value="{{ old('start_time', $businessHour?->start_time) }}" required></div>
                    <div><label class="form-label">End Time</label><input type="time" name="end_time" class="form-input" value="{{ old('end_time', $businessHour?->end_time) }}" required></div>
                </div>
                <div class="flex items-center gap-3 pt-2"><button type="submit" class="btn-primary">{{ $businessHour ? 'Update' : 'Add' }}</button><a href="{{ route('business-hours.index') }}" class="btn-secondary">Cancel</a></div>
            </form>
        </div>
    </div>
</x-layouts.dashboard>

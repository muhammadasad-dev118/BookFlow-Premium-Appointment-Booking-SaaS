<x-layouts.dashboard :title="$appointment ? 'Edit Appointment' : 'New Appointment'">
    <div class="max-w-2xl">
        <h2 class="text-xl font-bold text-white mb-6">{{ $appointment ? 'Edit Appointment' : 'New Appointment' }}</h2>
        <div class="glass-card p-6">
            <form method="POST" action="{{ $appointment ? route('appointments.update', $appointment) : route('appointments.store') }}" class="space-y-5">
                @csrf
                @if($appointment) @method('PUT') @endif

                <div>
                    <label class="form-label">Customer</label>
                    <select name="customer_id" class="form-input" required>
                        <option value="">Select customer...</option>
                        @foreach(\App\Models\Customer::where('tenant_id', session('tenant_id'))->get() as $c)
                            <option value="{{ $c->id }}" {{ old('customer_id', $appointment?->customer_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Service</label>
                        <select name="service_id" class="form-input" required>
                            <option value="">Select service...</option>
                            @foreach(\App\Models\Service::where('tenant_id', session('tenant_id'))->get() as $s)
                                <option value="{{ $s->id }}" {{ old('service_id', $appointment?->service_id) == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Staff</label>
                        <select name="staff_id" class="form-input" required>
                            <option value="">Select staff...</option>
                            @foreach(\App\Models\Staff::where('tenant_id', session('tenant_id'))->where('is_active', true)->get() as $st)
                                <option value="{{ $st->id }}" {{ old('staff_id', $appointment?->staff_id) == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Date & Time</label>
                        <input type="datetime-local" name="start_time" class="form-input" value="{{ old('start_time', $appointment?->start_time ? \Carbon\Carbon::parse($appointment->start_time)->format('Y-m-d\TH:i') : '') }}" required>
                    </div>
                    <div>
                        <label class="form-label">Status</label>
                        <select name="status" class="form-input" required>
                            @foreach(['pending', 'confirmed', 'completed', 'cancelled'] as $s)
                                <option value="{{ $s }}" {{ old('status', $appointment?->status ?? 'pending') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-input" rows="3" placeholder="Optional notes...">{{ old('notes', $appointment?->notes) }}</textarea>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="btn-primary">{{ $appointment ? 'Update' : 'Create' }} Appointment</button>
                    <a href="{{ route('appointments.index') }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.dashboard>

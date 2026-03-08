<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-sm border border-gray-100">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">{{ $tenant->name }} Booking</h2>

    <!-- Success Message -->
    @if (session()->has('message') || $step === 4)
        <div class="bg-green-50 z-50 p-4 rounded-md border border-green-200 text-center">
            <h3 class="text-lg font-medium text-green-800 mb-2">Success!</h3>
            <p class="text-green-700">{{ session('message') ?? 'Your appointment is confirmed.' }}</p>
            <div class="mt-4">
                <button wire:click="$set('step', 1)" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Book another</button>
            </div>
        </div>
    @else

        <!-- Progress Bar -->
        <div class="mb-8 hidden sm:block">
            <div class="flex items-center justify-between">
                <div class="flex-1 flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $step >= 1 ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-600' }}">1</div>
                    <div class="text-xs mt-2 {{ $step >= 1 ? 'text-indigo-600 font-medium' : 'text-gray-500' }}">Service</div>
                </div>
                <div class="flex-1 h-1 {{ $step >= 2 ? 'bg-indigo-600' : 'bg-gray-200' }}"></div>
                <div class="flex-1 flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $step >= 2 ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-600' }}">2</div>
                    <div class="text-xs mt-2 {{ $step >= 2 ? 'text-indigo-600 font-medium' : 'text-gray-500' }}">Time</div>
                </div>
                <div class="flex-1 h-1 {{ $step >= 3 ? 'bg-indigo-600' : 'bg-gray-200' }}"></div>
                <div class="flex-1 flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $step >= 3 ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-600' }}">3</div>
                    <div class="text-xs mt-2 {{ $step >= 3 ? 'text-indigo-600 font-medium' : 'text-gray-500' }}">Details</div>
                </div>
            </div>
        </div>

        <!-- Step 1: Service & Staff -->
        @if ($step === 1)
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Service</label>
                    <select wire:model.live="service_id" class="mt-1 block w-full pl-3 pr-10 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Choose a service...</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }} ({{ $service->duration_minutes }} min) - ${{ number_format($service->price, 2) }}</option>
                        @endforeach
                    </select>
                    @error('service_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                @if($service_id && $staffMembers)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Professional</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($staffMembers as $staff)
                            <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none">
                                <input type="radio" wire:model.live="staff_id" name="staff" value="{{ $staff->id }}" class="sr-only" aria-labelledby="staff-{{ $staff->id }}">
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span id="staff-{{ $staff->id }}" class="block text-sm font-medium text-gray-900">{{ $staff->name }}</span>
                                    </span>
                                </span>
                                <!-- Selected Border -->
                                <span class="pointer-events-none absolute -inset-px rounded-lg border-2 {{ $staff_id == $staff->id ? 'border-indigo-600' : 'border-transparent' }}" aria-hidden="true"></span>
                            </label>
                        @endforeach
                    </div>
                    @error('staff_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                @endif
                
                <div class="pt-4 border-t border-gray-100 flex justify-end">
                    <button wire:click="nextStep" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none" {{ !$service_id || !$staff_id ? 'disabled opacity-50' : '' }}>
                        Continue
                    </button>
                </div>
            </div>
        @endif

        <!-- Step 2: Date & Time -->
        @if ($step === 2)
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pick a Date</label>
                    <input type="date" wire:model.live="date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Available Times</label>
                    @if(count($availableSlots) > 0)
                        <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                            @foreach($availableSlots as $slot)
                                @php $fullTime = $date . ' ' . $slot; @endphp
                                <button type="button" wire:click="selectSlot('{{ $slot }}')" class="py-2 px-3 border rounded-md text-sm font-medium hover:bg-indigo-50 {{ $start_time === $fullTime ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300' }}">
                                    {{ \Carbon\Carbon::parse($slot)->format('g:i A') }}
                                </button>
                            @endforeach
                        </div>
                    @else
                        <div class="text-sm text-gray-500 bg-gray-50 p-4 rounded text-center">No available slots on this date.</div>
                    @endif
                    @error('start_time') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="pt-4 border-t border-gray-100 flex justify-between">
                    <button wire:click="previousStep" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                        Back
                    </button>
                    <button wire:click="nextStep" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none" {{ !$start_time ? 'disabled opacity-50' : '' }}>
                        Continue
                    </button>
                </div>
            </div>
        @endif

        <!-- Step 3: Customer Details & Finalize -->
        @if ($step === 3)
            <div class="space-y-6">
                <div class="bg-gray-50 p-4 rounded-md mb-6">
                    <h4 class="font-medium text-gray-900 mb-1">Appointment Summary</h4>
                    <p class="text-sm text-gray-600">Service: <strong>{{ \App\Models\Service::find($service_id)->name }}</strong></p>
                    <p class="text-sm text-gray-600">Time: <strong>{{ \Carbon\Carbon::parse($start_time)->format('l, F j, Y g:i A') }}</strong></p>
                    <p class="text-sm text-gray-600 mt-2">Price: <strong class="text-indigo-600">${{ number_format(\App\Models\Service::find($service_id)->price, 2) }}</strong></p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" wire:model="first_name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('first_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" wire:model="last_name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" wire:model="email" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Phone (Optional)</label>
                        <input type="text" wire:model="phone" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 flex justify-between items-center">
                    <button wire:click="previousStep" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                        Back
                    </button>
                    
                    <button wire:click="submit" wire:loading.attr="disabled" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none">
                        <span wire:loading.remove wire:target="submit">
                            {{ \App\Models\Service::find($service_id)->price > 0 ? 'Proceed to Payment' : 'Confirm Booking' }}
                        </span>
                        <span wire:loading wire:target="submit">Processing...</span>
                    </button>
                </div>
            </div>
        @endif
    @endif
</div>

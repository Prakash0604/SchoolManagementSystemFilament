<div class="flex flex-col md:flex-row gap-6">
    {{-- Sidebar: Set all --}}
    <div class="w-full md:w-1/4">
        <div class="p-4 bg-white shadow rounded border">
            <h2 class="font-bold mb-3">Set All</h2>
            <x-filament::button color="success" wire:click="setAll('present')">Present</x-filament::button>
            <x-filament::button color="danger" class="mt-2" wire:click="setAll('absent')">Absent</x-filament::button>
            <x-filament::button color="warning" class="mt-2" wire:click="setAll('leave')">On Leave</x-filament::button>
        </div>
    </div>

    {{-- Student List --}}
    <div class="w-full md:w-3/4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($students as $student)
                <div class="p-4 bg-white shadow border rounded space-y-2">
                    <p class="font-semibold text-gray-800">{{ $student->full_name }}</p>

                    {{-- Radio Buttons --}}
                    <div class="flex flex-col gap-1">
                        <label class="flex items-center gap-2">
                            <input type="radio"
                                wire:model="form.attendance_data.{{ $student->id }}.type"
                                value="present">
                            <span>Present</span>
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="radio"
                                wire:model="form.attendance_data.{{ $student->id }}.type"
                                value="absent">
                            <span>Absent</span>
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="radio"
                                wire:model="form.attendance_data.{{ $student->id }}.type"
                                value="leave">
                            <span>On Leave</span>
                        </label>
                    </div>

                    {{-- Hidden student_id (optional) --}}
                    <input type="hidden"
                        wire:model="form.attendance_data.{{ $student->id }}.student_id"
                        value="{{ $student->id }}">
                </div>
            @endforeach
        </div>
    </div>
</div>

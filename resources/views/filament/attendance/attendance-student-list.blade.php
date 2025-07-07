<div class="flex">
    <main class="w-full p-6 space-y-4 bg-black">
        <!-- Navbar -->
        <div class="flex justify-center gap-4">
            <button wire:click="setAll('present')" class="px-4 py-2 bg-green-600 text-gray-600 rounded">Present</button>
            <button wire:click="setAll('absent')" class="px-4 py-2 bg-red-600 text-gray-600 rounded">Absent</button>
            <button wire:click="setAll('leave')" class="px-4 py-2 bg-yellow-500 text-gray-600 rounded">On Leave</button>
        </div>

        <!-- Students -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($students as $student)
                <div class="p-4 bg-gray-600 shadow border rounded space-y-3">
                    <p class="font-semibold text-gray-900 text-sm">{{ $student['full_name'] }}</p>

                    <div class="flex flex-col gap-2 text-sm">
                        <label class="flex items-center gap-2">
                            <input type="radio"
                                wire:model="attendance_data.{{ $student['id'] }}.type"
                                value="Present"
                                class="text-green-600">
                            <span>Present</span>
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="radio"
                                wire:model="attendance_data.{{ $student['id'] }}.type"
                                value="Absent"
                                class="text-red-600">
                            <span>Absent</span>
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="radio"
                                wire:model="attendance_data.{{ $student['id'] }}.type"
                                value="On Leave"
                                class="text-yellow-500">
                            <span>On Leave</span>
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    </main>
</div>

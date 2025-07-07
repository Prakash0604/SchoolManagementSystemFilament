<div class="overflow-x-auto w-full">
    <table class="w-full text-sm text-left border border-gray-200 dark:border-gray-700 rounded-md overflow-hidden">
        <thead class="bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200">
            <tr>
                <th class="px-4 py-2">Grade</th>
                <th class="px-4 py-2">Section</th>
                <th class="px-4 py-2">Subject</th>
                <th class="px-4 py-2 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($getState() as $item)
                <tr class="border-b border-gray-100 dark:border-gray-700">
                    <td class="px-4 py-2">{{ $item->grade->name ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $item->section->name ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $item->subject->name ?? '-' }}</td>
                    <td class="px-4 py-2 text-right">
                        <form wire:submit.prevent="deleteSubject({{ $item->id }})">
                            <x-filament::button
                                type="submit"
                                color="danger"
                                size="sm"
                                icon="heroicon-o-trash"
                            >
                                Remove
                            </x-filament::button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-2 text-center text-gray-500 dark:text-gray-400">
                        No subjects assigned.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

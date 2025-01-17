<x-layout.app>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Patients') }}
            </h2>
            @php
                $role = auth()->user()->role;
            @endphp
            @if($role !== 'dentist')
                <a href="{{ route($role . '.patients.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Add New Patient
                </a>
            @endif
        </div>
    </x-slot>

    <x-card>
        <!-- Search and Filters -->
        <div class="mb-6">
            <form method="GET" action="{{ route($role . '.patients.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Patients</label>
                        <input type="text" name="search" id="search" 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Search by name or contact..."
                            value="{{ request('search') }}">
                    </div>
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                        <select name="gender" id="gender" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">All</option>
                            <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <div>
                            <label for="min_age" class="block text-sm font-medium text-gray-700">Min Age</label>
                            <input type="number" name="min_age" id="min_age" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                value="{{ request('min_age') }}" min="0" max="150">
                        </div>
                        <div>
                            <label for="max_age" class="block text-sm font-medium text-gray-700">Max Age</label>
                            <input type="number" name="max_age" id="max_age" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                value="{{ request('max_age') }}" min="0" max="150">
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Patients Table -->
        <x-table.table :headers="['Name', 'Contact', 'Gender', 'Date of Birth', 'Actions']">
            @forelse($patients as $patient)
                <x-table.row :striped="$loop->even">
                    <x-table.cell>{{ $patient->patient_name }}</x-table.cell>
                    <x-table.cell>{{ $patient->contact_information }}</x-table.cell>
                    <x-table.cell>{{ ucfirst($patient->gender) }}</x-table.cell>
                    <x-table.cell>{{ $patient->date_of_birth->format('M d, Y') }}</x-table.cell>
                    <x-table.cell>
                        <div class="flex space-x-2">
                            <a href="{{ route($role . '.patients.show', $patient) }}" class="text-blue-500 hover:text-blue-700">
                                View
                            </a>
                            @if($role !== 'dentist')
                                <a href="{{ route($role . '.patients.edit', $patient) }}" class="text-yellow-500 hover:text-yellow-700">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route($role . '.patients.destroy', $patient) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure you want to delete this patient?')">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                    </x-table.cell>
                </x-table.row>
            @empty
                <x-table.row>
                    <x-table.cell colspan="5" class="text-center">No patients found.</x-table.cell>
                </x-table.row>
            @endforelse
        </x-table.table>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $patients->links() }}
        </div>
    </x-card>
</x-layout.app> 
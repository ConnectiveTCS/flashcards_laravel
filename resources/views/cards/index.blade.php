<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Cards') }}
            </h2>
            <a href="{{ route('cards.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-indigo-700 transition transform hover:scale-105 duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Add Card
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 space-y-8">

            {{-- Statistics --}}
            <div class="grid grid-cols-3 gap-4 sm:gap-6">
                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 p-4 sm:p-6 text-center">
                    <div class="text-2xl font-bold text-indigo-700">{{ $totalCards ?? '-' }}</div>
                    <div class="text-gray-500 text-sm sm:text-base">Total Cards</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 p-4 sm:p-6 text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $correctCards ?? '-' }}</div>
                    <div class="text-gray-500 text-sm sm:text-base">Marked Correct</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 p-4 sm:p-6 text-center">
                    <div class="text-2xl font-bold text-yellow-500">{{ $bookmarkedCards ?? '-' }}</div>
                    <div class="text-gray-500 text-sm sm:text-base">Bookmarked</div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                {{-- Search --}}
                <form method="GET" action="{{ route('cards.search') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full md:w-auto">
                    <input type="text" name="query" placeholder="Search cards..." value="{{ request('query') }}"
                        class="rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm px-3 py-2 w-full sm:w-56 transition-all duration-300" />
                    <button type="submit" class="bg-indigo-500 text-white px-3 py-2 rounded-lg hover:bg-indigo-600 text-sm w-full sm:w-auto transition transform hover:scale-105 duration-200 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Search
                    </button>
                </form>
                
                {{-- Filter --}}
                <form method="GET" action="{{ route('cards.filter') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full md:w-auto">
                    <select name="category" class="rounded-lg border-gray-300 text-sm px-2 py-2 w-full sm:w-auto focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Categories</option>
                        @foreach(\App\Models\Card::select('category')->distinct()->pluck('category') as $cat)
                            <option value="{{ $cat }}" @if(request('category') == $cat) selected @endif>{{ $cat }}</option>
                        @endforeach
                    </select>
                    <select name="difficulty" class="rounded-lg border-gray-300 text-sm px-2 py-2 w-full sm:w-auto focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Difficulties</option>
                        @foreach(\App\Models\Card::select('difficulty')->distinct()->pluck('difficulty') as $diff)
                            <option value="{{ $diff }}" @if(request('difficulty') == $diff) selected @endif>{{ $diff }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-indigo-500 text-white px-3 py-2 rounded-lg hover:bg-indigo-600 text-sm w-full sm:w-auto transition transform hover:scale-105 duration-200 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>
                </form>
                
                {{-- Import/Export --}}
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full md:w-auto">
                    <form method="POST" action="{{ route('cards.import') }}" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full sm:w-auto">
                        @csrf
                        <div class="relative">
                            <input type="file" name="csv_file" accept=".csv" class="text-sm w-full sm:w-auto" required>
                        </div>
                        <button type="submit" class="bg-green-500 text-white px-3 py-2 rounded-lg hover:bg-green-600 text-sm w-full sm:w-auto transition transform hover:scale-105 duration-200 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                            </svg>
                            Import
                        </button>
                    </form>
                </div>
            </div>

            {{-- Table Management Actions --}}
            <div class="flex flex-wrap md:flex-nowrap justify-between items-center gap-3 my-4">
                <div class="flex items-center">
                    <input type="checkbox" id="select-all" class="rounded border-gray-300 text-indigo-600 mr-2 focus:ring-indigo-500 transition-opacity duration-200">
                    <label for="select-all" class="text-sm text-gray-700 select-none">Select All</label>
                </div>
                <div class="flex gap-2 w-full md:w-auto">
                    <button type="button" id="delete-selected" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-red-700 hover:shadow-md transition transform hover:scale-105 duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none w-full md:w-auto" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Delete Selected
                    </button>
                    <a href="{{ route('cards.export') }}" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-green-700 hover:shadow-md transition transform hover:scale-105 duration-200 w-full md:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        Export to CSV
                    </a>
                </div>
            </div>

            {{-- Flashcards Table with Bulk Delete Form --}}
            <form id="bulk-delete-form" method="POST" action="{{ route('cards.bulkDelete') }}">
                @csrf
                @method('DELETE')
                <div class="bg-white rounded-xl shadow-md border border-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gradient-to-r from-indigo-50 to-blue-50 border-b border-gray-200">
                                    <th class="px-4 py-3 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span>Select</span>
                                        </div>
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider whitespace-nowrap">Module</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider whitespace-nowrap">Topic</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider whitespace-nowrap">Question</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider whitespace-nowrap">Answer</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider whitespace-nowrap">Category</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider whitespace-nowrap">Difficulty</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-indigo-700 uppercase tracking-wider whitespace-nowrap">Correct</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-indigo-700 uppercase tracking-wider whitespace-nowrap">Bookmark</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-indigo-700 uppercase tracking-wider whitespace-nowrap">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($cards as $card)
                                    <tr class="hover:bg-blue-50 group transition-colors duration-150 ease-in-out">
                                        <td class="px-4 py-3 text-center">
                                            <input type="checkbox" name="card_ids[]" value="{{ $card->id }}" class="card-checkbox rounded border-gray-300 text-indigo-600">
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 whitespace-nowrap">{{ $card->module }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 whitespace-nowrap">{{ $card->topic }}</td>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900 group-hover:text-indigo-700 transition-colors whitespace-nowrap">{{ $card->question }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">{{ $card->answer }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">{{ $card->category }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">{{ $card->difficulty }}</td>
                                        <td class="px-4 py-3 text-center whitespace-nowrap">
                                            <form method="POST" action="{{ route('cards.correct', $card->id) }}">
                                                @csrf
                                                <button type="submit" title="Toggle Correct" class="focus:outline-none">
                                                    @if($card->is_correct)
                                                        <span class="inline-block w-6 h-6 text-green-600">&#10003;</span>
                                                    @else
                                                        <span class="inline-block w-6 h-6 text-gray-300">&#10003;</span>
                                                    @endif
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-4 py-3 text-center whitespace-nowrap">
                                            <form method="POST" action="{{ route('cards.bookmark', $card->id) }}">
                                                @csrf
                                                <button type="submit" title="Toggle Bookmark" class="focus:outline-none">
                                                    @if($card->is_bookmarked)
                                                        <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v12l7-4 7 4V5a2 2 0 00-2-2H5z"/></svg>
                                                    @else
                                                        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v12l7-4 7 4V5a2 2 0 00-2-2H5z"/></svg>
                                                    @endif
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-4 py-3 text-center whitespace-nowrap">
                                            <div class="flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-2">
                                                <a href="{{ route('cards.show', $card->id) }}" class="text-blue-600 hover:underline text-xs">View</a>
                                                <a href="{{ route('cards.edit', $card->id) }}" class="text-indigo-600 hover:underline text-xs">Edit</a>
                                                <form method="POST" action="{{ route('cards.destroy', $card->id) }}" onsubmit="return confirm('Delete this card?')" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:underline text-xs ml-0 sm:ml-1">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="px-6 py-10 text-center text-gray-500 italic">
                                            No flashcards available
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
            
            {{-- JavaScript for Bulk Delete Functionality --}}
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const selectAllCheckbox = document.getElementById('select-all');
                    const cardCheckboxes = document.querySelectorAll('.card-checkbox');
                    const deleteSelectedButton = document.getElementById('delete-selected');
                    const bulkDeleteForm = document.getElementById('bulk-delete-form');
                    
                    // Function to update delete button state
                    function updateDeleteButtonState() {
                        const checkedBoxes = document.querySelectorAll('.card-checkbox:checked').length;
                        deleteSelectedButton.disabled = checkedBoxes === 0;
                    }
                    
                    // Select all functionality
                    selectAllCheckbox.addEventListener('change', function() {
                        cardCheckboxes.forEach(checkbox => {
                            checkbox.checked = selectAllCheckbox.checked;
                        });
                        updateDeleteButtonState();
                    });
                    
                    // Update select all checkbox when individual checkboxes change
                    cardCheckboxes.forEach(checkbox => {
                        checkbox.addEventListener('change', function() {
                            const allChecked = document.querySelectorAll('.card-checkbox:checked').length === cardCheckboxes.length;
                            const anyChecked = document.querySelectorAll('.card-checkbox:checked').length > 0;
                            selectAllCheckbox.checked = allChecked;
                            selectAllCheckbox.indeterminate = anyChecked && !allChecked;
                            updateDeleteButtonState();
                        });
                    });
                    
                    // Delete selected cards
                    deleteSelectedButton.addEventListener('click', function() {
                        const selectedCount = document.querySelectorAll('.card-checkbox:checked').length;
                        if(confirm(`Are you sure you want to delete ${selectedCount} selected card(s)?`)) {
                            // Add visual feedback
                            deleteSelectedButton.disabled = true;
                            deleteSelectedButton.innerHTML = '<svg class="animate-spin h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Deleting...';
                            bulkDeleteForm.submit();
                        }
                    });
                    
                    // Initial state update
                    updateDeleteButtonState();
                });
            </script>
        </div>
    </div>
</x-app-layout>
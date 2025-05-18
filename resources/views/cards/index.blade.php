<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Cards') }}
            </h2>
            <a href="{{ route('cards.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-indigo-700 transition">
                + Add Card
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 space-y-8">

            {{-- Statistics --}}
            <div class="flex flex-row gap-4 sm:gap-6">
                <div class=" flex-1 bg-white rounded-lg shadow p-4 sm:p-6 text-center">
                    <div class="text-2xl font-bold text-indigo-700">{{ $totalCards ?? '-' }}</div>
                    <div class="text-gray-500">Total Cards</div>
                </div>
                <div class="flex-1 bg-white rounded-lg shadow p-4 sm:p-6 text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $correctCards ?? '-' }}</div>
                    <div class="text-gray-500">Marked Correct</div>
                </div>
                <div class="flex-1 bg-white rounded-lg shadow p-4 sm:p-6 text-center">
                    <div class="text-2xl font-bold text-yellow-500">{{ $bookmarkedCards ?? '-' }}</div>
                    <div class="text-gray-500">Bookmarked</div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                {{-- Search --}}
                <form method="GET" action="{{ route('cards.search') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full md:w-auto">
                    <input type="text" name="query" placeholder="Search cards..." value="{{ request('query') }}"
                        class="rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm px-3 py-2 w-full sm:w-56" />
                    <button type="submit" class="bg-indigo-500 text-white px-3 py-2 rounded-lg hover:bg-indigo-600 text-sm w-full sm:w-auto">Search</button>
                </form>
                {{-- Filter --}}
                <form method="GET" action="{{ route('cards.filter') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full md:w-auto">
                    <select name="category" class="rounded-lg border-gray-300 text-sm px-2 py-2 w-full sm:w-auto">
                        <option value="">All Categories</option>
                        @foreach(\App\Models\Card::select('category')->distinct()->pluck('category') as $cat)
                            <option value="{{ $cat }}" @if(request('category') == $cat) selected @endif>{{ $cat }}</option>
                        @endforeach
                    </select>
                    <select name="difficulty" class="rounded-lg border-gray-300 text-sm px-2 py-2 w-full sm:w-auto">
                        <option value="">All Difficulties</option>
                        @foreach(\App\Models\Card::select('difficulty')->distinct()->pluck('difficulty') as $diff)
                            <option value="{{ $diff }}" @if(request('difficulty') == $diff) selected @endif>{{ $diff }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-indigo-500 text-white px-3 py-2 rounded-lg hover:bg-indigo-600 text-sm w-full sm:w-auto">Filter</button>
                </form>
                {{-- Import/Export --}}
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full md:w-auto">
                    <form method="POST" action="{{ route('cards.import') }}" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full sm:w-auto">
                        @csrf
                        <input type="file" name="csv_file" accept=".csv" class="text-sm w-full sm:w-auto" required>
                        <button type="submit" class="bg-green-500 text-white px-3 py-2 rounded-lg hover:bg-green-600 text-sm w-full sm:w-auto">Import CSV</button>
                    </form>
                    <a href="{{ route('cards.export') }}" class="bg-blue-500 text-white px-3 py-2 rounded-lg hover:bg-blue-600 text-sm text-center w-full sm:w-auto">Export CSV</a>
                </div>
            </div>

            {{-- Export Button Above Table --}}
            <div class="flex justify-end mb-4">
                <a href="{{ route('cards.export') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-green-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    Export Cards to CSV
                </a>
            </div>

            {{-- Flashcards Table --}}
            <div class="bg-white rounded-xl shadow-md border border-gray-100 mt-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
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
                                    <td colspan="9" class="px-6 py-10 text-center text-gray-500 italic">
                                        No flashcards available
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
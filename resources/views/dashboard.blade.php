<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Flippable Flashcard -->
            <div 
                x-data="{ flipped: false }" 
                class="flex flex-col items-center justify-center min-h-[30rem] py-8"
            >
                <h1 class="text-3xl font-bold mb-6 text-gray-800 drop-shadow-lg">Flashcard Demo</h1>
                <div class="perspective w-full max-w-md h-72 mb-6">
                    <div 
                        class="relative w-full h-full transition-transform duration-500 cursor-pointer"
                        :class="{ '[transform:rotateY(180deg)]': flipped }"
                        style="transform-style: preserve-3d;"
                        @click="flipped = !flipped"
                    >
                        <!-- Front -->
                        <div class="absolute w-full h-full bg-white rounded-xl shadow-xl flex items-center justify-center text-2xl font-semibold text-gray-800 backface-hidden p-6"
                             style="backface-visibility: hidden;">
                            What is the capital of France?
                        </div>
                        <!-- Back -->
                        <div class="absolute w-full h-full bg-indigo-500 rounded-xl shadow-xl flex items-center justify-center text-2xl font-semibold text-white backface-hidden p-6"
                             style="backface-visibility: hidden; transform: rotateY(180deg);">
                            Paris
                        </div>
                    </div>
                </div>
                <div class="text-gray-600">Click the card to flip</div>
            </div>
            <!-- Tailwind custom class for 3D flip -->
            <style>
                .perspective { perspective: 1000px; }
            </style>
        </div>
    </div>
</x-app-layout>

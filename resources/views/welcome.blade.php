<x-guest-layout>
    <div class="flex items-center justify-center text-black/50">
        <a href="{{ url('questions') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
            Sepsis-Test durchführen
        </a>
    </div>
    <div class="flex items-center justify-center text-black/50 mt-4">
        <a href="{{ route('symptom.form') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
            SymptomCheck durchführen
        </a>
    </div>
</x-guest-layout>

<x-guest-layout>
    <div class="p-6">
        @if ($category === 'yellow')
            <div class="bg-yellow-200 border-l-4 border-yellow-500 text-yellow-700 p-4">
                <h2 class="font-bold text-2xl">Ärztliche Abklärung notwendig</h2>
                <p>Rufen Sie den Hausarzt oder den ärztlichen Bereitschaftsdienst 116 117</p>
            </div>
        @elseif ($category === 'red')
            <div class="bg-red-200 border-l-4 border-red-500 text-red-700 p-4">
                <h2 class="font-bold text-2xl">Eine Sepsis ist hochwahrscheinlich</h2>
                <p>Rufen Sie umgehend den NOTRUF 112</p>
            </div>
        @elseif ($category === 'green')
            <div class="bg-green-200 border-l-4 border-green-500 text-green-700 p-4">
                <h2 class="font-bold text-2xl">Beobachten</h2>
                <p>Beobachten Sie die Symptome einige Stunden und führen Sie bei Veränderung den Test erneut durch</p>
            </div>
        @else
            <div class="bg-gray-200 border-l-4 border-gray-500 text-gray-700 p-4">
                <h2 class="font-bold text-2xl">Kein Anzeichen</h2>
                <p>Kein Anzeichen für eine Sepsis</p>
            </div>
        @endif
        <a href="{{ url('questions') }}"
           class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Test erneut durchführen</a>
    </div>
</x-guest-layout>

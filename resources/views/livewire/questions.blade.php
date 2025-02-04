<div class="text-[#546E4F]">
        <img src="{{ asset('img/icons/' . $question['icon']) }}" alt="Icon" class="h-20 mr-2">
        <h2 class="text-xl font-semibold mb-4 flex items-center">
            <span class="mr-2">{{ $question['text'] }}</span>
            <span class="w-4 h-4 rounded-full
                @if($question['criticality'] == 1) bg-green-500
                @elseif($question['criticality'] == 2) bg-yellow-500
                @elseif($question['criticality'] == 3) bg-red-500
                @endif">
            </span>
        </h2>

        <div class="mb-4">
            <label class="block mb-2">
                <input type="radio" value="yes"
                       wire:model.boolean.change="currentAnswer">
                Trifft zu
            </label>
            <label class="block">
                <input type="radio" value="no"
                       wire:model.boolean.change="currentAnswer">
                Trifft nicht zu
            </label>
        </div>

        <div class="flex justify-between">
            @if ($currentQuestionIndex > 0)
                <button wire:click="previousQuestion" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Zurück
                </button>
            @else
                <div></div>
            @endif

            @if(!is_null($currentAnswer))
            <button wire:click="nextQuestion" class="bg-[#546E4F] text-white px-4 py-2 rounded hover:bg-green-700">
                Weiter
            </button>
            @endif
        </div>
    </div>

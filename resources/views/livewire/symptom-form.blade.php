<div class="max-w-xl mx-auto py-10 text-[#546E4F]">
    @if ($phase === 'gender')
    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-4">Bitte wählen Sie Ihr Geschlecht</h2>

        <div class="mb-4">
            <label class="block mb-2 cursor-pointer">
                <input type="radio" name="gender" value="male" wire:model.change="gender" class="mr-2">
                Männlich
            </label>
            <label class="block cursor-pointer">
                <input type="radio" name="gender" value="female" wire:model.change="gender" class="mr-2">
                Weiblich
            </label>
        </div>

        <div class="flex justify-end">
            @if($gender)
                <button wire:click="startSymptomForm" class="bg-[#546E4F] text-white px-4 py-2 rounded hover:bg-green-700">
                    Weiter
                </button>
            @endif
        </div>
    </div>
    @elseif ($phase === 'questions' || $phase === 'immediate')

    @if ($this->getCurrentLevel())
    <div class="mb-4 p-3 rounded flex items-center" style="background-color: {{ $this->getLevelColor() }}">
        <span class="w-4 h-4 rounded-full bg-white mr-2"></span>
        <div class="flex-1">
            <strong class="text-white">{{ $this->getCurrentLevel()->name }}</strong>
            <div class="mt-2 w-full bg-white h-2 rounded">
                <div class="h-2 rounded bg-black" style="width: {{ $this->getProgressPercentage() }}%"></div>
            </div>
        </div>
    </div>
    @endif

    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-3">{{ $currentQuestion->text }}</h2>
        <div class="space-x-4">
            <button wire:click="answer('yes')" class="px-4 py-2 text-white rounded" style="background-color: {{ $this->getLevelColor() }}">Yes</button>
            <button wire:click="answer('no')" class="px-4 py-2 bg-gray-600 text-white rounded">No</button>
        </div>
    </div>

    <div class="flex justify-between items-center">
        @if (count($history) > 0)
        <button wire:click="previousQuestion" class="text-sm underline" style="color: {{ $this->getLevelColor() }}">← Back</button>
        @else
        <div></div>
        @endif

        <button wire:click="resetQuestions" class="text-sm text-gray-600 underline">Start Over</button>
    </div>
    @elseif ($phase === 'result')
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Outcome</h2>
        <p class="mb-6">{{ $outcome }}</p>
        <button wire:click="resetQuestions" class="inline-block px-4 py-2 text-white rounded" style="background-color: {{ $this->getLevelColor() }}">Start Over</button>
    </div>
    @endif
</div>

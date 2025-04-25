<div class="w-full sm:max-w-md">

    @if ($this->getCurrentLevel() && $phase !== 'result')
    <div class="mt-6 px-6 py-4 bg-{{ $this->getLevelColor() }}-600 shadow-md overflow-hidden sm:rounded-lg">
        <div class="flex-1">
            <strong class="text-white">Fragenkatalog: {{ $this->getCurrentLevel()->name }}</strong>
            <div class="mt-2 w-full bg-{{ $this->getLevelColor() }}-400 h-2 rounded">
                <div class="h-2 rounded bg-{{ $this->getLevelColor() }}-100" style="width: {{ $this->getProgressPercentage() }}%"></div>
            </div>
        </div>
    </div>
    @endif


    @if ($phase === 'result')
    <div class="bg-{{ $this->getLevelColor() }}-600 text-white p-6 rounded shadow mt-4">
        <h2 class="text-2xl font-bold mb-4">{{ __('frontend.outcome') }}</h2>
        <p class="mb-6">{{ $outcome }}</p>
        <button wire:click="resetQuestions" class="px-4 py-2 text-gray-600 rounded bg-{{ $this->getLevelColor() }}-300 hover:bg-{{ $this->getLevelColor() }}-400">
            {{ __('frontend.reset') }}
        </button>
    </div>

    @else
    <div class="mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <div class="max-w-xl mx-auto py-10 text-[#546E4F]">
            @if ($phase === 'gender')
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">{{ __('frontend.chooseGender') }}</h2>

                <div class="mb-4">
                    <label class="block mb-2 cursor-pointer">
                        <input type="radio" name="gender" value="male" wire:model.live="gender" class="mr-2">
                        {{ __('frontend.male') }}
                    </label>
                    <label class="block cursor-pointer">
                        <input type="radio" name="gender" value="female" wire:model.live="gender" class="mr-2">
                        {{ __('frontend.female') }}
                    </label>
                </div>

                <div class="flex justify-end">
                    @if($gender)
                    <button wire:click="startSymptomForm"
                            class="px-4 py-2 text-white rounded bg-emerald-500 hover:bg-emerald-600">
                        {{ __('frontend.next') }}
                    </button>
                    @endif
                </div>
            </div>
            @elseif ($phase === 'questions' || $phase === 'immediate')


            @if ($this->hasIcon())
            <div class="flex justify-left">
                <div class="w-20 h-20">
                    <img src="{{ $this->getIconUrl() }}" alt="Question Icon" class="w-full h-full object-contain">
                </div>
            </div>
            @endif

            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">{{ $currentQuestion->text }}</h2>

                <div class="mb-4">
                    <label class="block mb-2 cursor-pointer">
                        <input type="radio" name="answer" value="yes" wire:model.live="currentAnswer" class="mr-2">
                        {{ __('frontend.yes') }}
                    </label>
                    <label class="block cursor-pointer">
                        <input type="radio" name="answer" value="no" wire:model.live="currentAnswer" class="mr-2">
                        {{ __('frontend.no') }}
                    </label>
                </div>
            </div>

            <div class="flex justify-between items-center">
                @if (count($history) > 0)
                <button wire:click="previousQuestion" class="px-4 py-2 text-white rounded bg-emerald-500 hover:bg-emerald-600">
                    {{ __('frontend.previous') }}
                </button>
                @else
                <button wire:click="resetQuestions" class="px-4 py-2 text-white rounded bg-emerald-500 hover:bg-emerald-600">
                    {{ __('frontend.previous') }}
                </button>
                @endif
                @if($currentAnswer)
                <button wire:click="answer()"
                        class="px-4 py-2 text-white rounded bg-emerald-500 hover:bg-emerald-600">
                    {{ __('frontend.next') }}
                </button>
                @endif
            </div>
            @endif
        </div>
    </div>
    @endif

</div>

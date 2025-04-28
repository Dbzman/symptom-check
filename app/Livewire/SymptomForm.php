<?php

namespace App\Livewire;

use App\Models\CriticalityLevel;
use App\Models\Outcome;
use App\Models\Question;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class SymptomForm extends Component
{
    public string $gender = '';
    public array $answers = [];
    public string $phase = 'gender';
    public ?string $outcome = null;
    public ?string $currentAnswer = null;

    public $currentQuestion;
    public $questionQueue = [];
    public $history = [];

    public function mount()
    {
        $this->loadSession();

        if ($this->phase === 'questions' && !$this->currentQuestion) {
            $this->loadQuestions();
        }
    }

    public function getCurrentLevel()
    {
        return $this->currentQuestion?->criticalityLevel;
    }

    public function getLevelColor()
    {
        return $this->getCurrentLevel()?->color ?? 'gray';
    }

    public function hasIcon()
    {
        return !empty($this->currentQuestion?->icon);
    }

    public function getIconUrl()
    {
        if (!$this->hasIcon()) {
            return null;
        }

        return Storage::url($this->currentQuestion->icon);
    }

    public function getNextButtonColor()
    {
        // Use the criticality level color for the Next button
        return $this->getLevelColor();
    }

    public function getCurrentLevelId()
    {
        return $this->getCurrentLevel()?->id;
    }

    public function getTotalInLevel()
    {
        $currentLevelId = $this->getCurrentLevelId();
        return collect($this->questionQueue)
            ->filter(fn($q) => $q->criticality_level_id === $currentLevelId)
            ->count() +
            collect($this->history)
            ->filter(fn($q) => $q->criticality_level_id === $currentLevelId)
            ->count() +
            ($this->currentQuestion?->criticality_level_id === $currentLevelId ? 1 : 0);
    }

    public function getAnsweredInLevel()
    {
        $currentLevelId = $this->getCurrentLevelId();
        return collect($this->history)
            ->filter(fn($q) => $q->criticality_level_id === $currentLevelId)
            ->count();
    }

    public function evaluateResultForLevel($levelId)
    {
        if (!$this->outcome) {
            // Find questions answered 'yes' for the specified level
            // Also include questions with reverse_meaning=true that were answered 'no'
            $levelYesAnswers = collect($this->answers)
                ->map(function($answer, $questionId) {
                    return [
                        'answer' => $answer,
                        'question' => Question::with('criticalityLevel')->find($questionId)
                    ];
                })
                ->filter(function($item) {
                    if (!$item['question']) return false;

                    // If reverse_meaning is true, then 'no' counts as a positive answer
                    if ($item['question']->reverse_meaning) {
                        return $item['answer'] === 'no';
                    }

                    // Otherwise, 'yes' counts as a positive answer
                    return $item['answer'] === 'yes';
                })
                ->map(fn($item) => $item['question'])
                ->filter(fn($q) => $q->criticality_level_id === $levelId);

            // Get the first 'yes' answered question for this level
            $relevantQuestion = $levelYesAnswers->first();

            if ($relevantQuestion) {
                // Find and set the appropriate outcome for this level and disease
                $this->outcome = Outcome::where('criticality_level_id', $levelId)
                    ->where('disease_id', $relevantQuestion->disease_id)
                    ->first()?->description;
            }
        }

        $this->saveSession();
    }

    public function startSymptomForm()
    {
        $this->phase = 'questions';
        $this->loadQuestions();
        $this->saveSession();
    }

    public function getProgressPercentage()
    {
        $total = $this->getTotalInLevel();
        if ($total === 0) {
            return 2;
        }

        $percentage = round(($this->getAnsweredInLevel() / $total) * 100);
        return max(2, $percentage); // Return at least 10%
    }

    public function updatedGender($value)
    {
        $this->saveSession();
    }

    public function loadQuestions()
    {
        $this->phase = 'questions';

        $questions = Question::with('criticalityLevel')
            ->when($this->gender, fn($q) => $q->where(function ($query) {
                $query->whereNull('gender')->orWhere('gender', $this->gender);
            }))
            ->get();

        $grouped = $questions->groupBy('criticality_level_id');
        // Order by sort_order field and then by question's sort_order if available
        $levels = CriticalityLevel::orderBy('sort_order')->get();

        foreach ($levels as $level) {
            if ($grouped->has($level->id)) {
                // Instead of shuffling, order by sort_order if available, or id as fallback
                $levelQuestions = $grouped[$level->id]->sortBy([
                    ['sort_order', 'asc'],
                    ['id', 'asc']
                ]);
                foreach ($levelQuestions as $q) {
                    $this->questionQueue[] = $q;
                }
            }
        }

        $this->nextQuestion();
    }

    public function nextQuestion()
    {
        if ($this->currentQuestion) {
            if (is_array($this->history)) {
                $this->history[] = $this->currentQuestion;
            } else {
                $this->history->push($this->currentQuestion);
            }
        }

        if ($this->questionQueue && count($this->questionQueue) > 0) {
            $oldLevelId = $this->getCurrentLevelId();

            if (is_array($this->questionQueue)) {
                $this->currentQuestion = array_shift($this->questionQueue);
            } else {
                // When it's a collection, get the first item and remove it
                $this->currentQuestion = $this->questionQueue->first();
                $this->questionQueue = $this->questionQueue->slice(1);
            }

            // If we're moving to a new level, check if there were any 'yes' answers in the previous level
            $newLevelId = $this->getCurrentLevelId();
            if ($oldLevelId && $oldLevelId !== $newLevelId) {
                // Look for 'yes' answers in the level we just completed
                if (collect($this->answers)
                    ->map(function($answer, $questionId) {
                        return [
                            'answer' => $answer,
                            'question' => Question::with('criticalityLevel')->find($questionId)
                        ];
                    })
                    ->filter(function($item) {
                        if (!$item['question']) return false;

                        // If reverse_meaning is true, then 'no' counts as a positive answer
                        if ($item['question']->reverse_meaning) {
                            return $item['answer'] === 'no';
                        }

                        // Otherwise, 'yes' counts as a positive answer
                        return $item['answer'] === 'yes';
                    })
                    ->map(fn($item) => $item['question'])
                    ->filter(fn($q) => $q->criticality_level_id === $oldLevelId)
                    ->isNotEmpty()) {

                    // Put the question back in the queue
                    if (is_array($this->questionQueue)) {
                        array_unshift($this->questionQueue, $this->currentQuestion);
                    } else {
                        $this->questionQueue = collect([$this->currentQuestion])->concat($this->questionQueue);
                    }

                    // Set the current question to null as we're moving to results
                    $this->currentQuestion = null;
                    $this->phase = 'result';
                    $this->evaluateResultForLevel($oldLevelId);
                    $this->saveSession();
                    return;
                }
            }

            // Reset current answer for new question
            $this->currentAnswer = null;
        } else {
            $this->phase = 'result';
            $this->evaluateResult();
        }

        $this->saveSession();
    }

    public function previousQuestion()
    {
        if (count($this->history) > 0) {
            if (is_array($this->questionQueue)) {
                array_unshift($this->questionQueue, $this->currentQuestion);
            } else {
                // When it's a collection, prepend the current question
                $this->questionQueue = collect([$this->currentQuestion])->concat($this->questionQueue);
            }

            if (is_array($this->history)) {
                $this->currentQuestion = array_pop($this->history);
            } else {
                // When it's a collection, get the last item and remove it
                $this->currentQuestion = $this->history->last();
                $this->history = $this->history->slice(0, -1);
            }

            // Restore previous answer if available
            $this->currentAnswer = $this->answers[$this->currentQuestion->id] ?? null;
            unset($this->answers[$this->currentQuestion->id]);
        }

        $this->saveSession();
    }

    public function answer($value = null)
    {
        // If no value provided, use the currentAnswer property
        $value = $value ?? $this->currentAnswer;

        if (!$value) {
            return;
        }

        $this->answers[$this->currentQuestion->id] = $value;

        // Check if this is a positive answer (yes for normal questions, no for reverse_meaning questions)
        $isPositiveAnswer = ($value === 'yes' && !$this->currentQuestion->reverse_meaning) ||
                           ($value === 'no' && $this->currentQuestion->reverse_meaning);

        if ($isPositiveAnswer) {
            $crit = $this->currentQuestion->criticalityLevel;
            if ($crit && $crit->immediate_result) {
                $this->outcome = Outcome::where('criticality_level_id', $crit->id)
                    ->where('disease_id', $this->currentQuestion->disease_id)
                    ->first()?->description ?? 'Call emergency services immediately!';
                $this->phase = 'result';
                $this->saveSession();
                return;
            }
        }

        $this->currentAnswer = null;
        $this->nextQuestion();
    }

    public function evaluateResult()
    {
        if (!$this->outcome) {
            $mostSevereYes = collect($this->answers)
                ->map(function($answer, $questionId) {
                    return [
                        'answer' => $answer,
                        'question' => Question::with('criticalityLevel')->find($questionId)
                    ];
                })
                ->filter(function($item) {
                    if (!$item['question']) return false;

                    // If reverse_meaning is true, then 'no' counts as a positive answer
                    if ($item['question']->reverse_meaning) {
                        return $item['answer'] === 'no';
                    }

                    // Otherwise, 'yes' counts as a positive answer
                    return $item['answer'] === 'yes';
                })
                ->map(fn($item) => $item['question'])
                ->sortByDesc(fn($q) => $q->criticalityLevel->sort_order ?? 0)
                ->first();

            if ($mostSevereYes) {
                $this->outcome = Outcome::where('criticality_level_id', $mostSevereYes->criticality_level_id)
                    ->where('disease_id', $mostSevereYes->disease_id)
                    ->first()?->description;
                // Keep the current question set when there's a positive outcome
            } else {
                $this->outcome = __('frontend.outcome.nothingDetected');
                // Reset the current question to null when nothing is detected
                $this->currentQuestion = null;
            }
        }

        $this->saveSession();
    }

    public function saveSession()
    {
        Session::put('symptom_form', [
            'gender' => $this->gender,
            'answers' => $this->answers,
            'phase' => $this->phase,
            'outcome' => $this->outcome,
            'queue' => collect($this->questionQueue)->pluck('id')->toArray(),
            'history' => collect($this->history)->pluck('id')->toArray(),
            'current' => $this->currentQuestion?->id,
            'currentAnswer' => $this->currentAnswer,
        ]);
    }

    public function loadSession()
    {
        if ($session = Session::get('symptom_form')) {
            $this->gender = $session['gender'] ?? '';
            $this->answers = $session['answers'] ?? [];
            $this->phase = $session['phase'] ?? 'gender';
            $this->outcome = $session['outcome'] ?? null;
            $this->currentAnswer = $session['currentAnswer'] ?? null;

            $this->questionQueue = Question::findMany($session['queue'] ?? [])->values();
            $this->history = Question::findMany($session['history'] ?? [])->values();
            $this->currentQuestion = Question::find($session['current'] ?? null);
        }
    }

    public function resetQuestions()
    {
        $this->gender = '';
        $this->answers = [];
        $this->phase = 'gender';
        $this->outcome = null;
        $this->currentQuestion = null;
        $this->questionQueue = [];
        $this->history = [];

        Session::forget('symptom_form');
    }

    public function render()
    {
        return view('livewire.symptom-form')->layout('layouts.frontend');
    }
}

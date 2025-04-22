<?php

namespace App\Livewire;

use App\Models\CriticalityLevel;
use App\Models\Outcome;
use App\Models\Question;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class SymptomForm extends Component
{
    public string $gender = '';
    public array $answers = [];
    public string $phase = 'gender';
    public ?string $outcome = null;

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
        // Now we just store the gender but don't start the form yet
    }

    public function startSymptomForm()
    {
        $currentLevelId = $this->getCurrentLevelId();
        return collect($this->history)
            ->filter(fn($q) => $q->criticality_level_id === $currentLevelId)
            ->count();
    }

    public function getProgressPercentage()
    {
        $total = $this->getTotalInLevel();
        if ($total === 0) {
            return 0;
        }
        return round(($this->getAnsweredInLevel() / $total) * 100);
    }

    public function updatedGender($value)
    {
        $this->loadQuestions();
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
        // Order by sort_order field
        $levels = CriticalityLevel::orderBy('sort_order')->get();

        foreach ($levels as $level) {
            if ($grouped->has($level->id)) {
                $levelQuestions = $grouped[$level->id]->shuffle();
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
            if (is_array($this->questionQueue)) {
                $this->currentQuestion = array_shift($this->questionQueue);
            } else {
                // When it's a collection, get the first item and remove it
                $this->currentQuestion = $this->questionQueue->first();
                $this->questionQueue = $this->questionQueue->slice(1);
            }
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

            unset($this->answers[$this->currentQuestion->id]);
        }

        $this->saveSession();
    }

    public function answer($value)
    {
        $this->answers[$this->currentQuestion->id] = $value;

        if ($value === 'yes') {
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

        $this->nextQuestion();
    }

    public function evaluateResult()
    {
        if (!$this->outcome) {
            $mostSevereYes = collect($this->answers)
                ->filter(fn($v) => $v === 'yes')
                ->keys()
                ->map(fn($id) => Question::with('criticalityLevel')->find($id))
                ->filter()
                ->sortByDesc(fn($q) => $q->criticalityLevel->sort_order ?? 0)
                ->first();

            if ($mostSevereYes) {
                $this->outcome = Outcome::where('criticality_level_id', $mostSevereYes->criticality_level_id)
                    ->where('disease_id', $mostSevereYes->disease_id)
                    ->first()?->description;
            } else {
                $this->outcome = 'No critical symptoms detected.';
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
        ]);
    }

    public function loadSession()
    {
        if ($session = Session::get('symptom_form')) {
            $this->gender = $session['gender'] ?? '';
            $this->answers = $session['answers'] ?? [];
            $this->phase = $session['phase'] ?? 'gender';
            $this->outcome = $session['outcome'] ?? null;

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
        return view('livewire.symptom-form')->layout('layouts.guest');
    }
}

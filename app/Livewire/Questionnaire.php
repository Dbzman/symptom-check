<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Question;
use App\Models\Outcome;

class Questionnaire extends Component
{
    public int $step = 0;
    public ?string $gender = null;
    public array $answers = [];
    public array $immediateQuestions = [];
    public array $diseaseQuestions = [];
    public int $currentQuestionIndex = 0;
    public ?Outcome $finalOutcome = null;

    public function mount()
    {
        $this->loadImmediateQuestions();
    }

    public function loadImmediateQuestions()
    {
        $this->immediateQuestions = Question::where('criticality_level_id', null)->get()->toArray();
    }

    public function setGender($value)
    {
        $this->gender = $value;
        $this->step = 1;
    }

    public function answerImmediate($questionId, $answer)
    {
        $this->answers[$questionId] = $answer;

        if ($answer) {
            // Emergency triggered
            $this->finalOutcome = new Outcome([
                'title' => 'Emergency Action Required',
                'description' => 'Please call emergency services immediately!',
            ]);
            $this->step = 99;
        } else {
            $this->currentQuestionIndex++;
            if ($this->currentQuestionIndex >= count($this->immediateQuestions)) {
                $this->loadDiseaseQuestions();
                $this->step = 2;
                $this->currentQuestionIndex = 0;
            }
        }
    }

    public function loadDiseaseQuestions()
    {
        $query = Question::whereNotNull('criticality_level_id')
            ->when($this->gender, fn ($q) =>
            $q->whereNull('gender')->orWhere('gender', $this->gender)
            )
            ->with('criticalityLevel');

        $questions = $query->get()
            ->groupBy('criticality_level_id')
            ->sortByDesc(fn ($q, $levelId) => optional($q->first()->criticalityLevel)->name)
            ->flatten();

        $this->diseaseQuestions = $questions->shuffle()->values()->toArray();
    }

    public function answerDiseaseQuestion($questionId, $answer)
    {
        $this->answers[$questionId] = $answer;
        $this->currentQuestionIndex++;

        if ($this->currentQuestionIndex >= count($this->diseaseQuestions)) {
            $this->finalOutcome = $this->evaluateAnswers();
            $this->step = 99;
        }
    }

    public function evaluateAnswers(): ?Outcome
    {
        $answered = collect($this->answers)->filter();

        $questionIds = $answered->keys();
        $questionModels = Question::whereIn('id', $questionIds)->get();

        $grouped = $questionModels->groupBy('disease_id');

        foreach ($grouped as $diseaseId => $questions) {
            $maxLevel = $questions
                ->pluck('criticality_level_id')
                ->filter()
                ->max();

            if ($maxLevel) {
                return Outcome::where('disease_id', $diseaseId)
                    ->where('criticality_level_id', $maxLevel)
                    ->first();
            }
        }

        return null;
    }

    public function render()
    {
        return view('livewire.questionnaire');
    }
}

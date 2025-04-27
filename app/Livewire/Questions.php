<?php

namespace App\Livewire;

use Livewire\Component;

class Questions extends Component
{
    public $questions = [
        ['text' => 'nie gekanntes Krankheitsgefühl', 'criticality' => 2, 'checked' => false, 'answered' => false, 'icon' => '1.svg'],
        ['text' => 'feucht-kalte oder marmorierte Haut', 'criticality' => 2, 'checked' => false, 'answered' => false, 'icon' => '1.svg'],
        ['text' => 'extreme Schmerzen', 'criticality' => 2, 'checked' => false, 'answered' => false, 'icon' => '1.svg'],
        ['text' => 'veränderter Puls: unter 50 oder über 120/min', 'criticality' => 2, 'checked' => false, 'answered' => false, 'icon' => '4.svg'],

        ['text' => 'Verwirrtheit, Wesensänderung, Apathie', 'criticality' => 3, 'checked' => false, 'answered' => false, 'icon' => '5.svg'],
        ['text' => 'Mehr als 20 Atemzüge pro Minute', 'criticality' => 3, 'checked' => false, 'answered' => false, 'icon' => '6.svg'],
        ['text' => 'Oberer Blutdruckwert kleiner als 100', 'criticality' => 3, 'checked' => false, 'answered' => false, 'icon' => '7.jpg'],

        ['text' => 'Husten, Halsschmerzen', 'criticality' => 1, 'checked' => false, 'answered' => false, 'icon' => '8.svg'],
        ['text' => 'Ohrenschmerzen', 'criticality' => 1, 'checked' => false, 'answered' => false, 'icon' => '9.svg'],
        ['text' => 'Fieber oder Schüttelfrost', 'criticality' => 1, 'checked' => false, 'answered' => false, 'icon' => '10.svg'],
        ['text' => 'starke Kopfschmerzen', 'criticality' => 1, 'checked' => false, 'answered' => false, 'icon' => '11.svg'],
        ['text' => 'Steifer Nacken', 'criticality' => 1, 'checked' => false, 'answered' => false, 'icon' => '12.svg'],
        ['text' => 'Schmerzen im Mund/Kiefer', 'criticality' => 1, 'checked' => false, 'answered' => false, 'icon' => '13.svg'],
        ['text' => 'Bauchschmerzen', 'criticality' => 1, 'checked' => false, 'answered' => false, 'icon' => '14.svg'],
        ['text' => 'harter, druckschmerzhafter Bauch', 'criticality' => 1, 'checked' => false, 'answered' => false, 'icon' => '15.svg'],
        ['text' => 'gerötete oder erwärmte Haut', 'criticality' => 1, 'checked' => false, 'answered' => false, 'icon' => '16.svg'],
        ['text' => 'Eiter-Ansammlung (Abzess)', 'criticality' => 1, 'checked' => false, 'answered' => false, 'icon' => '17.svg'],
        ['text' => 'Wirbelsäulenschmerzen', 'criticality' => 1, 'checked' => false, 'answered' => false, 'icon' => '18.svg'],
        ['text' => 'Häufiges, schmerzhaftes Wasserlassen', 'criticality' => 1, 'checked' => false, 'answered' => false, 'icon' => '19.svg'],
        ['text' => 'Trüber Urin', 'criticality' => 1, 'checked' => false, 'answered' => false, 'icon' => '20.svg'],
        ['text' => 'Schmerzen seitlich am Rücken', 'criticality' => 1, 'checked' => false, 'answered' => false, 'icon' => '21.svg'],
    ];

    public $currentQuestionIndex = 0;
    public $currentAnswer;

//    public function updatedCurrentAnswer($value)
//    {
//        $this->questions[$this->currentQuestionIndex]['checked'] = $value;
//    }

    public function nextQuestion()
    {

        $this->questions[$this->currentQuestionIndex]['answered'] = true;

        if ($this->currentQuestionIndex < count($this->questions) - 1) {
            $this->questions[$this->currentQuestionIndex]['checked'] = $this->currentAnswer;
            $this->currentQuestionIndex++;
            $this->currentAnswer = null;
        }

        if ($this->hasAnsweredCriticality(3)
            && $this->hasCheckedCriticality(3)) {
            return redirect()->route('result', ['category' => 'red']);
        } elseif ($this->hasAnsweredCriticality(2)
            && $this->hasCheckedCriticality(2)
            && $this->hasAnsweredCriticality(3)
            && !$this->hasCheckedCriticality(3)) {
            return redirect()->route('result', ['category' => 'yellow']);
        } elseif ($this->hasAnsweredCriticality(3)
            && $this->hasAnsweredCriticality(2)
            && $this->hasAnsweredCriticality(1)
            && $this->hasCheckedCriticality(1)) {
            return redirect()->route('result', ['category' => 'green']);
        } elseif ($this->hasAnsweredCriticality(3)
            && $this->hasAnsweredCriticality(2)
            && $this->hasAnsweredCriticality(1)) {
            return redirect()->route('result', ['category' => 'safe']);
        }
//        if ($this->currentQuestionIndex == 7) {
//            dump( $this->hasAnsweredCriticality(3));
//            dump( $this->hasCheckedCriticality(3));
//            dump( $this->hasAnsweredCriticality(2));
//            dump( $this->hasCheckedCriticality(2));
//        dd($this->questions)   ;
//
//        }
    }

    public function previousQuestion()
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
            $this->currentAnswer = $this->questions[$this->currentQuestionIndex]['checked'];
        }
    }

    public function render()
    {
        return view('livewire.questions', [
            'question' => $this->questions[$this->currentQuestionIndex],
        ])->layout('layouts.guest');
    }

    public function hasAnsweredCriticality(int $criticality): bool
    {
        $currentQuestions = collect($this->questions)->filter(function ($question) use ($criticality) {
            return $question['criticality'] == $criticality;
        });
        return $currentQuestions->every(function ($question) {
            return $question['answered'];
        });
    }

    public function hasCheckedCriticality(int $criticality): bool
    {
        $currentQuestions = collect($this->questions)->filter(function ($question) use ($criticality) {
            return $question['criticality'] == $criticality;
        });
        return $currentQuestions->contains(function ($question) {
            return $question['checked'];
        });
    }
}

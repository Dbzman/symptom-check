<?php

use App\Livewire\Questions;

it('can navigate through questions', function () {
    Livewire::test(Questions::class)
        ->assertSee('nie gekanntes Krankheitsgefühl')
        ->set('currentAnswer', 'yes')
        ->call('nextQuestion')
        ->assertSee('feucht-kalte oder marmorierte Haut')
        ->set('currentAnswer', 'no')
        ->call('nextQuestion')
        ->assertSee('extreme Schmerzen')
        ->call('previousQuestion')
        ->assertSee('feucht-kalte oder marmorierte Haut');
});


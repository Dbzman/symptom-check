<?php

namespace Database\Seeders;

use App\Models\Disease;
use App\Models\CriticalityLevel;
use App\Models\Question;
use App\Models\Outcome;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'dev@dbzman-online.eu',
            'password' => Hash::make('admin'), // Use a secure password in real apps!
        ]);

        $flu = Disease::create(['name' => 'Flu']);
        $covid = Disease::create(['name' => 'COVID-19']);
        $stroke = Disease::create(['name' => 'Stroke']);

        $level1 = CriticalityLevel::create([
            'name' => 'Low',
            'color' => 'green',
            'sort_order' => 3,
        ]);
        $level2 = CriticalityLevel::create([
            'name' => 'Medium',
            'color' => 'orange',
            'sort_order' => 2,
        ]);
        $level3 = CriticalityLevel::create([
            'name' => 'High',
            'color' => 'red',
            'immediate_result' => true,
            'sort_order' => 1,
        ]);
        $immediateLevel = CriticalityLevel::updateOrCreate([
            'name' => 'Immediate',
        ], [
            'color' => 'red',
            'immediate_result' => true,
        ]);


        // Emergency (general) questions — no disease
        Question::create(['text' => 'Are you having chest pain?', 'gender' => null, 'criticality_level_id' => $immediateLevel->id,]);
        Question::create(['text' => 'Are you experiencing sudden confusion?', 'gender' => null, 'criticality_level_id' => $immediateLevel->id,]);
        Question::create(['text' => 'Do you have difficulty breathing?', 'gender' => null, 'criticality_level_id' => $immediateLevel->id,]);

        // Disease-specific questions
        Question::create([
            'text' => 'Do you have a fever?',
            'disease_id' => $flu->id,
            'criticality_level_id' => $level1->id,
        ]);

        Question::create([
            'text' => 'Do you have a sore throat?',
            'disease_id' => $flu->id,
            'criticality_level_id' => $level2->id,
        ]);

        Question::create([
            'text' => 'Are you experiencing body aches?',
            'disease_id' => $covid->id,
            'criticality_level_id' => $level2->id,
            'gender' => 'female',
        ]);

        Question::create([
            'text' => 'Do you have a sudden droop on one side of your face?',
            'disease_id' => $stroke->id,
            'criticality_level_id' => $level3->id,
        ]);

        // Outcomes
        Outcome::create([
            'disease_id' => $flu->id,
            'criticality_level_id' => $level1->id,
            'title' => 'Mild flu symptoms',
            'description' => 'You may have mild flu. Rest and monitor symptoms.',
        ]);

        Outcome::create([
            'disease_id' => $flu->id,
            'criticality_level_id' => $level2->id,
            'title' => 'Moderate flu symptoms',
            'description' => 'Consult a doctor if symptoms persist.',
        ]);

        Outcome::create([
            'disease_id' => $stroke->id,
            'criticality_level_id' => $level3->id,
            'title' => 'Possible Stroke',
            'description' => 'Call emergency services immediately!',
        ]);
    }
}

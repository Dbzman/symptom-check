<?php

return [
    'navigation' => [
        'groups' => [
            'settings' => 'Einstellungen',
            'content' => 'Inhaltsverwaltung',
            'users' => 'Benutzerverwaltung',
        ],
        'resources' => [
            'criticality-levels' => 'Kritikalitätsstufen',
            'diseases' => 'Krankheiten',
            'questions' => 'Fragen',
            'outcomes' => 'Ergebnisse',
            'users' => 'Benutzer',
        ],
    ],
    'resources' => [
        'criticality-levels' => [
            'labels' => [
                'singular' => 'Kritikalitätsstufe',
                'plural' => 'Kritikalitätsstufen',
            ],
            'fields' => [
                'name' => 'Name',
                'color' => 'Farbe',
                'immediate_result' => 'Sofortiges Ergebnis',
                'immediate_result_help' => 'Wenn aktiviert, wird bei Beantwortung einer Frage mit dieser Kritikalitätsstufe sofort ein Ergebnis angezeigt',
                'sort_order' => 'Sortierreihenfolge',
                'sort_order_help' => 'Definieren Sie die Reihenfolge, in der die Stufen abgefragt werden (niedrigere Zahlen zuerst)',
            ],
        ],
        'diseases' => [
            'labels' => [
                'singular' => 'Krankheit',
                'plural' => 'Krankheiten',
            ],
            'fields' => [
                'name' => 'Name',
            ],
        ],
        'questions' => [
            'labels' => [
                'singular' => 'Frage',
                'plural' => 'Fragen',
            ],
            'fields' => [
                'text' => 'Fragetext',
                'svg_icon' => 'SVG-Symbol',
                'svg_preview' => 'SVG-Vorschau',
                'has_svg' => 'Hat Symbol',
                'disease' => 'Zugehörige Krankheit',
                'criticality_level' => 'Kritikalitätsstufe',
                'gender' => 'Geschlechtsspezifisch',
            ],
            'options' => [
                'gender' => [
                    'male' => 'Männlich',
                    'female' => 'Weiblich',
                ],
            ],
            'placeholders' => [
                'text' => 'Geben Sie den Fragetext ein',
                'disease' => 'Wählen Sie eine Krankheit',
                'criticality_level' => 'Wählen Sie eine Kritikalitätsstufe',
                'gender' => 'Wählen Sie ein Geschlecht (optional)',
            ],
            'helpers' => [
                'disease' => 'Leer lassen, um als "Eingangsfrage" gestellt werden zu können',
                'gender' => 'Geben Sie an, ob diese Frage nur für ein bestimmtes Geschlecht angezeigt werden soll',
                'text' => 'Die Frage, die dem Benutzer gestellt wird',
                'svg_icon' => 'Fügen Sie den SVG-Code ein (beginnend mit <svg> und endend mit </svg>), um ein Symbol für diese Frage anzuzeigen',
                'svg_preview' => 'So wird Ihr SVG-Symbol im Formular aussehen',
                            ],
                            'placeholders' => [
                'text' => 'Geben Sie den Fragetext ein',
                'disease' => 'Wählen Sie eine Krankheit',
                'criticality_level' => 'Wählen Sie eine Kritikalitätsstufe',
                'gender' => 'Wählen Sie ein Geschlecht (optional)',
                'svg_icon' => 'Fügen Sie Ihren SVG-Code hier ein...',
            ],
        ],
        'outcomes' => [
            'labels' => [
                'singular' => 'Ergebnis',
                'plural' => 'Ergebnisse',
            ],
            'fields' => [
                'title' => 'Titel',
                'description' => 'Beschreibung',
                'disease' => 'Krankheit',
                'criticality_level' => 'Kritikalitätsstufe',
            ],
            'placeholders' => [
                'description' => 'Geben Sie die Ergebnisbeschreibung ein',
                'disease' => 'Wählen Sie eine Krankheit',
                'criticality_level' => 'Wählen Sie eine Kritikalitätsstufe',
            ],
            'helpers' => [
                'description' => 'Dieser Text wird den Benutzern als Ergebnis angezeigt',
            ],
        ],
    ],
];

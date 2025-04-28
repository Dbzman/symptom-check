<?php

return [
    'color' => [
        'red' => 'Red',
        'orange' => 'Orange',
        'yellow' => 'Yellow',
        'green' => 'Green',
        'gray' => 'Gray',
    ],
    'navigation' => [
        'groups' => [
            'settings' => 'Settings',
            'content' => 'Content Management',
            'users' => 'User Management',
        ],
        'resources' => [
            'criticality-levels' => 'Criticality Levels',
            'diseases' => 'Diseases',
            'questions' => 'Questions',
            'outcomes' => 'Outcomes',
            'users' => 'Users',
        ],
    ],
    'resources' => [
        'criticality-levels' => [
            'labels' => [
                'singular' => 'Criticality Level',
                'plural' => 'Criticality Levels',
            ],
            'fields' => [
                'name' => 'Name',
                'color' => 'Color',
                'immediate_result' => 'Immediate Result',
                'immediate_result_help' => 'If enabled, answering a question with this criticality level will immediately show an outcome',
                'sort_order' => 'Sort Order',
                'sort_order_help' => 'Define the order in which levels are asked (lower numbers first)',
            ],
        ],
        'diseases' => [
            'labels' => [
                'singular' => 'Disease',
                'plural' => 'Diseases',
            ],
            'fields' => [
                'name' => 'Name',
            ],
        ],
        'questions' => [
            'general' => [
                'singular' => 'General Question',
                'plural' => 'General Questions',
            ],
            'labels' => [
                'singular' => 'Question',
                'plural' => 'Questions',
            ],
            'fields' => [
                'text' => 'Question Text',
                'svg_icon' => 'SVG Icon',
                'svg_preview' => 'SVG Preview',
                'has_svg' => 'Has Icon',
                'disease' => 'Related Disease',
                'criticality_level' => 'Criticality Level',
                'gender' => 'Gender Specific',
                'reverse_meaning' => 'Reverse Answer Meaning',
            ],
            'options' => [
                'gender' => [
                    'male' => 'Male',
                    'female' => 'Female',
                ],
            ],
            'placeholders' => [
                'text' => 'Enter question text',
                'disease' => 'Select a disease',
                'criticality_level' => 'Select criticality level',
                'gender' => 'Select gender (optional)',
            ],
            'helpers' => [
                'disease' => 'Leave empty for general questions like emergency checks',
                'gender' => 'Specify if this question should only appear for a specific gender',
                'text' => 'The question that will be asked to the user',
                'svg_icon' => 'Paste the SVG code (starting with <svg> and ending with </svg>) to display an icon with this question',
                'svg_preview' => 'This is how your SVG icon will look in the form',
                'reverse_meaning' => 'If enabled, a "No" answer will be treated as positive when evaluating results',
                            ],
                            'placeholders' => [
                'text' => 'Enter question text',
                'disease' => 'Select a disease',
                'criticality_level' => 'Select criticality level',
                'gender' => 'Select gender (optional)',
                'svg_icon' => 'Paste your SVG code here...',
            ],
        ],
        'outcomes' => [
            'labels' => [
                'singular' => 'Outcome',
                'plural' => 'Outcomes',
            ],
            'fields' => [
                'description' => 'Description',
                'disease' => 'Disease',
                'title' => 'Title',
                'criticality_level' => 'Criticality Level',
            ],
            'placeholders' => [
                'description' => 'Enter outcome description',
                'disease' => 'Select a disease',
                'criticality_level' => 'Select criticality level',
            ],
            'helpers' => [
                'description' => 'This text will be shown to users as the outcome',
            ],
        ],
    ],
];

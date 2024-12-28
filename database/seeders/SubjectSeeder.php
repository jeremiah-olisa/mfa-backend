<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Enums\Subjects;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjectList = [
            [
                'label' => 'Agric',
                'name' => Subjects::Agric,
                'icon_url' => asset("subjects/Agric.png")
            ],
            [
                'label' => 'Accounting',
                'name' => Subjects::Accounting,
                'icon_url' => asset("subjects/Accounting.png")
            ],
            [
                'label' => 'Biology',
                'name' => Subjects::Biology,
                'icon_url' => asset("subjects/Biology.png")
            ],
            [
                'label' => 'Chemistry',
                'name' => Subjects::Chemistry,
                'icon_url' => asset("subjects/Chemistry.png")
            ],
            [
                'label' => 'Civiledu',
                'name' => Subjects::Civiledu,
                'icon_url' => asset("subjects/CivilEdu.png")
            ],
            [
                'label' => 'Commerce',
                'name' => Subjects::Commerce,
                'icon_url' => asset("subjects/Commerce.png")
            ],
            [
                'label' => 'Computer',
                'name' => Subjects::Computer,
                'icon_url' => asset("subjects/Computer.png")
            ],
            [
                'label' => 'CRS',
                'name' => Subjects::CRS,
                'icon_url' => asset("subjects/CRS.png")
            ],
            [
                'label' => 'Marketing',
                'name' => Subjects::Marketing,
                'icon_url' => asset("subjects/Marketing.png")
            ],
            [
                'label' => 'Economics',
                'name' => Subjects::Economics,
                'icon_url' => asset("subjects/English.png")
            ],
            [
                'label' => 'English',
                'name' => Subjects::English,
                'icon_url' => asset("subjects/Economics.png")
            ],
            [
                'label' => 'Literature',
                'name' => Subjects::Literature,
                'icon_url' => asset("subjects/Geography.png")
            ],
            [
                'label' => 'Geography',
                'name' => Subjects::Geography,
                'icon_url' => asset("subjects/Literature.png")
            ],
            [
                'label' => 'Government',
                'name' => Subjects::Government,
                'icon_url' => asset("subjects/Marketing.png")
            ],
            [
                'label' => 'IRK',
                'name' => Subjects::IRK,
                'icon_url' => asset("subjects/Maths.png")
            ],
            [
                'label' => 'Maths',
                'name' => Subjects::Maths,
                'icon_url' => asset("subjects/Physics.png")
            ],
            [
                'label' => Subjects::FurtherMaths,
                'name' => Subjects::FurtherMaths,
                'icon_url' => asset("subjects/FurtherMaths.png")
            ],
            [
                'label' => Subjects::History,
                'name' => Subjects::History,
                'icon_url' => asset("subjects/History.png")
            ],
            [
                'label' => 'Physics',
                'name' => Subjects::Physics,
                'icon_url' => asset("subjects/IRK.png")
            ],
        ];

        // Insert subjects into the database
        foreach ($subjectList as $subject) {
            Subject::query()->upsert([
                'label' => $subject['label'],
                'name' => $subject['name'],
                'icon_url' => $subject['icon_url'],
            ], ['label', 'name']);
        }
    }
}

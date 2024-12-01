<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Enums\Subjects;

class SubjectSeeder extends Seeder
{
    public function run()
    {
        $subjectList = [
            ['label' => 'Accounting', 'name' => Subjects::Accounting],
            ['label' => 'Biology', 'name' => Subjects::Biology],
            ['label' => 'Chemistry', 'name' => Subjects::Chemistry],
            ['label' => 'Civiledu', 'name' => Subjects::Civiledu],
            ['label' => 'Commerce', 'name' => Subjects::Commerce],
            ['label' => 'CRS', 'name' => Subjects::CRS],
            ['label' => 'Economics', 'name' => Subjects::Economics],
            ['label' => 'English', 'name' => Subjects::English],
            ['label' => 'Literature', 'name' => Subjects::Literature],
            ['label' => 'Geography', 'name' => Subjects::Geography],
            ['label' => 'Government', 'name' => Subjects::Government],
            ['label' => 'IRK', 'name' => Subjects::IRK],
            ['label' => 'Maths', 'name' => Subjects::Maths],
            ['label' => 'Physics', 'name' => Subjects::Physics],
        ];

        // Insert subjects into the database
        foreach ($subjectList as $subject) {
            Subject::create([
                'label' => $subject['label'],
                'name' => $subject['name'],
            ]);
        }
    }
}

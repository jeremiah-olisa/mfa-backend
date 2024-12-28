<?php

namespace Database\Seeders;

use App\Constants\SetupConstant;
use App\Enums\Subjects;
use App\Models\ExamSubjectSyllabus;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class ExamSubjectSyllabiSeeder extends Seeder
{
    public function run(): void
    {

        $waecSyllabus = [
            [
                'exam' => SetupConstant::$exams[0],
                'subject' => Subjects::Agric,
                'syllabus_link' => asset('syllabus/waec/waec_wassce_agricultural_science_syllabus.pdf'),
            ],
            [
                'exam' => SetupConstant::$exams[0],
                'subject' => Subjects::Biology,
                'syllabus_link' => asset('syllabus/waec/waec_wassce_biology_syllabus.pdf'),
            ],
            [
                'exam' => SetupConstant::$exams[0],
                'subject' => Subjects::Chemistry,
                'syllabus_link' => asset('syllabus/waec/waec_wassce_chemistry_syllabus.pdf'),
            ],
            [
                'exam' => SetupConstant::$exams[0],
                'subject' => Subjects::CRS,
                'syllabus_link' => asset('syllabus/waec/waec_wassce_christian_religious_studies_syllabus.pdf'),
            ],
            [
                'exam' => SetupConstant::$exams[0],
                'subject' => Subjects::Civiledu,
                'syllabus_link' => asset('syllabus/waec/waec_wassce_civic_education_syllabus.pdf'),
            ],
            [
                'exam' => SetupConstant::$exams[0],
                'subject' => Subjects::Commerce,
                'syllabus_link' => asset('syllabus/waec/waec_wassce_commerce_syllabus.pdf'),
            ],
            [
                'exam' => SetupConstant::$exams[0],
                'subject' => Subjects::Computer,
                'syllabus_link' => asset('syllabus/waec/waec_wassce_computer_studies_syllabus.pdf'),
            ],
            [
                'exam' => SetupConstant::$exams[0],
                'subject' => Subjects::English,
                'syllabus_link' => asset('syllabus/waec/waec_wassce_english_language_syllabus.pdf'),
            ],
            [
                'exam' => SetupConstant::$exams[0],
                'subject' => Subjects::FurtherMaths,
                'syllabus_link' => asset('syllabus/waec/waec_wassce_further_mathematics_syllabus.pdf'),
            ],
            [
                'exam' => SetupConstant::$exams[0],
                'subject' => Subjects::Government,
                'syllabus_link' => asset('syllabus/waec/waec_wassce_government_syllabus.pdf'),
            ],
            [
                'exam' => SetupConstant::$exams[0],
                'subject' => Subjects::History,
                'syllabus_link' => asset('syllabus/waec/waec_wassce_history_syllabus.pdf'),
            ],
            [
                'exam' => SetupConstant::$exams[0],
                'subject' => Subjects::Maths,
                'syllabus_link' => asset('syllabus/waec/waec_wassce_mathematics_syllabus.pdf'),
            ],
            [
                'exam' => SetupConstant::$exams[0],
                'subject' => Subjects::Physics,
                'syllabus_link' => asset('syllabus/waec/waec_wassce_physics_syllabus.pdf'),
            ],
        ];

        $necoSyllabus = [];

        $jambSyllabus = [
            [
                'exam' => SetupConstant::$exams[2],
                'subject' => Subjects::Accounting,
                'syllabus_link' => 'https://myfirstattempt.com/assets/syllabus/Principles-of-Accounting.pdf',
            ],
            [
                'exam' => SetupConstant::$exams[2],
                'subject' => Subjects::Biology,
                'syllabus_link' => 'https://myfirstattempt.com/assets/syllabus/' . Subjects::Biology . '.pdf',
            ],
            [
                'exam' => SetupConstant::$exams[2],
                'subject' => Subjects::Chemistry,
                'syllabus_link' => 'https://myfirstattempt.com/assets/syllabus/' . Subjects::Chemistry . '.pdf',
            ],
            [
                'exam' => SetupConstant::$exams[2],
                'subject' => Subjects::Civiledu,
                'syllabus_link' => 'https://myfirstattempt.com/assets/syllabus/' . Subjects::Government . '.pdf',
            ],
            [
                'exam' => SetupConstant::$exams[2],
                'subject' => Subjects::Commerce,
                'syllabus_link' => 'https://myfirstattempt.com/assets/syllabus/' . Subjects::Commerce . '.pdf',
            ],
            [
                'exam' => SetupConstant::$exams[2],
                'subject' => Subjects::CRS,
                'syllabus_link' => 'https://myfirstattempt.com/assets/syllabus/' . Subjects::CRS . '.pdf',
            ],
            [
                'exam' => SetupConstant::$exams[2],
                'subject' => Subjects::Economics,
                'syllabus_link' => 'https://myfirstattempt.com/assets/syllabus/' . Subjects::Economics . '.pdf',
            ],
            [
                'exam' => SetupConstant::$exams[2],
                'subject' => Subjects::English,
                'syllabus_link' => 'https://myfirstattempt.com/assets/syllabus/' . Subjects::English . '.pdf',
            ],
            [
                'exam' => SetupConstant::$exams[2],
                'subject' => Subjects::Literature,
                'syllabus_link' => 'https://myfirstattempt.com/assets/syllabus/Literature-in-English.pdf',
            ],
            [
                'exam' => SetupConstant::$exams[2],
                'subject' => Subjects::Geography,
                'syllabus_link' => 'https://myfirstattempt.com/assets/syllabus/' . Subjects::Geography . '.pdf',
            ],
            [
                'exam' => SetupConstant::$exams[2],
                'subject' => Subjects::Government,
                'syllabus_link' => Subjects::Government,
            ],
            [
                'exam' => SetupConstant::$exams[2],
                'subject' => Subjects::IRK,
                'syllabus_link' => 'https://myfirstattempt.com/assets/syllabus/Islamic-Studies.pdf',
            ],
            [
                'exam' => SetupConstant::$exams[2],
                'subject' => Subjects::Maths,
                'syllabus_link' => 'https://myfirstattempt.com/assets/syllabus/' . Subjects::Maths . '.pdf',
            ],
            [
                'exam' => SetupConstant::$exams[2],
                'subject' => Subjects::Physics,
                'syllabus_link' => 'https://myfirstattempt.com/assets/syllabus/' . Subjects::Physics . '.pdf',
            ],
        ];


        $subjectSyllabi = array_merge($waecSyllabus, $necoSyllabus, $jambSyllabus);

        // Insert subjects into the database
        foreach ($subjectSyllabi as $syllabus) {
            try {

                $subjectId = Subject::query()->where('name', '=', $syllabus['subject'])
                    ->select('id')
                    ->firstOrFail()
                    ->id;

                ExamSubjectSyllabus::query()->upsert([
                    'exam' => $syllabus['exam'],
                    'syllabus_link' => $syllabus['syllabus_link'],
                    'subject_id' => $subjectId,
                ], ['exam', 'subject_id'], ['syllabus_link']);
            } catch (\Exception $exception) {
                throw new \Exception($exception->getMessage() . ' ' . $syllabus['subject'], $exception->getCode(), $exception);
            }
        }
    }

}

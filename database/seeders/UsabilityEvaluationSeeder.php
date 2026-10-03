<?php

namespace Database\Seeders;

use App\Models\UsabilityFeedback;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsabilityEvaluationSeeder extends Seeder
{
    /**
     * Run the database seeds for System Usability Scale (SUS) benchmark.
     */
    public function run(): void
    {
        if (UsabilityFeedback::count() >= 15) {
            return;
        }

        $roles = ['Mahasiswa Informatika', 'Mahasiswa Sistem Informasi', 'Mahasiswa Teknik Elektro', 'Project Leader', 'Software Engineering Student'];

        $sampleComments = [
            'Proses pencocokan skill sangat membantu mencari rekan proyek yang relevan.',
            'PWA Swap Hub terasa sangat cepat dan responsif saat dibuka di smartphone.',
            'Integrasi GitHub commit feed dan real-time chat membuat koordinasi tim jauh lebih mudah.',
            'Sistem Kanban board intuitif dan pembaruan tugas sinkron langsung.',
            'Tampilan clean, warna modern dan navigasi halaman mulus tanpa reload.',
            'Fitur skill swap dengan kalkulasi kecocokan sangat memudahkan pertukaran ilmu.',
            'Aplikasi sangat siap digunakan untuk kolaborasi proyek kuliah antar jurusan.',
        ];

        // 18 Sample respondents with high usability ratings (Typical SUS mean ~80 - 85)
        for ($i = 1; $i <= 18; $i++) {
            // High usability: Q_odd high (4 or 5), Q_even low (1 or 2)
            $answers = [
                'q1' => rand(4, 5),
                'q2' => rand(1, 2),
                'q3' => rand(4, 5),
                'q4' => rand(1, 2),
                'q5' => rand(4, 5),
                'q6' => rand(1, 2),
                'q7' => rand(4, 5),
                'q8' => rand(1, 2),
                'q9' => rand(4, 5),
                'q10' => rand(1, 2),
            ];

            $score = UsabilityFeedback::calculateScore($answers);

            UsabilityFeedback::create([
                'user_id' => User::inRandomOrder()->value('id'),
                'respondent_name' => 'Responden Mahasiswa #'.$i,
                'respondent_role' => $roles[array_rand($roles)],
                'q1' => $answers['q1'],
                'q2' => $answers['q2'],
                'q3' => $answers['q3'],
                'q4' => $answers['q4'],
                'q5' => $answers['q5'],
                'q6' => $answers['q6'],
                'q7' => $answers['q7'],
                'q8' => $answers['q8'],
                'q9' => $answers['q9'],
                'q10' => $answers['q10'],
                'sus_score' => $score,
                'grade' => UsabilityFeedback::determineGrade($score),
                'adjective_rating' => UsabilityFeedback::determineAdjective($score),
                'qualitative_feedback' => $sampleComments[array_rand($sampleComments)],
            ]);
        }
    }
}

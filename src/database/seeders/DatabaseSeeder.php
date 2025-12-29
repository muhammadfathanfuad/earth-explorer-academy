<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\Topic;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- TOPIK 1: STRUKTUR BUMI ---
        $bumi = Topic::create([
            'title' => 'Struktur Bumi',
            'slug' => 'struktur-bumi',
            'summary' => 'Pelajari lapisan-lapisan bumi mulai dari Kerak hingga Inti Bumi.',
            'content' => 'Bumi kita seperti bawang, memiliki banyak lapisan. Lapisan terluar disebut Kerak Bumi, tempat kita tinggal. Di bawahnya ada Mantel yang panas, dan di tengah ada Inti Bumi.',
            'image' => 'earth-layers.png', // Nanti kita siapkan gambarnya
        ]);

        // Soal untuk Struktur Bumi
        Quiz::create([
            'topic_id' => $bumi->id,
            'question' => 'Lapisan bumi yang paling luar dan tempat makhluk hidup tinggal disebut apa?',
            'option_a' => 'Mantel Bumi',
            'option_b' => 'Inti Luar',
            'option_c' => 'Kerak Bumi',
            'option_d' => 'Atmosfer',
            'correct_answer' => 'c',
            'explanation' => 'Kerak bumi adalah lapisan terluar yang keras dan tipis dibandingkan lapisan lainnya.',
        ]);

        Quiz::create([
            'topic_id' => $bumi->id,
            'question' => 'Bagian bumi manakah yang memiliki suhu paling panas?',
            'option_a' => 'Kerak Samudra',
            'option_b' => 'Inti Dalam',
            'option_c' => 'Mantel',
            'option_d' => 'Kutub Utara',
            'correct_answer' => 'b',
            'explanation' => 'Inti dalam memiliki suhu ribuan derajat celcius, hampir sama panasnya dengan permukaan matahari.',
        ]);

        // --- TOPIK 2: ATMOSFER ---
        $atmosfer = Topic::create([
            'title' => 'Atmosfer & Cuaca',
            'slug' => 'atmosfer-cuaca',
            'summary' => 'Kenapa ada hujan? Apa fungsi lapisan ozon? Ayo cari tahu!',
            'content' => 'Atmosfer adalah selimut gas yang menyelimuti bumi. Tanpa atmosfer, kita akan terpanggang matahari di siang hari dan beku di malam hari.',
            'image' => 'atmosphere.png',
        ]);

        // Soal untuk Atmosfer
        Quiz::create([
            'topic_id' => $atmosfer->id,
            'question' => 'Gas apa yang paling banyak terkandung dalam atmosfer bumi?',
            'option_a' => 'Oksigen',
            'option_b' => 'Karbon Dioksida',
            'option_c' => 'Nitrogen',
            'option_d' => 'Hidrogen',
            'correct_answer' => 'c',
            'explanation' => 'Sekitar 78% atmosfer bumi terdiri dari gas Nitrogen.',
        ]);
    }
}
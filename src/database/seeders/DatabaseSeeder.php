<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User Admin Filament
        User::create([
            'name' => 'King Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        // --- TOPIK 1: STRUKTUR BUMI ---
        // Perhatikan: 'content' sekarang berisi Array Slide, bukan teks panjang.
        $bumi = Topic::create([
            'title' => 'Struktur Bumi',
            'slug' => 'struktur-bumi',
            'summary' => 'Pelajari lapisan-lapisan bumi mulai dari Kerak hingga Inti Bumi.',
            'image' => 'topics/earth-layers.png', // Pastikan file ini ada di storage/app/public/topics/ nanti
            'content' => [
                [
                    'slide_image' => 'topics/earth-layers.png', // Placeholder (Ganti via admin nanti)
                    'slide_text' => '<p><strong>Bumi Seperti Bawang!</strong><br>Tahukah kamu? Bumi kita memiliki banyak lapisan, persis seperti bawang merah. Kita tidak tinggal di dalam bumi, tapi di permukaannya.</p>'
                ],
                [
                    'slide_image' => 'topics/earth-layers.png', 
                    'slide_text' => '<p><strong>Kerak Bumi</strong><br>Lapisan terluar disebut <em>Kerak Bumi</em>. Ini adalah tempat kita berpijak, membangun rumah, dan tempat lautan berada. Sifatnya keras dan padat.</p>'
                ],
                [
                    'slide_image' => 'topics/earth-layers.png', 
                    'slide_text' => '<p><strong>Mantel & Inti</strong><br>Di bawah kerak ada <em>Mantel</em> yang sangat panas (batuan cair). Lalu di pusat terdalam ada <em>Inti Bumi</em> yang suhunya setara permukaan matahari!</p>'
                ]
            ],
        ]);

        // Soal Struktur Bumi
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
            'image' => 'topics/atmosphere.png',
            'content' => [
                [
                    'slide_image' => 'topics/atmosphere.png',
                    'slide_text' => '<p><strong>Selimut Gas</strong><br>Atmosfer adalah selimut gas tebal yang menyelimuti bumi. Tanpa atmosfer, kita tidak bisa bernapas!</p>'
                ],
                [
                    'slide_image' => 'topics/atmosphere.png',
                    'slide_text' => '<p><strong>Pelindung Kita</strong><br>Selain untuk bernapas, atmosfer melindungi kita agar tidak terpanggang matahari di siang hari dan tidak membeku di malam hari.</p>'
                ]
            ],
        ]);

        // Soal Atmosfer
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
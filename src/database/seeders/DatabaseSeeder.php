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
        // SOAL 1: Pilihan Ganda (Model Lama)
        Quiz::create([
            'topic_id' => $bumi->id,
            'type' => 'multiple_choice', // Set Tipe
            'question' => 'Lapisan bumi yang paling luar dan tempat makhluk hidup tinggal disebut apa?',
            'option_a' => 'Mantel Bumi',
            'option_b' => 'Inti Luar',
            'option_c' => 'Kerak Bumi',
            'option_d' => 'Atmosfer',
            'correct_answer' => 'c',
            'explanation' => 'Kerak bumi adalah lapisan terluar yang keras.',
        ]);

        // SOAL 2: Mitos vs Fakta (BARU!)
        Quiz::create([
            'topic_id' => $bumi->id,
            'type' => 'true_false', // Set Tipe
            'question' => 'Inti Bumi itu dingin dan beku seperti es.',
            // Option A-D kosongkan saja, tidak terpakai
            'correct_answer' => 'false', // Artinya ini MITOS
            'explanation' => 'Salah! Inti bumi justru sangat panas, suhunya mencapai 6.000 derajat Celcius!',
        ]);

        // SOAL 3: Mitos vs Fakta
        Quiz::create([
            'topic_id' => $bumi->id,
            'type' => 'true_false',
            'question' => 'Gunung berapi bisa meletus karena ada tekanan gas dari dalam bumi.',
            'correct_answer' => 'true', // Artinya ini FAKTA
            'explanation' => 'Benar! Magma didorong keluar oleh tekanan gas yang kuat.',
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
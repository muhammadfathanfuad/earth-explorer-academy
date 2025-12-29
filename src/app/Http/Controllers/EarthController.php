<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;

class EarthController extends Controller
{
    public function index()
    {
        $topics = Topic::all();
        return view('home', compact('topics'));
    }

    public function show($slug)
    {
        $topic = Topic::with('quizzes')->where('slug', $slug)->firstOrFail();
        
        $content = $topic->content;
        $slides = [];

        // --- DETEKSI JENIS KONTEN ---
        // Cek apakah ada tag paragraf <p>. Jika tidak ada, berarti ini Teks Biasa (Seeder).
        $isHtml = strpos($content, '<p>') !== false;

        if (!$isHtml) {
            // LOGIKA 1: TEKS BIASA (Data Seeder)
            // Bungkus langsung jadi satu slide HTML
            $slides[] = [
                'text' => '<p>' . nl2br(e($content)) . '</p>',
                'image' => null
            ];
        } else {
            // LOGIKA 2: HTML (Data dari Admin Filament)
            
            // Pecah berdasarkan tutup paragraf </p>
            // explode akan membuat array potongan html
            $rawSlides = explode('</p>', $content);
            
            foreach($rawSlides as $rawSlide) {
                // Bersihkan spasi
                $rawSlide = trim($rawSlide);

                // Skip jika slide benar-benar kosong (hanya spasi atau tag kosong)
                if (empty($rawSlide) || $rawSlide == '<p>') {
                    continue;
                }

                // 1. AMBIL GAMBAR (Jika ada)
                $imageSrc = null;
                if (preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $rawSlide, $match)) {
                    $imageSrc = $match['src'];
                }

                // 2. BERSIHKAN TEKS
                // Buang tag <img> dari teks agar tidak muncul dobel
                $cleanText = preg_replace('/<img[^>]+>/i', '', $rawSlide);
                
                // Pastikan teks punya pembuka <p> (karena explode mungkin memotongnya)
                // Kita cek pakai strpos, kalau tidak ketemu <p di awal, kita tambahkan.
                if (strpos($cleanText, '<p') !== 0) {
                    $cleanText = '<p>' . $cleanText;
                }
                
                // Tambahkan penutup </p> kembali
                $cleanText .= '</p>';

                // Masukkan ke data
                $slides[] = [
                    'text' => $cleanText,
                    'image' => $imageSrc
                ];
            }
        }

        // FALLBACK: Jika karena suatu hal slide kosong, isi default agar tidak error
        if (empty($slides)) {
            $slides[] = [
                'text' => '<p>Konten materi sedang disiapkan oleh Profesor.</p>',
                'image' => null
            ];
        }

        return view('topic.show', compact('topic', 'slides'));
    }
}
// --- 1. SETUP DATA ---
// Data diambil dari window.topicData yang di-set oleh Blade
const quizzes = window.topicData?.quizzes || [];
console.log('Quiz Data:', quizzes); // <-- DEBUGGING LINE

const slideElements = document.querySelectorAll('.story-slide');
const totalSlides = slideElements.length;
const defaultImage = window.topicData?.defaultImage || '';
const storagePath = window.topicData?.storagePath || '';
const scoreStoreRoute = window.topicData?.scoreStoreRoute || '';
const topicId = window.topicData?.topicId || 0;
const csrfToken = window.topicData?.csrfToken || '';

let currentSlide = 0;
let currentQIndex = 0;
let score = 0;
let isAnswering = false;

// --- SWIPE LOGIC (REFACTORED INTO AN OBJECT) ---
// HARUS didefinisikan di awal untuk menghindari ReferenceError
// Menggunakan var untuk menghindari Temporal Dead Zone issue
var swipeGame = {
    cardElement: null,
    startX: 0,
    currentX: 0,
    isDragging: false,
    // Simpan referensi event handler untuk bisa dihapus nanti
    boundStartDrag: null,
    boundDrag: null,
    boundEndDrag: null,

    init() {
        // Hapus event listener lama jika ada
        this.destroy();

        this.cardElement = document.getElementById('swipe-card');
        if (!this.cardElement) return;

        // Bind event handlers dan simpan referensinya
        this.boundStartDrag = this.startDrag.bind(this);
        this.boundDrag = this.drag.bind(this);
        this.boundEndDrag = this.endDrag.bind(this);

        // Add mouse and touch event listeners
        this.cardElement.addEventListener('mousedown', this.boundStartDrag);
        this.cardElement.addEventListener('touchstart', this.boundStartDrag);

        document.addEventListener('mousemove', this.boundDrag);
        document.addEventListener('touchmove', this.boundDrag);

        document.addEventListener('mouseup', this.boundEndDrag);
        document.addEventListener('touchend', this.boundEndDrag);
        
        this.resetPosition();
    },

    destroy() {
        // Hapus event listener jika ada
        if (this.cardElement && this.boundStartDrag) {
            this.cardElement.removeEventListener('mousedown', this.boundStartDrag);
            this.cardElement.removeEventListener('touchstart', this.boundStartDrag);
        }

        if (this.boundDrag) {
            document.removeEventListener('mousemove', this.boundDrag);
            document.removeEventListener('touchmove', this.boundDrag);
        }

        if (this.boundEndDrag) {
            document.removeEventListener('mouseup', this.boundEndDrag);
            document.removeEventListener('touchend', this.boundEndDrag);
        }

        // Reset state
        this.isDragging = false;
        this.currentX = 0;
        this.startX = 0;
    },

    startDrag(e) {
        if (isAnswering || !this.cardElement) return;
        this.isDragging = true;
        this.startX = (e.type === 'touchstart') ? e.touches[0].clientX : e.clientX;
        this.cardElement.style.transition = 'none';
    },

    drag(e) {
        if (!this.isDragging || !this.cardElement) return;
        let clientX = (e.type === 'touchmove') ? e.touches[0].clientX : e.clientX;
        this.currentX = clientX - this.startX;
        let rotate = this.currentX * 0.1;
        this.cardElement.style.transform = `translateX(${this.currentX}px) rotate(${rotate}deg)`;

        if (this.currentX > 0) {
            document.getElementById('stamp-fact').style.opacity = Math.min(this.currentX / 100, 1);
            document.getElementById('stamp-myth').style.opacity = 0;
        } else {
            document.getElementById('stamp-myth').style.opacity = Math.min(Math.abs(this.currentX) / 100, 1);
            document.getElementById('stamp-fact').style.opacity = 0;
        }
    },

    endDrag(e) {
        if (!this.isDragging || !this.cardElement) return;
        this.isDragging = false;
        this.cardElement.style.transition = 'transform 0.3s ease';

        if (this.currentX > 100) {
            this.cardElement.style.transform = `translateX(1000px) rotate(30deg)`;
            setTimeout(() => {
                checkAnswer('true');
            }, 300);
        } else if (this.currentX < -100) {
            this.cardElement.style.transform = `translateX(-1000px) rotate(-30deg)`;
            setTimeout(() => {
                checkAnswer('false');
            }, 300);
        } else {
            this.resetPosition();
        }
        this.currentX = 0; // Reset currentX
    },

    resetPosition() {
        if (!this.cardElement) return;
        this.cardElement.style.transform = 'translateX(0px) rotate(0deg)';
        document.getElementById('stamp-fact').style.opacity = 0;
        document.getElementById('stamp-myth').style.opacity = 0;
    },

    trigger(direction) {
        if (isAnswering || !this.cardElement) return;
        this.cardElement.style.transition = 'transform 0.5s ease';
        if (direction === 'right') {
            this.cardElement.style.transform = `translateX(1000px) rotate(30deg)`;
            setTimeout(() => {
                checkAnswer('true');
            }, 500);
        } else {
            this.cardElement.style.transform = `translateX(-1000px) rotate(-30deg)`;
            setTimeout(() => {
                checkAnswer('false');
            }, 500);
        }
    }
};

// --- FUNCTIONS TO BE CALLED FROM HTML ---
function triggerSwipe(direction) {
    swipeGame.trigger(direction);
}

// --- LOGIKA STORY MODE ---
function showSlide(index) {
    slideElements.forEach(el => el.style.display = 'none');
    const activeSlide = document.getElementById('slide-' + index);
    if (activeSlide) {
        activeSlide.style.display = 'block';
        const imgElement = document.getElementById('dynamic-image');
        const defaultMascot = document.getElementById('default-mascot');
        let slideImageSrc = activeSlide.getAttribute('data-image');

        if (slideImageSrc && slideImageSrc.trim() !== "") {
            imgElement.src = storagePath + "/" + slideImageSrc;
            imgElement.style.display = 'inline-block';
            defaultMascot.style.display = 'none';
        } else if (defaultImage) {
            imgElement.src = defaultImage;
            imgElement.style.display = 'inline-block';
            defaultMascot.style.display = 'none';
        } else {
            imgElement.style.display = 'none';
            defaultMascot.style.display = 'inline-block';
        }
    }
    document.getElementById('btn-prev').style.display = index === 0 ? 'none' : 'inline-block';
    if (index >= totalSlides - 1) {
        document.getElementById('btn-next').style.display = 'none';
        document.getElementById('btn-quiz').style.display = 'inline-block';
    } else {
        document.getElementById('btn-next').style.display = 'inline-block';
        document.getElementById('btn-quiz').style.display = 'none';
    }
    let percent = Math.round(((index + 1) / totalSlides) * 100);
    document.getElementById('read-progress').style.width = percent + '%';
    document.getElementById('progress-text').innerText = percent + '%';
}

function nextSlide() {
    if (currentSlide < totalSlides - 1) {
        currentSlide++;
        showSlide(currentSlide);
    }
}

function prevSlide() {
    if (currentSlide > 0) {
        currentSlide--;
        showSlide(currentSlide);
    }
}

function enterQuizMode() {
    document.getElementById('story-mode').style.display = 'none'; // Sembunyikan Story
    document.getElementById('quiz-mode').style.display = 'block'; // Tampilkan Kuis
    startQuizGame();
}
// --- LOGIKA BARU: CEK MATERI ---
if (totalSlides > 0) {
    // Jika ada slide, mulai Story Mode dari slide 0
    showSlide(0);
} else {
    document.getElementById('story-mode').style.display = 'none';
    enterQuizMode();
}

// --- LOGIKA KUIS ---
const sfxCorrect = new Audio('https://actions.google.com/sounds/v1/cartoon/clang_and_wobble.ogg');
const sfxWrong = new Audio('https://actions.google.com/sounds/v1/cartoon/cartoon_boing.ogg');

function startQuizGame() {
    if (!quizzes || quizzes.length === 0) {
        alert("Belum ada soal!");
        return;
    }
    showQuestion();
}

function showQuestion() {
    let q = quizzes[currentQIndex];
    isAnswering = false;

    // Sembunyikan semua layout dulu termasuk result screen
    document.getElementById('question-loading').style.display = 'none';
    document.getElementById('layout-multiple-choice').style.display = 'none';
    document.getElementById('layout-swipe').style.display = 'none';
    document.getElementById('layout-sequence').style.display = 'none';
    document.getElementById('result-screen').style.display = 'none'; // Pastikan result screen disembunyikan

    // Update Progress
    let progressPercent = ((currentQIndex) / quizzes.length) * 100;
    document.getElementById('quiz-progress-bar').style.width = progressPercent + "%";
    document.getElementById('quiz-status').innerText = "Soal " + (currentQIndex + 1) + " dari " + quizzes.length;

    // --- PILIH LAYOUT SESUAI TIPE ---
    if (q.type === 'sequence') {
        setupSequenceMode(q); // <--- Fungsi Baru
    } else if (q.type === 'true_false' || (!q.option_a && !q.option_b)) {
        setupSwipeMode(q);
    } else {
        setupMultipleChoiceMode(q);
    }
}

function setupMultipleChoiceMode(q) {
    document.getElementById('layout-multiple-choice').style.display = 'block';

    let imageUrl = q.image ? storagePath + "/" + q.image : null;
    const mcImg = document.getElementById('mc-image');
    if (imageUrl) {
        mcImg.src = imageUrl;
        mcImg.style.display = 'inline-block';
    } else {
        mcImg.style.display = 'none';
    }

    document.getElementById('mc-question-text').innerText = q.question;
    updateButton('btn-a', q.option_a);
    updateButton('btn-b', q.option_b);
    updateButton('btn-c', q.option_c);
    updateButton('btn-d', q.option_d);

    // RESET STYLE TOMBOL (FIXED)
    document.querySelectorAll('.answer-btn').forEach(btn => {
        btn.className = 'btn w-100 p-4 fs-5 text-start answer-btn'; // Reset ke class dasar
        btn.disabled = false;
    });
}

function setupSwipeMode(q) {
    document.getElementById('layout-swipe').style.display = 'block';
    let imageUrl = q.image ? storagePath + "/" + q.image : null;
    const swipeImg = document.getElementById('swipe-image');
    const noImgText = document.getElementById('swipe-no-image');

    if (imageUrl) {
        swipeImg.src = imageUrl;
        swipeImg.style.display = 'block';
        noImgText.style.display = 'none';
    } else {
        swipeImg.style.display = 'none';
        noImgText.style.display = 'block';
    }

    document.getElementById('swipe-question-text').innerText = q.question;
    
    // Reset kartu sebelum inisialisasi
    const cardElement = document.getElementById('swipe-card');
    if (cardElement) {
        cardElement.style.transform = 'translateX(0px) rotate(0deg)';
        cardElement.style.transition = 'none';
    }
    document.getElementById('stamp-fact').style.opacity = 0;
    document.getElementById('stamp-myth').style.opacity = 0;
    
    // Initialize the swipe game object
    swipeGame.init();
}

function updateButton(btnId, text) {
    const btn = document.getElementById(btnId);
    if (text && text.trim() !== "") {
        btn.innerText = text;
        btn.parentElement.style.display = 'block';
    } else {
        btn.parentElement.style.display = 'none';
    }
}

// --- CHECK ANSWER (FIXED COLOR LOGIC) ---
function checkAnswer(choice) {
    if (isAnswering) return;
    isAnswering = true;

    let q = quizzes[currentQIndex];
    let correctAnswer = String(q.correct_answer).toLowerCase();
    let isCorrect = false;

    if (choice === correctAnswer) isCorrect = true;
    else if (choice === 'true' && (correctAnswer === 'a' || correctAnswer === 'true' || correctAnswer === 'benar'))
        isCorrect = true;
    else if (choice === 'false' && (correctAnswer === 'b' || correctAnswer === 'false' || correctAnswer ===
            'salah')) isCorrect = true;

    if (isCorrect) {
        score += 10;
        sfxCorrect.play().catch(e => {});
    } else {
        sfxWrong.play().catch(e => {});
    }

    // VISUAL FEEDBACK TOMBOL (PENTING!)
    if (document.getElementById('layout-multiple-choice').style.display !== 'none') {
        let btn = document.getElementById('btn-' + choice);
        if (btn) {
            // Hapus style default, tambah style baru
            if (isCorrect) {
                btn.classList.add('answer-correct');
            } else {
                btn.classList.add('answer-wrong');

                // Highlight jawaban benar
                if (['a', 'b', 'c', 'd'].includes(correctAnswer)) {
                    document.getElementById('btn-' + correctAnswer).classList.add('answer-correct');
                }
            }
        }
    }

    setTimeout(() => {
        isAnswering = false; // Reset flag
        currentQIndex++;
        if (currentQIndex < quizzes.length) {
            showQuestion();
        } else {
            showResult();
        }
    }, 1500);
}

// --- LOGIKA PUZZLE URUTAN (SEQUENCE) ---
let sortableInstance = null;

function setupSequenceMode(q) {
    document.getElementById('layout-sequence').style.display = 'block';
    document.getElementById('seq-question-text').innerText = q.question;

    const listEl = document.getElementById('sequence-list');
    listEl.innerHTML = ''; // Reset isi list

    // 1. Siapkan Data Item (A, B, C, D)
    let items = [{
            id: 'a',
            text: q.option_a,
            image: q.option_a_image || null
        },
        {
            id: 'b',
            text: q.option_b,
            image: q.option_b_image || null
        },
        {
            id: 'c',
            text: q.option_c,
            image: q.option_c_image || null
        },
        {
            id: 'd',
            text: q.option_d,
            image: q.option_d_image || null
        }
    ].filter(i => i.text); // Hanya ambil yang tidak kosong

    // 2. Acak Urutan (Shuffle) agar user menyusun ulang
    items = items.sort(() => Math.random() - 0.5);

    // 3. Render ke HTML dengan card style
    items.forEach((item, index) => {
        let li = document.createElement('li');
        li.className = 'seq-item';
        li.setAttribute('data-id', item.id); // ID ini kunci jawabannya
        
        // Siapkan gambar atau placeholder
        let imageHtml = '';
        if (item.image) {
            let imageUrl = storagePath + "/" + item.image;
            imageHtml = `<img src="${imageUrl}" alt="${item.text}" onerror="this.parentElement.innerHTML='<div class=\\'seq-image-placeholder\\'>📦</div>'">`;
        } else {
            // Placeholder dengan emoji berdasarkan teks atau index
            const getEmoji = (text, idx) => {
                const textLower = text.toLowerCase();
                if (textLower.includes('evaporasi') || textLower.includes('uap') || textLower.includes('air')) return '💨';
                if (textLower.includes('kondensasi') || textLower.includes('awan')) return '☁️';
                if (textLower.includes('presipitasi') || textLower.includes('hujan')) return '🌧️';
                if (textLower.includes('langkah') || textLower.includes('step')) return '👣';
                // Default emoji berdasarkan index
                const defaults = ['1️⃣', '2️⃣', '3️⃣', '4️⃣', '🌊', '☁️', '💧', '🌧️'];
                return defaults[idx % defaults.length];
            };
            imageHtml = `<div class="seq-image-placeholder">${getEmoji(item.text, index)}</div>`;
        }
        
        li.innerHTML = `
            <div class="seq-item-content">
                <div class="seq-drag-handle">
                    <i class="bi bi-grip-vertical"></i>
                </div>
                <div class="seq-image-container">
                    ${imageHtml}
                </div>
                <div class="seq-text">${item.text}</div>
                <div class="seq-arrow">
                    <i class="bi bi-arrows-move"></i>
                </div>
            </div>
        `;
        listEl.appendChild(li);
    });

    // 4. Aktifkan SortableJS
    if (sortableInstance) sortableInstance.destroy(); // Hapus instance lama jika ada
    sortableInstance = new Sortable(listEl, {
        animation: 200,
        ghostClass: 'sortable-ghost',
        dragClass: 'sortable-drag',
        handle: '.seq-drag-handle' // Hanya bisa drag dari handle
    });
}

function checkSequenceAnswer() {
    if (isAnswering) return;

    // 1. Ambil urutan saat ini
    const listEl = document.getElementById('sequence-list');
    const currentOrder = Array.from(listEl.children).map(li => li.getAttribute('data-id'));

    // 2. Kunci Jawaban yang Benar (Selalu A -> B -> C -> D)
    // Kita cek apakah urutan arraynya ['a', 'b', 'c', 'd'] (sesuai jumlah item)
    const correctOrder = ['a', 'b', 'c', 'd'].slice(0, currentOrder.length);

    // 3. Bandingkan
    const isCorrect = JSON.stringify(currentOrder) === JSON.stringify(correctOrder);

    isAnswering = true;
    if (isCorrect) {
        score += 10;
        sfxCorrect.play().catch(e => {});

        // Efek Hijau dengan animasi
        Array.from(listEl.children).forEach((li, index) => {
            setTimeout(() => {
                li.style.background = 'linear-gradient(135deg, #11998e 0%, #38ef7d 100%)';
                li.style.borderColor = '#38ef7d';
                li.style.boxShadow = '0 0 20px rgba(56, 239, 125, 0.5)';
                li.style.transform = 'scale(1.02)';
                li.style.color = '#fff';
                // Update text color
                const textEl = li.querySelector('.seq-text');
                if (textEl) textEl.style.color = '#fff';
            }, index * 100);
        });
    } else {
        sfxWrong.play().catch(e => {});

        // Efek Merah dengan animasi shake
        Array.from(listEl.children).forEach((li, index) => {
            setTimeout(() => {
                li.style.background = 'linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%)';
                li.style.borderColor = '#ff4b2b';
                li.style.boxShadow = '0 0 20px rgba(255, 75, 43, 0.5)';
                li.style.animation = 'shake 0.5s';
                li.style.color = '#fff';
                // Update text color
                const textEl = li.querySelector('.seq-text');
                if (textEl) textEl.style.color = '#fff';
            }, index * 50);
        });
    }

    // Lanjut soal berikutnya
    setTimeout(() => {
        isAnswering = false; // Reset flag
        currentQIndex++;
        if (currentQIndex < quizzes.length) {
            showQuestion();
        } else {
            showResult();
        }
    }, 2000); // Tunggu agak lama biar puas lihat hasilnya
}

// --- FINISH ---
function showResult() {
    document.getElementById('layout-multiple-choice').style.display = 'none';
    document.getElementById('layout-swipe').style.display = 'none';
    document.getElementById('layout-sequence').style.display = 'none'; // Sembunyikan layout sequence juga
    document.getElementById('result-screen').style.display = 'block';
    document.getElementById('final-score').innerText = score;
    document.getElementById('quiz-progress-bar').style.width = "100%";
    document.getElementById('quiz-progress-bar').classList.add('bar-completed'); // Ubah warna jadi emas
    saveScoreToDatabase(score);
}

function saveScoreToDatabase(finalScore) {
    fetch(scoreStoreRoute, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken
        },
        body: JSON.stringify({
            topic_id: topicId,
            score: finalScore
        })
    });
}
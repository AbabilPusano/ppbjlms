# Soul — LMS PPBJ

## Tentang dokumen ini

Dokumen ini adalah kompas project LMS PPBJ. Ia menjelaskan alasan produk ini dibuat, pengalaman yang ingin diwujudkan, dan prinsip yang harus dijaga ketika manusia maupun AI mengembangkan project.

`soul.md` bukan backlog, spesifikasi endpoint, atau catatan implementasi. Detail pekerjaan tetap berada di `todo-list.md`, test, migration, dan kode. Jika pilihan teknis bertentangan dengan jiwa produk di dokumen ini, pilihan tersebut perlu ditinjau kembali.

## Identitas

LMS PPBJ adalah ruang belajar bahasa Jepang untuk penutur bahasa Indonesia. Materi disusun bertahap mengikuti level JLPT N5 sampai N1 dan tersedia melalui web serta API untuk aplikasi mobile.

Produk ini mempertemukan tiga kebutuhan:

- siswa membutuhkan jalur belajar yang jelas, ramah, dan memberi rasa maju;
- guru membutuhkan alat yang praktis untuk menyusun serta mengelola materi;
- admin membutuhkan pengelolaan akun dan akses yang aman serta mudah diaudit.

## Misi

Membuat proses belajar bahasa Jepang terasa terarah, mudah dimulai, dan konsisten dijalani, tanpa mengorbankan ketepatan materi maupun kendali guru.

## Janji produk

Setiap interaksi harus membantu pengguna melakukan satu hal penting dengan lebih jelas: belajar, mengajar, atau mengelola.

Siswa tidak seharusnya merasa tersesat. Guru tidak seharusnya dipaksa memahami kerumitan teknis. Admin tidak seharusnya menebak apakah sebuah tindakan aman.

## Prinsip utama

### 1. Belajar adalah pusatnya

Fitur, animasi, XP, streak, gems, dan elemen permainan hanya bernilai jika memperkuat proses belajar. Gamifikasi tidak boleh mendorong klik kosong, membuat siswa takut salah, atau menutupi pemahaman yang lemah.

### 2. Kemajuan harus terlihat dan bermakna

Siswa perlu mengetahui posisi mereka, materi yang telah selesai, kesalahan yang perlu diperbaiki, dan langkah berikutnya. Progress harus berasal dari aktivitas nyata dan tersimpan secara konsisten.

### 3. Bertahap, bukan membebani

Materi bergerak dari pengenalan menuju latihan, umpan balik, dan penguatan. Hiragana, katakana, kanji, kosakata, tata bahasa, serta cerita harus disajikan sesuai tingkat kemampuan pengguna.

### 4. Kesalahan adalah bagian dari belajar

Umpan balik harus cepat, spesifik, dan membantu. Jangan mempermalukan siswa atau hanya menyatakan “salah” tanpa petunjuk yang berguna. Berikan kesempatan untuk memahami lalu mencoba kembali.

### 5. Guru tetap memegang kendali

AI boleh membantu menghasilkan draf cerita atau materi, tetapi bukan sumber kebenaran final. Konten hasil AI harus dapat ditinjau, diedit, dibuat ulang, dan disetujui guru sebelum digunakan siswa.

### 6. Sederhana di permukaan, kuat di dalam

Antarmuka harus terasa ringan meskipun domain dan data di belakangnya kompleks. Gunakan istilah yang dipahami pengguna, tampilkan hanya aksi yang relevan, dan berikan konsekuensi yang jelas sebelum tindakan penting.

### 7. Inklusif dan dapat diakses

Pengalaman utama harus dapat digunakan pada desktop dan mobile, dengan keyboard, fokus yang terlihat, label yang jelas, kontras yang cukup, serta ukuran target sentuh yang nyaman. Informasi penting tidak boleh disampaikan hanya melalui warna, ikon, atau suara.

## Karakter pengalaman

LMS PPBJ terasa:

- hangat, tetapi tidak kekanak-kanakan;
- memotivasi, tetapi tidak manipulatif;
- ringkas, tetapi tidak membingungkan;
- modern, tetapi tidak mengejar tren tanpa tujuan;
- bernuansa Jepang, tetapi tetap akrab bagi pengguna Indonesia;
- serius terhadap akurasi, tetapi memberi ruang aman untuk salah.

## Bahasa dan nada

Bahasa utama antarmuka adalah Bahasa Indonesia yang natural, singkat, dan konsisten. Gunakan bahasa Jepang ketika menjadi bagian dari materi, lalu berikan romaji atau arti saat level dan konteks membutuhkannya.

Copy antarmuka harus:

- menyebut tindakan secara langsung, misalnya “Simpan cerita” atau “Lanjut belajar”;
- menjelaskan cara memperbaiki masalah, bukan sekadar menyatakan gagal;
- menghindari jargon teknis di hadapan siswa dan guru;
- tidak membuat klaim kemajuan yang tidak didukung data;
- menjaga encoding UTF-8 agar aksara Jepang tampil dengan benar.

## Pengalaman per peran

### Siswa

Siswa datang untuk belajar, bukan mengelola sistem. Berikan jalur belajar, materi, latihan, cerita, referensi huruf, serta progress yang mudah dipahami. Aksi utama pada setiap layar harus jelas dan gangguan harus minimal.

### Guru

Guru menyusun pengalaman belajar. Pengelolaan unit, materi, langkah, soal, dan cerita harus efisien serta aman. Draf dan preview harus mudah diperiksa sebelum dipublikasikan. Operasi massal atau import harus memberi validasi dan laporan kesalahan yang dapat ditindaklanjuti.

### Admin

Admin menjaga akses dan kesehatan sistem. Batas kewenangan harus tegas, perubahan akun penting harus dapat dipahami, dan tindakan berisiko harus memiliki konfirmasi yang proporsional.

## Prinsip konten pembelajaran

- Selaraskan kosakata, kanji, panjang kalimat, dan pola tata bahasa dengan level JLPT yang dipilih.
- Utamakan contoh yang natural dan konteks yang mudah dipahami pembelajar Indonesia.
- Bedakan materi pengenalan, latihan, kuis, dan cerita secara jelas.
- Simpan jawaban benar dan penjelasan dalam format yang dapat divalidasi serta diuji.
- Jangan menganggap konten buatan AI benar hanya karena formatnya valid.
- Tinjau ulang ejaan, romaji, arti, tingkat kesulitan, dan kesesuaian budaya sebelum publikasi.

## Prinsip engineering

### Data adalah sumber kebenaran

Halaman web dan API harus merepresentasikan data domain yang sama. Hindari mempertahankan data dummy setelah alur sudah terhubung ke database. Progress, urutan materi, role, dan status publikasi harus memiliki satu sumber kebenaran yang jelas.

### Kontrak harus konsisten

Jaga penamaan, relasi, format JSON, status HTTP, validasi, dan bentuk response tetap konsisten. Perubahan kontrak web, API, atau database harus mempertimbangkan kompatibilitas klien mobile dan data yang sudah ada.

### Aman secara default

- Semua route privat wajib melalui autentikasi dan otorisasi berbasis role.
- Input dari form, import, API, dan AI selalu dianggap tidak tepercaya sampai divalidasi.
- Password, token, API key, dan isi `.env` tidak boleh masuk log, response, fixture publik, atau commit.
- Tindakan destruktif harus dibatasi, dikonfirmasi, dan sebisa mungkin dapat dipulihkan.
- Pesan error untuk pengguna tidak boleh membocorkan detail internal sistem.

### Perubahan kecil yang utuh

Lebih baik menyelesaikan satu alur dari UI sampai penyimpanan dan test daripada menambah banyak layar yang tampak selesai tetapi tidak berfungsi. Jangan meninggalkan tombol mati, state palsu, atau keberhasilan semu.

### Bukti, bukan asumsi

Perubahan penting harus didukung test atau pemeriksaan yang relevan. Bug diperbaiki pada akar masalahnya. Ketika keputusan dibuat berdasarkan asumsi, tuliskan asumsi tersebut dan buat mudah untuk diverifikasi.

### Hormati struktur project

Gunakan konvensi Laravel, Blade, Vite, dan Tailwind yang sudah dipilih. Utamakan komponen/layout yang dapat digunakan ulang, controller yang fokus, validasi terpusat, relationship Eloquent yang eksplisit, dan migration yang dapat di-rollback dengan aman.

## Urutan prioritas keputusan

Ketika dua pilihan sama-sama masuk akal, prioritaskan:

1. kebenaran dan keamanan data;
2. hasil belajar siswa;
3. kejelasan bagi pengguna;
4. kendali serta efisiensi guru;
5. aksesibilitas dan performa;
6. kemudahan pemeliharaan;
7. keindahan visual;
8. kebaruan teknis.

## Definition of done

Sebuah perubahan dianggap selesai ketika:

- alur utama benar-benar dapat digunakan, bukan hanya terlihat selesai;
- loading, empty, success, validation, dan error state yang relevan ditangani;
- autentikasi dan role diperiksa pada sisi server;
- data tersimpan dan dibaca kembali secara konsisten;
- tampilan utama layak digunakan di desktop dan mobile;
- teks Indonesia dan aksara Jepang tampil tanpa kerusakan encoding;
- test yang relevan lulus dan tidak merusak alur yang sudah ada;
- dokumentasi atau kontrak diperbarui jika perilaku publik berubah;
- tidak ada secret, debug output, atau artefak sementara yang ikut terkirim.

## Yang bukan tujuan

LMS PPBJ bukan sekadar clone aplikasi belajar populer, bukan arena mengumpulkan poin tanpa pemahaman, dan bukan mesin publikasi konten AI tanpa pengawasan. Project ini juga tidak perlu mengadopsi teknologi baru hanya agar terlihat modern.

## Pertanyaan sebelum mengirim perubahan

Sebelum sebuah fitur atau perubahan diterima, tanyakan:

1. Apakah ini membuat belajar, mengajar, atau mengelola menjadi lebih jelas?
2. Apakah pengguna memahami apa yang terjadi dan apa langkah berikutnya?
3. Apakah data, akses, serta kegagalan ditangani dengan aman?
4. Apakah materi bahasa Jepang tetap akurat dan sesuai level?
5. Apakah solusi ini dapat dirawat tanpa menciptakan dua sumber kebenaran?

Jika jawabannya belum jelas, perubahan tersebut belum selesai.

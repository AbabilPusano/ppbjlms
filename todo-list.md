# TODO List Project LMS PPBJ

Dokumen ini adalah checklist besar untuk melanjutkan project dari kondisi sekarang. Isinya mencakup UI, backend, database, auth, testing, build, deployment, dan housekeeping.

## Status Singkat Saat Ini

- Project target: Laravel 13 + Blade + Vite/Tailwind.
- UI dummy/static sudah ada untuk dashboard belajar, login, stories, letters, profile, edit profile, guru dashboard, dan lesson flow.
- Backend LMS belum aktif: belum ada controller, API route, service, policy, request validation, atau model domain selain `User`.
- Database LMS sudah mulai dibuat lewat migration, tetapi belum ada Eloquent model/relationship/seeder lengkap.
- Build Vite sudah bisa jalan, tetapi Node lokal masih `20.18.0`; Vite minta `20.19+` atau `22.12+`.
- Ada perubahan lokal `.env.example` terhapus yang belum jelas asalnya dan belum di-commit.

## 1. Housekeeping Awal

- [ ] Putuskan apakah `.env.example` yang terhapus mau direstore atau memang dihapus.
- [ ] Pastikan branch kerja yang dipakai jelas: `rafka` atau branch baru.
- [ ] Commit pekerjaan lesson flow dan login compact jika sudah disetujui.
- [ ] Hapus file yang tidak diperlukan dari `public/ui-assets` jika ternyata asset Next default tidak dipakai.
- [ ] Rapikan `public/ui-static/*`: putuskan apakah fallback static tetap dipakai atau hanya Vite build.
- [ ] Update Node.js minimal ke `20.19.0` agar warning Vite hilang.
- [ ] Jalankan ulang `composer install`, `npm install`, `npm run build`, dan `php artisan test` setelah environment stabil.

## 2. UI Student

- [x] Dashboard belajar dummy/static.
- [x] Sidebar student desktop/tablet/mobile.
- [x] Login card compact.
- [x] Stories list dummy.
- [x] Letters page dummy.
- [x] Profile page dummy.
- [x] Edit profile dummy.
- [x] Lesson flow dummy.
- [ ] Buat story reader `/stories/{id}` dengan reader, page navigation, translation toggle, dan dictionary bottom sheet.
- [ ] Buat placeholder `/quest`.
- [ ] Buat placeholder `/shop`.
- [ ] Buat `/settings/account` atau putuskan route ini tidak dipakai.
- [ ] Hubungkan semua link dummy agar tidak ada tombol mati yang membingungkan.
- [ ] Cek responsive semua halaman: desktop, tablet, mobile.
- [ ] Cek teks Jepang/emoji agar UTF-8 bersih dan tidak mojibake.
- [ ] Rapikan copywriting UI agar konsisten formal/padat.
- [ ] Kurangi CSS yang tidak terpakai dari `resources/css/app.css` jika sudah masuk tahap production.

## 3. UI Lesson Flow

- [x] Route dummy `/lesson/n5/unit/{unit}/level/{level}`.
- [x] New-letter screen.
- [x] Select-image question.
- [x] Translate/word-bank question.
- [x] Feedback correct/wrong.
- [x] Score screen.
- [ ] Tambahkan variasi soal lebih lengkap: listening, drag-order, multiple choice text-only, guidebook.
- [ ] Tambahkan state lives/heart yang benar-benar berkurang saat salah.
- [ ] Tambahkan keyboard accessibility untuk option dan word bank.
- [ ] Simpan progress sementara di localStorage untuk UI-only mode.
- [ ] Saat backend siap, ganti dummy questions menjadi data dari API.

## 4. UI Guru/Admin

- [x] Dashboard guru home dummy.
- [ ] Buat layout guru reusable untuk semua halaman admin.
- [ ] Buat `/guru/dashboard/stories` UI dummy.
- [ ] Buat `/guru/dashboard/units` UI dummy.
- [ ] Buat `/guru/dashboard/nodes` UI dummy.
- [ ] Buat `/guru/dashboard/questions` UI dummy.
- [ ] Buat `/guru/dashboard/nodes/{nodeId}/questions` UI dummy.
- [ ] Buat component/form untuk create/edit section.
- [ ] Buat component/form untuk create/edit unit.
- [ ] Buat component/form untuk create/edit node.
- [ ] Buat component/form untuk create/edit question.
- [ ] Buat component/form untuk create/edit story.
- [ ] Buat UI reorder/drag handle dummy untuk unit/node/question/story.
- [ ] Buat UI import/export XLSX dummy atau tunda sampai backend siap.
- [ ] Pastikan admin UI responsive dan navigasi mobile drawer berfungsi.

## 5. Database dan Migration

- [x] Migration awal untuk `sections`, `units`, `nodes`, `questions`, `stories`, `user_progress`, `question_responses`.
- [ ] Audit migration `2026_07_09_004545_update_users_and_create_lms_tables.php`.
- [ ] Pertimbangkan rename kolom ke style Laravel snake_case: `section_id`, `unit_id`, `node_id`, `user_id`, `created_at`, `updated_at`.
- [ ] Jika tetap memakai camelCase, pastikan semua model set konstanta timestamp dan foreign key manual.
- [ ] Perbaiki `database/schema.sql`: ada typo `ADD COLUMN gems ... AFTER gems`; harusnya setelah `totalXP`.
- [ ] Sinkronkan `schema.sql` dengan migration atau hapus jika tidak dipakai.
- [ ] Tentukan DB target: SQLite lokal, MySQL, atau PostgreSQL.
- [ ] Tambahkan index tambahan untuk query umum: section order, unit order, node order, question order.
- [ ] Tentukan format JSON untuk `questions.options`, `questions.correctAnswer`, `questions.relatedCharacters`.
- [ ] Tentukan format JSON untuk `stories.content`.
- [ ] Tambahkan constraint/enum application-level untuk role, question type, node type, unit type, progress status.
- [ ] Buat rollback migration yang aman untuk semua perubahan schema.

## 6. Eloquent Model dan Relationship

- [ ] Update `User` fillable untuk `username`, `role`, `bio`, `avatar`, `streak`, `totalXP`, `gems`, `league`.
- [ ] Tambahkan casts di `User`: `streak`, `totalXP`, `gems`.
- [ ] Buat model `Section`.
- [ ] Buat model `Unit`.
- [ ] Buat model `Node`.
- [ ] Buat model `Question`.
- [ ] Buat model `Story`.
- [ ] Buat model `UserProgress`.
- [ ] Buat model `QuestionResponse`.
- [ ] Definisikan relationship:
  - [ ] `Section hasMany Unit`.
  - [ ] `Section hasMany Story`.
  - [ ] `Unit belongsTo Section`.
  - [ ] `Unit hasMany Node`.
  - [ ] `Node belongsTo Unit`.
  - [ ] `Node hasMany Question`.
  - [ ] `Question belongsTo Node`.
  - [ ] `User hasMany UserProgress`.
  - [ ] `User hasMany QuestionResponse`.
- [ ] Tambahkan casts JSON untuk `options`, `correctAnswer`, `relatedCharacters`, `content`.
- [ ] Jika ID pakai string, set `$incrementing = false` dan `$keyType = 'string'`.
- [ ] Tentukan strategi ID: UUID, ULID, atau string manual.

## 7. Seeder dan Dummy Data Backend

- [ ] Update `DatabaseSeeder` agar user test memiliki `username` dan `role`, karena migration membuat kolom itu required.
- [ ] Buat `SectionSeeder`: N5, N4, N3, N2, N1.
- [ ] Buat `UnitSeeder`: beberapa unit per section.
- [ ] Buat `NodeSeeder`: lesson, practice, quiz, guidebook.
- [ ] Buat `QuestionSeeder` dengan format JSON yang final.
- [ ] Buat `StorySeeder` dengan content reader yang final.
- [ ] Buat `UserProgressSeeder`.
- [ ] Buat `QuestionResponseSeeder`.
- [ ] Pastikan `php artisan migrate:fresh --seed` berjalan tanpa error.

## 8. Auth dan Session

- [ ] Tentukan auth stack: Laravel session biasa, Sanctum, Breeze, Fortify, atau custom.
- [ ] Buat login real untuk username/password.
- [ ] Buat logout real.
- [ ] Buat middleware auth untuk halaman student yang butuh login.
- [ ] Buat middleware role guru/admin.
- [ ] Tentukan role final: `guru`, `siswa`, mungkin `admin`.
- [ ] Buat policy/authorization untuk resource guru.
- [ ] Tambahkan CSRF handling untuk form/API yang memakai session.
- [ ] Putuskan apakah profile edit bisa update email/password.
- [ ] Buat reset password jika dibutuhkan.

## 9. Backend Route dan Controller

- [ ] Buat route group student.
- [ ] Buat route group auth.
- [ ] Buat route group guru/admin.
- [ ] Buat controller `AuthController`.
- [ ] Buat controller `ProfileController`.
- [ ] Buat controller `LearningDashboardController`.
- [ ] Buat controller `LessonController`.
- [ ] Buat controller `StoryController`.
- [ ] Buat controller `LetterController` jika data huruf disimpan server-side.
- [ ] Buat controller guru:
  - [ ] `GuruDashboardController`.
  - [ ] `SectionController`.
  - [ ] `UnitController`.
  - [ ] `NodeController`.
  - [ ] `QuestionController`.
  - [ ] `StoryManagementController`.
- [ ] Ganti route preview UI-only menjadi route controller ketika backend siap.
- [ ] Putuskan apakah API memakai `routes/api.php` atau web route returning JSON.

## 10. API/Data Contract

- [ ] Definisikan endpoint `GET /api/session/me`.
- [ ] Definisikan endpoint `POST /login`.
- [ ] Definisikan endpoint `POST /logout`.
- [ ] Definisikan endpoint `GET /api/lms/sections`.
- [ ] Definisikan endpoint `GET /api/lms/units`.
- [ ] Definisikan endpoint `GET /api/lms/nodes`.
- [ ] Definisikan endpoint `GET /api/lms/questions?nodeId=...`.
- [ ] Definisikan endpoint `POST /api/lms/questions/responses`.
- [ ] Definisikan endpoint `GET /api/lms/progress`.
- [ ] Definisikan endpoint `POST /api/lms/progress`.
- [ ] Definisikan endpoint `GET /api/lms/stories`.
- [ ] Definisikan endpoint `GET /api/lms/stories/{id}`.
- [ ] Definisikan endpoint CRUD guru untuk sections/units/nodes/questions/stories.
- [ ] Buat format response standar: `data`, `message`, `errors`.
- [ ] Buat pagination/filter/sort untuk dashboard guru.

## 11. Request Validation

- [ ] Buat Form Request untuk login.
- [ ] Buat Form Request untuk update profile.
- [ ] Buat Form Request untuk create/update section.
- [ ] Buat Form Request untuk create/update unit.
- [ ] Buat Form Request untuk create/update node.
- [ ] Buat Form Request untuk create/update question.
- [ ] Buat Form Request untuk create/update story.
- [ ] Validasi JSON question berdasarkan type soal.
- [ ] Validasi urutan/order agar tidak bentrok.
- [ ] Validasi role dan permission.

## 12. Business Logic LMS

- [ ] Tentukan aturan progress node selesai.
- [ ] Tentukan apakah user boleh skip locked node.
- [ ] Hitung unit status: completed, in_progress, locked.
- [ ] Hitung progress percentage per unit.
- [ ] Hitung active step berikutnya.
- [ ] Log setiap jawaban question.
- [ ] Hitung score lesson.
- [ ] Hitung streak harian.
- [ ] Hitung XP/gems/league jika fitur gamification dipakai.
- [ ] Buat review question random berdasarkan riwayat salah/benar.
- [ ] Buat service khusus `LearningProgressService`.
- [ ] Buat service khusus `QuestionEvaluationService`.

## 13. Integrasi UI ke Backend

- [ ] Ganti dummy dashboard belajar dengan query unit/node/progress.
- [ ] Ganti lesson dummy dengan questions dari database.
- [ ] Simpan response lesson ke backend.
- [ ] Simpan progress node selesai ke backend.
- [ ] Ganti stories dummy dengan stories database.
- [ ] Ganti profile dummy dengan user login.
- [ ] Ganti edit profile dummy dengan update real.
- [ ] Ganti login dummy dengan auth real.
- [ ] Ganti guru dashboard dummy dengan data summary real.
- [ ] Ganti guru CRUD dummy dengan create/update/delete real.
- [ ] Pastikan loading, empty, error state ada di semua halaman.

## 14. Frontend Build dan Asset

- [ ] Update Node.js ke `20.19+`.
- [ ] Putuskan apakah `@rolldown/binding-win32-x64-msvc` tetap ditulis eksplisit di `package.json`.
- [ ] Pastikan `npm install` bersih di mesin baru.
- [ ] Pastikan `npm run build` menghasilkan `public/build/manifest.json`.
- [ ] Putuskan apakah `public/ui-static` fallback diperlukan di production.
- [ ] Jika fallback tidak diperlukan, hapus `public/ui-static` dan fallback layout.
- [ ] Optimalkan ukuran `resources/css/app.css`.
- [ ] Pisahkan CSS per domain jika file terlalu besar: login, student, lesson, guru.
- [ ] Pastikan asset `oni.png` dan `oni.webp` dipakai atau hapus yang tidak dipakai.

## 15. Testing

- [ ] Update test default Laravel agar sesuai project.
- [ ] Tambahkan feature test route public UI: `/`, `/login`, `/stories`, `/letters`, `/profile`, `/guru/dashboard`, `/lesson/...`.
- [ ] Tambahkan test auth login/logout setelah auth real dibuat.
- [ ] Tambahkan test CRUD section/unit/node/question/story.
- [ ] Tambahkan test progress lesson.
- [ ] Tambahkan test response question.
- [ ] Tambahkan test policy role guru/siswa.
- [ ] Tambahkan test seeder dan migration.
- [ ] Jalankan `php artisan test`.
- [ ] Jalankan `npm run build` di CI atau sebelum deploy.

## 16. Security

- [ ] Pastikan password selalu hashed.
- [ ] Jangan expose `correctAnswer` ke client sebelum user check jawaban, kecuali lesson mode memang client-side.
- [ ] Tambahkan authorization untuk semua route guru.
- [ ] Validasi dan sanitize semua JSON input.
- [ ] Hindari mass assignment pada model.
- [ ] Rate limit login.
- [ ] Rate limit API question response jika perlu.
- [ ] Pastikan `.env` tidak masuk Git.
- [ ] Restore/siapkan `.env.example` yang aman.
- [ ] Audit dependency Composer dan npm.

## 17. Deployment

- [ ] Tentukan target deploy: shared hosting, VPS, Laravel Forge, Railway, Render, atau lainnya.
- [ ] Siapkan environment variables production.
- [ ] Siapkan database production.
- [ ] Jalankan migration production.
- [ ] Jalankan seeder awal production jika dibutuhkan.
- [ ] Setup storage link jika ada upload/avatar.
- [ ] Setup queue worker jika memakai queue.
- [ ] Setup scheduler jika streak/reset harian pakai schedule.
- [ ] Build frontend untuk production.
- [ ] Cache config/route/view saat deploy.
- [ ] Tambahkan backup database.

## 18. Dokumentasi

- [ ] Update README dengan cara setup lokal.
- [ ] Dokumentasikan versi PHP, Composer, Node, npm.
- [ ] Dokumentasikan cara migrate dan seed.
- [ ] Dokumentasikan route utama.
- [ ] Dokumentasikan data contract question JSON.
- [ ] Dokumentasikan role dan permission.
- [ ] Dokumentasikan command build/deploy.
- [ ] Simpan brief migrasi UI di `.agents` atau pindahkan ke `docs/`.

## 19. Prioritas Disarankan

1. [ ] Bereskan housekeeping: `.env.example`, Node version, branch/commit.
2. [ ] Audit dan perbaiki migration/schema.
3. [ ] Buat model Eloquent dan relationship.
4. [ ] Buat seeder yang bisa menjalankan data LMS awal.
5. [ ] Implement auth login/logout real.
6. [ ] Implement backend dashboard belajar dan lesson.
7. [ ] Integrasikan UI student ke backend.
8. [ ] Implement story reader.
9. [ ] Implement guru CRUD backend + UI.
10. [ ] Tambahkan test dan dokumentasi setup/deploy.

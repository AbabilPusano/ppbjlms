# UI Migration Brief

Dokumen ini adalah panduan untuk agent berikutnya yang akan bekerja di project tujuan. Fokus tahap berikutnya adalah **recreate/copy UI dari project lama memakai data dummy/static**, tanpa membawa backend, API, auth, database, production data, atau logic bisnis.

## 1. Ringkasan Tujuan Migrasi

- Tujuan utama: membuat ulang tampilan LMS PPBJ dari project lama di project baru.
- Prioritas: UI student-facing terlebih dahulu, lalu lesson flow, lalu login/profile/settings, lalu dashboard guru/admin.
- Data di project tujuan harus dummy/static.
- Jangan redesign. Ikuti struktur visual, spacing, warna, layout, typography, dan interaction pattern dari project lama.
- Jangan mengubah project lama selain dokumentasi ini.
- Jangan copy backend/API/auth/session/proxy/database logic.

## 2. Stack/Teknologi Project Lama

| Area | Teknologi/Library | Catatan UI |
| --- | --- | --- |
| Framework | Next.js `16.1.6` App Router | Semua halaman ada di `app/**/page.tsx`. Banyak halaman adalah client component. |
| React | React `19.2.3`, React DOM `19.2.3` | UI state lokal banyak memakai `useState/useEffect`. |
| Styling | Tailwind CSS v4 via `@import "tailwindcss"` | Lesson flow banyak memakai utility class Tailwind. |
| Styling custom | `app/globals.css` | File global sangat besar, berisi design system, student shell, stories, letters, profile, legacy login/settings, dan helper `ds-*`. |
| CSS Modules | `app/login/login.module.css`, `app/settings/profile/profile.module.css` | Dipakai untuk login dan edit profile modern. |
| Icons | `react-icons` dan Google Material Symbols | Student memakai `react-icons/fi`; guru memakai `<span className="material-symbols-outlined">`. |
| Images | `next/image` | Dipakai di login dan legacy account settings untuk maskot oni. |
| Font | `next/font/google` Geist, external Google Fonts Public Sans, Material Symbols | `app/layout.tsx` load Geist, Public Sans, Material Symbols. |
| State/auth | Zustand, Axios | **Jangan migrasi auth/API dulu**. Untuk UI dummy boleh ganti dengan object lokal. |
| XLSX | `xlsx` | Hanya terkait UI/fitur dashboard guru detail soal import/export. Optional untuk fase UI dummy. |

File konfigurasi penting:

- `package.json`
- `next.config.ts`
- `postcss.config.mjs`
- `tsconfig.json`
- `app/layout.tsx`
- `app/globals.css`

## 3. Struktur Folder Penting Project Lama

```text
app/
  layout.tsx
  globals.css
  page.tsx
  login/
    page.tsx
    login.module.css
  stories/
    page.tsx
    [id]/page.tsx
  letters/page.tsx
  profile/page.tsx
  settings/
    profile/
      page.tsx
      profile.module.css
    account/page.tsx
  quest/page.tsx
  shop/page.tsx
  lesson/n5/
    unit/[unit]/level/[level]/page.tsx
    types/lesson.ts
    data/questions.ts
  guru/dashboard/
    layout.tsx
    page.tsx
    stories/page.tsx
    units/page.tsx
    nodes/page.tsx
    nodes/[nodeId]/questions/page.tsx
    questions/page.tsx
  components/
    home/
    lesson/
    guru/
lib/
  axios.ts
store/
  authStore.ts
public/
  images/oni.webp
  images/oni.png
  oni.mp4
  Desain tanpa judul.png
referensi/
  homepageDuo.html
  guru.html
```

Catatan:

- `app/api/**`, `lib/server/**`, `proxy.ts`, `lib/axios.ts`, dan `store/authStore.ts` hanya perlu dipahami sebagai dependency lama, **jangan dimigrasi pada fase UI**.
- `referensi/*.html` adalah referensi desain lama/prototype, bukan runtime utama.
- PNG di root seperti `dashboard-home-fixed.png`, `questions-page-fixed.png`, `stories-page-fixed.png` adalah screenshot pembanding visual, bukan asset runtime aplikasi.

## 4. Daftar Route/Halaman yang Perlu Dicopy UI-nya

| Route | File lama | Prioritas | Data dummy yang dibutuhkan |
| --- | --- | --- | --- |
| `/` | `app/page.tsx` + `components/home/MainContent.tsx` | P0 | User dummy, units, steps, progress. |
| `/lesson/n5/unit/[unit]/level/[level]` | `app/lesson/n5/unit/[unit]/level/[level]/page.tsx` | P0 | Questions dummy, selected answer state, score. |
| `/stories` | `app/stories/page.tsx` + `components/home/StoriesContent.tsx` | P1 | Collections dan story cards dummy. |
| `/stories/[id]` | `app/stories/[id]/page.tsx` | P1 | Story pages, highlights, dictionary bottom sheet. |
| `/letters` | `app/letters/page.tsx` | P1 | Hiragana, katakana, kanji static arrays. |
| `/profile` | `app/profile/page.tsx` | P1 | Profile dummy, stats, activity links, modal state. |
| `/settings/profile` | `app/settings/profile/page.tsx` | P2 | Form profile dummy. |
| `/login` | `app/login/page.tsx` + CSS module | P2 | Login form mock state; no real auth. |
| `/quest` | `app/quest/page.tsx` | P3 | Disabled/placeholder page. |
| `/shop` | `app/shop/page.tsx` | P3 | Disabled/placeholder page. |
| `/settings/account` | `app/settings/account/page.tsx` | P3 | Legacy password form mock. |
| `/guru/dashboard` | `app/guru/dashboard/page.tsx` + layout | P2/P3 | Dashboard cards and stats dummy. |
| `/guru/dashboard/stories` | `app/guru/dashboard/stories/page.tsx` | P3 | Stories list, sections, create form dummy. |
| `/guru/dashboard/units` | `app/guru/dashboard/units/page.tsx` | P3 | Sections, units, drag/select UI dummy. |
| `/guru/dashboard/nodes` | `app/guru/dashboard/nodes/page.tsx` | P3 | Sections, units, nodes, tabs, drag UI dummy. |
| `/guru/dashboard/questions` | `app/guru/dashboard/questions/page.tsx` | P3 | Nodes, questions, cascading selects, question form dummy. |
| `/guru/dashboard/nodes/[nodeId]/questions` | `app/guru/dashboard/nodes/[nodeId]/questions/page.tsx` | P3 | Node question list, multi-page form, optional XLSX actions dummy. |

## 5. Tampilan Tiap Halaman

### `/` Dashboard Belajar

- Layout utama: `app-container` dengan sidebar kiri tetap dan `main-content`.
- Jika data ada: tampil `learning-shell` dengan hero editorial:
  - Kicker `Ruang belajar N5`.
  - Heading besar serif.
  - Dua stat card: jumlah unit dan step selesai.
  - CTA `Lanjut Unit ...`.
- Mode overview:
  - Section heading `Pilih ritmemu`.
  - Grid `unit-grid` berisi `unit-card`.
  - Unit card punya status pill, judul topik, deskripsi, progress bar, footer step.
- Mode detail unit:
  - Tombol kembali.
  - Panel utama dengan hero unit, progress, counts.
  - Roadmap vertical `roadmap-step` dengan marker, status, subtitle, connector.
  - Sidebar kanan lokal berisi `Fokus berikutnya` dan `Catatan belajar`.
- State API lama harus diganti dummy: contoh 3 unit N5 dengan status `completed`, `in_progress`, `locked`.

### `/lesson/n5/unit/[unit]/level/[level]`

- UI terpisah dari editorial dashboard; lebih mirip Duolingo.
- Background putih, content max width `max-w-5xl`, header/footer fixed.
- Header: tombol close, progress bar pink `#fb85bf`, heart/lives count.
- Tipe layar:
  - `new-letter`: kartu besar huruf + reading, label `New letter`.
  - `select-image`: pertanyaan kiri, grid 2x2 option card kanan, emoji/image text, selected border pink.
  - `translate-to-ja`: prompt bubble, character SVG sederhana, answer area, word bank.
  - Score screen: card tengah dengan stats `Benar` dan `Akurasi`.
- Footer: tombol `Skip`, `Check`, `Continue`; feedback correct/wrong dengan panel pink/red.
- Untuk dummy, gunakan 5-8 soal lokal. Jangan fetch `/api/lms/questions`.

### `/stories`

- Pakai sidebar student yang sama.
- Main content adalah `StoriesContent`.
- Hero `Ruang baca harian`, stats `Siap dibaca`, `Total cerita`, CTA `Lanjut baca`.
- Collections berisi cards:
  - `story-card--peach`, `story-card--mint`, `story-card--blue`.
  - Card available berupa link; locked card non-clickable dengan label `Segera hadir`.
  - Cover icon dari `react-icons/fi`, status `Siap`, category, duration, open indicator.

### `/stories/[id]`

- Reader layar penuh dark mode, bukan student sidebar.
- Header sticky: back button, progress bar green, flag button.
- Card tengah:
  - Area visual gradient berdasarkan `bgColor`, emoji besar.
  - Text Jepang dengan highlighted words clickable.
  - Toggle translation menampilkan text Inggris.
- Bottom toolbar sticky:
  - Toggle translation (`文A`), autoplay, listen/speaker.
  - Previous/next navigation.
- Word bottom sheet:
  - Overlay gelap, sheet dari bawah, word title, tags, definitions, reading/romaji, examples.
- Risiko: beberapa teks Jepang/emoji di file lama terlihat mojibake. Saat implementasi, buat dummy UTF-8 bersih dan cek visual.

### `/letters`

- Student sidebar + `main-content`.
- Hero `Ruang huruf Jepang`, stats set aktif dan jumlah huruf.
- Board:
  - Tabs `Hiragana`, `Katakana`, `Kanji`.
  - Panel kartu huruf `kana-card`, menampilkan symbol besar dan romaji.
- Data static sudah ada di file lama; boleh recreate array dummy bersih.

### `/profile`

- Student sidebar + `main-content`.
- Hero `Profil belajar`, stat streak, CTA edit profile.
- Profile card:
  - Avatar kotak coral dengan karakter Jepang.
  - Username pill, nama, bio, joined, email.
- Stats grid: lesson, streak.
- Activity panel: cards ke letters, stories, settings profile.
- Actions: edit profile, change password modal, logout confirm modal.
- Toast success.
- Untuk dummy: jangan call `/api/session/me` atau `/logout`; gunakan object lokal dan modal mock.

### `/settings/profile`

- Student sidebar + `main-content`.
- CSS module `profile.module.css`.
- Hero grid dengan status card dan link kembali.
- Layout dua kolom:
  - Avatar card: preview, title, text, `Ubah avatar`.
  - Form card: nama, username dengan prefix `@`, email, bio, saved pill, action buttons.
- State saved hanya lokal.

### `/login`

- CSS module `login.module.css`.
- Full-screen grid: intro kiri dan form kanan.
- Visual:
  - Background pastel grid, border frame tipis.
  - Brand `LMS PPBJ` dengan logo `文`.
  - Copy besar serif.
  - Mascot `/images/oni.webp`.
  - Sticky note dan 3 support chips.
  - Form card dengan username/password, show/hide password, submit button, error box, spinner.
- Untuk dummy UI, submit cukup mock loading/error/success. Jangan bawa auth/session.

### `/quest` dan `/shop`

- Keduanya placeholder/disabled.
- Student sidebar + hero `Halaman ini sudah dinonaktifkan`.
- CTA ke dashboard, cards ke `/letters` dan `/stories`.
- Prioritas rendah.

### `/settings/account`

- Legacy password page memakai global class lama `login-container`, `login-card`, `login-input`, `google-btn`.
- Gambar `/images/oni.png`.
- UI sudah kalah konsisten dengan profile modal baru. Prioritas rendah; boleh diganti/dilebur dengan modal profile pada fase UI.

### `/guru/dashboard` dan Turunannya

- Layout guru memakai `app/guru/dashboard/layout.tsx`.
- Visual admin:
  - Background `#f8f9fa`.
  - Sidebar kiri putih 256px, top nav fixed, Material Symbols.
  - Accent utama `#9d396f` dan pink `#fb85bf`.
  - Cards bento, forms, list rows, tabs, drag handles, action buttons.
- Halaman:
  - Home: bento grid module cards dan stats cards.
  - Stories: header, toggle form, section select, title/order inputs, story cards.
  - Units: section grouped list, unit type labels, create/edit form, select mode, bulk delete, drag reorder UI.
  - Nodes: tabs per section, unit groups, node rows, detail/edit/delete buttons, drag reorder UI.
  - Questions: global bank soal dengan cascading section/unit/node selects, multi-page form, list rows.
  - Node Questions: per-node question list, complex form, `CharacterDropdown`, XLSX action bar.
- Untuk UI dummy, semua mutations cukup update local state atau noop.

## 6. Layout Utama

| Layout | File lama | Fungsi | Status migrasi |
| --- | --- | --- | --- |
| Root layout | `app/layout.tsx` | Load global CSS, Geist, Public Sans, Material Symbols. | Recreate sesuai project tujuan. |
| Student shell | `app-container`, `LeftSidebar`, `main-content` | Sidebar kiri/bottom mobile + content paper canvas. | P0. |
| Student sidebar | `app/components/home/LeftSidebar.tsx` | Icon rail: Belajar, Cerita, Huruf, Profil; Guru muncul bila role guru. | P0, role dummy bisa always hide/show via config. |
| RightSidebar | `app/components/home/RightSidebar.tsx` | Sekarang return `null`; CSS lama masih ada. | Jangan prioritas; abaikan dulu. |
| Learning hero | CSS `.learning-hero`, `.learning-stat-card`, `.learning-continue-btn` | Shared hero untuk dashboard, stories, letters, profile, placeholder. | P0/P1. |
| Guru layout | `app/guru/dashboard/layout.tsx` | Sidebar admin, top nav, responsive drawer. | P2/P3. |
| Lesson layout | Lesson page + `LessonHeader`/`LessonFooter` | Fullscreen quiz layout. | P0 setelah home. |

## 7. Komponen UI Penting

### Student/Home

- `LeftSidebar`
- `MainContent`
- `UnitSection`
- `LessonNode`
- `StoriesContent`
- `StoryCard` internal di `StoriesContent`
- `LettersContent` internal di `letters/page.tsx`
- Profile modals:
  - `SuccessToast`
  - `LogoutConfirmModal`
  - `ChangePasswordModal`

### Lesson

- `LessonHeader`
- `LessonFooter`
- `NewLetterQuestion`
- `SelectImageQuestion`
- `TranslateQuestion`
- `ScoreScreen`

### Guru/Admin

- `GuruLayout`
- Dashboard bento card pattern
- Section tabs
- Grouped section/unit lists
- Drag handle rows
- CRUD form panels
- `CharacterDropdown`
- XLSX action buttons (`EXPORT XLSX`, `IMPORT XLSX`, `TEMPLATE`) optional in UI-only phase

### Shared/Design System Classes

- `learning-shell`
- `learning-hero`
- `learning-stat-card`
- `learning-continue-btn`
- `section-heading`
- `ds-section-eyebrow`
- `ds-empty`
- `ds-loading-block`
- `ds-spinner`
- `profile-activity-card`
- `home-compact-grid`

## 8. File/Folder yang Wajib Direferensikan atau Dicopy untuk UI

Wajib dijadikan referensi utama:

- `app/globals.css`
- `app/layout.tsx`
- `app/components/home/LeftSidebar.tsx`
- `app/components/home/MainContent.tsx`
- `app/components/home/UnitSection.tsx`
- `app/components/home/LessonNode.tsx`
- `app/components/home/StoriesContent.tsx`
- `app/letters/page.tsx`
- `app/profile/page.tsx`
- `app/settings/profile/page.tsx`
- `app/settings/profile/profile.module.css`
- `app/login/page.tsx`
- `app/login/login.module.css`
- `app/lesson/n5/unit/[unit]/level/[level]/page.tsx`
- `app/components/lesson/*`
- `app/guru/dashboard/layout.tsx`
- `app/guru/dashboard/page.tsx`
- `app/guru/dashboard/*/page.tsx`
- `app/components/guru/CharacterDropdown.tsx`

Wajib dibawa sebagai asset UI runtime:

- `public/images/oni.webp`
- `public/images/oni.png`

Referensi visual tambahan, bukan runtime wajib:

- `dashboard-home-current.png`
- `dashboard-home-fixed.png`
- `questions-page-current.png`
- `questions-page-fixed.png`
- `stories-current.png`
- `stories-form-current.png`
- `stories-form-redesigned.png`
- `stories-page-current.png`
- `stories-page-fixed.png`
- `referensi/homepageDuo.html`
- `referensi/guru.html`

Asset default Next yang tidak wajib:

- `public/next.svg`
- `public/vercel.svg`
- `public/file.svg`
- `public/globe.svg`
- `public/window.svg`

Asset lain yang perlu diverifikasi kebutuhan:

- `public/oni.mp4`
- `public/Desain tanpa judul.png`

## 9. Asset yang Wajib Dibawa

| Asset | Dipakai di | Catatan |
| --- | --- | --- |
| `public/images/oni.webp` | Login modern | Mascot di intro login. |
| `public/images/oni.png` | Legacy account settings | Optional bila route legacy tetap dibuat. |
| Google Font `Public Sans` | Global + guru | Bisa pakai `<link>` seperti lama atau font setup project tujuan. |
| Google Material Symbols | Guru dashboard dan `CharacterDropdown` | Harus tersedia jika tetap memakai class `material-symbols-outlined`. |
| `react-icons/fi` | Student sidebar, story cards, profile, login, settings | Dependency UI utama. |
| `react-icons/io5` | Legacy `/settings/account` | Hanya `IoArrowBack`. |

## 10. Styling Global / Theme / Tokens yang Perlu Ditiru

### Editorial Japanese Theme

Variabel utama di `app/globals.css`:

```css
:root {
  --paper: #f4f0e8;
  --paper-soft: #ebe6da;
  --ink: #1d1b1b;
  --ink-soft: #3e3a36;
  --muted: #6c6862;
  --accent: #bd3d32;
  --line: #d6d0c5;
  --panel-dark: #1b1b1a;
  --panel-ink: #f8f3e9;
  --font-serif: "Iowan Old Style", "Palatino Linotype", "Book Antiqua", "Yu Mincho", serif;
  --font-sans: "Public Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
  --font-jp: "Yu Mincho", "Hiragino Mincho ProN", serif;
}
```

### Home Haven Theme

Dipakai di student sidebar, stories, letters, profile:

```css
--home-ink: #243036;
--home-muted: #64737a;
--home-blue: #dfeeff;
--home-mint: #dff4e7;
--home-peach: #ffd9bf;
--home-coral: #e4654e;
--home-coral-dark: #b74635;
--home-yellow: #fff8cf;
--home-line: rgba(36, 48, 54, 0.12);
```

### Lesson Theme

- Pink primary: `#fb85bf`
- Pink shadow: `#d45e99`
- Purple label: `#ce82ff`
- Text dark: `#3c3c3c`
- Muted grey: `#a5a5a5`, border `#e5e5e5`
- Wrong red: `#ff4b4b`, `#ea2b2b`

### Guru/Admin Theme

- Primary: `#9d396f`
- Primary container/pink: `#fb85bf`
- Dark primary: `#77174f`
- Error: `#93000a`, error container `#ffdad6`
- Surface: `#f8f9fa`, cards `#fff`, border `#e5e7eb`
- Material reference tokens ada di `referensi/guru.html`.

### Responsive Pattern

- Student sidebar:
  - Desktop: fixed left rail.
  - Tablet: compact 84/88px rail.
  - Mobile: bottom nav full width.
- Main content:
  - Desktop margin-left mengikuti sidebar.
  - Mobile padding bottom besar untuk bottom nav.
- Cards mostly radius `8px` pada newer home/profile/login, `16px` pada guru/lesson forms.

## 11. Dependency UI yang Mungkin Dibutuhkan di Project Tujuan

Minimal untuk recreate UI:

```json
{
  "dependencies": {
    "react-icons": "^5.6.0"
  },
  "devDependencies": {
    "tailwindcss": "^4",
    "@tailwindcss/postcss": "^4"
  }
}
```

Bila project tujuan juga Next.js:

- `next`
- `react`
- `react-dom`
- `next/image`
- `next/font/google`

Optional:

- `xlsx` hanya jika UI dashboard guru perlu tombol import/export yang benar-benar bekerja.
- `axios` jangan dibawa untuk fase UI dummy.
- `zustand` jangan dibawa untuk fase UI dummy kecuali project tujuan sudah punya store UI sendiri.

## 12. Data Dummy yang Perlu Dibuat

### User Dummy

```ts
const dummyUser = {
  id: "user-1",
  name: "Rapka",
  username: "rapka",
  role: "student", // atau "guru" untuk menampilkan nav Guru
  email: "rapka@example.com",
  bio: "Learning Japanese, one calm step at a time.",
  avatar: "人",
  joined: "March 2026",
  streak: 14,
  lessonsCompleted: 42
};
```

### Units dan Steps Dummy

Buat minimal:

- 3 section/unit N5.
- 1 unit completed, 1 in progress, 1 locked.
- 4-6 steps per unit.
- Step type: `lesson`, `practice`, `quiz`, `guidebook`.
- Status: `completed`, `active`, `locked`.

### Lesson Questions Dummy

Buat minimal:

- 1 `new-letter`.
- 2 `select-image`.
- 2 `translate-to-ja`.
- 1 score flow.

Gunakan teks Jepang UTF-8 bersih, misalnya `あ`, `みず`, `すし`, `ごはん`, `おちゃ`.

### Stories Dummy

Buat:

- 2 collections.
- 5 story cards.
- 1 available story.
- 1 detail story dengan 3 pages.
- Highlight dictionary per page:
  - `word`, `color`, `reading`, `romaji`, `types`, `definitions`, `examples`.

### Letters Dummy

Ambil ulang data dari `app/letters/page.tsx` atau buat subset:

- Hiragana 46.
- Katakana 46.
- Kanji 40-50 dasar.

### Profile Dummy

- Stats: lesson count, streak.
- Activity cards: letters, stories, settings profile.
- Modal state for password/logout.

### Guru Dummy

Buat static arrays:

- Sections: `N5`, `N4`, `N3`, `N2`, `N1`.
- Units: title, description, type, section.
- Nodes: title, type, unit.
- Questions: type, question, options, correctAnswer, relatedCharacters.
- Stories: title, section, order.

## 13. Mapping Komponen Lama ke Rekomendasi Komponen Baru

| Lama | Rekomendasi baru | Catatan |
| --- | --- | --- |
| `LeftSidebar` | `StudentSidebar` | Hapus dependency auth; prop `showGuruLink?: boolean`. |
| `MainContent` | `LearningDashboard` | Terima `units` dummy sebagai prop. |
| `UnitSection` | `LearningUnitCard` | Reuse class visual `unit-card`. |
| `LessonNode` | `RoadmapStepCard` | Reuse status mapping. |
| `StoriesContent` | `StoriesPageContent` | Split `StoryCard` menjadi komponen sendiri. |
| Internal `LettersContent` | `LettersBoard` | Data static dari file/dummy module. |
| Profile modals | `PasswordModal`, `LogoutModal`, `Toast` | Local state saja. |
| `LoginContent` | `LoginMockPage` | Submit mock; jangan panggil session API. |
| `LessonHeader/Footer` | `LessonTopBar`, `LessonBottomBar` | Pertahankan fixed layout dan Tailwind classes. |
| `NewLetterQuestion` | `NewLetterSlide` | Dummy prop. |
| `SelectImageQuestion` | `MultipleChoiceQuestion` | Gunakan emoji/image dummy. |
| `TranslateQuestion` | `WordBankQuestion` | Local selected words. |
| `ScoreScreen` | `LessonScoreScreen` | Local score. |
| `GuruLayout` | `TeacherDashboardShell` | Pertahankan inline atau pindah ke CSS/module sesuai project tujuan. |
| `CharacterDropdown` | `MultiSelectCharacterDropdown` | Ganti fetch infinite scroll dengan static options. |
| Guru CRUD pages | `Teacher*Page` | Semua create/edit/delete local state/noop. |

## 14. Prioritas Migrasi

1. P0: foundation styling
   - Global theme/tokens.
   - Root font setup.
   - Student shell/sidebar.
2. P0: `/` dashboard belajar
   - Hero, unit overview, unit detail, roadmap.
3. P0: lesson flow
   - Header/footer fixed, question types, score screen.
4. P1: `/stories` dan `/stories/[id]`
   - Story listing, reader, bottom sheet.
5. P1: `/letters`
   - Tabs dan kana cards.
6. P1: `/profile`
   - Profile card, stats, activity, modals.
7. P2: `/settings/profile` dan `/login`
   - CSS modules atau adaptasi setara.
8. P2/P3: guru dashboard shell dan home.
9. P3: guru CRUD pages.
10. P3: `/quest`, `/shop`, `/settings/account`.

## 15. Hal yang Tidak Boleh Dimigrasi Dulu

- Jangan migrasi `app/api/**`.
- Jangan migrasi `proxy.ts`.
- Jangan migrasi `lib/server/session.ts`.
- Jangan migrasi cookie/token/session handling.
- Jangan migrasi `lib/axios.ts` sebagai API client aktif.
- Jangan migrasi Zustand auth store untuk auth nyata.
- Jangan sambungkan ke backend.
- Jangan membuat login real.
- Jangan bawa production data.
- Jangan membuat database/schema.
- Jangan implement business logic progress asli.
- Jangan implement permission/role guard asli.
- Jangan implement import/export XLSX real kecuali diminta terpisah.

## 16. Risiko dan Catatan Penting

- Banyak UI lama masih API-driven:
  - `/`
  - `/profile`
  - `/lesson/**`
  - semua `/guru/dashboard/**`
  - `CharacterDropdown`
- Ganti semua API call dengan dummy/local state.
- `app/globals.css` sangat besar dan berisi beberapa generasi style:
  - editorial theme awal,
  - legacy Duolingo-like home,
  - shared `ds-*`,
  - login legacy,
  - home haven final,
  - profile final pass.
  Saat recreate, ambil style yang benar-benar dipakai halaman target; jangan copy buta kalau project tujuan punya design system sendiri.
- Ada class untuk `right-sidebar`, tetapi komponen `RightSidebar` sekarang `null`. Jangan buat sidebar kanan dulu kecuali diperlukan.
- Story detail dan local lesson dummy lama terlihat ada mojibake pada teks Jepang/emoji. Buat dummy UTF-8 baru dan cek visual.
- `react-icons` dan Material Symbols berbeda sistem icon. Pastikan keduanya tersedia atau map ke icon library project tujuan.
- Guru pages mayoritas memakai inline style. Jika project tujuan punya CSS/Tailwind convention, boleh adaptasi selama visual sama.
- Lesson flow memakai Tailwind utility; pastikan Tailwind v4/config project tujuan mendukung arbitrary values seperti `shadow-[...]`.
- Mobile bottom nav student sangat penting; pastikan tidak overlap dengan content.
- `/settings/account` memakai legacy class `login-container`, `login-card`, `google-btn`; class `google-btn` perlu dicek jika ingin route ini hidup, karena tidak semua class legacy terlihat konsisten.

## 17. Checklist untuk Agent Berikutnya

- [ ] Baca brief ini sebelum menyentuh project tujuan.
- [ ] Pastikan tahap kerja hanya UI dummy/static.
- [ ] Tentukan apakah project tujuan Next.js/React/Tailwind atau stack lain, lalu adaptasi komponen tanpa redesign.
- [ ] Setup font Public Sans, font serif fallback, dan Material Symbols jika guru dashboard dibuat.
- [ ] Port/recreate token warna utama: editorial, home haven, lesson, guru.
- [ ] Buat dummy data module untuk user, units, lesson questions, stories, letters, profile, guru data.
- [ ] Buat student shell dan sidebar.
- [ ] Implement `/` dashboard belajar dengan overview dan detail unit.
- [ ] Implement lesson flow dengan dummy question state.
- [ ] Implement stories list dan story reader.
- [ ] Implement letters page.
- [ ] Implement profile dan edit profile.
- [ ] Implement login mock.
- [ ] Implement guru dashboard jika masih masuk scope.
- [ ] Pastikan tidak ada import ke API/auth/session lama.
- [ ] Pastikan tidak ada request network untuk data LMS.
- [ ] Cek desktop, tablet, mobile untuk sidebar/bottom nav.
- [ ] Cek text Jepang/emoji tampil UTF-8 bersih.
- [ ] Bandingkan visual dengan screenshot root bila tersedia.
- [ ] Catat halaman/komponen yang sengaja ditunda.

## 18. Referensi Cepat File Lama

| Kebutuhan | File lama |
| --- | --- |
| Global theme dan class utama | `app/globals.css` |
| Font/global shell | `app/layout.tsx` |
| Student sidebar | `app/components/home/LeftSidebar.tsx` |
| Learning dashboard | `app/page.tsx`, `app/components/home/MainContent.tsx`, `UnitSection.tsx`, `LessonNode.tsx` |
| Stories list | `app/stories/page.tsx`, `app/components/home/StoriesContent.tsx` |
| Story reader | `app/stories/[id]/page.tsx` |
| Letters | `app/letters/page.tsx` |
| Profile | `app/profile/page.tsx` |
| Edit profile | `app/settings/profile/page.tsx`, `app/settings/profile/profile.module.css` |
| Login | `app/login/page.tsx`, `app/login/login.module.css` |
| Lesson | `app/lesson/n5/unit/[unit]/level/[level]/page.tsx`, `app/components/lesson/*` |
| Guru dashboard | `app/guru/dashboard/layout.tsx`, `app/guru/dashboard/page.tsx` |
| Guru forms/lists | `app/guru/dashboard/**/page.tsx`, `app/components/guru/CharacterDropdown.tsx` |
| Runtime images | `public/images/oni.webp`, `public/images/oni.png` |


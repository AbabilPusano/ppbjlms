<?php

namespace App\Http\Controllers;

use App\Models\Grammar;
use App\Models\Huruf;
use App\Models\Kalimat;
use App\Models\Kanji;
use App\Models\Kata;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GuruReferenceController extends Controller
{
    public function index(Request $request): View
    {
        $tab = $request->query('tab', 'huruf');

        $hurufs = null;
        $kanjis = null;
        $katas = null;
        $kalimats = null;
        $grammars = null;

        if ($tab === 'huruf') {
            $hurufs = Huruf::query()->orderBy('tipe')->orderBy('simbol')->paginate(30)->withQueryString();
        } elseif ($tab === 'kanji') {
            $kanjis = Kanji::query()->orderBy('kanji')->paginate(30)->withQueryString();
        } elseif ($tab === 'kata') {
            $katas = Kata::with('kanji')->orderBy('kata')->paginate(30)->withQueryString();
        } elseif ($tab === 'kalimat') {
            $kalimats = Kalimat::with('katas')->orderBy('kalimat')->paginate(30)->withQueryString();
        } elseif ($tab === 'grammar') {
            $grammars = Grammar::with('kalimat')->orderBy('struktur')->paginate(30)->withQueryString();
        }

        // Shared dropdown data
        $allKanjis = Kanji::query()->orderBy('kanji')->get(['id', 'kanji', 'makna']);
        $allKalimats = Kalimat::query()->orderBy('kalimat')->get(['id', 'kalimat']);
        $allKatas = Kata::query()->orderBy('kata')->get(['id', 'kata', 'arti']);

        return view('guru-reference-index', [
            'tab' => $tab,
            'hurufs' => $hurufs,
            'kanjis' => $kanjis,
            'katas' => $katas,
            'kalimats' => $kalimats,
            'grammars' => $grammars,
            'allKanjis' => $allKanjis,
            'allKalimats' => $allKalimats,
            'allKatas' => $allKatas,
        ]);
    }

    // --- HURUF CRUD ---
    public function storeHuruf(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tipe' => ['required', 'string', 'in:hiragana,katakana'],
            'simbol' => ['required', 'string', 'max:50'],
            'cara_baca' => ['required', 'string', 'max:50'],
        ]);

        Huruf::create([
            'id' => (string) Str::uuid(),
            'tipe' => $validated['tipe'],
            'simbol' => $validated['simbol'],
            'cara_baca' => $validated['cara_baca'],
        ]);

        return redirect()->route('guru.referensi.index', ['tab' => 'huruf'])
            ->with('status', 'success')
            ->with('message', 'Huruf baru berhasil ditambahkan.');
    }

    public function updateHuruf(Request $request, Huruf $huruf): RedirectResponse
    {
        $validated = $request->validate([
            'tipe' => ['required', 'string', 'in:hiragana,katakana'],
            'simbol' => ['required', 'string', 'max:50'],
            'cara_baca' => ['required', 'string', 'max:50'],
        ]);

        $huruf->update($validated);

        return redirect()->route('guru.referensi.index', ['tab' => 'huruf'])
            ->with('status', 'success')
            ->with('message', 'Huruf berhasil diperbarui.');
    }

    public function destroyHuruf(Huruf $huruf): RedirectResponse
    {
        $huruf->delete();
        return redirect()->route('guru.referensi.index', ['tab' => 'huruf'])
            ->with('status', 'success')
            ->with('message', 'Huruf berhasil dihapus.');
    }

    // --- KANJI CRUD ---
    public function storeKanji(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kanji' => ['required', 'string', 'max:50'],
            'onyomi' => ['nullable', 'string', 'max:191'],
            'kunyomi' => ['nullable', 'string', 'max:191'],
            'makna' => ['required', 'string', 'max:191'],
            'level' => ['nullable', 'string', 'in:N5,N4,N3,N2,N1'],
        ]);

        Kanji::create([
            'id' => (string) Str::uuid(),
            'kanji' => $validated['kanji'],
            'onyomi' => $validated['onyomi'],
            'kunyomi' => $validated['kunyomi'],
            'makna' => $validated['makna'],
            'level' => $validated['level'],
        ]);

        return redirect()->route('guru.referensi.index', ['tab' => 'kanji'])
            ->with('status', 'success')
            ->with('message', 'Kanji baru berhasil ditambahkan.');
    }

    public function updateKanji(Request $request, Kanji $kanji): RedirectResponse
    {
        $validated = $request->validate([
            'kanji' => ['required', 'string', 'max:50'],
            'onyomi' => ['nullable', 'string', 'max:191'],
            'kunyomi' => ['nullable', 'string', 'max:191'],
            'makna' => ['required', 'string', 'max:191'],
            'level' => ['nullable', 'string', 'in:N5,N4,N3,N2,N1'],
        ]);

        $kanji->update($validated);

        return redirect()->route('guru.referensi.index', ['tab' => 'kanji'])
            ->with('status', 'success')
            ->with('message', 'Kanji berhasil diperbarui.');
    }

    public function destroyKanji(Kanji $kanji): RedirectResponse
    {
        $kanji->delete();
        return redirect()->route('guru.referensi.index', ['tab' => 'kanji'])
            ->with('status', 'success')
            ->with('message', 'Kanji berhasil dihapus.');
    }

    // --- KATA CRUD ---
    public function storeKata(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kata' => ['required', 'string', 'max:191'],
            'romaji' => ['required', 'string', 'max:191'],
            'arti' => ['required', 'string', 'max:191'],
            'kanji_id' => ['nullable', 'string', 'exists:kanjis,id'],
            'deskripsi' => ['nullable', 'string'],
            'tipe_kata' => ['nullable', 'string', 'max:191'],
            'level' => ['nullable', 'string', 'in:N5,N4,N3,N2,N1'],
        ]);

        Kata::create([
            'id' => (string) Str::uuid(),
            'kata' => $validated['kata'],
            'romaji' => $validated['romaji'],
            'arti' => $validated['arti'],
            'kanji_id' => $validated['kanji_id'],
            'deskripsi' => $validated['deskripsi'],
            'tipe_kata' => $validated['tipe_kata'],
            'level' => $validated['level'],
        ]);

        return redirect()->route('guru.referensi.index', ['tab' => 'kata'])
            ->with('status', 'success')
            ->with('message', 'Kata baru berhasil ditambahkan.');
    }

    public function updateKata(Request $request, Kata $kata): RedirectResponse
    {
        $validated = $request->validate([
            'kata' => ['required', 'string', 'max:191'],
            'romaji' => ['required', 'string', 'max:191'],
            'arti' => ['required', 'string', 'max:191'],
            'kanji_id' => ['nullable', 'string', 'exists:kanjis,id'],
            'deskripsi' => ['nullable', 'string'],
            'tipe_kata' => ['nullable', 'string', 'max:191'],
            'level' => ['nullable', 'string', 'in:N5,N4,N3,N2,N1'],
        ]);

        $kata->update($validated);

        return redirect()->route('guru.referensi.index', ['tab' => 'kata'])
            ->with('status', 'success')
            ->with('message', 'Kata berhasil diperbarui.');
    }

    public function destroyKata(Kata $kata): RedirectResponse
    {
        $kata->delete();
        return redirect()->route('guru.referensi.index', ['tab' => 'kata'])
            ->with('status', 'success')
            ->with('message', 'Kata berhasil dihapus.');
    }

    // --- KALIMAT CRUD ---
    public function storeKalimat(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kalimat' => ['required', 'string', 'max:191'],
            'romaji' => ['required', 'string', 'max:191'],
            'arti' => ['required', 'string', 'max:191'],
            'deskripsi' => ['nullable', 'string'],
            'kata_ids' => ['nullable', 'array'],
            'kata_ids.*' => ['string', 'exists:katas,id'],
        ]);

        $kalimat = Kalimat::create([
            'id' => (string) Str::uuid(),
            'kalimat' => $validated['kalimat'],
            'romaji' => $validated['romaji'],
            'arti' => $validated['arti'],
            'deskripsi' => $validated['deskripsi'],
        ]);

        if (!empty($validated['kata_ids'])) {
            $kalimat->katas()->sync($validated['kata_ids']);
        }

        return redirect()->route('guru.referensi.index', ['tab' => 'kalimat'])
            ->with('status', 'success')
            ->with('message', 'Kalimat baru berhasil ditambahkan.');
    }

    public function updateKalimat(Request $request, Kalimat $kalimat): RedirectResponse
    {
        $validated = $request->validate([
            'kalimat' => ['required', 'string', 'max:191'],
            'romaji' => ['required', 'string', 'max:191'],
            'arti' => ['required', 'string', 'max:191'],
            'deskripsi' => ['nullable', 'string'],
            'kata_ids' => ['nullable', 'array'],
            'kata_ids.*' => ['string', 'exists:katas,id'],
        ]);

        $kalimat->update([
            'kalimat' => $validated['kalimat'],
            'romaji' => $validated['romaji'],
            'arti' => $validated['arti'],
            'deskripsi' => $validated['deskripsi'],
        ]);

        $kataIds = $validated['kata_ids'] ?? [];
        $kalimat->katas()->sync($kataIds);

        return redirect()->route('guru.referensi.index', ['tab' => 'kalimat'])
            ->with('status', 'success')
            ->with('message', 'Kalimat berhasil diperbarui.');
    }

    public function destroyKalimat(Kalimat $kalimat): RedirectResponse
    {
        $kalimat->katas()->detach();
        $kalimat->delete();
        return redirect()->route('guru.referensi.index', ['tab' => 'kalimat'])
            ->with('status', 'success')
            ->with('message', 'Kalimat berhasil dihapus.');
    }

    // --- GRAMMAR CRUD ---
    public function storeGrammar(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'struktur' => ['required', 'string', 'max:191'],
            'kalimat_id' => ['nullable', 'string', 'exists:kalimats,id'],
            'deskripsi' => ['nullable', 'string'],
            'level' => ['nullable', 'string', 'in:N5,N4,N3,N2,N1'],
        ]);

        Grammar::create([
            'id' => (string) Str::uuid(),
            'struktur' => $validated['struktur'],
            'kalimat_id' => $validated['kalimat_id'],
            'deskripsi' => $validated['deskripsi'],
            'level' => $validated['level'],
        ]);

        return redirect()->route('guru.referensi.index', ['tab' => 'grammar'])
            ->with('status', 'success')
            ->with('message', 'Grammar baru berhasil ditambahkan.');
    }

    public function updateGrammar(Request $request, Grammar $grammar): RedirectResponse
    {
        $validated = $request->validate([
            'struktur' => ['required', 'string', 'max:191'],
            'kalimat_id' => ['nullable', 'string', 'exists:kalimats,id'],
            'deskripsi' => ['nullable', 'string'],
            'level' => ['nullable', 'string', 'in:N5,N4,N3,N2,N1'],
        ]);

        $grammar->update($validated);

        return redirect()->route('guru.referensi.index', ['tab' => 'grammar'])
            ->with('status', 'success')
            ->with('message', 'Grammar berhasil diperbarui.');
    }

    public function destroyGrammar(Grammar $grammar): RedirectResponse
    {
        $grammar->delete();
        return redirect()->route('guru.referensi.index', ['tab' => 'grammar'])
            ->with('status', 'success')
            ->with('message', 'Grammar berhasil dihapus.');
    }
}

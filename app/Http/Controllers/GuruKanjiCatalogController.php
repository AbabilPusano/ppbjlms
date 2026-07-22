<?php

namespace App\Http\Controllers;

use App\Services\KanjiCatalogService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class GuruKanjiCatalogController extends Controller
{
    public function __construct(protected KanjiCatalogService $catalogService)
    {
    }

    public function index(): View
    {
        $config = $this->catalogService->getRawConfig();
        $payload = $config !== []
            ? json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            : $this->defaultPayload();

        return view('guru-kanji-catalog-ui', compact('config', 'payload'));
    }

    public function update(Request $request): RedirectResponse
    {
        $levelCode = trim((string) $request->input('level_code', ''));
        $textPayload = trim((string) $request->input('text_payload', ''));
        $jsonPayload = trim((string) $request->input('payload', ''));
        $importSample = $request->boolean('import_sample');
        $parsedPayload = trim((string) $request->input('parsed_payload', ''));
        if ($importSample) {
            // copy sample to active catalog if available
            $samplePath = storage_path('app/kanji/sample-catalog.json');
            $target = storage_path('app/kanji/catalog.json');
            if (file_exists($samplePath)) {
                copy($samplePath, $target);
                return back()->with('success', 'Sample catalog berhasil diimpor.');
            }
            return back()->withErrors(['payload' => 'Sample catalog tidak ditemukan.']);
        }

        if ($textPayload === '' && $jsonPayload === '' && $parsedPayload === '') {
            return back()->withErrors(['payload' => 'Isi daftar kanji terlebih dahulu.']);
        }

        if ($parsedPayload !== '') {
            // parsed_payload is expected to be a JSON string with structure { LEVELCODE: { label, helper, groups: [...] } }
            $this->catalogService->saveFromPayload($parsedPayload);
        } elseif ($textPayload !== '') {
            $this->catalogService->saveLevelFromText($levelCode, $textPayload);
        } else {
            $this->catalogService->saveFromPayload($jsonPayload);
        }

        return back()->with('success', 'Daftar kanji berhasil disimpan.');
    }

    protected function defaultPayload(): string
    {
        return json_encode([
            'N5' => [
                'label' => 'Kanji N5',
                'helper' => '113 kanji JLPT N5',
                'groups' => [
                    [
                        'title' => 'Angka & Uang',
                        'letters' => [
                            ['一', 'ichi'],
                            ['二', 'ni'],
                            ['三', 'san'],
                        ],
                    ],
                ],
            ],
            'N4' => [
                'label' => 'Kanji N4',
                'helper' => '209 kanji JLPT N4',
                'groups' => [
                    [
                        'title' => 'Waktu',
                        'letters' => [
                            ['朝', 'asa'],
                            ['昼', 'hiru'],
                        ],
                    ],
                ],
            ],
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}

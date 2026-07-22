<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GuruPenilaianController extends Controller
{
    public function index(Request $request): View
    {
        $selectedBatch = $request->query('batch');

        $batches = User::query()
            ->where('role', 'siswa')
            ->whereNotNull('batch')
            ->where('batch', '!=', '')
            ->distinct()
            ->pluck('batch')
            ->values();

        $students = User::query()
            ->where('role', 'siswa')
            ->when($selectedBatch, function ($query, $batch) {
                $query->where('batch', $batch);
            })
            ->with(['latestProgress.node.unit'])
            ->orderBy('name', 'asc')
            ->paginate(30)
            ->withQueryString();

        return view('guru-penilaian-ui', [
            'students' => $students,
            'batches' => $batches,
            'selectedBatch' => $selectedBatch,
        ]);
    }

    public function update(Request $request, User $student): RedirectResponse
    {
        // Make sure we only update students
        if ($student->role !== 'siswa') {
            abort(403, 'Aksi ini hanya diperbolehkan untuk akun siswa.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'batch' => ['required', 'string', 'max:50'],
        ]);

        $student->update([
            'name' => $validated['name'],
            'batch' => $validated['batch'],
        ]);

        return redirect()
            ->back()
            ->with('status', 'student-updated')
            ->with('message', "Data siswa {$student->username} berhasil diperbarui.");
    }
}

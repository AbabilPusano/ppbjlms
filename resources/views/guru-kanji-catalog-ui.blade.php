@extends('layouts.lms-ui', ['title' => 'Kelola Kanji - Guru'])

@section('body')
@php($currentUser = auth()->user())
<div class="guru-shell">
    <header class="guru-topbar">
        <div>
            <p>Guru</p>
        </div>
        <div class="guru-user">
            <div>
                <p>{{ $currentUser?->name ?? 'Guru' }}</p>
                <span>{{ ucfirst($currentUser?->role ?? 'guru') }}</span>
            </div>
            <strong>{{ strtoupper(substr($currentUser?->name ?? 'G', 0, 1)) }}</strong>
        </div>
    </header>

    <aside class="guru-sidebar">
        <div class="guru-logo">
            <span class="material-symbols-outlined" aria-hidden="true">school</span>
            <strong>GURU LMS</strong>
        </div>
        <nav class="guru-nav" aria-label="Navigasi guru">
            <a href="{{ route('guru.dashboard') }}"><span class="material-symbols-outlined">home</span>Dashboard</a>
            <a href="{{ route('guru.units.index') }}"><span class="material-symbols-outlined">category</span>Unit Pembelajaran</a>
            <a href="{{ route('guru.kanji-catalog.index') }}" class="active"><span class="material-symbols-outlined">translate</span>Kanji Catalog</a>
        </nav>
    </aside>

    <main class="guru-main">
        <div class="guru-heading">
            <div>
                <h1>Kelola Kanji Catalog</h1>
                <p>Masukkan data kanji dalam format JSON. Data ini akan dipakai untuk halaman siswa.</p>
            </div>
        </div>

        @if (session('success'))
            <div style="margin-bottom: 16px; padding: 12px 16px; border-radius: 12px; background: #ecfdf3; color: #166534; font-weight: 700;">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div style="margin-bottom: 16px; padding: 12px 16px; border-radius: 12px; background: #fef2f2; color: #991b1b; font-weight: 700;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('guru.kanji-catalog.update') }}" style="display: grid; gap: 16px;">
            @csrf
            <div style="padding: 20px; border-radius: 20px; background: var(--card-bg); border: 1px solid var(--border);">
                <label for="level_code" style="display:block; font-weight:700; margin-bottom:8px;">Pilih Level</label>
                <select id="level_code" name="level_code" style="width: 220px; border:1px solid var(--border); border-radius: 12px; padding: 10px 12px; background: white;">
                    <option value="N5">N5</option>
                    <option value="N4">N4</option>
                    <option value="N3">N3</option>
                    <option value="N2">N2</option>
                    <option value="N1">N1</option>
                </select>
            </div>

            <div style="padding: 20px; border-radius: 20px; background: var(--card-bg); border: 1px solid var(--border);">
                <label for="text_payload" style="display:block; font-weight:700; margin-bottom:8px;">Input Kanji (teks sederhana)</label>
                <textarea id="text_payload" name="text_payload" rows="18" placeholder="# Angka &amp; Uang&#10;一|ichi|オン|くん|9|radical|N3|arti singkat" style="width:100%; border:1px solid var(--border); border-radius: 12px; padding: 12px; font-family: Consolas, monospace; font-size: 0.9rem;">{{ old('text_payload') }}</textarea>
                <p style="margin-top: 8px; color: var(--text-muted); font-size: 0.9rem;">Format: baris dimulai dengan <strong>#</strong> untuk judul kelompok. Setelah itu tiap baris pakai pipe-separated fields:
                <br><strong>char|romaji</strong> atau
                <br><strong>char|romaji|onyomi|kunyomi|stroke_count|radical|jlpt|meaning</strong>
                </p>
            </div>

            <div style="padding: 20px; border-radius: 20px; background: var(--card-bg); border: 1px solid var(--border);">
                <label for="payload" style="display:block; font-weight:700; margin-bottom:8px;">JSON (opsional, kalau mau)</label>
                <textarea id="payload" name="payload" rows="12" style="width:100%; border:1px solid var(--border); border-radius: 12px; padding: 12px; font-family: Consolas, monospace; font-size: 0.9rem;">{{ $payload }}</textarea>
            </div>
            <div style="display:flex; gap:12px; align-items:center;">
                <button type="submit" name="action" value="save_text" style="padding: 12px 18px; border: none; border-radius: 999px; background: var(--home-coral); color: white; font-weight: 800; cursor: pointer;">Simpan (teks)</button>
                <button type="button" id="previewBtn" style="padding: 12px 18px; border: 1px solid var(--border); border-radius: 999px; background: transparent; color: var(--text); font-weight: 700; cursor: pointer;">Preview</button>
                <button type="button" id="saveParsedBtn" style="padding: 12px 18px; border: none; border-radius: 999px; background: #2b6cb0; color: white; font-weight: 800; cursor: pointer;">Simpan Parsed</button>
                <button type="submit" name="import_sample" value="1" style="padding: 12px 18px; border: 1px dashed var(--border); border-radius: 999px; background: transparent; color: var(--text); font-weight: 700; cursor: pointer;">Import Sample</button>
            </div>
            
            <input type="hidden" name="parsed_payload" id="parsed_payload" value="">
        </form>

        <section id="previewSection" style="display:none; margin-top:18px;">
            <h3 style="margin:0 0 12px 0;">Preview Parsed</h3>
            <div id="previewContainer" style="padding:12px; border-radius:12px; background:var(--card-bg); border:1px solid var(--border); max-height:420px; overflow:auto;"></div>
        </section>

        <script>
        (function(){
            const textarea = document.getElementById('text_payload');
            const previewBtn = document.getElementById('previewBtn');
            const previewSection = document.getElementById('previewSection');
            const previewContainer = document.getElementById('previewContainer');
            const saveParsedBtn = document.getElementById('saveParsedBtn');
            const parsedPayloadInput = document.getElementById('parsed_payload');
            const levelSelect = document.getElementById('level_code');

            let parsedObj = null;

            function parseTextToObject(text){
                const lines = text.split(/\r?\n/).map(l=>l.trim());
                const groups = [];
                let current = { title: 'Umum', letters: [] };
                for (const raw of lines){
                    if (!raw) continue;
                    if (raw.startsWith('#')){ if (current.letters.length) groups.push(current); current = { title: raw.replace(/^#+\s*/,'') || 'Umum', letters: [] }; continue; }
                    const parts = raw.split('|').map(p=>p.trim());
                    if (parts.length < 2) continue;
                    const meta = { char: parts[0], romaji: parts[1], onyomi: parts[2]||'', kunyomi: parts[3]||'', stroke_count: Number(parts[4]||0)||0, radical: parts[5]||'', jlpt: parts[6]||'', meaning: parts[7]||'', examples: [] };
                    current.letters.push(meta);
                }
                if (current.letters.length) groups.push(current);
                const level = (levelSelect && levelSelect.value) ? levelSelect.value : 'N5';
                const obj = {};
                obj[level] = { label: 'Kanji ' + level, helper: 'Generated by preview', groups: groups };
                return obj;
            }

            function renderPreview(obj){
                parsedObj = obj;
                previewContainer.innerHTML = '';
                for (const [lvl, set] of Object.entries(obj)){
                    const hdr = document.createElement('div'); hdr.innerHTML = '<strong>'+set.label+'</strong> — '+set.helper; previewContainer.appendChild(hdr);
                    for (const group of set.groups){
                        const gEl = document.createElement('div'); gEl.style.marginTop='10px';
                        gEl.innerHTML = '<div style="font-weight:700; border-left:3px solid var(--home-coral); padding-left:8px;">'+group.title+'</div>';
                        const list = document.createElement('div'); list.style.display='grid'; list.style.gridTemplateColumns='repeat(auto-fill, minmax(220px,1fr))'; list.style.gap='8px'; list.style.marginTop='8px';
                        for (const letter of group.letters){
                            const card = document.createElement('div'); card.style.padding='8px'; card.style.border='1px solid var(--border)'; card.style.borderRadius='8px';
                            card.innerHTML = `<div style="font-size:20px; font-weight:800;">${letter.char}</div><div style="color:var(--text-muted)">${letter.romaji}</div>`;
                            const editBtn = document.createElement('button'); editBtn.textContent='Edit'; editBtn.style.marginTop='8px'; editBtn.style.padding='6px 8px'; editBtn.style.fontSize='12px'; editBtn.onclick = ()=> openEditor(letter, card, obj);
                            card.appendChild(editBtn);
                            list.appendChild(card);
                        }
                        gEl.appendChild(list);
                        previewContainer.appendChild(gEl);
                    }
                }
            }

            function openEditor(letter, container, obj){
                // remove existing editor if any
                const existing = container.querySelector('.inline-editor'); if (existing) existing.remove();
                const form = document.createElement('div'); form.className = 'inline-editor'; form.style.display='grid'; form.style.gap='6px'; form.style.marginTop='8px';
                const inputs = {};
                for (const key of ['romaji','onyomi','kunyomi','stroke_count','radical','jlpt','meaning']){
                    const inp = document.createElement('input'); inp.value = letter[key] ?? '';
                    inp.placeholder = key; inp.style.padding='6px'; inp.style.fontSize='12px';
                    form.appendChild(inp);
                    inputs[key] = inp;
                }
                const actions = document.createElement('div'); actions.style.display='flex'; actions.style.gap='8px';
                const save = document.createElement('button'); save.textContent='Apply'; save.style.marginTop='8px'; save.onclick = ()=> {
                    for (const k of Object.keys(inputs)){
                        letter[k] = k==='stroke_count' ? Number(inputs[k].value||0) : inputs[k].value;
                    }
                    // re-render preview from parsedObj
                    renderPreview(parsedObj);
                };
                const cancel = document.createElement('button'); cancel.textContent='Cancel'; cancel.style.marginTop='8px'; cancel.onclick = ()=> { renderPreview(parsedObj); };
                actions.appendChild(save); actions.appendChild(cancel); form.appendChild(actions);
                container.appendChild(form);
            }

            previewBtn.addEventListener('click', ()=>{
                const obj = parseTextToObject(textarea.value || '');
                renderPreview(obj);
                previewSection.style.display='block';
            });

            saveParsedBtn.addEventListener('click', ()=>{
                const obj = parsedObj || parseTextToObject(textarea.value || '');
                parsedPayloadInput.value = JSON.stringify(obj, null, 2);
                // submit form
                const form = parsedPayloadInput.closest('form');
                form.submit();
            });

            
        })();
        </script>
    </main>
</div>
@endsection

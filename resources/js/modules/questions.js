import { createDragAutoScroller } from './utils.js';

export default function initQuestionsModule() {
  function initKanjiSelect(wrapper) {
    const searchInput = wrapper.querySelector('.guru-kanji-select-search');
    const hiddenInput = wrapper.querySelector('.guru-kanji-select-value');
    const dropdown = wrapper.querySelector('.guru-kanji-select-dropdown');
    const optionsContainer = wrapper.querySelector('.guru-kanji-select-options');
    const loadingIndicator = wrapper.querySelector('.guru-kanji-select-loading');
    const arrow = wrapper.querySelector('.guru-kanji-select-arrow');

    let page = 1;
    let hasMore = true;
    let isLoading = false;
    let query = '';
    let selectedId = hiddenInput.value || '';
    let selectedText = '';

    const fetchKanjis = async (reset = false) => {
      if (isLoading) return;
      if (reset) {
        page = 1;
        hasMore = true;
        optionsContainer.innerHTML = '';
      }
      if (!hasMore) return;

      isLoading = true;
      if (loadingIndicator) loadingIndicator.style.display = 'block';

      try {
        const res = await fetch(`/api/guru/kanjis/search?q=${encodeURIComponent(query)}&page=${page}`);
        const data = await res.json();

        data.results.forEach(item => {
          const div = document.createElement('div');
          div.className = `guru-kanji-select-option ${item.id === selectedId ? 'is-selected' : ''}`;
          div.setAttribute('data-id', item.id);
          div.setAttribute('data-text', `${item.kanji} (${item.makna})`);
          div.innerHTML = `
            <strong style="font-family: var(--font-jp); font-size: 16px;">${item.kanji}</strong>
            <span style="color: #667085; font-size: 12px;">${item.makna}</span>
          `;
          div.addEventListener('click', (e) => {
            e.stopPropagation();
            selectItem(item.id, `${item.kanji} (${item.makna})`);
            closeDropdown();
          });
          optionsContainer.appendChild(div);
        });

        hasMore = data.pagination.more;
        page += 1;
      } catch (e) {
        console.error(e);
      } finally {
        isLoading = false;
        if (loadingIndicator) loadingIndicator.style.display = 'none';
      }
    };

    const selectItem = (id, text) => {
      selectedId = id;
      selectedText = text;
      hiddenInput.value = id;
      searchInput.value = id ? text : '';
      searchInput.placeholder = id ? text : 'Cari Kanji (Karakter / Makna)...';
      
      hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));

      optionsContainer.querySelectorAll('.guru-kanji-select-option').forEach(opt => {
        opt.classList.toggle('is-selected', opt.getAttribute('data-id') === id);
      });
    };

    wrapper.setValue = (id, text) => {
      selectItem(id, text);
    };

    const openDropdown = () => {
      dropdown.style.display = 'flex';
      if (optionsContainer.children.length === 0) {
        fetchKanjis(true);
      }
    };

    const closeDropdown = () => {
      dropdown.style.display = 'none';
      searchInput.value = selectedId ? selectedText : '';
    };

    let searchTimeout;
    searchInput.addEventListener('input', () => {
      query = searchInput.value;
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => {
        fetchKanjis(true);
      }, 300);
    });

    searchInput.addEventListener('focus', () => {
      searchInput.value = '';
      openDropdown();
    });

    arrow.addEventListener('click', (e) => {
      e.stopPropagation();
      if (dropdown.style.display === 'none') {
        openDropdown();
        searchInput.focus();
      } else {
        closeDropdown();
      }
    });

    optionsContainer.addEventListener('scroll', () => {
      if (optionsContainer.scrollTop + optionsContainer.clientHeight >= optionsContainer.scrollHeight - 5) {
        fetchKanjis();
      }
    });

    document.addEventListener('click', (e) => {
      if (!wrapper.contains(e.target)) {
        closeDropdown();
      }
    });
  }

  function initCharacterSelect(wrapper) {
    const searchInput = wrapper.querySelector('.guru-character-select-search');
    const hiddenInput = wrapper.querySelector('.guru-character-select-value');
    const dropdown = wrapper.querySelector('.guru-character-select-dropdown');
    const optionsContainer = wrapper.querySelector('.guru-character-select-options');
    const loadingIndicator = wrapper.querySelector('.guru-character-select-loading');
    const arrow = wrapper.querySelector('.guru-character-select-arrow');
    const form = wrapper.closest('form');
    const pronunciationInput = form ? form.querySelector('[name="character_pronunciation"]') : null;

    let page = 1;
    let hasMore = true;
    let isLoading = false;
    let query = '';
    let selectedSymbol = hiddenInput.value || '';
    let selectedText = '';

    const fetchHurufs = async (reset = false) => {
      if (isLoading) return;
      if (reset) {
        page = 1;
        hasMore = true;
        optionsContainer.innerHTML = '';
      }
      if (!hasMore) return;

      isLoading = true;
      if (loadingIndicator) loadingIndicator.style.display = 'block';

      try {
        const res = await fetch(`/api/guru/hurufs/search?q=${encodeURIComponent(query)}&page=${page}`);
        const data = await res.json();

        data.results.forEach(item => {
          const div = document.createElement('div');
          div.className = `guru-character-select-option ${item.simbol === selectedSymbol ? 'is-selected' : ''}`;
          div.setAttribute('data-symbol', item.simbol);
          div.setAttribute('data-text', `${item.simbol} (${item.cara_baca})`);
          div.innerHTML = `
            <strong style="font-family: var(--font-jp); font-size: 16px;">${item.simbol}</strong>
            <span style="color: #667085; font-size: 12px; text-transform: capitalize;">${item.tipe} (${item.cara_baca})</span>
          `;
          div.addEventListener('click', (e) => {
            e.stopPropagation();
            selectItem(item.simbol, item.cara_baca);
            closeDropdown();
          });
          optionsContainer.appendChild(div);
        });

        hasMore = data.pagination.more;
        page += 1;
      } catch (e) {
        console.error(e);
      } finally {
        isLoading = false;
        if (loadingIndicator) loadingIndicator.style.display = 'none';
      }
    };

    const selectItem = (symbol, pronunciation) => {
      selectedSymbol = symbol;
      hiddenInput.value = symbol;
      selectedText = symbol ? `${symbol} (${pronunciation})` : '';
      searchInput.value = selectedText;
      searchInput.placeholder = symbol ? selectedText : 'Cari Huruf (Simbol / Romaji)...';

      if (pronunciationInput) {
        pronunciationInput.value = pronunciation || '';
      }

      hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));

      optionsContainer.querySelectorAll('.guru-character-select-option').forEach(opt => {
        opt.classList.toggle('is-selected', opt.getAttribute('data-symbol') === symbol);
      });
    };

    wrapper.setValue = (symbol, pronunciation) => {
      selectItem(symbol, pronunciation);
    };

    const openDropdown = () => {
      dropdown.style.display = 'flex';
      if (optionsContainer.children.length === 0) {
        fetchHurufs(true);
      }
    };

    const closeDropdown = () => {
      dropdown.style.display = 'none';
      searchInput.value = selectedSymbol ? selectedText : '';
    };

    let searchTimeout;
    searchInput.addEventListener('input', () => {
      query = searchInput.value;
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => {
        fetchHurufs(true);
      }, 300);
    });

    searchInput.addEventListener('focus', () => {
      searchInput.value = '';
      openDropdown();
    });

    arrow.addEventListener('click', (e) => {
      e.stopPropagation();
      if (dropdown.style.display === 'none') {
        openDropdown();
        searchInput.focus();
      } else {
        closeDropdown();
      }
    });

    optionsContainer.addEventListener('scroll', () => {
      if (optionsContainer.scrollTop + optionsContainer.clientHeight >= optionsContainer.scrollHeight - 5) {
        fetchHurufs();
      }
    });

    document.addEventListener('click', (e) => {
      if (!wrapper.contains(e.target)) {
        closeDropdown();
      }
    });
  }

  document.querySelectorAll('[data-guru-questions]').forEach((root) => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const dragAutoScroller = createDragAutoScroller();
    const nodeId = root.getAttribute('data-material-id');
    const state = {
      node: null,
      steps: [],
    };

    let stepKanjiSelect = null;
    const kanjiWrapper = root.querySelector('#step-kanji-select');
    if (kanjiWrapper) {
      initKanjiSelect(kanjiWrapper);
      stepKanjiSelect = kanjiWrapper;
    }

    let stepCharacterSelect = null;
    const characterWrapper = root.querySelector('#step-character-select');
    if (characterWrapper) {
      initCharacterSelect(characterWrapper);
      stepCharacterSelect = characterWrapper;
    }

    const titleNode = root.querySelector('[data-question-title]');
    const subtitleNode = root.querySelector('[data-question-subtitle]');
    const loadingNode = root.querySelector('[data-questions-loading]');
    const errorNode = root.querySelector('[data-questions-error]');
    const errorMessageNode = root.querySelector('[data-questions-error-message]');
    const emptyNode = root.querySelector('[data-questions-empty]');
    const listNode = root.querySelector('[data-question-list]');
    const stepModal = root.querySelector('[data-question-modal]');
    const stepForm = root.querySelector('[data-question-form]');
    const stepIdInput = root.querySelector('[data-question-id]');
    const stepTypeSelect = root.querySelector('[data-question-type]');
    const quizTypeSelect = root.querySelector('[data-quiz-type]');
    const stepModalTitle = root.querySelector('[data-question-modal-title]');
    const stepFormError = root.querySelector('[data-question-form-error]');
    const importModal = root.querySelector('[data-step-import-modal]');
    const importForm = root.querySelector('[data-step-import-form]');
    const importFormError = root.querySelector('[data-step-import-error]');
    const deleteSelectedBtn = root.querySelector('[data-delete-selected]');
    const selectedCountSpan = root.querySelector('[data-selected-count]');
    const selectAllContainer = root.querySelector('[data-select-all-container]');
    const selectAllCheckbox = root.querySelector('#select-all-steps');

    const escapeHtml = (value) => String(value ?? '')
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');

    const fetchJson = async (url, options = {}) => {
      const response = await fetch(url, {
        headers: {
          Accept: 'application/json',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          ...(options.headers || {}),
        },
        ...options,
      });

      const payload = await response.json().catch(() => ({}));
      if (!response.ok) {
        const message = payload.message || Object.values(payload.errors || {})?.flat()?.[0] || 'Request gagal.';
        throw new Error(message);
      }

      return payload;
    };

    const setLoading = (isLoading) => {
      if (loadingNode) loadingNode.hidden = !isLoading;
      if (listNode) listNode.hidden = isLoading;
      if (emptyNode) emptyNode.hidden = true;
      if (errorNode) errorNode.hidden = true;
      if (selectAllContainer) selectAllContainer.style.display = 'none';
    };

    const setError = (message) => {
      if (loadingNode) loadingNode.hidden = true;
      if (listNode) listNode.hidden = true;
      if (selectAllContainer) selectAllContainer.style.display = 'none';
      if (emptyNode) emptyNode.hidden = true;
      if (errorNode) errorNode.hidden = false;
      if (errorMessageNode) errorMessageNode.textContent = message;
    };

    const renderStepFields = () => {
      const type = stepTypeSelect?.value || 'text';
      root.querySelectorAll('[data-question-fields]').forEach((fields) => {
        fields.hidden = fields.getAttribute('data-question-fields') !== type;
      });

      if (type === 'quiz') {
        const quizType = quizTypeSelect?.value || 'multiple_choice';
        root.querySelectorAll('[data-quiz-fields]').forEach((fields) => {
          fields.hidden = fields.getAttribute('data-quiz-fields') !== quizType;
        });
      }
    };

    const updateDeleteSelectedButton = () => {
      if (!listNode) return;
      const checkedBoxes = listNode.querySelectorAll('.step-checkbox:checked');
      const count = checkedBoxes.length;
      if (deleteSelectedBtn) {
        deleteSelectedBtn.style.display = count > 0 ? 'inline-flex' : 'none';
      }
      if (selectedCountSpan) {
        selectedCountSpan.textContent = count;
      }
      if (selectAllCheckbox) {
        const allBoxes = listNode.querySelectorAll('.step-checkbox');
        selectAllCheckbox.checked = allBoxes.length > 0 && count === allBoxes.length;
      }
    };

    const renderSteps = () => {
      if (titleNode) titleNode.textContent = state.node?.title ? `Langkah Belajar: ${state.node.title}` : 'Langkah Pembelajaran';
      if (subtitleNode) subtitleNode.textContent = 'Kelola urutan dan isi langkah belajar (teks, pengenalan huruf, kuis).';

      if (selectAllContainer) {
        selectAllContainer.style.display = state.steps.length > 0 ? 'flex' : 'none';
      }
      if (selectAllCheckbox) {
        selectAllCheckbox.checked = false;
      }

      if (!listNode) return;
      if (state.steps.length === 0) {
        listNode.hidden = true;
        if (emptyNode) emptyNode.hidden = false;
        updateDeleteSelectedButton();
        return;
      }

      listNode.hidden = false;
      if (emptyNode) emptyNode.hidden = true;

      listNode.innerHTML = state.steps.map((step, index) => {
        let contentHtml = '';
        if (step.type === 'text') {
          contentHtml = `<p class="guru-step-preview-text">${escapeHtml(step.content || '').substring(0, 120)}...</p>`;
        } else if (step.type === 'character') {
          contentHtml = `<p><strong>Huruf:</strong> ${escapeHtml(step.character_symbol || '-')} &nbsp;&nbsp;|&nbsp;&nbsp; <strong>Cara Baca:</strong> ${escapeHtml(step.character_pronunciation || '-')}</p>`;
        } else if (step.type === 'vocabulary') {
          contentHtml = `<p><strong>Kata:</strong> ${escapeHtml(step.character_symbol || '-')} (${escapeHtml(step.character_pronunciation || '-')}) &nbsp;&nbsp;|&nbsp;&nbsp; <strong>Arti:</strong> ${escapeHtml(step.content || '-')}</p>`;
        } else if (step.type === 'quiz') {
          const options = step.quiz_options || [];
          contentHtml = `
            <p><strong>Pertanyaan:</strong> ${escapeHtml(step.quiz_query || '-')}</p>
            <small style="color: #666;">Pilihan: ${options.map((opt, idx) => `[${idx === step.quiz_correct_index ? '<b>' + escapeHtml(opt) + '</b>' : escapeHtml(opt)}]`).join(' / ')}</small>
          `;
        }

        return `
          <article class="guru-question-card" data-question-card="${step.id}" draggable="true">
            <div class="guru-question-card__number" style="cursor: grab; display: flex; align-items: center; gap: 4px;">
              <span class="material-symbols-outlined" style="font-size: 18px; color: #aaa; vertical-align: middle;">drag_indicator</span>
              ${index + 1}
            </div>
            <div class="guru-question-card__body">
              <span class="guru-material-type guru-material-type--${step.type}">${step.type_label}</span>
              <div style="margin-top: 8px;">${contentHtml}</div>
            </div>
            <div class="guru-unit-card__actions" style="display: flex; align-items: center; gap: 8px;">
              <button type="button" class="guru-unit-button" data-edit-question="${step.id}">
                <span class="material-symbols-outlined" aria-hidden="true">edit</span>Edit
              </button>
              <input type="checkbox" class="step-checkbox" value="${step.id}" style="width: 18px; height: 18px; cursor: pointer; accent-color: var(--accent); margin-left: 4px;">
            </div>
          </article>
        `;
      }).join('');

      setupDragAndDrop();
      updateDeleteSelectedButton();
    };

    const loadSteps = async () => {
      setLoading(true);
      try {
        const payload = await fetchJson(`/api/guru/nodes/${nodeId}/steps`);
        state.node = payload.data.node;
        state.steps = payload.data.steps || [];
        if (loadingNode) loadingNode.hidden = true;
        if (errorNode) errorNode.hidden = true;
        renderSteps();
      } catch (error) {
        setError(error.message || 'Gagal memuat langkah.');
      }
    };

    const openModal = (modal) => {
      if (modal) modal.hidden = false;
    };

    const closeModal = (modal) => {
      if (modal) modal.hidden = true;
    };

    const openStepModal = (step = null) => {
      stepForm?.reset();
      if (stepKanjiSelect) {
        stepKanjiSelect.setValue('', '');
      }
      if (stepCharacterSelect) {
        stepCharacterSelect.setValue('', '');
      }
      if (stepFormError) stepFormError.hidden = true;
      if (stepIdInput) stepIdInput.value = step?.id || '';
      if (stepModalTitle) stepModalTitle.textContent = step ? 'Edit Langkah' : 'Tambah Langkah';

      if (step && stepForm) {
        stepForm.elements.type.value = step.type || 'text';
        stepForm.elements.sort_order.value = step.sort_order ?? '';

        if (step.type === 'text') {
          stepForm.elements.content.value = step.content || '';
        } else if (step.type === 'character') {
          if (stepCharacterSelect) {
            stepCharacterSelect.setValue(step.character_symbol || '', step.character_pronunciation || '');
          } else {
            stepForm.elements.character_symbol.value = step.character_symbol || '';
            stepForm.elements.character_pronunciation.value = step.character_pronunciation || '';
          }
        } else if (step.type === 'vocabulary') {
          stepForm.elements.vocab_kata.value = step.character_symbol || '';
          stepForm.elements.vocab_romaji.value = step.character_pronunciation || '';
          stepForm.elements.vocab_arti.value = step.content || '';
          if (stepKanjiSelect) {
            stepKanjiSelect.setValue(step.kanji_id || '', step.kanji_text || '');
          } else {
            stepForm.elements.vocab_kanji_id.value = step.kanji_id || '';
          }
          stepForm.elements.vocab_tipe_kata.value = step.tipe_kata || '';
          stepForm.elements.vocab_deskripsi.value = step.deskripsi || '';
          stepForm.elements.vocab_level.value = step.level || '';
        } else if (step.type === 'quiz') {
          stepForm.elements.quiz_instruction.value = step.quiz_instruction || 'PILIH JAWABAN YANG BENAR';
          stepForm.elements.quiz_query.value = step.quiz_query || '';
          stepForm.elements.quiz_option_0.value = step.quiz_options?.[0] || '';
          stepForm.elements.quiz_option_1.value = step.quiz_options?.[1] || '';
          stepForm.elements.quiz_option_2.value = step.quiz_options?.[2] || '';
          stepForm.elements.quiz_option_3.value = step.quiz_options?.[3] || '';
          stepForm.elements.quiz_correct_index.value = String(step.quiz_correct_index ?? 0);
        }
      } else if (stepForm) {
        stepForm.elements.type.value = 'text';
        stepForm.elements.sort_order.value = '';
      }

      renderStepFields();
      openModal(stepModal);
    };

    const saveStep = async () => {
      if (!stepForm) return;

      const formData = new FormData(stepForm);
      const id = formData.get('id');
      const type = String(formData.get('type') || 'text');

      const payload = {
        node_id: nodeId,
        type,
      };

      const sortOrder = formData.get('sort_order');
      if (sortOrder !== '' && sortOrder !== null) {
        payload.sort_order = Number(sortOrder);
      }

      if (type === 'text') {
        payload.content = String(formData.get('content') || '');
      } else if (type === 'character') {
        payload.character_symbol = String(formData.get('character_symbol') || '');
        payload.character_pronunciation = String(formData.get('character_pronunciation') || '');
      } else if (type === 'vocabulary') {
        payload.character_symbol = String(formData.get('vocab_kata') || '');
        payload.character_pronunciation = String(formData.get('vocab_romaji') || '');
        payload.content = String(formData.get('vocab_arti') || '');
        payload.kanji_id = String(formData.get('vocab_kanji_id') || '');
        payload.tipe_kata = String(formData.get('vocab_tipe_kata') || '');
        payload.deskripsi = String(formData.get('vocab_deskripsi') || '');
        payload.level = String(formData.get('vocab_level') || '');
      } else if (type === 'quiz') {
        payload.quiz_instruction = String(formData.get('quiz_instruction') || 'PILIH JAWABAN YANG BENAR');
        payload.quiz_query = String(formData.get('quiz_query') || '');
        payload.quiz_options = [
          String(formData.get('quiz_option_0') || ''),
          String(formData.get('quiz_option_1') || ''),
          String(formData.get('quiz_option_2') || ''),
          String(formData.get('quiz_option_3') || ''),
        ];
        payload.quiz_correct_index = Number(formData.get('quiz_correct_index') || 0);
      }

      const submit = root.querySelector('[data-question-submit]');
      if (submit) submit.disabled = true;
      if (stepFormError) stepFormError.hidden = true;

      try {
        await fetchJson(id ? `/api/guru/steps/${id}` : '/api/guru/steps', {
          method: id ? 'PUT' : 'POST',
          body: JSON.stringify(payload),
        });
        closeModal(stepModal);
        await loadSteps();
      } catch (error) {
        if (stepFormError) {
          stepFormError.textContent = error.message || 'Langkah gagal disimpan.';
          stepFormError.hidden = false;
        }
      } finally {
        if (submit) submit.disabled = false;
      }
    };

    const deleteStep = async (step) => {
      if (!step || !window.confirm('Hapus langkah pembelajaran ini?')) return;

      setLoading(true);
      try {
        await fetchJson(`/api/guru/steps/${step.id}`, { method: 'DELETE' });
        await loadSteps();
      } catch (error) {
        setError(error.message || 'Langkah gagal dihapus.');
      }
    };

    const moveStep = async (draggedId, targetId) => {
      if (draggedId === targetId) return;

      const items = Array.from(state.steps);
      const draggedIndex = items.findIndex((item) => String(item.id) === String(draggedId));
      const targetIndex = items.findIndex((item) => String(item.id) === String(targetId));

      if (draggedIndex === -1 || targetIndex === -1) return;

      const [removed] = items.splice(draggedIndex, 1);
      items.splice(targetIndex, 0, removed);

      const reordered = items.map((item, idx) => ({
        id: item.id,
        sort_order: idx + 1,
      }));

      state.steps = items.map((item, idx) => ({ ...item, sort_order: idx + 1 }));
      renderSteps();

      try {
        await fetchJson('/api/guru/steps/reorder', {
          method: 'PATCH',
          body: JSON.stringify({ steps: reordered }),
        });
      } catch (error) {
        console.error('Reorder error:', error);
        loadSteps();
      }
    };

    const setupDragAndDrop = () => {
      let draggedId = null;

      listNode.querySelectorAll('[data-question-card]').forEach((card) => {
        card.addEventListener('dragstart', (event) => {
          draggedId = card.getAttribute('data-question-card');
          card.classList.add('is-dragging');
          dragAutoScroller.start(event.clientY);
          if (event.dataTransfer) {
            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData('text/plain', draggedId);
          }
        });

        card.addEventListener('dragover', (event) => {
          event.preventDefault();
          card.classList.add('is-drag-over');
        });

        card.addEventListener('dragleave', () => {
          card.classList.remove('is-drag-over');
        });

        card.addEventListener('drop', (event) => {
          event.preventDefault();
          card.classList.remove('is-drag-over');
          const targetId = card.getAttribute('data-question-card');
          moveStep(draggedId, targetId);
        });

        card.addEventListener('dragend', () => {
          dragAutoScroller.stop();
          card.classList.remove('is-dragging');
        });
      });
    };

    root.addEventListener('dragover', (event) => {
      if (!dragAutoScroller.isActive()) return;
      event.preventDefault();
      if (event.dataTransfer) event.dataTransfer.dropEffect = 'move';
      dragAutoScroller.update(event.clientY);
    });

    root.addEventListener('drop', (event) => {
      if (dragAutoScroller.isActive()) event.preventDefault();
      dragAutoScroller.stop();
    });

    root.addEventListener('dragend', () => {
      dragAutoScroller.stop();
    });

    root.addEventListener('click', (event) => {
      const target = event.target instanceof Element ? event.target : null;
      if (!target) return;

      if (target.closest('[data-open-question-modal]')) {
        openStepModal();
      }

      const openImportBtn = target.closest('[data-open-import-modal]');
      if (openImportBtn) {
        importForm?.reset();
        if (importFormError) importFormError.hidden = true;
        openModal(importModal);
      }

      if (target.closest('[data-close-guru-modal]')) {
        closeModal(target.closest('.guru-modal-overlay'));
      }

      const editButton = target.closest('[data-edit-question]');
      if (editButton) {
        const step = state.steps.find((item) => String(item.id) === String(editButton.getAttribute('data-edit-question')));
        openStepModal(step);
      }

      const deleteButton = target.closest('[data-delete-question]');
      if (deleteButton) {
        const step = state.steps.find((item) => String(item.id) === String(deleteButton.getAttribute('data-delete-question')));
        deleteStep(step);
      }

      if (target.closest('[data-retry-questions]')) {
        loadSteps();
      }
    });

    stepTypeSelect?.addEventListener('change', renderStepFields);
    quizTypeSelect?.addEventListener('change', renderStepFields);

    stepForm?.addEventListener('submit', (event) => {
      event.preventDefault();
      saveStep();
    });

    const uploadZone = root.querySelector('#upload-zone');
    const fileInput = root.querySelector('#file-input');
    const uploadContent = root.querySelector('#upload-zone-content');
    const fileNameDisplay = root.querySelector('#file-name-display');
    const fileNameText = root.querySelector('#file-name-text');
    const fileClearBtn = root.querySelector('#file-clear-btn');

    if (uploadZone && fileInput) {
      uploadZone.addEventListener('click', (e) => {
        if (e.target.closest('#file-clear-btn')) return;
        fileInput.click();
      });

      fileInput.addEventListener('change', () => {
        if (fileInput.files && fileInput.files.length > 0) {
          if (fileNameText) fileNameText.textContent = fileInput.files[0].name;
          if (fileNameDisplay) fileNameDisplay.hidden = false;
          if (uploadContent) uploadContent.hidden = true;
        } else {
          if (fileNameDisplay) fileNameDisplay.hidden = true;
          if (uploadContent) uploadContent.hidden = false;
        }
      });

      fileClearBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        fileInput.value = '';
        fileInput.dispatchEvent(new Event('change'));
      });

      ['dragenter', 'dragover'].forEach((eventName) => {
        uploadZone.addEventListener(eventName, (e) => {
          e.preventDefault();
          uploadZone.classList.add('is-dragover');
        }, false);
      });

      ['dragleave', 'drop'].forEach((eventName) => {
        uploadZone.addEventListener(eventName, (e) => {
          e.preventDefault();
          uploadZone.classList.remove('is-dragover');
        }, false);
      });

      uploadZone.addEventListener('drop', (e) => {
        e.preventDefault();
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files && files.length > 0) {
          fileInput.files = files;
          fileInput.dispatchEvent(new Event('change'));
        }
      }, false);
    }

    importForm?.addEventListener('submit', async (event) => {
      event.preventDefault();
      if (importFormError) importFormError.hidden = true;
      const submitBtn = importForm.querySelector('[data-step-import-submit]');
      if (submitBtn) submitBtn.disabled = true;

      const formData = new FormData(importForm);
      try {
        const response = await fetch(`/api/guru/nodes/${nodeId}/import-steps`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            Accept: 'application/json',
          },
          body: formData,
        });

        const payload = await response.json().catch(() => ({}));
        if (!response.ok) {
          throw new Error(payload.message || 'Import gagal.');
        }

        closeModal(importModal);
        if (fileInput) {
          fileInput.value = '';
          fileInput.dispatchEvent(new Event('change'));
        }
        await loadSteps();
      } catch (error) {
        if (importFormError) {
          importFormError.textContent = error.message;
          importFormError.hidden = false;
        }
      } finally {
        if (submitBtn) submitBtn.disabled = false;
      }
    });

    listNode?.addEventListener('change', (e) => {
      if (e.target.classList.contains('step-checkbox')) {
        updateDeleteSelectedButton();
      }
    });

    selectAllCheckbox?.addEventListener('change', () => {
      const isChecked = selectAllCheckbox.checked;
      listNode?.querySelectorAll('.step-checkbox').forEach((box) => {
        box.checked = isChecked;
      });
      updateDeleteSelectedButton();
    });

    deleteSelectedBtn?.addEventListener('click', async () => {
      const checkedBoxes = listNode?.querySelectorAll('.step-checkbox:checked');
      if (!checkedBoxes) return;
      const ids = Array.from(checkedBoxes).map((box) => box.value);
      if (ids.length === 0 || !window.confirm(`Hapus ${ids.length} langkah terpilih secara bersamaan?`)) return;

      setLoading(true);
      try {
        await Promise.all(ids.map(id => fetchJson(`/api/guru/steps/${id}`, { method: 'DELETE' })));
        await loadSteps();
      } catch (error) {
        setError(error.message || 'Gagal menghapus beberapa langkah.');
      }
    });

    root.querySelectorAll('.guru-modal-overlay').forEach((modal) => {
      modal.addEventListener('click', (event) => {
        if (event.target === modal) closeModal(modal);
      });
    });

    loadSteps();
  });
}

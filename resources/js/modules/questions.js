import { createDragAutoScroller } from './utils.js';

export default function initQuestionsModule() {
  function initSearchSelect(wrapper, config = {}) {
    const searchInput = wrapper.querySelector('.guru-search-select-search');
    const hiddenInput = wrapper.querySelector('.guru-search-select-value');
    const dropdown = wrapper.querySelector('.guru-search-select-dropdown');
    const optionsContainer = wrapper.querySelector('.guru-search-select-options');
    const loadingIndicator = wrapper.querySelector('.guru-search-select-loading');
    const arrow = wrapper.querySelector('.guru-search-select-arrow');

    let page = 1;
    let hasMore = true;
    let isLoading = false;
    let query = '';
    let selectedId = hiddenInput.value || '';
    let selectedText = '';

    const fetchItems = async (reset = false) => {
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
        const fetchUrl = config.url(query, page);
        const res = await fetch(fetchUrl);
        const data = await res.json();

        data.results.forEach(item => {
          const mapped = config.mapItem(item);
          const div = document.createElement('div');
          div.className = `guru-search-select-option ${mapped.id === selectedId ? 'is-selected' : ''}`;
          div.setAttribute('data-id', mapped.id);
          div.setAttribute('data-text', mapped.text);
          div.innerHTML = mapped.html;
          div.addEventListener('mousedown', (e) => {
            e.preventDefault();
            e.stopPropagation();
            selectItem(mapped.id, mapped.text, item);
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

    const selectItem = (id, text, item = null) => {
      selectedId = id;
      hiddenInput.value = id;
      selectedText = id ? text : '';
      searchInput.value = selectedText;
      searchInput.placeholder = id ? selectedText : (config.placeholder || 'Cari...');

      hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));

      optionsContainer.querySelectorAll('.guru-search-select-option').forEach(opt => {
        opt.classList.toggle('is-selected', opt.getAttribute('data-id') === id);
      });

      if (config.onSelect) {
        config.onSelect(id, text, item);
      }
    };

    wrapper.setValue = (id, text, item = null) => {
      selectItem(id, text, item);
    };

    const openDropdown = () => {
      dropdown.style.display = 'flex';
      if (optionsContainer.children.length === 0) {
        fetchItems(true);
      }
    };

    const closeDropdown = () => {
      dropdown.style.display = 'none';
      searchInput.value = selectedId ? selectedText : '';
      searchInput.blur();
    };

    let searchTimeout;
    searchInput.addEventListener('input', () => {
      query = searchInput.value;
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => {
        fetchItems(true);
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
        fetchItems();
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
      initSearchSelect(kanjiWrapper, {
        url: (query, page) => `/api/guru/kanjis/search?q=${encodeURIComponent(query)}&page=${page}`,
        mapItem: (item) => ({
          id: item.id,
          text: `${item.kanji} (${item.makna})`,
          html: `<strong style="font-family: var(--font-jp); font-size: 16px;">${item.kanji}</strong> <span style="color: #667085; font-size: 12px;">${item.makna}</span>`
        }),
        placeholder: 'Cari Kanji (Karakter / Makna)...'
      });
      stepKanjiSelect = kanjiWrapper;
    }

    const vocabSearchSection = root.querySelector('#vocab-search-section');
    const vocabManualSection = root.querySelector('#vocab-manual-section');
    const vocabBtnCreateNew = root.querySelector('#vocab-btn-create-new');
    const vocabBtnBackToSearch = root.querySelector('#vocab-btn-back-to-search');

    let stepKataSelect = null;
    const kataWrapper = root.querySelector('#step-kata-select');
    if (kataWrapper) {
      initSearchSelect(kataWrapper, {
        url: (query, page) => `/api/guru/katas/search?q=${encodeURIComponent(query)}&page=${page}`,
        mapItem: (item) => ({
          id: item.id,
          text: `${item.kata} (${item.romaji})`,
          html: `<strong style="font-family: var(--font-jp); font-size: 16px;">${item.kata}</strong> <span style="color: #667085; font-size: 12px;">${item.romaji} (${item.arti})</span>`
        }),
        placeholder: 'Cari Kosakata (Jepang / Romaji / Arti)...',
        onSelect: (id, text, selectedKata) => {
          if (selectedKata && stepForm) {
            stepForm.elements.vocab_kata.value = selectedKata.kata || '';
            stepForm.elements.vocab_kana.value = selectedKata.kana || '';
            stepForm.elements.vocab_romaji.value = selectedKata.romaji || '';
            stepForm.elements.vocab_arti.value = selectedKata.arti || '';
            if (stepKanjiSelect) {
              stepKanjiSelect.setValue(selectedKata.kanji_id || '', selectedKata.kanji_text || '');
            } else {
              stepForm.elements.vocab_kanji_id.value = selectedKata.kanji_id || '';
            }
            stepForm.elements.vocab_tipe_kata.value = selectedKata.tipe_kata || '';
            stepForm.elements.vocab_level.value = selectedKata.level || '';
            stepForm.elements.vocab_deskripsi.value = selectedKata.deskripsi || '';
          }
          const searchDetailInput = root.querySelector('#vocab-search-detail');
          if (searchDetailInput && selectedKata) {
            searchDetailInput.value = `${selectedKata.romaji || ''} (${selectedKata.arti || ''})`;
          }
        }
      });
      stepKataSelect = kataWrapper;
    }

    if (vocabBtnCreateNew) {
      vocabBtnCreateNew.addEventListener('click', () => {
        if (stepForm) {
          stepForm.elements.vocab_kata.value = '';
          stepForm.elements.vocab_kana.value = '';
          stepForm.elements.vocab_romaji.value = '';
          stepForm.elements.vocab_arti.value = '';
          if (stepKanjiSelect) {
            stepKanjiSelect.setValue('', '');
          } else {
            stepForm.elements.vocab_kanji_id.value = '';
          }
          stepForm.elements.vocab_tipe_kata.value = '';
          stepForm.elements.vocab_level.value = '';
          stepForm.elements.vocab_deskripsi.value = '';
        }
        if (stepKataSelect) {
          stepKataSelect.setValue('', '');
        }
        if (vocabManualSection) vocabManualSection.style.display = 'flex';
        if (vocabSearchSection) vocabSearchSection.style.display = 'none';
      });
    }

    if (vocabBtnBackToSearch) {
      vocabBtnBackToSearch.addEventListener('click', () => {
        if (stepForm) {
          stepForm.elements.vocab_kata.value = '';
          stepForm.elements.vocab_kana.value = '';
          stepForm.elements.vocab_romaji.value = '';
          stepForm.elements.vocab_arti.value = '';
          if (stepKanjiSelect) {
            stepKanjiSelect.setValue('', '');
          } else {
            stepForm.elements.vocab_kanji_id.value = '';
          }
          stepForm.elements.vocab_tipe_kata.value = '';
          stepForm.elements.vocab_level.value = '';
          stepForm.elements.vocab_deskripsi.value = '';
        }
        if (stepKataSelect) {
          stepKataSelect.setValue('', '');
        }
        if (vocabManualSection) vocabManualSection.style.display = 'none';
        if (vocabSearchSection) vocabSearchSection.style.display = 'flex';
      });
    }

    let stepCharacterSelect = null;
    const characterWrapper = root.querySelector('#step-character-select');
    if (characterWrapper) {
      const pronunciationInput = root.querySelector('[name="character_pronunciation"]');
      initSearchSelect(characterWrapper, {
        url: (query, page) => `/api/guru/hurufs/search?q=${encodeURIComponent(query)}&page=${page}`,
        mapItem: (item) => ({
          id: item.simbol,
          text: `${item.simbol} (${item.cara_baca})`,
          html: `<strong style="font-family: var(--font-jp); font-size: 16px;">${item.simbol}</strong> <span style="color: #667085; font-size: 12px; text-transform: capitalize;">${item.tipe} (${item.cara_baca})</span>`
        }),
        placeholder: 'Cari Huruf (Simbol / Romaji)...',
        onSelect: (id, text, item) => {
          if (pronunciationInput && item) {
            pronunciationInput.value = item.cara_baca || '';
          }
        }
      });
      stepCharacterSelect = characterWrapper;

      const hiddenInput = characterWrapper.querySelector('.guru-search-select-value');
      if (hiddenInput) {
        hiddenInput.addEventListener('change', async () => {
          const val = hiddenInput.value;
          if (!val) return;

          const stepId = stepIdInput?.value || '';

          try {
            const res = await fetch(`/api/guru/steps/check-character?symbol=${encodeURIComponent(val)}&exclude_step_id=${stepId}`);
            const data = await res.json();
            if (data.exists) {
              if (stepFormError) {
                stepFormError.textContent = `huruf sudah ada di node ${data.node_title}`;
                stepFormError.hidden = false;
              }
              const submitBtn = stepForm?.querySelector('[data-question-submit]');
              if (submitBtn) submitBtn.disabled = true;
            } else {
              if (stepFormError && stepFormError.textContent.startsWith('huruf sudah ada di node')) {
                stepFormError.hidden = true;
                stepFormError.textContent = '';
              }
              const submitBtn = stepForm?.querySelector('[data-question-submit]');
              if (submitBtn) submitBtn.disabled = false;
            }
          } catch (e) {
            console.error(e);
          }
        });
      }
    }

    let quizCharacterSelect = null;
    const quizCharacterWrapper = root.querySelector('#quiz-character-select');

    const triggerAutoQuizGeneration = async () => {
      const charSymbol = root.querySelector('[name="quiz_character_symbol"]')?.value || '';
      const charPronunciation = root.querySelector('#quiz-character-pronunciation')?.value || '';
      const quizType = root.querySelector('[data-quiz-type]')?.value || '';
      const quizQuestionType = root.querySelector('[data-quiz-question-type]')?.value || 'character';

      if (!charSymbol || !quizType || quizQuestionType !== 'character') return;

      try {
        const res = await fetch(`/api/guru/nodes/${nodeId}/available-quiz-characters`);
        const data = await res.json();
        const list = (data.characters || []).filter(c => c.character_symbol && c.character_symbol.trim() !== '');

        if (list.length < 4) {
          if (stepFormError) {
            stepFormError.textContent = 'belum cukup huruf untuk ditambahkan kuis';
            stepFormError.hidden = false;
          }
          const submitBtn = stepForm?.querySelector('[data-question-submit]');
          if (submitBtn) submitBtn.disabled = true;
          return;
        } else {
          if (stepFormError && stepFormError.textContent === 'belum cukup huruf untuk ditambahkan kuis') {
            stepFormError.hidden = true;
            stepFormError.textContent = '';
          }
          const submitBtn = stepForm?.querySelector('[data-question-submit]');
          if (submitBtn) submitBtn.disabled = false;
        }

        const otherChars = list.filter(c => c.character_symbol !== charSymbol);
        const shuffled = otherChars.sort(() => 0.5 - Math.random());
        const distractors = shuffled.slice(0, 3);

        let questionText = '';
        let options = [];

        if (quizType === 'romaji_to_kana') {
          questionText = charPronunciation;
          const correctAns = charSymbol;
          const distKanas = distractors.map(d => d.character_symbol);
          options = [correctAns, ...distKanas];
        } else if (quizType === 'kana_to_romaji') {
          questionText = charSymbol;
          const correctAns = charPronunciation;
          const distRomaji = distractors.map(d => d.character_pronunciation);
          options = [correctAns, ...distRomaji];
        }

        const correctVal = options[0];
        const shuffledOptions = options.sort(() => 0.5 - Math.random());
        const correctIndex = shuffledOptions.indexOf(correctVal);

        if (stepForm) {
          stepForm.elements.quiz_query.value = questionText;
          stepForm.elements.quiz_option_0.value = shuffledOptions[0] || '';
          stepForm.elements.quiz_option_1.value = shuffledOptions[1] || '';
          stepForm.elements.quiz_option_2.value = shuffledOptions[2] || '';
          stepForm.elements.quiz_option_3.value = shuffledOptions[3] || '';
          stepForm.elements.quiz_correct_index.value = String(correctIndex);
        }
      } catch (e) {
        console.error(e);
      }
    };

    if (quizCharacterWrapper) {
      const pronunciationInput = root.querySelector('#quiz-character-pronunciation');
      initSearchSelect(quizCharacterWrapper, {
        url: (query, page) => `/api/guru/hurufs/search?q=${encodeURIComponent(query)}&page=${page}`,
        mapItem: (item) => ({
          id: item.simbol,
          text: `${item.simbol} (${item.cara_baca})`,
          html: `<strong style="font-family: var(--font-jp); font-size: 16px;">${item.simbol}</strong> <span style="color: #667085; font-size: 12px; text-transform: capitalize;">${item.tipe} (${item.cara_baca})</span>`
        }),
        placeholder: 'Cari Huruf (Simbol / Romaji)...',
        onSelect: (id, text, item) => {
          if (pronunciationInput && item) {
            pronunciationInput.value = item.cara_baca || '';
          }
        }
      });
      quizCharacterSelect = quizCharacterWrapper;

      const hiddenInput = quizCharacterWrapper.querySelector('.guru-search-select-value');
      const quizTypeContainer = root.querySelector('#quiz-type-container');
      const quizTypeSelect = root.querySelector('[data-quiz-type]');
      const quizQuestionTypeSelect = root.querySelector('[data-quiz-question-type]');

      if (hiddenInput) {
        hiddenInput.addEventListener('change', () => {
          const val = hiddenInput.value;
          if (quizTypeContainer) {
            quizTypeContainer.style.display = val ? 'flex' : 'none';
          }
          if (!val && quizTypeSelect) {
            quizTypeSelect.value = '';
          }
          triggerAutoQuizGeneration();
        });
      }

      if (quizTypeSelect) {
        quizTypeSelect.addEventListener('change', () => {
          triggerAutoQuizGeneration();
        });
      }

      if (quizQuestionTypeSelect) {
        quizQuestionTypeSelect.addEventListener('change', renderStepFields);
      }
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

    function renderStepFields() {
      const type = stepTypeSelect?.value || 'text';
      root.querySelectorAll('[data-question-fields]').forEach((fields) => {
        fields.hidden = fields.getAttribute('data-question-fields') !== type;
      });

      if (type === 'quiz') {
        const quizQuestionType = root.querySelector('[data-quiz-question-type]')?.value || 'character';
        root.querySelectorAll('[data-quiz-question-fields]').forEach((fields) => {
          fields.style.display = fields.getAttribute('data-quiz-question-fields') === quizQuestionType ? 'block' : 'none';
        });

        // Hide multiple choice inputs (question query, choices) when question type is character (auto generated)
        const showMultipleChoiceFields = quizQuestionType !== 'character';
        root.querySelectorAll('[data-quiz-fields]').forEach((fields) => {
          fields.style.display = showMultipleChoiceFields ? 'block' : 'none';
        });

        // Show/hide quiz-type-container based on whether a character is selected
        const quizCharSymbol = root.querySelector('[name="quiz_character_symbol"]')?.value || '';
        const quizTypeContainer = root.querySelector('#quiz-type-container');
        if (quizTypeContainer) {
          quizTypeContainer.style.display = (quizQuestionType === 'character' && quizCharSymbol) ? 'flex' : 'none';
        }
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
          const isHurufLevel = (state.node && state.node.level_name && state.node.level_name.toLowerCase() === 'huruf');
          const displayWord = isHurufLevel ? (step.kana || step.character_symbol) : step.character_symbol;
          contentHtml = `<p><strong>Kata:</strong> ${escapeHtml(displayWord || '-')} (${escapeHtml(step.character_pronunciation || '-')}) &nbsp;&nbsp;|&nbsp;&nbsp; <strong>Arti:</strong> ${escapeHtml(step.content || '-')}</p>`;
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
      if (stepKataSelect) {
        stepKataSelect.setValue('', '');
      }
      if (stepCharacterSelect) {
        stepCharacterSelect.setValue('', '');
      }
      if (quizCharacterSelect) {
        quizCharacterSelect.setValue('', '');
      }

      if (vocabSearchSection) vocabSearchSection.style.display = 'flex';
      if (vocabManualSection) vocabManualSection.style.display = 'none';
      const searchDetailInput = root.querySelector('#vocab-search-detail');
      if (searchDetailInput) {
        searchDetailInput.value = '';
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
          if (vocabSearchSection) vocabSearchSection.style.display = 'flex';
          if (vocabManualSection) vocabManualSection.style.display = 'none';

          if (stepKataSelect) {
            stepKataSelect.setValue(step.kata_id || '', `${step.character_symbol || ''} (${step.character_pronunciation || ''})`);
          }
          if (searchDetailInput) {
            searchDetailInput.value = `${step.character_pronunciation || ''} (${step.content || ''})`;
          }

          stepForm.elements.vocab_kata.value = step.character_symbol || '';
          stepForm.elements.vocab_kana.value = step.kana || '';
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

          if (stepForm.elements.quiz_question_type) {
            stepForm.elements.quiz_question_type.value = 'character';
          }
          if (quizCharacterSelect) {
            quizCharacterSelect.setValue(step.character_symbol || '', step.character_pronunciation || '');
          }

          let detectedQuizType = '';
          if (step.character_symbol && step.character_pronunciation) {
            if (step.quiz_query === step.character_pronunciation) {
              detectedQuizType = 'romaji_to_kana';
            } else if (step.quiz_query === step.character_symbol) {
              detectedQuizType = 'kana_to_romaji';
            }
          }
          if (stepForm.elements.quiz_type) {
            stepForm.elements.quiz_type.value = detectedQuizType;
          }
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
        payload.kana = String(formData.get('vocab_kana') || '');
        payload.character_pronunciation = String(formData.get('vocab_romaji') || '');
        payload.content = String(formData.get('vocab_arti') || '');
        payload.kanji_id = String(formData.get('vocab_kanji_id') || '');
        payload.tipe_kata = String(formData.get('vocab_tipe_kata') || '');
        payload.deskripsi = String(formData.get('vocab_deskripsi') || '');
        payload.level = String(formData.get('vocab_level') || '');
        payload.vocab_selected_id = String(formData.get('vocab_selected_id') || '');
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
        payload.character_symbol = String(formData.get('quiz_character_symbol') || '');
        payload.character_pronunciation = String(formData.get('quiz_character_pronunciation') || '');
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

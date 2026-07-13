document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-letter-tab]').forEach((tab) => {
    tab.addEventListener('click', () => {
      const target = tab.getAttribute('data-letter-tab');

      document.querySelectorAll('[data-letter-tab]').forEach((item) => {
        item.classList.toggle('letters-tab--active', item === tab);
      });

      document.querySelectorAll('[data-letter-panel]').forEach((panel) => {
        panel.hidden = panel.getAttribute('data-letter-panel') !== target;
      });
    });
  });

  document.querySelectorAll('[data-toggle-password]').forEach((button) => {
    button.addEventListener('click', () => {
      const input = button.closest('.login-password')?.querySelector('input');
      if (!input) return;

      const show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      button.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
      const icon = button.querySelector('.material-symbols-outlined');
      if (icon) icon.textContent = show ? 'visibility_off' : 'visibility';
    });
  });

  document.querySelectorAll('[data-profile-modal]').forEach((button) => {
    button.addEventListener('click', () => {
      const modalName = button.getAttribute('data-profile-modal');
      const modal = document.querySelector(`[data-modal="${modalName}"]`);
      if (modal) modal.hidden = false;
    });
  });

  document.querySelectorAll('[data-close-modal]').forEach((button) => {
    button.addEventListener('click', () => {
      const modal = button.closest('[data-modal]');
      if (modal) modal.hidden = true;
    });
  });

  document.querySelectorAll('[data-modal]').forEach((modal) => {
    modal.addEventListener('click', (event) => {
      if (event.target === modal) modal.hidden = true;
    });
  });

  document.querySelectorAll('[data-fake-save]').forEach((button) => {
    button.addEventListener('click', () => {
      // UI only: simulate save feedback without sending any request.
      const pill = document.querySelector('[data-save-pill]');
      if (!pill) return;

      pill.hidden = false;
      window.setTimeout(() => {
        pill.hidden = true;
      }, 2400);
    });
  });

  document.querySelectorAll('[data-guru-menu]').forEach((button) => {
    button.addEventListener('click', () => {
      button.closest('.guru-shell')?.classList.toggle('guru-menu-open');
    });
  });

  document.querySelectorAll('[data-guru-units]').forEach((root) => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const state = {
      levels: [],
      activeLevelId: window.localStorage.getItem('guru.activeLevelId') || null,
      units: [],
      loading: false,
    };

    const activeLevelNode = root.querySelector('[data-active-level]');
    const unitCountNode = root.querySelector('[data-unit-count]');
    const materialCountNode = root.querySelector('[data-material-count]');
    const quizCountNode = root.querySelector('[data-quiz-count]');
    const loadingNode = root.querySelector('[data-units-loading]');
    const errorNode = root.querySelector('[data-units-error]');
    const errorMessageNode = root.querySelector('[data-units-error-message]');
    const emptyNode = root.querySelector('[data-units-empty]');
    const listNode = root.querySelector('[data-unit-list]');
    const unitModal = root.querySelector('[data-unit-modal]');
    const materialModal = root.querySelector('[data-material-modal]');
    const detailModal = root.querySelector('[data-material-detail-modal]');
    const detailTitle = root.querySelector('[data-material-detail-title]');
    const detailType = root.querySelector('[data-material-detail-type]');
    const detailUnit = root.querySelector('[data-material-detail-unit]');
    const detailOrder = root.querySelector('[data-material-detail-order]');
    const levelModal = root.querySelector('[data-level-modal]');
    const unitForm = root.querySelector('[data-unit-form]');
    const unitIdInput = root.querySelector('[data-unit-id]');
    const unitLevelSelect = root.querySelector('[data-unit-level]');
    const unitModalTitle = root.querySelector('[data-unit-modal-title]');
    const unitFormError = root.querySelector('[data-unit-form-error]');
    const materialForm = root.querySelector('[data-material-form]');
    const materialIdInput = root.querySelector('[data-material-id]');
    const materialLevelSelect = root.querySelector('[data-material-level]');
    const materialUnitSelect = root.querySelector('[data-material-unit]');
    const materialModalTitle = root.querySelector('[data-material-modal-title]');
    const materialFormError = root.querySelector('[data-material-form-error]');
    const levelOptions = root.querySelector('[data-level-options]');

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
      state.loading = isLoading;
      if (loadingNode) loadingNode.hidden = !isLoading;
      if (listNode) listNode.hidden = isLoading;
      if (emptyNode) emptyNode.hidden = true;
      if (errorNode) errorNode.hidden = true;
    };

    const setError = (message) => {
      if (loadingNode) loadingNode.hidden = true;
      if (listNode) listNode.hidden = true;
      if (emptyNode) emptyNode.hidden = true;
      if (errorNode) errorNode.hidden = false;
      if (errorMessageNode) errorMessageNode.textContent = message;
    };

    const activeLevel = () => state.levels.find((level) => String(level.id) === String(state.activeLevelId));

    const renderLevelControls = () => {
      const current = activeLevel();
      if (activeLevelNode) activeLevelNode.textContent = current?.code || '-';

      if (unitLevelSelect) {
        unitLevelSelect.innerHTML = state.levels
          .map((level) => `<option value="${level.id}">${escapeHtml(level.code)} - ${escapeHtml(level.name)}</option>`)
          .join('');
      }

      if (materialLevelSelect) {
        materialLevelSelect.innerHTML = state.levels
          .map((level) => `<option value="${level.id}">${escapeHtml(level.code)} - ${escapeHtml(level.name)}</option>`)
          .join('');
        if (state.activeLevelId) materialLevelSelect.value = String(state.activeLevelId);
      }

      if (levelOptions) {
        levelOptions.innerHTML = state.levels.map((level) => `
          <button type="button" class="${String(level.id) === String(state.activeLevelId) ? 'is-active' : ''}" data-select-level="${level.id}">
            <span>${escapeHtml(level.code)}</span>
            <strong>${escapeHtml(level.name)}</strong>
            <small>${level.units_count ?? 0} unit</small>
          </button>
        `).join('');
      }
    };

    const renderUnitSelect = (selectedUnitId = '') => {
      if (!materialUnitSelect) return;

      materialUnitSelect.innerHTML = state.units.map((unit) => `
        <option value="${unit.id}">${escapeHtml(unit.title)}</option>
      `).join('');

      if (selectedUnitId) {
        materialUnitSelect.value = String(selectedUnitId);
      }
    };

    const labelForType = (type) => (type === 'quiz' ? 'Quiz' : 'Materi Pembelajaran');

    const findMaterial = (materialId) => {
      for (const unit of state.units) {
        const material = (unit.materials || []).find((item) => String(item.id) === String(materialId));
        if (material) return { material, unit };
      }

      return { material: null, unit: null };
    };

    const renderUnits = () => {
      const materials = state.units.flatMap((unit) => unit.materials || []);
      if (unitCountNode) unitCountNode.textContent = String(state.units.length);
      if (materialCountNode) materialCountNode.textContent = String(materials.length);
      if (quizCountNode) quizCountNode.textContent = String(materials.filter((material) => material.type === 'quiz').length);
      renderLevelControls();
      renderUnitSelect();

      if (!listNode) return;

      if (state.units.length === 0) {
        listNode.hidden = true;
        if (emptyNode) emptyNode.hidden = false;
        return;
      }

      listNode.hidden = false;
      if (emptyNode) emptyNode.hidden = true;

      listNode.innerHTML = state.units.map((unit, index) => {
        const unitMaterials = unit.materials || [];

        return `
          <article class="guru-unit-card" data-unit-card="${unit.id}" aria-label="Unit ${escapeHtml(unit.title)} dapat diurutkan dengan drag and drop">
            <div class="guru-unit-card__order">
              <span class="material-symbols-outlined guru-unit-card__drag" draggable="true" style="cursor: grab;" aria-hidden="true">drag_indicator</span>
              <strong>${index + 1}</strong>
            </div>
            <div class="guru-unit-card__body">
              <div class="guru-unit-card__meta">
                <span>${escapeHtml(unit.category_japanese)}</span>
                <small>${escapeHtml(unit.category_romaji)}</small>
              </div>
              <h2>${escapeHtml(unit.title)}</h2>
              <p>${escapeHtml(unit.description || 'Tanpa deskripsi.')}</p>
              <div style="margin-top: 8px; font-size: 0.9em; color: #666;">
                <span>${unitMaterials.length} isi unit tersedia</span>
              </div>
            </div>
            <div class="guru-unit-card__actions" style="display: flex; flex-direction: column; gap: 8px; justify-content: center;">
              <a class="guru-unit-button guru-unit-button--primary" href="/guru/units/${unit.id}/materials" style="display: inline-flex; align-items: center; justify-content: center; text-decoration: none; width: 100%;">
                <span class="material-symbols-outlined" aria-hidden="true" style="margin-right: 4px;">account_tree</span>
                KELOLA ISI
              </a>
              <button type="button" class="guru-unit-button" data-edit-unit="${unit.id}" style="width: 100%;">
                <span class="material-symbols-outlined" aria-hidden="true">edit</span>
                EDIT
              </button>
              <button type="button" class="guru-unit-button guru-unit-button--danger" data-delete-unit="${unit.id}" style="width: 100%;">
                <span class="material-symbols-outlined" aria-hidden="true">delete</span>
                HAPUS
              </button>
            </div>
          </article>
        `;
      }).join('');
    };

    const loadUnits = async () => {
      const level = activeLevel();
      if (!level) {
        state.units = [];
        renderUnits();
        return;
      }

      setLoading(true);
      try {
        const payload = await fetchJson(`/api/guru/levels/${level.id}/materials`);
        state.units = payload.data.units || [];
        if (loadingNode) loadingNode.hidden = true;
        if (errorNode) errorNode.hidden = true;
        renderUnits();
      } catch (error) {
        setError(error.message || 'Gagal memuat unit.');
      }
    };

    const loadLevels = async () => {
      setLoading(true);
      try {
        const payload = await fetchJson('/api/guru/levels');
        state.levels = payload.data || [];
        const hasStoredLevel = state.levels.some((level) => String(level.id) === String(state.activeLevelId));
        state.activeLevelId = hasStoredLevel
          ? state.activeLevelId
          : (state.levels.find((level) => level.code === 'N5')?.id || state.levels[0]?.id || null);
        if (state.activeLevelId) window.localStorage.setItem('guru.activeLevelId', state.activeLevelId);
        renderLevelControls();
        await loadUnits();
      } catch (error) {
        setError(error.message || 'Gagal memuat level.');
      }
    };

    const openModal = (modal) => {
      if (modal) modal.hidden = false;
    };

    const closeModal = (modal) => {
      if (modal) modal.hidden = true;
    };

    const openUnitModal = (unit = null) => {
      unitForm?.reset();
      if (unitFormError) unitFormError.hidden = true;
      if (unitIdInput) unitIdInput.value = unit?.id || '';
      if (unitModalTitle) unitModalTitle.textContent = unit ? 'Edit Unit' : 'Tambah Unit';
      if (unitLevelSelect && activeLevel()) unitLevelSelect.value = String(unit?.level_id || state.activeLevelId);

      if (unit && unitForm) {
        unitForm.elements.title.value = unit.title || '';
        unitForm.elements.description.value = unit.description || '';
        if (unitForm.elements.category_japanese) unitForm.elements.category_japanese.value = unit.category_japanese || '学習';
        if (unitForm.elements.category_romaji) unitForm.elements.category_romaji.value = unit.category_romaji || 'gakushu';
        if (unitForm.elements.sort_order) unitForm.elements.sort_order.value = unit.sort_order ?? '';
        if (unitForm.elements.is_active) {
          if (unitForm.elements.is_active.type === 'checkbox') {
            unitForm.elements.is_active.checked = Boolean(unit.is_active);
          } else {
            unitForm.elements.is_active.value = unit.is_active ? '1' : '0';
          }
        }
      } else if (unitForm) {
        if (unitForm.elements.category_japanese) unitForm.elements.category_japanese.value = '学習';
        if (unitForm.elements.category_romaji) unitForm.elements.category_romaji.value = 'gakushu';
        if (unitForm.elements.sort_order) unitForm.elements.sort_order.value = '';
        if (unitForm.elements.is_active) {
          if (unitForm.elements.is_active.type === 'checkbox') {
            unitForm.elements.is_active.checked = true;
          } else {
            unitForm.elements.is_active.value = '1';
          }
        }
      }

      openModal(unitModal);
    };

    const openMaterialModal = (material = null, selectedUnitId = '') => {
      materialForm?.reset();
      if (materialFormError) materialFormError.hidden = true;
      if (materialIdInput) materialIdInput.value = material?.id || '';
      if (materialModalTitle) materialModalTitle.textContent = material ? 'Edit Isi Unit' : 'Tambah Isi Unit';

      const unitId = material?.unit_id || selectedUnitId || state.units[0]?.id || '';
      const unit = state.units.find((u) => String(u.id) === String(unitId));
      const levelId = unit ? unit.level_id : (state.activeLevelId || '');

      if (materialForm) {
        if (materialForm.elements.level_id) materialForm.elements.level_id.value = String(levelId);
        if (materialForm.elements.type) materialForm.elements.type.value = material?.type || 'lesson';
        if (materialForm.elements.order) materialForm.elements.order.value = material?.order ?? '';
        if (material && materialForm.elements.title) {
          materialForm.elements.title.value = material.title || '';
        }
      }

      renderLevelControls();
      renderUnitSelect(unitId);
      openModal(materialModal);
    };

    const openDetailModal = (material, unit) => {
      if (!material) return;

      if (detailTitle) detailTitle.textContent = material.title || 'Materi Pembelajaran';
      if (detailType) detailType.textContent = labelForType(material.type);
      if (detailUnit) detailUnit.textContent = unit?.title || '-';
      if (detailOrder) detailOrder.textContent = String(material.order ?? '-');
      openModal(detailModal);
    };

    const saveUnit = async () => {
      if (!unitForm) return;

      const formData = new FormData(unitForm);
      const id = formData.get('id');
      const payload = {
        level_id: Number(formData.get('level_id')),
        title: String(formData.get('title') || ''),
        description: String(formData.get('description') || ''),
        category_japanese: String(formData.get('category_japanese') || '学習'),
        category_romaji: String(formData.get('category_romaji') || 'gakushu'),
        is_active: unitForm.elements.is_active
          ? (unitForm.elements.is_active.type === 'checkbox' ? unitForm.elements.is_active.checked : String(formData.get('is_active')) === '1')
          : true,
      };
      const sortOrder = String(formData.get('sort_order') || '');
      if (sortOrder !== '') payload.sort_order = Number(sortOrder);

      const submit = root.querySelector('[data-unit-submit]');
      if (submit) submit.disabled = true;
      if (unitFormError) unitFormError.hidden = true;

      try {
        await fetchJson(id ? `/api/guru/units/${id}` : '/api/guru/units', {
          method: id ? 'PUT' : 'POST',
          body: JSON.stringify(payload),
        });
        state.activeLevelId = String(payload.level_id);
        window.localStorage.setItem('guru.activeLevelId', state.activeLevelId);
        closeModal(unitModal);
        await loadLevels();
      } catch (error) {
        if (unitFormError) {
          unitFormError.textContent = error.message || 'Unit gagal disimpan.';
          unitFormError.hidden = false;
        }
      } finally {
        if (submit) submit.disabled = false;
      }
    };

    const saveMaterial = async () => {
      if (!materialForm) return;

      const formData = new FormData(materialForm);
      const id = formData.get('id');
      const payload = {
        unit_id: String(formData.get('unit_id') || ''),
        type: String(formData.get('type') || 'lesson'),
        title: String(formData.get('title') || ''),
      };
      const levelId = formData.get('level_id');
      if (levelId) payload.level_id = Number(levelId);
      const order = String(formData.get('order') || '');
      if (order !== '') payload.order = Number(order);

      const submit = root.querySelector('[data-material-submit]');
      if (submit) submit.disabled = true;
      if (materialFormError) materialFormError.hidden = true;

      try {
        const response = await fetchJson(id ? `/api/guru/materials/${id}` : '/api/guru/materials', {
          method: id ? 'PUT' : 'POST',
          body: JSON.stringify(payload),
        });

        closeModal(materialModal);
        await loadUnits();

        if (!id && response.data?.type === 'quiz') {
          window.location.href = `/guru/materi/${response.data.id}/questions`;
        }
      } catch (error) {
        if (materialFormError) {
          materialFormError.textContent = error.message || 'Isi unit gagal disimpan.';
          materialFormError.hidden = false;
        }
      } finally {
        if (submit) submit.disabled = false;
      }
    };

    const deleteMaterial = async (material) => {
      if (!material || !window.confirm(`Hapus isi unit "${material.title}"?`)) return;

      setLoading(true);
      try {
        await fetchJson(`/api/guru/materials/${material.id}`, { method: 'DELETE' });
        await loadUnits();
      } catch (error) {
        setError(error.message || 'Isi unit gagal dihapus.');
      }
    };

    const deleteUnit = async (unit) => {
      if (!unit || !window.confirm(`Hapus unit "${unit.title}"?`)) return;

      setLoading(true);
      try {
        await fetchJson(`/api/guru/units/${unit.id}`, { method: 'DELETE' });
        await loadLevels();
      } catch (error) {
        setError(error.message || 'Unit gagal dihapus.');
      }
    };

    const saveUnitOrder = async (orderedIds, previousUnits) => {
      try {
        await fetchJson('/api/guru/units/reorder', {
          method: 'PATCH',
          body: JSON.stringify({
            level_id: Number(state.activeLevelId),
            unit_ids: orderedIds,
          }),
        });
        await loadUnits();
      } catch (error) {
        state.units = previousUnits;
        renderUnits();
        setError(error.message || 'Urutan unit gagal disimpan.');
      }
    };

    const moveUnit = (draggedId, targetId) => {
      if (!draggedId || !targetId || String(draggedId) === String(targetId)) return;

      const previousUnits = [...state.units];
      const fromIndex = state.units.findIndex((unit) => String(unit.id) === String(draggedId));
      const toIndex = state.units.findIndex((unit) => String(unit.id) === String(targetId));
      if (fromIndex < 0 || toIndex < 0) return;

      const nextUnits = [...state.units];
      const [moved] = nextUnits.splice(fromIndex, 1);
      nextUnits.splice(toIndex, 0, moved);
      state.units = nextUnits;
      renderUnits();
      saveUnitOrder(nextUnits.map((unit) => unit.id), previousUnits);
    };

    const moveMaterial = async (unitId, draggedId, targetId) => {
      if (draggedId === targetId) return;

      const unit = state.units.find((u) => String(u.id) === String(unitId));
      if (!unit) return;

      const items = Array.from(unit.materials || []);
      const draggedIndex = items.findIndex((item) => String(item.id) === String(draggedId));
      const targetIndex = items.findIndex((item) => String(item.id) === String(targetId));

      if (draggedIndex === -1 || targetIndex === -1) return;

      const [removed] = items.splice(draggedIndex, 1);
      items.splice(targetIndex, 0, removed);

      const reordered = items.map((item, idx) => ({
        id: item.id,
        order: idx + 1,
      }));

      unit.materials = items.map((item, idx) => ({ ...item, order: idx + 1 }));
      renderUnits();

      try {
        await fetchJson('/api/guru/materials/reorder', {
          method: 'PATCH',
          body: JSON.stringify({ materials: reordered }),
        });
      } catch (error) {
        console.error('Material reorder error:', error);
        loadUnits();
      }
    };

    root.addEventListener('click', (event) => {
      const target = event.target instanceof Element ? event.target : null;
      if (!target) return;

      if (target.closest('[data-open-unit-modal]')) {
        openUnitModal();
      }

      const openMaterialButton = target.closest('[data-open-material-modal]');
      if (openMaterialButton) {
        openMaterialModal(null, openMaterialButton.getAttribute('data-unit-id') || '');
      }

      if (target.closest('[data-open-level-modal]')) {
        openModal(levelModal);
      }

      if (target.closest('[data-close-guru-modal]')) {
        closeModal(target.closest('.guru-modal-overlay'));
      }

      const levelButton = target.closest('[data-select-level]');
      if (levelButton) {
        state.activeLevelId = levelButton.getAttribute('data-select-level');
        window.localStorage.setItem('guru.activeLevelId', state.activeLevelId);
        closeModal(levelModal);
        renderLevelControls();
        loadUnits();
      }

      const editButton = target.closest('[data-edit-unit]');
      if (editButton) {
        const unit = state.units.find((item) => String(item.id) === String(editButton.getAttribute('data-edit-unit')));
        openUnitModal(unit);
      }

      const deleteButton = target.closest('[data-delete-unit]');
      if (deleteButton) {
        const unit = state.units.find((item) => String(item.id) === String(deleteButton.getAttribute('data-delete-unit')));
        deleteUnit(unit);
      }

      const detailButton = target.closest('[data-material-detail]');
      if (detailButton) {
        const { material, unit } = findMaterial(detailButton.getAttribute('data-material-detail'));
        openDetailModal(material, unit);
      }

      const editMaterialButton = target.closest('[data-edit-material]');
      if (editMaterialButton) {
        const { material } = findMaterial(editMaterialButton.getAttribute('data-edit-material'));
        openMaterialModal(material);
      }

      const deleteMaterialButton = target.closest('[data-delete-material]');
      if (deleteMaterialButton) {
        const { material } = findMaterial(deleteMaterialButton.getAttribute('data-delete-material'));
        deleteMaterial(material);
      }

      if (target.closest('[data-retry-units]')) {
        loadUnits();
      }
    });

    root.addEventListener('dragstart', (event) => {
      // ONLY allow dragging if drag handle icon was clicked
      if (!event.target.classList.contains('guru-unit-card__drag') && !event.target.closest('.guru-unit-card__drag')) {
        if (event.target.classList.contains('guru-material-drag-handle') || event.target.closest('.guru-material-drag-handle')) {
          const row = event.target.closest('.guru-unit-material');
          if (row) {
            row.classList.add('is-dragging-material');
            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData('text/material-id', row.getAttribute('data-material-id'));
            event.dataTransfer.setData('text/parent-unit-id', row.closest('[data-unit-card]').getAttribute('data-unit-card'));
          }
          return;
        }
        event.preventDefault();
        return;
      }

      const card = event.target.closest('[data-unit-card]');
      if (!card) return;

      card.classList.add('is-dragging');
      event.dataTransfer.effectAllowed = 'move';
      event.dataTransfer.setData('text/plain', card.getAttribute('data-unit-card'));
    });

    root.addEventListener('dragover', (event) => {
      const isMaterial = event.dataTransfer.types.includes('text/material-id');
      if (isMaterial) {
        const row = event.target instanceof Element ? event.target.closest('.guru-unit-material') : null;
        if (!row) return;
        event.preventDefault();
        event.dataTransfer.dropEffect = 'move';
        root.querySelectorAll('.guru-unit-material.is-drag-over-material').forEach((item) => {
          if (item !== row) item.classList.remove('is-drag-over-material');
        });
        row.classList.add('is-drag-over-material');
        return;
      }

      const card = event.target instanceof Element ? event.target.closest('[data-unit-card]') : null;
      if (!card) return;

      event.preventDefault();
      event.dataTransfer.dropEffect = 'move';
      root.querySelectorAll('[data-unit-card].is-drag-over').forEach((item) => {
        if (item !== card) item.classList.remove('is-drag-over');
      });
      card.classList.add('is-drag-over');
    });

    root.addEventListener('dragleave', (event) => {
      const row = event.target instanceof Element ? event.target.closest('.guru-unit-material') : null;
      if (row && !row.contains(event.relatedTarget)) {
        row.classList.remove('is-drag-over-material');
      }

      const card = event.target instanceof Element ? event.target.closest('[data-unit-card]') : null;
      if (card && !card.contains(event.relatedTarget)) {
        card.classList.remove('is-drag-over');
      }
    });

    root.addEventListener('drop', (event) => {
      const isMaterial = event.dataTransfer.types.includes('text/material-id');
      if (isMaterial) {
        const row = event.target instanceof Element ? event.target.closest('.guru-unit-material') : null;
        if (!row) return;
        event.preventDefault();

        const draggedId = event.dataTransfer.getData('text/material-id');
        const parentUnitId = event.dataTransfer.getData('text/parent-unit-id');
        const targetId = row.getAttribute('data-material-id');
        const targetParentUnitId = row.closest('[data-unit-card]').getAttribute('data-unit-card');

        if (parentUnitId === targetParentUnitId) {
          moveMaterial(parentUnitId, draggedId, targetId);
        }

        root.querySelectorAll('.guru-unit-material.is-drag-over-material').forEach((item) => {
          item.classList.remove('is-drag-over-material');
        });
        return;
      }

      const card = event.target instanceof Element ? event.target.closest('[data-unit-card]') : null;
      if (!card) return;

      event.preventDefault();
      const draggedId = event.dataTransfer.getData('text/plain');
      const targetId = card.getAttribute('data-unit-card');
      root.querySelectorAll('[data-unit-card].is-drag-over').forEach((item) => item.classList.remove('is-drag-over'));
      moveUnit(draggedId, targetId);
    });

    root.addEventListener('dragend', () => {
      root.querySelectorAll('.guru-unit-material.is-dragging-material').forEach((item) => {
        item.classList.remove('is-dragging-material');
      });
      root.querySelectorAll('[data-unit-card].is-dragging, [data-unit-card].is-drag-over').forEach((item) => {
        item.classList.remove('is-dragging', 'is-drag-over');
      });
    });

    root.querySelectorAll('.guru-modal-overlay').forEach((modal) => {
      modal.addEventListener('click', (event) => {
        if (event.target === modal) closeModal(modal);
      });
    });

    unitForm?.addEventListener('submit', (event) => {
      event.preventDefault();
      saveUnit();
    });

    materialForm?.addEventListener('submit', (event) => {
      event.preventDefault();
      saveMaterial();
    });

    materialLevelSelect?.addEventListener('change', () => {
      state.activeLevelId = materialLevelSelect.value;
      window.localStorage.setItem('guru.activeLevelId', state.activeLevelId);
      loadUnits();
    });

    loadLevels();
  });

  document.querySelectorAll('[data-guru-materials]').forEach((root) => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const state = {
      unitId: window.currentUnitId || null,
      levelId: window.currentLevelId || null,
      units: [],
    };

    const loadingNode = root.querySelector('[data-materials-loading]');
    const errorNode = root.querySelector('[data-materials-error]');
    const errorMessageNode = root.querySelector('[data-materials-error-message]');
    const emptyNode = root.querySelector('[data-materials-empty]');
    const listNode = root.querySelector('[data-material-unit-list]');
    const materialModal = root.querySelector('[data-material-modal]');
    const detailModal = root.querySelector('[data-material-detail-modal]');
    const detailTitle = root.querySelector('[data-material-detail-title]');
    const detailType = root.querySelector('[data-material-detail-type]');
    const detailUnit = root.querySelector('[data-material-detail-unit]');
    const detailOrder = root.querySelector('[data-material-detail-order]');
    const materialForm = root.querySelector('[data-material-form]');
    const materialIdInput = root.querySelector('[data-material-id]');
    const materialModalTitle = root.querySelector('[data-material-modal-title]');
    const materialFormError = root.querySelector('[data-material-form-error]');

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
    };

    const setError = (message) => {
      if (loadingNode) loadingNode.hidden = true;
      if (listNode) listNode.hidden = true;
      if (emptyNode) emptyNode.hidden = true;
      if (errorNode) errorNode.hidden = false;
      if (errorMessageNode) errorMessageNode.textContent = message;
    };

    const labelForType = (type) => (type === 'quiz' ? 'Quiz' : 'Materi Pembelajaran');

    const renderMaterials = () => {
      if (!listNode) return;

      const unit = state.units.find((u) => String(u.id) === String(state.unitId));
      if (!unit) {
        listNode.hidden = true;
        if (emptyNode) emptyNode.hidden = false;
        return;
      }

      const unitMaterials = unit.materials || [];
      if (unitMaterials.length === 0) {
        listNode.hidden = true;
        if (emptyNode) emptyNode.hidden = false;
        return;
      }

      listNode.hidden = false;
      if (emptyNode) emptyNode.hidden = true;

      listNode.innerHTML = unitMaterials.map((material, materialIndex) => `
        <article class="guru-unit-card" data-unit-card="${material.id}" aria-label="Materi dapat diurutkan dengan drag and drop">
          <div class="guru-unit-card__order">
            <span class="material-symbols-outlined guru-unit-card__drag" draggable="true" style="cursor: grab;" aria-hidden="true">drag_indicator</span>
            <strong>${materialIndex + 1}</strong>
          </div>
          <div class="guru-unit-card__body">
            <div class="guru-unit-card__meta">
              <span>${escapeHtml(labelForType(material.type).toUpperCase())}</span>
            </div>
            <h2>${escapeHtml(material.title)}</h2>
            <p>${material.type === 'quiz' ? `${material.questions_count || 0} soal tersedia` : 'Konten belajar di dalam unit'}</p>
          </div>
          <div class="guru-unit-card__actions" style="display: flex; flex-direction: column; gap: 8px; justify-content: center;">
            <a class="guru-unit-button guru-unit-button--primary" href="/guru/materi/${material.id}/questions" style="display: inline-flex; align-items: center; justify-content: center; text-decoration: none; width: 100%;">
              <span class="material-symbols-outlined" aria-hidden="true" style="margin-right: 4px;">menu_book</span>
              KELOLA LANGKAH
            </a>
            <button type="button" class="guru-unit-button" data-edit-material="${material.id}" style="width: 100%;">
              <span class="material-symbols-outlined" aria-hidden="true">edit</span>
              EDIT
            </button>
            <button type="button" class="guru-unit-button guru-unit-button--danger" data-delete-material="${material.id}" style="width: 100%;">
              <span class="material-symbols-outlined" aria-hidden="true">delete</span>
              HAPUS
            </button>
          </div>
        </article>
      `).join('');
    };

    const loadMaterials = async () => {
      if (!state.levelId) return;

      setLoading(true);
      try {
        const payload = await fetchJson(`/api/guru/levels/${state.levelId}/materials`);
        const allUnits = payload.data.units || [];
        state.units = allUnits.filter((unit) => String(unit.id) === String(state.unitId));
        if (loadingNode) loadingNode.hidden = true;
        if (errorNode) errorNode.hidden = true;
        renderMaterials();
      } catch (error) {
        setError(error.message || 'Gagal memuat materi.');
      }
    };

    const findMaterial = (materialId) => {
      for (const unit of state.units) {
        const material = (unit.materials || []).find((item) => String(item.id) === String(materialId));
        if (material) return { material, unit };
      }
      return { material: null, unit: null };
    };

    const openModal = (modal) => {
      if (modal) modal.hidden = false;
    };

    const closeModal = (modal) => {
      if (modal) modal.hidden = true;
    };

    const openMaterialModal = (material = null) => {
      materialForm?.reset();
      if (materialFormError) materialFormError.hidden = true;
      if (materialIdInput) materialIdInput.value = material?.id || '';
      if (materialModalTitle) materialModalTitle.textContent = material ? 'Edit Isi Unit' : 'Tambah Isi Unit';

      if (material && materialForm) {
        if (materialForm.elements.title) materialForm.elements.title.value = material.title || '';
      }
      openModal(materialModal);
    };

    const openDetailModal = (material, unit) => {
      if (!material) return;
      if (detailTitle) detailTitle.textContent = material.title || 'Materi Pembelajaran';
      if (detailType) detailType.textContent = labelForType(material.type);
      if (detailUnit) detailUnit.textContent = unit?.title || '-';
      if (detailOrder) detailOrder.textContent = String(material.order ?? '-');
      openModal(detailModal);
    };

    const saveMaterial = async () => {
      if (!materialForm) return;

      const formData = new FormData(materialForm);
      const id = formData.get('id');
      const payload = {
        unit_id: state.unitId,
        level_id: Number(state.levelId),
        type: 'lesson',
        title: String(formData.get('title') || ''),
      };

      const submit = root.querySelector('[data-material-submit]');
      if (submit) submit.disabled = true;
      if (materialFormError) materialFormError.hidden = true;

      try {
        await fetchJson(id ? `/api/guru/materials/${id}` : '/api/guru/materials', {
          method: id ? 'PUT' : 'POST',
          body: JSON.stringify(payload),
        });
        closeModal(materialModal);
        await loadMaterials();
      } catch (error) {
        if (materialFormError) {
          materialFormError.textContent = error.message || 'Isi unit gagal disimpan.';
          materialFormError.hidden = false;
        }
      } finally {
        if (submit) submit.disabled = false;
      }
    };

    const deleteMaterial = async (material) => {
      if (!material || !window.confirm(`Hapus isi unit "${material.title}"?`)) return;

      setLoading(true);
      try {
        await fetchJson(`/api/guru/materials/${material.id}`, { method: 'DELETE' });
        await loadMaterials();
      } catch (error) {
        setError(error.message || 'Isi unit gagal dihapus.');
      }
    };

    const moveMaterial = async (draggedId, targetId) => {
      if (draggedId === targetId) return;

      const unit = state.units.find((u) => String(u.id) === String(state.unitId));
      if (!unit) return;

      const items = Array.from(unit.materials || []);
      const draggedIndex = items.findIndex((item) => String(item.id) === String(draggedId));
      const targetIndex = items.findIndex((item) => String(item.id) === String(targetId));

      if (draggedIndex === -1 || targetIndex === -1) return;

      const [removed] = items.splice(draggedIndex, 1);
      items.splice(targetIndex, 0, removed);

      const reordered = items.map((item, idx) => ({
        id: item.id,
        order: idx + 1,
      }));

      unit.materials = items.map((item, idx) => ({ ...item, order: idx + 1 }));
      renderMaterials();

      try {
        await fetchJson('/api/guru/materials/reorder', {
          method: 'PATCH',
          body: JSON.stringify({ materials: reordered }),
        });
      } catch (error) {
        console.error('Material reorder error:', error);
        loadMaterials();
      }
    };

    root.addEventListener('click', (event) => {
      const target = event.target instanceof Element ? event.target : null;
      if (!target) return;

      if (target.closest('[data-close-guru-modal]')) {
        closeModal(target.closest('.guru-modal-overlay'));
      }

      const openButton = target.closest('[data-open-material-modal]');
      if (openButton) {
        openMaterialModal();
      }

      const detailButton = target.closest('[data-material-detail]');
      if (detailButton) {
        const { material, unit } = findMaterial(detailButton.getAttribute('data-material-detail'));
        openDetailModal(material, unit);
      }

      const editButton = target.closest('[data-edit-material]');
      if (editButton) {
        const { material } = findMaterial(editButton.getAttribute('data-edit-material'));
        openMaterialModal(material);
      }

      const deleteButton = target.closest('[data-delete-material]');
      if (deleteButton) {
        const { material } = findMaterial(deleteButton.getAttribute('data-delete-material'));
        deleteMaterial(material);
      }

      if (target.closest('[data-retry-materials]')) {
        loadMaterials();
      }
    });

    materialForm?.addEventListener('submit', (event) => {
      event.preventDefault();
      saveMaterial();
    });

    root.addEventListener('dragstart', (event) => {
      if (!event.target.classList.contains('guru-unit-card__drag') && !event.target.closest('.guru-unit-card__drag')) {
        event.preventDefault();
        return;
      }
      const card = event.target.closest('.guru-unit-card');
      if (card) {
        card.classList.add('is-dragging-unit');
        event.dataTransfer.effectAllowed = 'move';
        event.dataTransfer.setData('text/material-id', card.getAttribute('data-unit-card'));
      }
    });

    root.addEventListener('dragover', (event) => {
      const isMaterial = event.dataTransfer.types.includes('text/material-id');
      if (isMaterial) {
        const card = event.target instanceof Element ? event.target.closest('.guru-unit-card') : null;
        if (!card) return;
        event.preventDefault();
        event.dataTransfer.dropEffect = 'move';
        root.querySelectorAll('.guru-unit-card.is-drag-over-unit').forEach((item) => {
          if (item !== card) item.classList.remove('is-drag-over-unit');
        });
        card.classList.add('is-drag-over-unit');
      }
    });

    root.addEventListener('dragleave', (event) => {
      const card = event.target instanceof Element ? event.target.closest('.guru-unit-card') : null;
      if (card && !card.contains(event.relatedTarget)) {
        card.classList.remove('is-drag-over-unit');
      }
    });

    root.addEventListener('drop', (event) => {
      const isMaterial = event.dataTransfer.types.includes('text/material-id');
      if (isMaterial) {
        const card = event.target instanceof Element ? event.target.closest('.guru-unit-card') : null;
        if (!card) return;
        event.preventDefault();

        const draggedId = event.dataTransfer.getData('text/material-id');
        const targetId = card.getAttribute('data-unit-card');

        moveMaterial(draggedId, targetId);

        root.querySelectorAll('.guru-unit-card.is-drag-over-unit').forEach((item) => {
          item.classList.remove('is-drag-over-unit');
        });
      }
    });

    root.addEventListener('dragend', () => {
      root.querySelectorAll('.guru-unit-card.is-dragging-unit').forEach((item) => {
        item.classList.remove('is-dragging-unit');
      });
      root.querySelectorAll('.guru-unit-card.is-drag-over-unit').forEach((item) => {
        item.classList.remove('is-drag-over-unit');
      });
    });

    root.querySelectorAll('.guru-modal-overlay').forEach((modal) => {
      modal.addEventListener('click', (event) => {
        if (event.target === modal) closeModal(modal);
      });
    });

    loadMaterials();
  });

  document.querySelectorAll('[data-guru-questions]').forEach((root) => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const nodeId = root.getAttribute('data-material-id');
    const state = {
      node: null,
      steps: [],
    };

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
    };

    const setError = (message) => {
      if (loadingNode) loadingNode.hidden = true;
      if (listNode) listNode.hidden = true;
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

    const renderSteps = () => {
      if (titleNode) titleNode.textContent = state.node?.title ? `Langkah Belajar: ${state.node.title}` : 'Langkah Pembelajaran';
      if (subtitleNode) subtitleNode.textContent = 'Kelola urutan dan isi langkah belajar (teks, pengenalan huruf, kuis).';

      if (!listNode) return;
      if (state.steps.length === 0) {
        listNode.hidden = true;
        if (emptyNode) emptyNode.hidden = false;
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
        } else if (step.type === 'quiz') {
          const options = step.quiz_options || [];
          contentHtml = `
            <p><strong>Pertanyaan:</strong> ${escapeHtml(step.quiz_query || '-')}</p>
            <small style="color: #666;">Pilihan: ${options.map((opt, idx) => `[${idx === step.quiz_correct_index ? '<b>' + escapeHtml(opt) + '</b>' : escapeHtml(opt)}]`).join(' / ')}</small>
          `;
        }

        return `
          <article class="guru-question-card" data-question-card="${step.id}" draggable="true">
            <div class="guru-question-card__number" style="cursor: grab;">
              <span class="material-symbols-outlined" style="font-size: 18px; color: #aaa; vertical-align: middle; margin-right: 4px;">drag_indicator</span>
              ${index + 1}
            </div>
            <div class="guru-question-card__body">
              <span class="guru-material-type guru-material-type--${step.type}">${step.type_label}</span>
              <div style="margin-top: 8px;">${contentHtml}</div>
            </div>
            <div class="guru-unit-card__actions">
              <button type="button" class="guru-unit-button" data-edit-question="${step.id}">
                <span class="material-symbols-outlined" aria-hidden="true">edit</span>Edit
              </button>
              <button type="button" class="guru-unit-button guru-unit-button--danger" data-delete-question="${step.id}">
                <span class="material-symbols-outlined" aria-hidden="true">delete</span>Hapus
              </button>
            </div>
          </article>
        `;
      }).join('');

      // Setup drag and drop for steps
      setupDragAndDrop();
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
      if (stepFormError) stepFormError.hidden = true;
      if (stepIdInput) stepIdInput.value = step?.id || '';
      if (stepModalTitle) stepModalTitle.textContent = step ? 'Edit Langkah' : 'Tambah Langkah';

      if (step && stepForm) {
        stepForm.elements.type.value = step.type || 'text';
        stepForm.elements.sort_order.value = step.sort_order ?? '';

        if (step.type === 'text') {
          stepForm.elements.content.value = step.content || '';
        } else if (step.type === 'character') {
          stepForm.elements.character_symbol.value = step.character_symbol || '';
          stepForm.elements.character_pronunciation.value = step.character_pronunciation || '';
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

      // Update locally
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
          card.classList.remove('is-dragging');
        });
      });
    };

    root.addEventListener('click', (event) => {
      const target = event.target instanceof Element ? event.target : null;
      if (!target) return;

      if (target.closest('[data-open-question-modal]')) {
        openStepModal();
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

    root.querySelectorAll('.guru-modal-overlay').forEach((modal) => {
      modal.addEventListener('click', (event) => {
        if (event.target === modal) closeModal(modal);
      });
    });

    loadSteps();
  });

  document.querySelectorAll('[data-lesson]').forEach((lessonRoot) => {
    const questionsNode = lessonRoot.querySelector('[data-lesson-questions]');
    if (!questionsNode) return;

    const questions = JSON.parse(questionsNode.textContent || '[]');
    let currentIndex = 0;
    let selectedOptionId = null;
    let selectedWords = [];
    let status = 'none';
    let score = 0;

    const progressBar = lessonRoot.querySelector('[data-lesson-progress]');
    const footer = lessonRoot.querySelector('[data-lesson-footer]');
    const feedback = lessonRoot.querySelector('[data-lesson-feedback]');
    const action = lessonRoot.querySelector('[data-lesson-action]');
    const screens = lessonRoot.querySelectorAll('[data-lesson-screen]');
    const scoreScreen = lessonRoot.querySelector('[data-lesson-score]');

    const getCurrentQuestion = () => questions[currentIndex];

    const resetQuestionState = () => {
      selectedOptionId = null;
      selectedWords = [];
      status = 'none';
      footer?.classList.remove('is-correct', 'is-wrong');
    };

    const setActionState = () => {
      const question = getCurrentQuestion();
      if (!question || !action) return;

      if (question.type === 'new-letter') {
        action.textContent = 'Lanjut';
        action.disabled = false;
        return;
      }

      if (status === 'none') {
        action.textContent = 'Periksa';
        action.disabled = question.type === 'select-image'
          ? selectedOptionId === null
          : selectedWords.length === 0;
      } else {
        action.textContent = 'Lanjut';
        action.disabled = false;
      }
    };

    const renderFeedback = () => {
      if (!feedback) return;

      if (status === 'none') {
        feedback.innerHTML = '<button type="button" class="lesson-skip" data-lesson-skip>Lewati</button>';
        feedback.querySelector('[data-lesson-skip]')?.addEventListener('click', () => {
          goNext();
        });
        return;
      }

      const question = getCurrentQuestion();
      const isCorrect = status === 'correct';
      feedback.innerHTML = `
        <div class="lesson-feedback-card ${isCorrect ? 'is-correct' : 'is-wrong'}">
          <div class="lesson-feedback-icon">${isCorrect ? '✓' : '×'}</div>
          <div>
            <strong>${isCorrect ? 'Jawaban benar' : 'Jawaban yang benar:'}</strong>
            ${isCorrect ? '' : `<span>${question.answer || ''}</span>`}
          </div>
        </div>
      `;
    };

    const renderChoiceOptions = (question) => {
      const container = lessonRoot.querySelector('[data-choice-options]');
      if (!container) return;

      container.innerHTML = '';
      question.options.forEach((option, index) => {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = `lesson-choice ${selectedOptionId === option.id ? 'is-selected' : ''}`;
        button.disabled = status !== 'none';
        button.innerHTML = `
          <span class="lesson-choice__index">${index + 1}</span>
          <span class="lesson-choice__image" aria-hidden="true">${option.image || ''}</span>
          <span class="lesson-choice__text">
            <strong>${option.text}</strong>
            <span>${option.sub || ''}</span>
          </span>
        `;
        button.addEventListener('click', () => {
          if (status !== 'none') return;
          selectedOptionId = option.id;
          render();
        });
        container.appendChild(button);
      });
    };

    const makeWordButton = (word) => {
      const button = document.createElement('button');
      button.type = 'button';
      button.className = 'lesson-word';
      button.innerHTML = `${word.text}${word.sub ? `<small>${word.sub}</small>` : ''}`;
      button.addEventListener('click', () => {
        if (status !== 'none') return;
        const exists = selectedWords.some((item) => item.id === word.id);
        selectedWords = exists
          ? selectedWords.filter((item) => item.id !== word.id)
          : [...selectedWords, word];
        render();
      });
      return button;
    };

    const renderWordBank = (question) => {
      const answerBank = lessonRoot.querySelector('[data-answer-bank]');
      const wordBank = lessonRoot.querySelector('[data-word-bank]');
      if (!answerBank || !wordBank) return;

      answerBank.innerHTML = '';
      wordBank.innerHTML = '';

      selectedWords.forEach((word) => {
        answerBank.appendChild(makeWordButton(word));
      });

      question.options.forEach((word) => {
        const isSelected = selectedWords.some((item) => item.id === word.id);
        if (isSelected) {
          const placeholder = document.createElement('span');
          placeholder.className = 'lesson-word-placeholder';
          wordBank.appendChild(placeholder);
          return;
        }
        wordBank.appendChild(makeWordButton(word));
      });
    };

    const render = () => {
      const question = getCurrentQuestion();
      if (!question) return;

      const progress = questions.length > 0 ? Math.round((currentIndex / questions.length) * 100) : 0;
      if (progressBar) progressBar.style.width = `${progress}%`;

      screens.forEach((screen) => {
        screen.hidden = screen.getAttribute('data-lesson-screen') !== question.type;
      });
      if (scoreScreen) scoreScreen.hidden = true;

      if (question.type === 'new-letter') {
        const letter = lessonRoot.querySelector('[data-lesson-letter]');
        const reading = lessonRoot.querySelector('[data-lesson-reading]');
        if (letter) letter.textContent = question.letter;
        if (reading) reading.textContent = question.reading;
      }

      if (question.type === 'select-image') {
        const copy = lessonRoot.querySelector('[data-choice-question]');
        if (copy) copy.textContent = question.question;
        renderChoiceOptions(question);
      }

      if (question.type === 'translate-to-ja') {
        const copy = lessonRoot.querySelector('[data-translate-question]');
        const prompt = lessonRoot.querySelector('[data-translate-prompt]');
        if (copy) copy.textContent = question.question;
        if (prompt) prompt.textContent = question.prompt;
        renderWordBank(question);
      }

      footer?.classList.toggle('is-correct', status === 'correct');
      footer?.classList.toggle('is-wrong', status === 'wrong');
      renderFeedback();
      setActionState();
    };

    const checkAnswer = () => {
      const question = getCurrentQuestion();
      let correct = false;

      if (question.type === 'select-image') {
        correct = question.options.some((option) => option.id === selectedOptionId && option.correct);
      }

      if (question.type === 'translate-to-ja') {
        const userIds = selectedWords.map((word) => word.id).join(',');
        const correctIds = (question.correctIds || []).join(',');
        correct = userIds === correctIds;
      }

      status = correct ? 'correct' : 'wrong';
      if (correct) score += 1;
      render();
    };

    const showScore = () => {
      screens.forEach((screen) => {
        screen.hidden = true;
      });
      if (footer) footer.hidden = true;
      if (progressBar) progressBar.style.width = '100%';
      if (scoreScreen) scoreScreen.hidden = false;

      const correct = lessonRoot.querySelector('[data-score-correct]');
      const total = lessonRoot.querySelector('[data-score-total]');
      const accuracy = lessonRoot.querySelector('[data-score-accuracy]');
      if (correct) correct.textContent = String(score);
      if (total) total.textContent = `dari ${questions.length} soal`;
      if (accuracy) accuracy.textContent = `${Math.round((score / questions.length) * 100)}%`;
    };

    function goNext() {
      const question = getCurrentQuestion();
      if (question?.type === 'new-letter') {
        score += 1;
      }

      if (currentIndex < questions.length - 1) {
        currentIndex += 1;
        resetQuestionState();
        render();
        return;
      }

      showScore();
    }

    action?.addEventListener('click', () => {
      const question = getCurrentQuestion();
      if (!question) return;

      if (question.type === 'new-letter' || status !== 'none') {
        goNext();
        return;
      }

      checkAnswer();
    });

    render();
  });
});

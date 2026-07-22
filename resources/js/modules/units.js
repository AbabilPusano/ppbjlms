import { createDragAutoScroller } from './utils.js';

export default function initUnitsModule() {
  document.querySelectorAll('[data-guru-units]').forEach((root) => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const dragAutoScroller = createDragAutoScroller();
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
    const deleteSelectedBtn = root.querySelector('[data-delete-selected]');
    const selectedCountSpan = root.querySelector('[data-selected-count]');
    const selectAllContainer = root.querySelector('[data-select-all-container]');
    const selectAllCheckbox = root.querySelector('#select-all-units');
    const levelSelectInline = root.querySelector('[data-select-level-inline]');

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
      if (selectAllContainer) selectAllContainer.style.display = 'none';
    };

    const setError = (message) => {
      if (loadingNode) loadingNode.hidden = true;
      if (listNode) listNode.hidden = true;
      if (emptyNode) emptyNode.hidden = true;
      if (errorNode) errorNode.hidden = false;
      if (errorMessageNode) errorMessageNode.textContent = message;
      if (selectAllContainer) selectAllContainer.style.display = 'none';
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

      if (levelSelectInline) {
        levelSelectInline.innerHTML = state.levels
          .map((level) => `<option value="${level.id}">${escapeHtml(level.code)} - ${escapeHtml(level.name)}</option>`)
          .join('');
        if (state.activeLevelId) levelSelectInline.value = String(state.activeLevelId);
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

    const updateDeleteSelectedButton = () => {
      if (!listNode) return;
      const checkedBoxes = listNode.querySelectorAll('.unit-checkbox:checked');
      const count = checkedBoxes.length;
      if (deleteSelectedBtn) {
        deleteSelectedBtn.style.display = count > 0 ? 'inline-flex' : 'none';
      }
      if (selectedCountSpan) {
        selectedCountSpan.textContent = count;
      }
      if (selectAllCheckbox) {
        const allBoxes = listNode.querySelectorAll('.unit-checkbox');
        selectAllCheckbox.checked = allBoxes.length > 0 && count === allBoxes.length;
      }
    };

    const renderUnits = () => {
      const materials = state.units.flatMap((unit) => unit.materials || []);
      if (unitCountNode) unitCountNode.textContent = String(state.units.length);
      if (materialCountNode) materialCountNode.textContent = String(materials.length);
      if (quizCountNode) quizCountNode.textContent = String(materials.filter((material) => material.type === 'quiz').length);
      renderLevelControls();
      renderUnitSelect();

      if (selectAllContainer) {
        selectAllContainer.style.display = state.units.length > 0 ? 'flex' : 'none';
      }
      if (selectAllCheckbox) {
        selectAllCheckbox.checked = false;
      }
      updateDeleteSelectedButton();

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
            <div class="guru-unit-card__actions" style="display: flex; flex-direction: column; gap: 8px; justify-content: center; align-items: flex-end;">
              <div style="display: flex; align-items: center; gap: 8px; width: 100%; justify-content: flex-end; margin-bottom: 4px;">
                <input type="checkbox" class="unit-checkbox" value="${unit.id}" style="width: 20px; height: 20px; cursor: pointer; accent-color: var(--accent);">
              </div>
              <a class="guru-unit-button guru-unit-button--primary" href="/guru/units/${unit.id}/materials" style="display: inline-flex; align-items: center; justify-content: center; text-decoration: none; width: 100%;">
                <span class="material-symbols-outlined" aria-hidden="true" style="margin-right: 4px;">account_tree</span>
                KELOLA ISI
              </a>
              <button type="button" class="guru-unit-button" data-edit-unit="${unit.id}" style="width: 100%;">
                <span class="material-symbols-outlined" aria-hidden="true">edit</span>
                EDIT
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
      if (!event.target.classList.contains('guru-unit-card__drag') && !event.target.closest('.guru-unit-card__drag')) {
        if (event.target.classList.contains('guru-material-drag-handle') || event.target.closest('.guru-material-drag-handle')) {
          const row = event.target.closest('.guru-unit-material');
          if (row) {
            row.classList.add('is-dragging-material');
            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData('text/material-id', row.getAttribute('data-material-id'));
            event.dataTransfer.setData('text/parent-unit-id', row.closest('[data-unit-card]').getAttribute('data-unit-card'));
            dragAutoScroller.start(event.clientY);
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
      dragAutoScroller.start(event.clientY);
    });

    root.addEventListener('dragover', (event) => {
      if (dragAutoScroller.isActive()) {
        event.preventDefault();
        event.dataTransfer.dropEffect = 'move';
        dragAutoScroller.update(event.clientY);
      }

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
      const wasReorderDrag = dragAutoScroller.isActive();
      dragAutoScroller.stop();
      if (wasReorderDrag) event.preventDefault();
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
      dragAutoScroller.stop();
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

    levelSelectInline?.addEventListener('change', () => {
      state.activeLevelId = levelSelectInline.value;
      window.localStorage.setItem('guru.activeLevelId', state.activeLevelId);
      loadUnits();
    });

    listNode?.addEventListener('change', (event) => {
      if (event.target instanceof HTMLInputElement && event.target.classList.contains('unit-checkbox')) {
        updateDeleteSelectedButton();
      }
    });

    selectAllCheckbox?.addEventListener('change', () => {
      if (!selectAllCheckbox) return;
      const isChecked = selectAllCheckbox.checked;
      listNode?.querySelectorAll('.unit-checkbox').forEach((box) => {
        box.checked = isChecked;
      });
      updateDeleteSelectedButton();
    });

    deleteSelectedBtn?.addEventListener('click', async () => {
      const checkedBoxes = listNode?.querySelectorAll('.unit-checkbox:checked');
      if (!checkedBoxes) return;
      const ids = Array.from(checkedBoxes).map((box) => box.value);
      if (ids.length === 0 || !window.confirm(`Hapus ${ids.length} unit terpilih secara bersamaan?`)) return;

      setLoading(true);
      try {
        await Promise.all(ids.map(id => fetchJson(`/api/guru/units/${id}`, { method: 'DELETE' })));
        await loadLevels();
      } catch (error) {
        setError(error.message || 'Gagal menghapus beberapa unit.');
      }
    });

    loadLevels();
  });
}

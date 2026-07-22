import { createDragAutoScroller } from './utils.js';

export default function initMaterialsModule() {
  document.querySelectorAll('[data-guru-materials]').forEach((root) => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const dragAutoScroller = createDragAutoScroller();
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
    const importModal = root.querySelector('[data-material-import-modal]');
    const importForm = root.querySelector('[data-material-import-form]');
    const importFormError = root.querySelector('[data-material-import-error]');
    const deleteSelectedBtn = root.querySelector('[data-delete-selected]');
    const selectedCountSpan = root.querySelector('[data-selected-count]');
    const selectAllContainer = root.querySelector('[data-select-all-container]');
    const selectAllCheckbox = root.querySelector('#select-all-materials');

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
      if (emptyNode) emptyNode.hidden = true;
      if (errorNode) errorNode.hidden = false;
      if (errorMessageNode) errorMessageNode.textContent = message;
      if (selectAllContainer) selectAllContainer.style.display = 'none';
    };

    const labelForType = (type) => (type === 'quiz' ? 'Quiz' : 'Materi Pembelajaran');

    const updateDeleteSelectedButton = () => {
      if (!listNode) return;
      const checkedBoxes = listNode.querySelectorAll('.material-checkbox:checked');
      const count = checkedBoxes.length;
      if (deleteSelectedBtn) {
        deleteSelectedBtn.style.display = count > 0 ? 'inline-flex' : 'none';
      }
      if (selectedCountSpan) {
        selectedCountSpan.textContent = count;
      }
      if (selectAllCheckbox) {
        const allBoxes = listNode.querySelectorAll('.material-checkbox');
        selectAllCheckbox.checked = allBoxes.length > 0 && count === allBoxes.length;
      }
    };

    const renderMaterials = () => {
      if (!listNode) return;

      const unit = state.units.find((u) => String(u.id) === String(state.unitId));
      if (!unit) {
        listNode.hidden = true;
        if (emptyNode) emptyNode.hidden = false;
        return;
      }

      const unitMaterials = unit.materials || [];

      if (selectAllContainer) {
        selectAllContainer.style.display = unitMaterials.length > 0 ? 'flex' : 'none';
      }
      if (selectAllCheckbox) {
        selectAllCheckbox.checked = false;
      }
      updateDeleteSelectedButton();

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
          <div class="guru-unit-card__actions" style="display: flex; flex-direction: column; gap: 8px; justify-content: center; align-items: flex-end;">
            <div style="display: flex; align-items: center; gap: 8px; width: 100%; justify-content: flex-end; margin-bottom: 4px;">
              <input type="checkbox" class="material-checkbox" value="${material.id}" style="width: 20px; height: 20px; cursor: pointer; accent-color: var(--accent);">
            </div>
            <a class="guru-unit-button guru-unit-button--preview" href="/guru/materials/${material.id}/preview" target="_blank" rel="noopener" style="display: inline-flex; align-items: center; justify-content: center; text-decoration: none; width: 100%;">
              <span class="material-symbols-outlined" aria-hidden="true">visibility</span>
              PREVIEW
            </a>
            <a class="guru-unit-button guru-unit-button--primary" href="/guru/materi/${material.id}/questions" style="display: inline-flex; align-items: center; justify-content: center; text-decoration: none; width: 100%;">
              <span class="material-symbols-outlined" aria-hidden="true" style="margin-right: 4px;">menu_book</span>
              KELOLA LANGKAH
            </a>
            <button type="button" class="guru-unit-button" data-edit-material="${material.id}" style="width: 100%;">
              <span class="material-symbols-outlined" aria-hidden="true">edit</span>
              EDIT
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

      const openImportBtn = target.closest('[data-open-import-modal]');
      if (openImportBtn) {
        importForm?.reset();
        if (importFormError) importFormError.hidden = true;
        openModal(importModal);
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

    importForm?.addEventListener('submit', async (event) => {
      event.preventDefault();
      if (importFormError) importFormError.hidden = true;
      const submitBtn = importForm.querySelector('[data-material-import-submit]');
      if (submitBtn) submitBtn.disabled = true;

      const formData = new FormData(importForm);
      try {
        const response = await fetch(`/api/guru/units/${state.unitId}/import`, {
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
        await loadMaterials();
      } catch (error) {
        if (importFormError) {
          importFormError.textContent = error.message;
          importFormError.hidden = false;
        }
      } finally {
        if (submitBtn) submitBtn.disabled = false;
      }
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
        dragAutoScroller.start(event.clientY);
      }
    });

    root.addEventListener('dragover', (event) => {
      if (dragAutoScroller.isActive()) {
        event.preventDefault();
        event.dataTransfer.dropEffect = 'move';
        dragAutoScroller.update(event.clientY);
      }

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
      const wasReorderDrag = dragAutoScroller.isActive();
      dragAutoScroller.stop();
      if (wasReorderDrag) event.preventDefault();
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
      dragAutoScroller.stop();
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

    listNode?.addEventListener('change', (event) => {
      if (event.target instanceof HTMLInputElement && event.target.classList.contains('material-checkbox')) {
        updateDeleteSelectedButton();
      }
    });

    selectAllCheckbox?.addEventListener('change', () => {
      if (!selectAllCheckbox) return;
      const isChecked = selectAllCheckbox.checked;
      listNode?.querySelectorAll('.material-checkbox').forEach((box) => {
        box.checked = isChecked;
      });
      updateDeleteSelectedButton();
    });

    deleteSelectedBtn?.addEventListener('click', async () => {
      const checkedBoxes = listNode?.querySelectorAll('.material-checkbox:checked');
      if (!checkedBoxes) return;
      const ids = Array.from(checkedBoxes).map((box) => box.value);
      if (ids.length === 0 || !window.confirm(`Hapus ${ids.length} isi unit terpilih secara bersamaan?`)) return;

      setLoading(true);
      try {
        await Promise.all(ids.map(id => fetchJson(`/api/guru/materials/${id}`, { method: 'DELETE' })));
        await loadMaterials();
      } catch (error) {
        setError(error.message || 'Gagal menghapus beberapa isi unit.');
      }
    });

    loadMaterials();
  });
}

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
});

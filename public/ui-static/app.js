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
        action.textContent = 'Continue';
        action.disabled = false;
        return;
      }

      if (status === 'none') {
        action.textContent = 'Check';
        action.disabled = question.type === 'select-image'
          ? selectedOptionId === null
          : selectedWords.length === 0;
      } else {
        action.textContent = 'Continue';
        action.disabled = false;
      }
    };

    const renderFeedback = () => {
      if (!feedback) return;

      if (status === 'none') {
        feedback.innerHTML = '<button type="button" class="lesson-skip" data-lesson-skip>Skip</button>';
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
            <strong>${isCorrect ? 'Good job!' : 'Correct solution:'}</strong>
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

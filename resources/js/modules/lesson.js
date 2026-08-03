export default function initLessonModule() {
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

      if (question.type === 'new-letter' || question.type === 'new-vocabulary') {
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
          <span class="lesson-choice__text" style="display: flex; align-items: center; justify-content: center; height: 100%; width: 100%;">
            <strong style="font-size: 1.25rem;">${option.text}</strong>
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

      if (question.type === 'new-vocabulary') {
        const kata = lessonRoot.querySelector('[data-vocab-kata]');
        const romaji = lessonRoot.querySelector('[data-vocab-romaji]');
        const arti = lessonRoot.querySelector('[data-vocab-arti]');
        
        if (kata) kata.textContent = question.letter;
        if (romaji) romaji.textContent = question.reading;
        if (arti) arti.textContent = question.meaning;

        const kanji = lessonRoot.querySelector('[data-vocab-kanji]');
        const kanjiRow = lessonRoot.querySelector('[data-vocab-meta-kanji-row]');
        if (kanjiRow) {
          if (question.kanji) {
            kanjiRow.style.display = 'flex';
            if (kanji) kanji.textContent = `${question.kanji} (${question.kanji_meaning || ''})`;
          } else {
            kanjiRow.style.display = 'none';
          }
        }

        const tipe = lessonRoot.querySelector('[data-vocab-tipe]');
        const tipeRow = lessonRoot.querySelector('[data-vocab-meta-tipe-row]');
        if (tipeRow) {
          if (question.tipe_kata) {
            tipeRow.style.display = 'flex';
            if (tipe) tipe.textContent = question.tipe_kata;
          } else {
            tipeRow.style.display = 'none';
          }
        }

        const lvl = lessonRoot.querySelector('[data-vocab-level]');
        const lvlRow = lessonRoot.querySelector('[data-vocab-meta-level-row]');
        if (lvlRow) {
          if (question.level) {
            lvlRow.style.display = 'flex';
            if (lvl) lvl.textContent = question.level;
          } else {
            lvlRow.style.display = 'none';
          }
        }

        const desc = lessonRoot.querySelector('[data-vocab-deskripsi]');
        const descRow = lessonRoot.querySelector('[data-vocab-meta-desc-row]');
        if (descRow) {
          if (question.deskripsi) {
            descRow.style.display = 'flex';
            if (desc) desc.textContent = question.deskripsi;
          } else {
            descRow.style.display = 'none';
          }
        }
      }

      if (question.type === 'select-image') {
        const copy = lessonRoot.querySelector('[data-choice-question]');
        const inst = lessonRoot.querySelector('[data-choice-instruction]');
        if (copy) copy.textContent = question.question;
        if (inst) inst.textContent = question.instruction || 'PILIH JAWABAN YANG BENAR';
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

      const quizQuestions = questions.filter(q => q.type === 'select-image' || q.type === 'translate-to-ja');
      const totalQuizCount = quizQuestions.length;
      
      const correct = lessonRoot.querySelector('[data-score-correct]');
      const total = lessonRoot.querySelector('[data-score-total]');
      const accuracy = lessonRoot.querySelector('[data-score-accuracy]');

      if (totalQuizCount === 0) {
        if (correct) correct.textContent = '0';
        if (total) total.textContent = 'Materi saja';
        if (accuracy) accuracy.textContent = '100%';
      } else {
        if (correct) correct.textContent = String(score);
        if (total) total.textContent = `dari ${totalQuizCount} soal`;
        if (accuracy) accuracy.textContent = `${Math.round((score / totalQuizCount) * 100)}%`;
      }

      const completeUrl = lessonRoot.getAttribute('data-complete-url');
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
      if (completeUrl) {
        fetch(completeUrl, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify({ status: 'completed' })
        })
        .then(response => response.json())
        .then(data => {
          console.log('Progress saved successfully:', data);
        })
        .catch(err => {
          console.error('Error saving progress:', err);
        });
      }
    };

    function goNext() {
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

      if (question.type === 'new-letter' || question.type === 'new-vocabulary' || status !== 'none') {
        goNext();
        return;
      }

      checkAnswer();
    });

    render();
  });
}

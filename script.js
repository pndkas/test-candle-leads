/**
 * script.js — JavaScript validation and UX interactions
 *
 * Handles two main parts:
 * 1. Client-side validation before submit
 * 2. Form submission via AJAX (fetch API) without page reload
 */

document.addEventListener('DOMContentLoaded', () => {
  const form        = document.getElementById('lead-form');
  const submitBtn   = document.getElementById('submit-btn');
  const alertBox    = document.getElementById('alert-box');
  const successView = document.getElementById('success-view');

  // ============================================================
  // Real-time validation — Show errors as soon as user leaves a field
  // ============================================================
  const fields = ['name', 'email', 'scent'];
  fields.forEach(id => {
    const el = document.getElementById(id);
    if (!el) return;

    el.addEventListener('blur', () => validateField(el));
    el.addEventListener('input', () => {
      if (el.classList.contains('is-invalid')) validateField(el);
    });
  });

  /**
   * validateField — Validates a single field
   * Returns true if valid, false if invalid
   */
  function validateField(el) {
    const errorEl = document.getElementById(`${el.id}-error`);
    let message = '';

    if (el.id === 'name') {
      if (!el.value.trim()) {
        message = 'Please enter your name';
      } else if (el.value.trim().length > 100) {
        message = 'Name must not exceed 100 characters';
      }
    }

    if (el.id === 'email') {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!el.value.trim()) {
        message = 'Please enter your email';
      } else if (!emailRegex.test(el.value.trim())) {
        message = 'Invalid email format';
      }
    }

    if (el.id === 'scent') {
      if (!el.value) {
        message = 'Please select your preferred scent';
      }
    }

    if (message) {
      el.classList.add('is-invalid');
      el.classList.remove('is-valid');
      if (errorEl) errorEl.textContent = message;
      return false;
    } else {
      el.classList.remove('is-invalid');
      el.classList.add('is-valid');
      if (errorEl) errorEl.textContent = '';
      return true;
    }
  }

  /**
   * validateAll — Validates all fields before submit
   */
  function validateAll() {
    const results = fields.map(id => {
      const el = document.getElementById(id);
      return el ? validateField(el) : true;
    });
    return results.every(Boolean);
  }

  // ============================================================
  // Form submit — Send data via AJAX
  // ============================================================
  form.addEventListener('submit', async (e) => {
    e.preventDefault(); // Prevent normal browser submit behavior

    hideAlert();

    if (!validateAll()) {
      // Scroll to the first invalid field
      const firstInvalid = form.querySelector('.is-invalid');
      if (firstInvalid) firstInvalid.focus();
      return;
    }

    // Loading state
    setLoading(true);

    try {
      const formData = new FormData(form);

      const response = await fetch('save_lead.php', {
        method: 'POST',
        body: formData,
      });

      const data = await response.json();

      if (data.success) {
        // Hide form, show success view
        form.closest('.form-card').style.display = 'none';
        successView.classList.remove('hidden');
        successView.classList.add('fade-in');
      } else {
        // Show server-side errors
        const errorMessages = data.errors
          ? data.errors.join('<br>')
          : (data.message || 'An error occurred. Please try again.');
        showAlert(errorMessages, 'error');
      }

    } catch (err) {
      // Network error or JSON parse error
      showAlert('Cannot connect. Please check your internet connection.', 'error');
    } finally {
      setLoading(false);
    }
  });

  // ============================================================
  // Helper functions
  // ============================================================
  function setLoading(isLoading) {
    submitBtn.disabled = isLoading;
    submitBtn.querySelector('.btn-text').textContent = isLoading ? 'Sending...' : 'Submit';
    submitBtn.querySelector('.btn-spinner').classList.toggle('hidden', !isLoading);
  }

  function showAlert(html, type) {
    alertBox.innerHTML = html;
    alertBox.className = `alert alert-${type}`;
    alertBox.classList.remove('hidden');
    alertBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }

  function hideAlert() {
    alertBox.classList.add('hidden');
    alertBox.innerHTML = '';
  }

  // ============================================================
  // Scent card selection UX
  // ============================================================
  const scentCards = document.querySelectorAll('.scent-card');
  const scentInput = document.getElementById('scent');

  scentCards.forEach(card => {
    card.addEventListener('click', () => {
      scentCards.forEach(c => c.classList.remove('selected'));
      card.classList.add('selected');
      scentInput.value = card.dataset.value;
      validateField(scentInput);
    });

    // Keyboard accessibility
    card.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        card.click();
      }
    });
  });

  // ============================================================
  // Floating particles animation
  // ============================================================
  createParticles();

  function createParticles() {
    const container = document.querySelector('.particles');
    if (!container) return;

    const emojis = ['🕯️', '✨', '🌸', '🌿', '⭐'];
    const count = 15;

    for (let i = 0; i < count; i++) {
      const particle = document.createElement('span');
      particle.className = 'particle';
      particle.textContent = emojis[Math.floor(Math.random() * emojis.length)];
      particle.style.left = `${Math.random() * 100}%`;
      particle.style.animationDelay = `${Math.random() * 8}s`;
      particle.style.animationDuration = `${6 + Math.random() * 6}s`;
      particle.style.fontSize = `${0.8 + Math.random() * 0.8}rem`;
      container.appendChild(particle);
    }
  }
});

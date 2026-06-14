<?php
/**
 * index.php — Main Landing Page
 *
 * Serves as HTML structure and PHP entry point
 * Form data is sent via JavaScript fetch() to save_lead.php
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- SEO Meta Tags -->
  <title>Lumière Candle Co. — Custom Candles</title>
  <meta name="description" content="Register for early access to premium handmade candles. Specially curated scents just for you.">
  <meta property="og:title" content="Lumière Candle Co.">
  <meta property="og:description" content="Premium handmade candles — custom made with your preferred scent">
  <meta name="theme-color" content="#0f0c0a">

  <link rel="stylesheet" href="style.css">
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🕯️</text></svg>">
</head>
<body>

  <!-- ============================================================
       HERO SECTION
       ============================================================ -->
  <header class="hero" role="banner">
    <!-- Floating particles (Created by JS) -->
    <div class="particles" aria-hidden="true"></div>

    <span class="brand-icon" aria-hidden="true">🕯️</span>
    <p class="brand-tagline">Lumière Candle Co.</p>

    <h1 class="hero-title">
      Candles crafted<br>
      <em>for your moments</em>
    </h1>

    <p class="hero-subtitle">
      Handmade from specially selected natural ingredients, every candle tells a story with a unique scent.
    </p>

    <div class="divider" aria-hidden="true">✦</div>
  </header>

  <!-- ============================================================
       FEATURES STRIP — Product Highlights
       ============================================================ -->
  <section class="features" aria-label="Our Features">
    <div class="feature-item">
      <span aria-hidden="true">🌿</span>
      <span>100% Natural Ingredients</span>
    </div>
    <div class="feature-item">
      <span aria-hidden="true">✋</span>
      <span>Handmade Every Piece</span>
    </div>
    <div class="feature-item">
      <span aria-hidden="true">🎨</span>
      <span>Choose Your Scent</span>
    </div>
    <div class="feature-item">
      <span aria-hidden="true">📦</span>
      <span>Nationwide Delivery</span>
    </div>
  </section>

  <!-- ============================================================
       MAIN CONTENT — Lead Capture Form
       ============================================================ -->
  <main id="main-content">

    <!-- Form Card -->
    <div class="form-card" role="region" aria-label="Early Access Registration">
      <h2 class="form-title">✉️ Early Access Registration</h2>
      <p class="form-subtitle">Fill out the form to get notified when the product is available.</p>

      <!-- Alert Box (Shows error/success from server) -->
      <div id="alert-box" class="hidden" role="alert" aria-live="polite"></div>

      <!-- Lead Form -->
      <form id="lead-form" novalidate aria-label="Registration Form">

        <!-- Name -->
        <div class="form-group">
          <label for="name">
            Full Name <span class="required-star" aria-label="Required">*</span>
          </label>
          <input
            type="text"
            id="name"
            name="name"
            placeholder="e.g. John Doe"
            maxlength="100"
            autocomplete="name"
            required
          >
          <span id="name-error" class="field-error" role="alert"></span>
        </div>

        <!-- Email -->
        <div class="form-group">
          <label for="email">
            Email <span class="required-star" aria-label="Required">*</span>
          </label>
          <input
            type="email"
            id="email"
            name="email"
            placeholder="example@email.com"
            maxlength="254"
            autocomplete="email"
            required
          >
          <span id="email-error" class="field-error" role="alert"></span>
        </div>

        <!-- Scent Selection — Using card UI instead of dropdown for better UX -->
        <div class="form-group">
          <label>
            Preferred Scent <span class="required-star" aria-label="Required">*</span>
          </label>

          <!-- Hidden input to store selected value -->
          <input type="hidden" id="scent" name="scent" required>

          <div class="scent-grid" role="radiogroup" aria-label="Select your preferred scent">
            <div class="scent-card" data-value="Vanilla"    role="radio" aria-checked="false" tabindex="0">
              <span class="scent-emoji" aria-hidden="true">🍦</span>
              <span class="scent-name">Vanilla</span>
            </div>
            <div class="scent-card" data-value="Lavender"   role="radio" aria-checked="false" tabindex="0">
              <span class="scent-emoji" aria-hidden="true">💜</span>
              <span class="scent-name">Lavender</span>
            </div>
            <div class="scent-card" data-value="Sandalwood" role="radio" aria-checked="false" tabindex="0">
              <span class="scent-emoji" aria-hidden="true">🪵</span>
              <span class="scent-name">Sandalwood</span>
            </div>
            <div class="scent-card" data-value="Citrus"     role="radio" aria-checked="false" tabindex="0">
              <span class="scent-emoji" aria-hidden="true">🍋</span>
              <span class="scent-name">Citrus</span>
            </div>
            <div class="scent-card" data-value="Unsure"     role="radio" aria-checked="false" tabindex="0">
              <span class="scent-emoji" aria-hidden="true">🤔</span>
              <span class="scent-name">Unsure</span>
            </div>
          </div>
          <span id="scent-error" class="field-error" role="alert"></span>
        </div>

        <!-- Message (Optional) -->
        <div class="form-group">
          <label for="message">Additional Message (Optional)</label>
          <textarea
            id="message"
            name="message"
            placeholder="Tell us more about what you want, e.g., special occasions, colors, or sizes..."
            maxlength="500"
            rows="4"
          ></textarea>
          <span id="message-error" class="field-error" role="alert"></span>
        </div>

        <!-- Submit -->
        <button type="submit" id="submit-btn" class="btn-submit">
          <div class="btn-spinner hidden" aria-hidden="true"></div>
          <span class="btn-text">Submit</span>
        </button>

      </form>
    </div><!-- /.form-card -->

    <!-- ============================================================
         SUCCESS VIEW — Shown after successful form submission
         ============================================================ -->
    <div id="success-view" class="success-view hidden" role="status" aria-live="polite">
      <span class="success-icon" aria-hidden="true">🕯️</span>
      <h2 class="success-title">Thank you very much!</h2>
      <p class="success-text">
        We have received your information.<br>
        Our team will contact you soon when the product is ready. 🌸
      </p>
    </div>

  </main><!-- /#main-content -->

  <!-- ============================================================
       FOOTER
       ============================================================ -->
  <footer>
    <p>&copy; <?= date('Y') ?> Lumière Candle Co. — Built with ❤️ &amp; 🕯️</p>
  </footer>

  <script src="script.js"></script>
</body>
</html>

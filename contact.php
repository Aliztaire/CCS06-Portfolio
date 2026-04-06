<?php
//  CONTROLLER 
require_once 'config.php';
ensureTable();

// 
$navBrand    = "";      // e.g. "johndoe.dev"
$currentPage = "contact.php";
$navLinks    = navLinks();

// ── Page headings
$eyebrow        = "Contact";
$headline       = "";   // e.g. "Let's Talk."
$headlineItalic = "";   // e.g. optional italic portion
$pageIntro      = "";   // e.g. "Open to collaborations, internships, and interesting projects."

// ── Form labels
$labelName        = "Full Name";
$labelEmail       = "Email Address";
$labelMessage     = "Your Message";
$placeholderName  = "";     // e.g. "Juan dela Cruz"
$placeholderEmail = "";     // e.g. "juan@email.com"
$placeholderMsg   = "";     // e.g. "Tell me about your project..."
$btnSubmit        = "Send Message";

// ── Social links array
$socialLinks = [
    // ["platform" => "GitHub",   "handle" => "@username",  "url" => "https://github.com/username"],
    // ["platform" => "LinkedIn", "handle" => "Full Name",  "url" => "https://linkedin.com/in/..."],
    // ["platform" => "Email",    "handle" => "you@email",  "url" => "mailto:you@email.com"],
];
$socialHeading = "Find Me";

// ── Thank-you footer message
$thankYouFooter = "";   // e.g. "Thank you for writing. I'll reply within 48 hours."

// ── Validation/response messages
$msgEmpty   = "Please fill in all fields.";
$msgEmail   = "Enter a valid email address.";
$msgSuccess = "Message received! I'll get back to you soon.";
$msgDbFail  = "Could not save your message. Please try again.";

// ── Footer
$footerLeft  = "";      // e.g. "© 2026 John Doe"
$footerRight = "";      // e.g. "Always open to a good conversation"
$navDate     = date('F j, Y');

// ═══════════════════════════════════════════════════════
//  FORM PROCESSING (identical logic — do not edit)
// ═══════════════════════════════════════════════════════
$formStatus = "";
$formMessage = "";
$keepName = $keepEmail = $keepMsg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputName  = trim(htmlspecialchars($_POST['name']    ?? '', ENT_QUOTES));
    $inputEmail = trim(htmlspecialchars($_POST['email']   ?? '', ENT_QUOTES));
    $inputMsg   = trim(htmlspecialchars($_POST['message'] ?? '', ENT_QUOTES));

    if ($inputName === '' || $inputEmail === '' || $inputMsg === '') {
        $formStatus = "error"; $formMessage = $msgEmpty;
        $keepName = $inputName; $keepEmail = $inputEmail; $keepMsg = $inputMsg;
    } elseif (!filter_var($inputEmail, FILTER_VALIDATE_EMAIL)) {
        $formStatus = "error"; $formMessage = $msgEmail;
        $keepName = $inputName; $keepEmail = $inputEmail; $keepMsg = $inputMsg;
    } else {
        $db = db(); $ok = false;
        if ($db) {
            $stmt = $db->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("sss", $inputName, $inputEmail, $inputMsg);
                $ok = $stmt->execute();
                $stmt->close();
            }
        }
        $formStatus  = $ok ? "success" : "error";
        $formMessage = $ok ? $msgSuccess : $msgDbFail;
    }
}
// ═══════════════════════════════════════════════════════
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> Contact · Alistaire </title>
  <?php echo getCSS(); ?>
</head>
<body>

<!-- NAV -->
<nav>
  <div class="nav-brand-wrap">
    <a class="nav-brand" href="index.php"><?php echo $navBrand; ?></a>
  </div>
  <div class="nav-links-wrap">
    <ul class="nav-links">
      <?php foreach ($navLinks as $label => $href): ?>
        <li>
          <a href="<?php echo $href; ?>"
             class="<?php echo ($href === $currentPage) ? 'active' : ''; ?>">
            <?php echo $label; ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
    <span class="nav-date"><?php echo $navDate; ?></span>
  </div>
</nav>

<!-- PAGE -->
<div class="page">

  <!-- MASTHEAD -->
  <div class="masthead">
    <div class="kicker"><?php echo $eyebrow; ?></div>
    <h1 class="headline" style="font-size:clamp(2.5rem,6vw,5.5rem);">
      <?php echo $headline; ?>
      <?php if ($headlineItalic): ?>
        <span class="italic"><?php echo $headlineItalic; ?></span>
      <?php endif; ?>
    </h1>
  </div>

  <p style="color:var(--ink-light);font-size:1rem;
            line-height:1.8;margin-top:1.5rem;max-width:560px;">
    <?php echo $pageIntro; ?>
  </p>

  <!-- CONTACT LAYOUT -->
  <div class="contact-layout">

    <!-- FORM COLUMN -->
    <div>
      <div class="section-rule">
        <span class="section-rule-label">Send A Message</span>
      </div>

      <?php if ($formMessage): ?>
        <div class="alert alert-<?php echo $formStatus; ?>">
          <?php echo ($formStatus === 'success') ? '✓' : '!'; ?>
          &nbsp;<?php echo $formMessage; ?>
        </div>
      <?php endif; ?>

      <?php if ($formStatus !== 'success'): ?>
      <form method="POST" action="contact.php">
        <div class="form-group">
          <label for="f-name"><?php echo $labelName; ?></label>
          <input type="text" id="f-name" name="name"
                 placeholder="<?php echo $placeholderName; ?>"
                 value="<?php echo htmlspecialchars($keepName); ?>">
        </div>
        <div class="form-group">
          <label for="f-email"><?php echo $labelEmail; ?></label>
          <input type="email" id="f-email" name="email"
                 placeholder="<?php echo $placeholderEmail; ?>"
                 value="<?php echo htmlspecialchars($keepEmail); ?>">
        </div>
        <div class="form-group">
          <label for="f-msg"><?php echo $labelMessage; ?></label>
          <textarea id="f-msg" name="message"
                    placeholder="<?php echo $placeholderMsg; ?>"><?php echo htmlspecialchars($keepMsg); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">
          <?php echo $btnSubmit; ?> →
        </button>
      </form>
      <?php endif; ?>
    </div>

    <!-- SIDEBAR: Social + Thank You -->
    <div>
      <div class="section-rule">
        <span class="section-rule-label"><?php echo $socialHeading; ?></span>
      </div>

      <?php if (!empty($socialLinks)): ?>
        <div style="border:1.5px solid var(--ink);overflow:hidden;">
          <?php foreach ($socialLinks as $link): ?>
            <a class="social-item" href="<?php echo $link['url']; ?>" target="_blank" rel="noopener">
              <span class="social-platform"><?php echo $link['platform']; ?></span>
              <span class="social-handle"><?php echo $link['handle']; ?></span>
              <span style="margin-left:auto;font-size:.8rem;">↗</span>
            </a>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p style="color:var(--ink-muted);font-size:.82rem;font-style:italic;">
          Social links will appear here.
        </p>
      <?php endif; ?>

      <!-- THANK YOU MESSAGE -->
      <?php if ($thankYouFooter): ?>
        <div class="thankyou-box">
          "<?php echo $thankYouFooter; ?>"
        </div>
      <?php endif; ?>
    </div>

  </div>
</div>

<footer>
  <strong><?php echo $footerLeft; ?></strong>
  <span><?php echo $footerRight; ?></span>
</footer>

</body>
</html>

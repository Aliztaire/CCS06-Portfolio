<?php
//  CONTROLLER 
require_once 'config.php';

$navBrand    = "Alis.dev";      // e.g. "johndoe.dev"
$currentPage = "about.php";
$navLinks    = navLinks();

// ── Identity
$fullName    = "Alistaire Marc Gulliver E. Espinosa";      
$nickname    = "Alis";    
$roleTitle   = "CS Student | Aspiring Data Scientist";      

// ── Biography (long string)
$biography   = "I am a second-year CS student at Angeles University Foundation. I am a passionate student with a 
strong interest in data science and software development. I have well-grounded technical skills in Python, HTML/CSS/JS, and SQL,
 as well as backgrounds in leadership and collaboration.";     

// Skills List
$skills = ["HTML", "CSS", "JavaScript", "Python", "Java","SQL", "Git/GitHub"];

// Education background
$education = [
    [
        "degree" => "BS in Computer Science (2nd-Year)",    
        "school" => "Angeles University Foundation",     
        "loc"    => "Angeles City, Pampanga",     
        "year"   => "2024 – Present · 2nd Year",    
        "note"   => "Relevant coursework: Discrete Structures, Data Structures and Algorithms, Information Management, etc.",    
    ],
];

// Highlights (numbered list)
$highlights = [ 
  ["icon"  => "01", "text" => "Class President, BSCS 2-A 2025-2026"],
  ["icon"  => "02", "text" => "2nd-Year CYO President, CS 2-A 2025-2026"],
  ["icon"  => "03", "text" => "ACM Vice-President, 2025-2026"],
];

// ── Pull quote (one memorable sentence)
$pullQuote = "";        // e.g. "Code is craft. Craft takes patience."

// ── Section labels
$eyebrow           = "About";
$sectionBio        = "Biography";
$sectionSkills     = "Technical Skills";
$sectionEducation  = "Education";
$sectionHighlights = "Notable Highlights";
$ctaLabel          = "Get In Touch →";

// ── Footer
$footerLeft  = "© 2026 - Aliztaire";  
$footerRight = "Pampanga, PH";  
$navDate     = date('F j, Y');

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> About · Alistaire </title>
    <link rel="stylesheet" href="styles.css">
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
    <h1 class="headline" style="font-size:clamp(2.5rem,5vw,5rem);">
      <?php echo $nickname; ?>,&nbsp;
      <span class="italic"><?php echo $fullName; ?></span>
    </h1>
    <p style="font-family:var(--font-mono);font-size:.78rem;
              letter-spacing:.14em;color:var(--accent);margin-top:.8rem;">
      <?php echo $roleTitle; ?>
    </p>
  </div>

  <!-- TWO-COLUMN LAYOUT -->
  <div class="two-col-even">

    <!-- LEFT: Bio + Highlights -->
    <div>
      <div class="section-rule">
        <span class="section-rule-label"><?php echo $sectionBio; ?></span>
      </div>
      <p style="color:var(--ink-light);line-height:1.9;font-size:.95rem;">
        <?php echo $biography; ?>
      </p>

      <?php if ($pullQuote): ?>
        <div class="pull-quote" style="margin-top:2rem;"><?php echo $pullQuote; ?></div>
      <?php endif; ?>

      <?php if (!empty($highlights)): ?>
      <div class="section-rule" style="margin-top:2.5rem;">
        <span class="section-rule-label"><?php echo $sectionHighlights; ?></span>
      </div>
      <?php foreach ($highlights as $h): ?>
        <div class="highlight-row">
          <span class="highlight-num"><?php echo $h['icon']; ?></span>
          <span class="highlight-text"><?php echo $h['text']; ?></span>
        </div>
      <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- RIGHT: Skills + Education + CTA -->
    <div>
      <div class="section-rule">
        <span class="section-rule-label"><?php echo $sectionSkills; ?></span>
      </div>
      <div class="skills-wrap">
        <?php foreach ($skills as $skill): ?>
          <span class="skill-chip"><?php echo $skill; ?></span>
        <?php endforeach; ?>
      </div>

      <div class="section-rule" style="margin-top:2.5rem;">
        <span class="section-rule-label"><?php echo $sectionEducation; ?></span>
      </div>
      <?php foreach ($education as $edu): ?>
        <div class="edu-block">
          <div class="edu-degree"><?php echo $edu['degree']; ?></div>
          <div class="edu-school"><?php echo $edu['school']; ?></div>
          <div class="edu-year"><?php echo $edu['loc']; ?> · <?php echo $edu['year']; ?></div>
          <?php if ($edu['note']): ?>
            <div class="edu-note"><?php echo $edu['note']; ?></div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>

      <div style="margin-top:2.5rem;">
        <a href="contact.php" class="btn btn-accent"><?php echo $ctaLabel; ?></a>
      </div>
    </div>

  </div>
</div>

<footer>
  <strong><?php echo $footerLeft; ?></strong>
  <span><?php echo $footerRight; ?></span>
</footer>

</body>
</html>

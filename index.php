<?php
//  CONTROLLER  (personal details)
require_once 'config.php';

//
$navBrand        = "Alis.dev";        

// ── Masthead / issue line info
$issueLabel      = "Portfolio";
$issueSeason     = "2026 Edition";          
$issueRole       = "CS Student";         
$issueLocation   = "Pampanga, PH";        

// Hero Page
$eyebrow         = "BS Computer Science · AUF";         
$headline        = "Hello, I'm";          
$headlineName    = "Alistaire.";         
$deck            = "I build things that matter.";         

// Pages
$ctaPrimary      = "Read More";
$ctaPrimaryHref  = "about.php";
$ctaSecondary    = "Contact";
$ctaSecondaryHref= "contact.php";

// Stats strip
$stats = [  
    ["Senior High School Valedictorian", "2023-2024"],   
    ["Consistent Honors", "elementary to college"],   
];

// Feature cards 
$cards = [
    ["icon" => "◈", "title" => "Lead", "desc" => "Leading projects and teams to success."],
    ["icon" => "◈", "title" => "Design", "desc" => "Craft innovative and impactful projects."],
    ["icon" => "◈", "title" => "Collaborate", "desc" => "Work with others to bring ideas to life."],
];

//  Pull quote 
$pullQuote = "Building the Future";   

//  Footer
$footerLeft  = "© 2026 - Aliztaire";  
$footerRight = "Pampanga, PH";  

// Navbar and date
$currentPage = "index.php";
$navLinks    = navLinks();
$navDate     = date('F j, Y');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> Home · Alistaire </title>
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
    <div class="issue-line">
      <span><?php echo $issueLabel; ?></span>
      <span><?php echo $issueSeason; ?></span>
      <span><?php echo $issueRole; ?></span>
      <span><?php echo $issueLocation; ?></span>
    </div>

    <div class="kicker"><?php echo $eyebrow; ?></div>

    <h1 class="headline">
      <?php echo $headline; ?><br>
      <span class="italic"><?php echo $headlineName; ?></span>
    </h1>
  </div>

  <!-- MAIN NEWSPAPER GRID -->
  <div class="news-grid">

    <!-- LEFT: Deck + CTAs + Pull Quote -->
    <div>
      <p class="deck" style="margin-bottom:2rem;"><?php echo $deck; ?></p>

      <div style="display:flex;gap:.8rem;flex-wrap:wrap;margin-bottom:2rem;">
        <a href="<?php echo $ctaPrimaryHref; ?>" class="btn btn-primary">
          <?php echo $ctaPrimary; ?> →
        </a>
        <a href="<?php echo $ctaSecondaryHref; ?>" class="btn btn-outline">
          <?php echo $ctaSecondary; ?>
        </a>
      </div>

      <?php if ($pullQuote): ?>
        <div class="pull-quote"><?php echo $pullQuote; ?></div>
      <?php endif; ?>
    </div>

    <!-- COLUMN DIVIDER -->
    <div class="news-col-divider"></div>

    <!-- RIGHT: Stats -->
    <div>
      <div class="section-rule">
        <span class="section-rule-label">Stat Highlights</span>
      </div>
      <?php foreach ($stats as [$val, $lbl]): ?>
        <div style="display:flex;align-items:baseline;gap:.75rem;
                    padding:.75rem 0;border-bottom:1px dashed #c8bfaa;">
          <span style="font-family:var(--font-head);font-size:2rem;
                       font-weight:900;color:var(--accent);line-height:1;">
            <?php echo $val; ?>
          </span>
          <span style="font-family:var(--font-mono);font-size:.7rem;
                       letter-spacing:.12em;text-transform:uppercase;
                       color:var(--ink-muted);">
            <?php echo $lbl; ?>
          </span>
        </div>
      <?php endforeach; ?>
    </div>

  </div>

  <!-- FEATURE CARDS -->
  <div class="section-rule">
    <span class="section-rule-label">What I Do</span>
  </div>
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:1.2rem;">
    <?php foreach ($cards as $card): ?>
      <div class="card-box">
        <div class="card-icon"><?php echo $card['icon']; ?></div>
        <div style="font-family:var(--font-head);font-size:1.05rem;font-weight:700;margin-bottom:.5rem;">
          <?php echo $card['title']; ?>
        </div>
        <p style="font-size:.88rem;color:var(--ink-light);line-height:1.7;">
          <?php echo $card['desc']; ?>
        </p>
      </div>
    <?php endforeach; ?>
  </div>

</div>

<footer>
  <strong><?php echo $footerLeft; ?></strong>
  <span><?php echo $footerRight; ?></span>
</footer>

</body>
</html>

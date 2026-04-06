<?php
//  CONFIG — update before deploying
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'portfolio_db');

define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'admin123');   

//  DB CONNECTION (singleton)
function db(): ?mysqli {
    static $conn = null;
    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            error_log('DB connect failed: ' . $conn->connect_error);
            return null;
        }
    }
    return $conn;
}

//  AUTO-CREATE TABLE 
function ensureTable(): void {
    $db = db();
    if (!$db) return;
    $db->query("CREATE TABLE IF NOT EXISTS contacts (
        id         INT AUTO_INCREMENT PRIMARY KEY,
        name       VARCHAR(120) NOT NULL,
        email      VARCHAR(200) NOT NULL,
        message    TEXT         NOT NULL,
        reply      TEXT         DEFAULT NULL,
        created_at DATETIME     DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

//  SHARED NAV LINKS
function navLinks(): array {
    return [
        'Home'    => 'index.php',
        'About'   => 'about.php',
        'Contact' => 'contact.php',
    ];
}


function getCSS(): string { return '
<style>
@import url("https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400;1,700&family=Barlow:ital,wght@0,300;0,400;0,500;0,600;1,300&family=Courier+Prime:wght@400;700&display=swap");

/* ── RESET ───────────────────────────────────── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }

:root {
  --paper:     #f5f0e8;
  --paper-dark:#ede7d9;
  --ink:       #1a1208;
  --ink-light: #5c4f3a;
  --ink-muted: #9c8f7a;
  --rule:      #1a1208;
  --accent:    #c0431a;   /* terracotta / rust */
  --accent2:   #2a6b4a;  /* forest green */
  --accent3:   #8b5e1a;  /* warm amber */
  --white:     #ffffff;
  --radius:    3px;
  --font-head: "Playfair Display", Georgia, serif;
  --font-body: "Barlow", sans-serif;
  --font-mono: "Courier Prime", "Courier New", monospace;
}

body {
  background: var(--paper);
  color: var(--ink);
  font-family: var(--font-body);
  font-size: 15px;
  line-height: 1.65;
  min-height: 100vh;
}


/* ── NAV ─────────────────────────────────────── */
nav {
  position: sticky; top: 0; z-index: 100;
  background: var(--ink);
  display: flex; align-items: stretch;
  height: 56px;
}

.nav-brand-wrap {
  background: var(--accent);
  display: flex; align-items: center;
  padding: 0 2rem;
  flex-shrink: 0;
}
.nav-brand {
  font-family: var(--font-mono);
  font-weight: 700;
  font-size: .88rem;
  color: var(--white);
  text-decoration: none;
  letter-spacing: .08em;
  text-transform: uppercase;
}

.nav-links-wrap {
  display: flex; align-items: center;
  padding: 0 2rem; gap: 0;
  flex: 1;
}
.nav-links { display: flex; gap: 0; list-style: none; height: 100%; }
.nav-links li { height: 100%; display: flex; }
.nav-links a {
  display: flex; align-items: center;
  padding: 0 1.5rem;
  color: rgba(255,255,255,.55);
  text-decoration: none;
  font-family: var(--font-mono);
  font-size: .75rem;
  letter-spacing: .14em;
  text-transform: uppercase;
  border-right: 1px solid rgba(255,255,255,.08);
  transition: all .15s;
}
.nav-links a:hover { color: var(--white); background: rgba(255,255,255,.06); }
.nav-links a.active { color: var(--accent); }

.nav-date {
  margin-left: auto;
  font-family: var(--font-mono);
  font-size: .7rem;
  color: rgba(255,255,255,.3);
  display: flex; align-items: center;
  padding-right: 2rem;
  letter-spacing: .06em;
}

/* ── PAGE WRAPPER ────────────────────────────── */
.page {
  max-width: 1100px;
  margin: 0 auto;
  padding: 0 2rem 6rem;
  animation: fadeIn .4s ease both;
}
@keyframes fadeIn {
  from { opacity:0; transform:translateY(10px); }
  to   { opacity:1; transform:none; }
}

/* ── MASTHEAD (top ruled area) ───────────────── */
.masthead {
  border-top: 6px solid var(--ink);
  border-bottom: 2px solid var(--ink);
  padding: 2.5rem 0 2rem;
  margin-bottom: 0;
  position: relative;
}
.masthead::after {
  content: "";
  display: block;
  height: 1px;
  background: var(--ink);
  margin-top: .5rem;
}
.issue-line {
  font-family: var(--font-mono);
  font-size: .68rem;
  letter-spacing: .18em;
  text-transform: uppercase;
  color: var(--ink-muted);
  margin-bottom: 1rem;
  display: flex; gap: 2rem; align-items: center;
}
.issue-line span { display: flex; align-items: center; gap: .5rem; }
.issue-line span::before { content:"·"; color: var(--accent); }

/* ── TYPOGRAPHY ──────────────────────────────── */
.headline {
  font-family: var(--font-head);
  font-size: clamp(3rem, 7vw, 7rem);
  font-weight: 900;
  line-height: .95;
  letter-spacing: -.02em;
  color: var(--ink);
}
.headline .italic {
  font-style: italic;
  font-weight: 400;
  color: var(--accent);
}
.deck {
  font-family: var(--font-body);
  font-size: 1.05rem;
  font-weight: 300;
  color: var(--ink-light);
  line-height: 1.75;
  max-width: 540px;
}
.kicker {
  font-family: var(--font-mono);
  font-size: .7rem;
  letter-spacing: .2em;
  text-transform: uppercase;
  color: var(--accent);
  margin-bottom: .8rem;
  display: flex; align-items: center; gap: .6rem;
}
.kicker::after { content:""; flex:1; height:1px; background:var(--accent); max-width:40px; }

.section-rule {
  display: flex; align-items: center; gap: 1rem;
  margin: 2.5rem 0 1.5rem;
}
.section-rule-label {
  font-family: var(--font-mono);
  font-size: .68rem;
  letter-spacing: .2em;
  text-transform: uppercase;
  color: var(--ink);
  white-space: nowrap;
  background: var(--ink);
  color: var(--paper);
  padding: .2rem .7rem;
}
.section-rule::after { content:""; flex:1; height:1px; background:var(--ink); }

.pull-quote {
  font-family: var(--font-head);
  font-size: 1.5rem;
  font-style: italic;
  font-weight: 400;
  color: var(--accent);
  border-left: 4px solid var(--accent);
  padding: .8rem 1.5rem;
  margin: 2rem 0;
  line-height: 1.4;
}

/* ── BUTTONS ─────────────────────────────────── */
.btn {
  display: inline-flex; align-items: center; gap: .5rem;
  font-family: var(--font-mono);
  font-size: .75rem;
  font-weight: 700;
  letter-spacing: .14em;
  text-transform: uppercase;
  text-decoration: none;
  cursor: pointer;
  transition: all .15s;
  border: none;
  border-radius: 0;
  padding: .75rem 1.8rem;
}
.btn-primary {
  background: var(--ink);
  color: var(--paper);
  border: 2px solid var(--ink);
}
.btn-primary:hover {
  background: var(--accent);
  border-color: var(--accent);
}
.btn-outline {
  background: transparent;
  color: var(--ink);
  border: 2px solid var(--ink);
}
.btn-outline:hover {
  background: var(--ink);
  color: var(--paper);
}
.btn-accent {
  background: var(--accent);
  color: var(--white);
  border: 2px solid var(--accent);
}
.btn-accent:hover { background: #a83914; border-color: #a8391a; }
.btn-sm { padding: .45rem 1.1rem; font-size: .68rem; }
.btn-danger {
  background: transparent;
  color: var(--accent);
  border: 1px solid var(--accent);
  padding: .4rem 1rem;
  font-size: .68rem;
}
.btn-danger:hover { background: var(--accent); color: var(--white); }
.btn-reply {
  background: transparent;
  color: var(--accent2);
  border: 1px solid var(--accent2);
  padding: .4rem 1rem;
  font-size: .68rem;
}
.btn-reply:hover { background: var(--accent2); color: var(--white); }
.btn-view {
  background: transparent;
  color: var(--ink);
  border: 1px solid var(--ink);
  padding: .4rem 1rem;
  font-size: .68rem;
}
.btn-view:hover { background: var(--ink); color: var(--paper); }

/* ── NEWSPAPER COLUMNS ───────────────────────── */
.news-grid {
  display: grid;
  grid-template-columns: 2fr 1px 1fr;
  gap: 2.5rem;
  border-top: 2px solid var(--ink);
  padding-top: 2.5rem;
  margin-top: 2.5rem;
}
.news-col-divider { background: var(--ink); }

.two-col-even {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 3rem;
  border-top: 2px solid var(--ink);
  padding-top: 2.5rem;
  margin-top: 2.5rem;
}
.contact-layout {
  display: grid;
  grid-template-columns: 1.5fr 1fr;
  gap: 3rem;
  border-top: 2px solid var(--ink);
  padding-top: 2.5rem;
  margin-top: 2rem;
}

@media (max-width: 700px) {
  .news-grid    { grid-template-columns: 1fr; }
  .news-col-divider { display: none; }
  .two-col-even { grid-template-columns: 1fr; }
  .contact-layout { grid-template-columns: 1fr; }
}

/* ── SKILL CHIPS ─────────────────────────────── */
.skills-wrap { display: flex; flex-wrap: wrap; gap: .5rem; margin-top: .8rem; }
.skill-chip {
  font-family: var(--font-mono);
  font-size: .72rem;
  letter-spacing: .08em;
  border: 1px solid var(--ink);
  padding: .3rem .85rem;
  color: var(--ink);
  background: transparent;
  transition: all .15s;
}
.skill-chip:hover { background: var(--ink); color: var(--paper); }

/* ── STAT BOX ────────────────────────────────── */
.stat-strip {
  display: flex; gap: 0;
  border: 2px solid var(--ink);
  margin: 2rem 0;
}
.stat-box {
  flex: 1; text-align: center;
  padding: 1.2rem .5rem;
  border-right: 1px solid var(--ink);
}
.stat-box:last-child { border-right: none; }
.stat-num {
  font-family: var(--font-head);
  font-size: 2.2rem;
  font-weight: 900;
  color: var(--accent);
  line-height: 1;
}
.stat-lbl {
  font-family: var(--font-mono);
  font-size: .62rem;
  letter-spacing: .14em;
  text-transform: uppercase;
  color: var(--ink-muted);
  margin-top: .25rem;
}

/* ── FORM ────────────────────────────────────── */
.form-group { margin-bottom: 1.4rem; }
label {
  display: block;
  font-family: var(--font-mono);
  font-size: .7rem;
  letter-spacing: .16em;
  text-transform: uppercase;
  color: var(--ink-light);
  margin-bottom: .45rem;
}
input[type=text], input[type=email],
input[type=password], textarea {
  width: 100%;
  background: var(--white);
  border: 1.5px solid var(--ink);
  border-radius: 0;
  color: var(--ink);
  font-family: var(--font-body);
  font-size: .95rem;
  padding: .8rem 1rem;
  outline: none;
  transition: border-color .15s, box-shadow .15s;
}
input:focus, textarea:focus {
  border-color: var(--accent);
  box-shadow: 3px 3px 0 var(--accent);
}
textarea { resize: vertical; min-height: 140px; }

/* ── SOCIAL ──────────────────────────────────── */
.social-item {
  display: flex; align-items: center; gap: 1rem;
  padding: .85rem 1rem;
  border-bottom: 1px solid var(--paper-dark);
  text-decoration: none;
  color: var(--ink);
  transition: all .15s;
}
.social-item:hover { background: var(--ink); color: var(--paper); }
.social-item:hover .social-platform { color: var(--accent); }
.social-item:hover .social-handle { color: rgba(245,240,232,.7); }
.social-platform {
  font-family: var(--font-mono); font-size: .68rem;
  letter-spacing: .14em; text-transform: uppercase;
  color: var(--accent); min-width: 72px; font-weight: 700;
}
.social-handle { font-size: .88rem; color: var(--ink-light); }

/* ── ALERTS ──────────────────────────────────── */
.alert {
  padding: .9rem 1.2rem;
  font-family: var(--font-mono);
  font-size: .8rem;
  margin-bottom: 1.5rem;
  border-left: 4px solid;
  display: flex; gap: .7rem;
}
.alert-success { border-color: var(--accent2); background: rgba(42,107,74,.08); color: var(--accent2); }
.alert-error   { border-color: var(--accent);  background: rgba(192,67,26,.08); color: var(--accent); }

/* ── EDU BLOCK ───────────────────────────────── */
.edu-block {
  border: 1.5px solid var(--ink);
  padding: 1.2rem 1.4rem;
  margin-bottom: 1rem;
  position: relative;
}
.edu-block::before {
  content: "";
  position: absolute; top: 6px; left: 6px;
  width: 100%; height: 100%;
  border: 1.5px solid var(--ink);
  z-index: -1;
  opacity: .2;
}
.edu-degree { font-family: var(--font-head); font-size: 1rem; font-weight: 700; }
.edu-school { color: var(--accent); font-family: var(--font-mono); font-size: .8rem; letter-spacing: .06em; margin: .25rem 0; }
.edu-year   { color: var(--ink-muted); font-size: .78rem; font-family: var(--font-mono); }
.edu-note   { color: var(--ink-light); font-size: .8rem; margin-top: .6rem; border-top: 1px dashed var(--ink-muted); padding-top: .5rem; }

/* ── HIGHLIGHTS ──────────────────────────────── */
.highlight-row {
  display: flex; gap: .8rem; align-items: flex-start;
  padding: .75rem 0;
  border-bottom: 1px dashed var(--paper-dark);
}
.highlight-num {
  font-family: var(--font-head); font-size: 1.1rem;
  font-style: italic; color: var(--accent); min-width: 28px;
}
.highlight-text { font-size: .88rem; color: var(--ink-light); line-height: 1.5; }

/* ── ADMIN TABLE ─────────────────────────────── */
.table-outer {
  border: 2px solid var(--ink);
  overflow: hidden; overflow-x: auto;
}
.records-table { width: 100%; border-collapse: collapse; font-size: .85rem; }
.records-table th {
  font-family: var(--font-mono);
  font-size: .65rem;
  letter-spacing: .18em;
  text-transform: uppercase;
  background: var(--ink);
  color: var(--paper);
  padding: .75rem 1rem;
  text-align: left;
}
.records-table td {
  padding: .9rem 1rem;
  border-bottom: 1px solid var(--paper-dark);
  vertical-align: middle;
}
.records-table tr:hover td { background: rgba(192,67,26,.04); }
.td-name  { font-weight: 600; color: var(--ink); font-family: var(--font-head); }
.td-email { color: var(--accent); font-family: var(--font-mono); font-size: .8rem; }
.preview-text {
  max-width: 200px; overflow: hidden;
  text-overflow: ellipsis; white-space: nowrap;
  display: block; color: var(--ink-muted); font-size: .82rem;
}
.actions-cell { display: flex; gap: .4rem; flex-wrap: wrap; }

.badge {
  font-family: var(--font-mono); font-size: .62rem;
  letter-spacing: .1em; text-transform: uppercase;
  padding: .2rem .65rem;
  border-radius: 0;
}
.badge-new     { background: rgba(192,67,26,.12); color: var(--accent); border: 1px solid var(--accent); }
.badge-replied { background: rgba(42,107,74,.12); color: var(--accent2); border: 1px solid var(--accent2); }

/* ── MODAL ───────────────────────────────────── */
.modal-backdrop {
  display: none; position: fixed; inset: 0; z-index: 500;
  background: rgba(26,18,8,.7);
  align-items: center; justify-content: center;
}
.modal-backdrop.open { display: flex; }
.modal {
  background: var(--paper);
  border: 2px solid var(--ink);
  box-shadow: 8px 8px 0 var(--ink);
  padding: 2.5rem;
  max-width: 560px; width: 90%;
  max-height: 85vh; overflow-y: auto;
  animation: modalIn .2s ease;
}
@keyframes modalIn {
  from { opacity:0; transform:translate(-8px,-8px); }
  to   { opacity:1; transform:none; }
}
.modal-header {
  display: flex; justify-content: space-between; align-items: flex-start;
  border-bottom: 2px solid var(--ink);
  padding-bottom: 1rem; margin-bottom: 1.5rem;
}
.modal-title { font-family: var(--font-head); font-size: 1.3rem; font-weight: 700; }
.modal-close {
  background: var(--ink); border: none; color: var(--paper);
  font-size: 1.1rem; cursor: pointer;
  width: 30px; height: 30px; display: flex;
  align-items: center; justify-content: center;
  transition: background .15s;
}
.modal-close:hover { background: var(--accent); }
.modal-meta {
  font-family: var(--font-mono); font-size: .72rem;
  color: var(--ink-muted); margin-bottom: 1.2rem;
  letter-spacing: .06em;
}
.modal-msg {
  background: var(--white);
  border: 1.5px solid var(--ink);
  padding: 1rem 1.2rem;
  font-size: .9rem; line-height: 1.75;
  white-space: pre-wrap; margin-bottom: 1.4rem;
  color: var(--ink);
}
.existing-reply {
  border: 1.5px dashed var(--accent2);
  padding: 1rem 1.2rem; margin-bottom: 1.2rem;
  background: rgba(42,107,74,.04);
}
.existing-reply-label {
  font-family: var(--font-mono); font-size: .65rem;
  letter-spacing: .16em; text-transform: uppercase;
  color: var(--accent2); margin-bottom: .5rem;
}

/* ── STATS ADMIN ─────────────────────────────── */
.admin-stats-row { display: flex; gap: 0; border: 2px solid var(--ink); margin-bottom: 2rem; }
.admin-stat { flex:1; padding:1.4rem; border-right:1px solid var(--ink); text-align:center; }
.admin-stat:last-child { border-right:none; }
.admin-stat-num { font-family:var(--font-head); font-size:2.5rem; font-weight:900; line-height:1; }
.admin-stat-lbl { font-family:var(--font-mono); font-size:.62rem; letter-spacing:.16em; text-transform:uppercase; color:var(--ink-muted); margin-top:.3rem; }

/* ── LOGIN ───────────────────────────────────── */
.login-page { display:flex; align-items:center; justify-content:center; min-height:calc(100vh - 56px); padding:2rem; }
.login-box {
  background: var(--white);
  border: 2px solid var(--ink);
  box-shadow: 8px 8px 0 var(--ink);
  padding: 3rem; max-width: 420px; width: 100%;
}

/* ── FOOTER ──────────────────────────────────── */
footer {
  border-top: 4px solid var(--ink);
  padding: 1.5rem 2rem;
  display: flex; align-items: center; justify-content: space-between;
  max-width: 1100px; margin: 0 auto;
  font-family: var(--font-mono); font-size: .7rem;
  color: var(--ink-muted); letter-spacing: .08em;
}
footer strong { color: var(--ink); }

/* ── CARD ────────────────────────────────────── */
.card-box {
  border: 1.5px solid var(--ink);
  padding: 1.5rem;
  background: var(--white);
  position: relative;
  transition: transform .15s, box-shadow .15s;
}
.card-box:hover { transform: translate(-3px,-3px); box-shadow: 6px 6px 0 var(--ink); }
.card-icon { font-size: 1.6rem; margin-bottom: .7rem; }

.thankyou-box {
  background: var(--ink);
  color: var(--paper);
  padding: 1.5rem;
  margin-top: 1.5rem;
  font-family: var(--font-body);
  font-size: .88rem;
  font-style: italic;
  line-height: 1.75;
  border-left: 4px solid var(--accent);
}
</style>
'; }

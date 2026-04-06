<?php
// ═══════════════════════════════════════════════════════
//  CONTROLLER  —  Admin: Contact Record Management
//  All UI text in variables. Logic identical to v1.
// ═══════════════════════════════════════════════════════
require_once 'config.php';
session_start();
ensureTable();

// ── All UI text as variables ───────────────────────────
$pageTitle        = "Admin · Inbox";
$navBrand         = "";             // same as your other pages

$loginEyebrow     = "Restricted";
$loginHeadline    = "Admin";
$loginItalic      = "Access.";
$loginSub         = "Enter credentials to manage contact submissions.";
$labelUser        = "Username";
$labelPass        = "Password";
$btnLogin         = "Sign In";
$errLoginMsg      = "Incorrect username or password.";

$adminEyebrow     = "Inbox";
$adminHeadline    = "Contact";
$adminItalic      = "Records.";
$adminSub         = "All messages submitted through the contact form.";
$btnLogout        = "Log Out";

$statLblTotal     = "Total";
$statLblNew       = "Unread";
$statLblReplied   = "Replied";

$colName          = "Name";
$colEmail         = "Email";
$colPreview       = "Preview";
$colDate          = "Date";
$colStatus        = "Status";
$colActions       = "Actions";

$badgeNew         = "New";
$badgeReplied     = "Replied";
$btnView          = "View";
$btnReply         = "Reply";
$btnDelete        = "Delete";
$emptyMsg         = "No messages yet. Share your contact page to start receiving submissions.";

$modalViewTitle   = "Message from";
$modalViewMeta1   = "From:";
$modalViewMeta2   = "Received:";
$modalMsgLabel    = "Message";
$modalReplyLabel  = "Your Reply";
$btnCloseModal    = "×";

$modalReplyTitle  = "Reply to";
$labelReplyText   = "Write Your Reply";
$replyPlaceholder = "Type your reply here...";
$btnSendReply     = "Send Reply";
$btnCancelReply   = "Cancel";

$confirmDelete    = "Permanently delete this message? This cannot be undone.";

$msgDeleteOk  = "Message deleted.";
$msgReplyOk   = "Reply saved.";
$msgReplyEmpty= "Reply cannot be empty.";
$msgDbError   = "Database error. Please try again.";

$footerLeft  = "Admin Panel";
$footerRight = "Secure Area";
$navDate     = date('F j, Y');

// ═══════════════════════════════════════════════════════
//  AUTH
// ═══════════════════════════════════════════════════════
$loginError = "";

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}
if (isset($_POST['do_login'])) {
    $u = trim($_POST['username'] ?? '');
    $p = trim($_POST['password'] ?? '');
    if ($u === ADMIN_USER && $p === ADMIN_PASS) {
        $_SESSION['admin_auth'] = true;
        header('Location: admin.php');
        exit;
    }
    $loginError = $errLoginMsg;
}

$isAuth = !empty($_SESSION['admin_auth']);

// ═══════════════════════════════════════════════════════
//  ACTIONS
// ═══════════════════════════════════════════════════════
$flashMsg = ""; $flashType = "";

if ($isAuth) {

    // DELETE
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
        $id   = (int)$_POST['delete_id'];
        $stmt = db()->prepare("DELETE FROM contacts WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $flashMsg  = $msgDeleteOk;
        $flashType = "success";
        $stmt->close();
    }

    // REPLY
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_id'])) {
        $id    = (int)$_POST['reply_id'];
        $reply = trim(htmlspecialchars($_POST['reply_text'] ?? '', ENT_QUOTES));
        if ($reply === '') {
            $flashMsg = $msgReplyEmpty; $flashType = "error";
        } else {
            $stmt = db()->prepare("UPDATE contacts SET reply = ? WHERE id = ?");
            $stmt->bind_param("si", $reply, $id);
            $stmt->execute();
            $flashMsg  = $msgReplyOk;
            $flashType = "success";
            $stmt->close();
        }
    }

    // FETCH
    $records = [];
    $result  = db()->query("SELECT * FROM contacts ORDER BY created_at DESC");
    if ($result) while ($row = $result->fetch_assoc()) $records[] = $row;

    $totalCount   = count($records);
    $repliedCount = count(array_filter($records, fn($r) => $r['reply'] !== null));
    $newCount     = $totalCount - $repliedCount;
}
// ═══════════════════════════════════════════════════════
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $pageTitle; ?></title>
  <?php echo getCSS(); ?>
</head>
<body>

<!-- NAV -->
<nav>
  <div class="nav-brand-wrap">
    <a class="nav-brand" href="index.php"><?php echo $navBrand ?: 'Portfolio'; ?></a>
  </div>
  <div class="nav-links-wrap">
    <ul class="nav-links">
      <?php foreach (navLinks() as $label => $href): ?>
        <li><a href="<?php echo $href; ?>"><?php echo $label; ?></a></li>
      <?php endforeach; ?>
      <?php if ($isAuth): ?>
        <li>
          <form method="POST" style="margin:0;height:100%;display:flex;align-items:center;">
            <button name="logout" style="background:none;border:none;
              color:rgba(255,255,255,.55);font-family:var(--font-mono);
              font-size:.75rem;letter-spacing:.14em;text-transform:uppercase;
              padding:0 1.5rem;cursor:pointer;height:100%;
              border-right:1px solid rgba(255,255,255,.08);
              transition:color .15s;"
              onmouseover="this.style.color='#fff'"
              onmouseout="this.style.color='rgba(255,255,255,.55)'">
              <?php echo $btnLogout; ?>
            </button>
          </form>
        </li>
      <?php endif; ?>
    </ul>
    <span class="nav-date"><?php echo $navDate; ?></span>
  </div>
</nav>

<!-- ══════════════ LOGIN ══════════════ -->
<?php if (!$isAuth): ?>
<div class="login-page">
  <div class="login-box">
    <div class="kicker"><?php echo $loginEyebrow; ?></div>
    <h1 class="headline" style="font-size:2.8rem;margin-bottom:.5rem;">
      <?php echo $loginHeadline; ?>
      <span class="italic"><?php echo $loginItalic; ?></span>
    </h1>
    <p style="color:var(--ink-light);font-size:.88rem;margin-bottom:2rem;">
      <?php echo $loginSub; ?>
    </p>

    <?php if ($loginError): ?>
      <div class="alert alert-error">! &nbsp;<?php echo $loginError; ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label for="a-user"><?php echo $labelUser; ?></label>
        <input type="text" id="a-user" name="username" autocomplete="username">
      </div>
      <div class="form-group">
        <label for="a-pass"><?php echo $labelPass; ?></label>
        <input type="password" id="a-pass" name="password" autocomplete="current-password">
      </div>
      <button type="submit" name="do_login" class="btn btn-primary" style="width:100%;">
        <?php echo $btnLogin; ?> →
      </button>
    </form>
  </div>
</div>

<!-- ══════════════ ADMIN PANEL ══════════════ -->
<?php else: ?>
<div class="page" style="max-width:1200px;">

  <!-- Header -->
  <div class="masthead">
    <div class="kicker"><?php echo $adminEyebrow; ?></div>
    <h1 class="headline" style="font-size:clamp(2rem,4vw,4rem);">
      <?php echo $adminHeadline; ?>
      <span class="italic"><?php echo $adminItalic; ?></span>
    </h1>
    <p style="font-family:var(--font-mono);font-size:.75rem;
              color:var(--ink-muted);letter-spacing:.1em;margin-top:.5rem;">
      <?php echo $adminSub; ?>
    </p>
  </div>

  <!-- Flash -->
  <?php if ($flashMsg): ?>
    <div class="alert alert-<?php echo $flashType; ?>" style="margin-top:1.5rem;">
      <?php echo ($flashType === 'success') ? '✓' : '!'; ?>
      &nbsp;<?php echo $flashMsg; ?>
    </div>
  <?php endif; ?>

  <!-- Stats -->
  <div class="admin-stats-row" style="margin-top:2rem;">
    <div class="admin-stat">
      <div class="admin-stat-num"><?php echo $totalCount; ?></div>
      <div class="admin-stat-lbl"><?php echo $statLblTotal; ?></div>
    </div>
    <div class="admin-stat">
      <div class="admin-stat-num" style="color:var(--accent);"><?php echo $newCount; ?></div>
      <div class="admin-stat-lbl"><?php echo $statLblNew; ?></div>
    </div>
    <div class="admin-stat">
      <div class="admin-stat-num" style="color:var(--accent2);"><?php echo $repliedCount; ?></div>
      <div class="admin-stat-lbl"><?php echo $statLblReplied; ?></div>
    </div>
  </div>

  <!-- Table -->
  <?php if (empty($records)): ?>
    <div style="text-align:center;padding:5rem 2rem;color:var(--ink-muted);">
      <div style="font-size:2.5rem;margin-bottom:1rem;">✉</div>
      <p style="font-family:var(--font-mono);font-size:.82rem;letter-spacing:.08em;">
        <?php echo $emptyMsg; ?>
      </p>
    </div>
  <?php else: ?>
    <div class="table-outer" style="margin-top:1rem;">
      <table class="records-table">
        <thead>
          <tr>
            <th><?php echo $colName; ?></th>
            <th><?php echo $colEmail; ?></th>
            <th><?php echo $colPreview; ?></th>
            <th><?php echo $colDate; ?></th>
            <th><?php echo $colStatus; ?></th>
            <th><?php echo $colActions; ?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($records as $row):
            $isReplied = ($row['reply'] !== null);
            $preview   = substr($row['message'], 0, 75) . (strlen($row['message']) > 75 ? '…' : '');
            $dateStr   = date('M j, Y · g:i A', strtotime($row['created_at']));
          ?>
          <tr>
            <td class="td-name"><?php echo htmlspecialchars($row['name']); ?></td>
            <td class="td-email"><?php echo htmlspecialchars($row['email']); ?></td>
            <td><span class="preview-text"><?php echo htmlspecialchars($preview); ?></span></td>
            <td style="font-family:var(--font-mono);font-size:.75rem;white-space:nowrap;">
              <?php echo $dateStr; ?>
            </td>
            <td>
              <span class="badge <?php echo $isReplied ? 'badge-replied' : 'badge-new'; ?>">
                <?php echo $isReplied ? $badgeReplied : $badgeNew; ?>
              </span>
            </td>
            <td>
              <div class="actions-cell">
                <button class="btn btn-view btn-sm"
                        onclick='openView(<?php echo json_encode([
                          "name"    => $row['name'],
                          "email"   => $row['email'],
                          "date"    => $dateStr,
                          "message" => $row['message'],
                          "reply"   => $row['reply'],
                        ]); ?>)'>
                  <?php echo $btnView; ?>
                </button>
                <button class="btn btn-reply btn-sm"
                        onclick='openReply(<?php echo json_encode([
                          "id"    => $row['id'],
                          "name"  => $row['name'],
                          "reply" => $row['reply'],
                        ]); ?>)'>
                  <?php echo $btnReply; ?>
                </button>
                <form method="POST" style="margin:0;"
                      onsubmit="return confirm('<?php echo addslashes($confirmDelete); ?>')">
                  <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                  <button type="submit" class="btn btn-danger btn-sm"><?php echo $btnDelete; ?></button>
                </form>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>

</div>

<!-- VIEW MODAL -->
<div class="modal-backdrop" id="modal-view">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title" id="mv-title"></div>
      <button class="modal-close" onclick="closeModal('modal-view')"><?php echo $btnCloseModal; ?></button>
    </div>
    <div class="modal-meta">
      <?php echo $modalViewMeta1; ?> <strong id="mv-email"></strong>
      &nbsp;·&nbsp;
      <?php echo $modalViewMeta2; ?> <span id="mv-date"></span>
    </div>
    <div style="font-family:var(--font-mono);font-size:.68rem;letter-spacing:.16em;
                text-transform:uppercase;color:var(--ink-muted);margin-bottom:.5rem;">
      <?php echo $modalMsgLabel; ?>
    </div>
    <div class="modal-msg" id="mv-msg"></div>
    <div id="mv-reply-wrap" style="display:none;">
      <div class="existing-reply">
        <div class="existing-reply-label"><?php echo $modalReplyLabel; ?></div>
        <p id="mv-reply" style="font-size:.88rem;color:var(--ink);white-space:pre-wrap;"></p>
      </div>
    </div>
  </div>
</div>

<!-- REPLY MODAL -->
<div class="modal-backdrop" id="modal-reply">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title" id="mr-title"></div>
      <button class="modal-close" onclick="closeModal('modal-reply')"><?php echo $btnCloseModal; ?></button>
    </div>
    <form method="POST">
      <input type="hidden" name="reply_id" id="mr-id">
      <div class="form-group">
        <label><?php echo $labelReplyText; ?></label>
        <textarea name="reply_text" id="mr-text"
                  placeholder="<?php echo $replyPlaceholder; ?>"></textarea>
      </div>
      <div style="display:flex;gap:.8rem;">
        <button type="submit" class="btn btn-primary"><?php echo $btnSendReply; ?> →</button>
        <button type="button" class="btn btn-outline"
                onclick="closeModal('modal-reply')"><?php echo $btnCancelReply; ?></button>
      </div>
    </form>
  </div>
</div>

<script>
const VIEW_TITLE  = <?php echo json_encode($modalViewTitle); ?>;
const REPLY_TITLE = <?php echo json_encode($modalReplyTitle); ?>;

function openView(data) {
  document.getElementById('mv-title').textContent = VIEW_TITLE + ' ' + data.name;
  document.getElementById('mv-email').textContent = data.email;
  document.getElementById('mv-date').textContent  = data.date;
  document.getElementById('mv-msg').textContent   = data.message;
  const rw = document.getElementById('mv-reply-wrap');
  if (data.reply) {
    document.getElementById('mv-reply').textContent = data.reply;
    rw.style.display = 'block';
  } else {
    rw.style.display = 'none';
  }
  document.getElementById('modal-view').classList.add('open');
}

function openReply(data) {
  document.getElementById('mr-title').textContent = REPLY_TITLE + ' ' + data.name;
  document.getElementById('mr-id').value          = data.id;
  document.getElementById('mr-text').value        = data.reply || '';
  document.getElementById('modal-reply').classList.add('open');
}

function closeModal(id) {
  document.getElementById(id).classList.remove('open');
}

document.querySelectorAll('.modal-backdrop').forEach(el => {
  el.addEventListener('click', e => { if (e.target === el) el.classList.remove('open'); });
});
document.addEventListener('keydown', e => {
  if (e.key === 'Escape')
    document.querySelectorAll('.modal-backdrop.open').forEach(el => el.classList.remove('open'));
});
</script>

<?php endif; ?>

<footer>
  <strong><?php echo $footerLeft; ?></strong>
  <span><?php echo $footerRight; ?></span>
</footer>

</body>
</html>

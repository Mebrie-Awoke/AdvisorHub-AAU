<?php
require_once __DIR__ . '/../../controllers/AdvisorController.php';
$ctrl = new AdvisorController();
$data = $ctrl->dashboard();
include __DIR__ . '/../layouts/header.php';
?>

<div class="page-header">
    <h1>Advisor Dashboard</h1>
    <p>Welcome back, <strong><?php echo htmlspecialchars($data['advisor']['full_name'] ?? $_SESSION['user_email']); ?></strong>! Manage your students here.</p>
</div>

<div class="content-grid">

    <div class="card">
        <div class="card-header">
            <h3>My Students</h3>
        </div>
        <div class="card-body">
            <?php if (empty($data['students'])): ?>
                <p>No students assigned yet.</p>
            <?php else: ?>
                <ul class="list-simple">
                <?php foreach($data['students'] as $s): ?>
                    <li><?php echo htmlspecialchars($s['full_name']); ?> — <?php echo htmlspecialchars($s['student_id']); ?></li>
                <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>Send Notification</h3></div>
        <div class="card-body">
            <form action="index.php?action=send_notification" method="POST">
                <div class="form-group"><label>Subject</label><input name="subject" required></div>
                <div class="form-group"><label>Message</label><textarea name="message" rows="4" required></textarea></div>
                <div class="form-group"><label><input type="checkbox" name="sent_to_all" value="1"> Send to all assigned students</label></div>
                <div class="form-group"><label><input type="checkbox" name="is_urgent" value="1"> Mark as urgent (sends email)</label></div>
                <button class="btn btn-primary">Send</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>Incoming Questions</h3></div>
        <div class="card-body">
            <?php if (empty($data['questions'])): ?>
                <p>No questions yet.</p>
            <?php else: ?>
                <?php foreach($data['questions'] as $q): ?>
                    <div class="question">
                        <strong><?php echo htmlspecialchars($q['subject'] ?? 'Question'); ?></strong>
                        <div class="meta">From: <?php echo htmlspecialchars($q['student_name']); ?> — <?php echo htmlspecialchars($q['created_at']); ?></div>
                        <p><?php echo nl2br(htmlspecialchars($q['question_text'])); ?></p>
                        <?php if($q['status'] != 'answered'): ?>
                        <form action="index.php?action=answer_question" method="POST">
                            <input type="hidden" name="question_id" value="<?php echo $q['id']; ?>">
                            <textarea name="answer" required placeholder="Write your answer..."></textarea>
                            <button class="btn">Answer</button>
                        </form>
                        <?php else: ?>
                            <div class="answer"><strong>Answer:</strong> <?php echo nl2br(htmlspecialchars($q['answer_text'])); ?></div>
                            <form action="index.php?action=resolve_question" method="POST" style="margin-top:8px;">
                                <input type="hidden" name="question_id" value="<?php echo $q['id']; ?>">
                                <button class="btn btn-primary">Mark Resolved</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

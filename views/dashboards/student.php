<?php
require_once __DIR__ . '/../../controllers/StudentController.php';
$ctrl = new StudentController();
$data = $ctrl->dashboard();
include __DIR__ . '/../layouts/header.php';
?>

<div class="page-header">
    <h1>Student Dashboard</h1>
    <p>Welcome back, <strong><?php echo htmlspecialchars($data['student']['full_name'] ?? $_SESSION['user_email']); ?></strong>! Here's your overview.</p>
</div>

<div class="content-grid">

    <div class="card">
        <div class="card-header"><h3>My Advisor</h3></div>
        <div class="card-body">
            <?php if (empty($data['advisor'])): ?>
                <p>No advisor assigned yet.</p>
            <?php else: ?>
                <strong><?php echo htmlspecialchars($data['advisor']['full_name']); ?></strong><br>
                Department: <?php echo htmlspecialchars($data['advisor']['department']); ?><br>
                Office: <?php echo htmlspecialchars($data['advisor']['office_location']); ?><br>
                Phone: <?php echo htmlspecialchars($data['advisor']['phone']); ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>Ask a Question</h3></div>
        <div class="card-body">
            <form action="index.php?action=ask_question" method="POST">
                <div class="form-group"><input name="subject" placeholder="Subject" required></div>
                <div class="form-group"><textarea name="message" rows="4" placeholder="Write your question..." required></textarea></div>
                <button class="btn btn-primary">Send to Advisor</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>Notifications</h3></div>
        <div class="card-body">
            <?php if (empty($data['notifications'])): ?>
                <p>No notifications.</p>
            <?php else: ?>
                <?php foreach($data['notifications'] as $n): ?>
                    <div class="notification <?php echo $n['is_read'] ? 'read' : 'unread'; ?>">
                        <strong><?php echo htmlspecialchars($n['subject']); ?></strong>
                        <p><?php echo nl2br(htmlspecialchars($n['message'])); ?></p>
                        <?php if(!$n['is_read']): ?>
                        <form action="index.php?action=mark_notification_read" method="POST">
                            <input type="hidden" name="notification_id" value="<?php echo $n['id']; ?>">
                            <button class="btn">Mark read</button>
                        </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>My Questions</h3></div>
        <div class="card-body">
            <?php if (empty($data['questions'])): ?>
                <p>No questions yet.</p>
            <?php else: ?>
                <?php foreach($data['questions'] as $q): ?>
                    <div class="question">
                        <strong><?php echo htmlspecialchars($q['subject'] ?? 'Question'); ?></strong>
                        <div class="meta">Status: <?php echo htmlspecialchars($q['status']); ?> — <?php echo htmlspecialchars($q['created_at']); ?></div>
                        <p><?php echo nl2br(htmlspecialchars($q['question_text'])); ?></p>
                        <?php if(!empty($q['answer_text'])): ?>
                            <div class="answer"><strong>Answer:</strong> <?php echo nl2br(htmlspecialchars($q['answer_text'])); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

<?php 
require_once __DIR__ . '/../../controllers/AdvisorController.php';
$controller = new AdvisorController();
$data = $controller->getDashboardData();

include __DIR__ . '/../layouts/header.php'; 
?>

<div class="page-header">
    <h1>Advisor Dashboard</h1>
    <p>Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>.</p>
</div>

<div class="content-grid">
    <div class="card">
        <div class="card-header">
            <h3>My Assigned Students</h3>
            <span class="card-icon">🎓</span>
        </div>
        <div class="card-body">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Student ID</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data['students'])): ?>
                            <tr><td colspan="3" style="text-align:center; padding:24px; color:var(--text-secondary);">No students assigned to you yet.</td></tr>
                        <?php else: ?>
                            <?php foreach($data['students'] as $student): ?>
                            <tr>
                                <td class="td-name"><?php echo htmlspecialchars($student['student_name']); ?></td>
                                <td><?php echo htmlspecialchars($student['student_number']); ?></td>
                                <td><?php echo htmlspecialchars($student['student_email']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Inbox -->
    <div class="card">
        <div class="card-header">
            <h3>Inbox</h3>
            <span class="card-icon">✉️</span>
        </div>
        <div class="card-body">
            <div class="message-list">
                <?php if(empty($data['messages'])): ?>
                    <p style="text-align:center; color:var(--text-secondary); padding:24px;">No messages in your inbox.</p>
                <?php else: ?>
                    <?php foreach($data['messages'] as $msg): ?>
                        <div class="message-item" style="border-bottom: 1px solid #eee; padding: 12px 0;">
                            <div style="font-weight: 600; margin-bottom: 4px;">
                                <?php echo htmlspecialchars($msg['title']); ?>
                                <?php if($msg['message_type'] === 'broadcast'): ?>
                                    <span class="topbar-badge" style="background-color: #ffe0b2; color: #e65100;">Broadcast</span>
                                <?php endif; ?>
                            </div>
                            <div style="font-size: 0.9rem; color: #555; margin-bottom: 8px;">
                                <?php echo nl2br(htmlspecialchars($msg['message'])); ?>
                            </div>
                            <div style="font-size: 0.8rem; color: #999;">
                                From: <?php echo htmlspecialchars($msg['sender_name']); ?> | <?php echo date('M d, Y h:i A', strtotime($msg['sent_at'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

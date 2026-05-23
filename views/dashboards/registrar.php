<?php 
require_once __DIR__ . '/../../controllers/RegistrarController.php';
$registrarController = new RegistrarController();
$data = $registrarController->getDashboardData();

include __DIR__ . '/../layouts/header.php'; 
?>

<div class="page-header">
    <h1>Registrar Dashboard</h1>
    <p>Manage students, advisors, and assignments across the university.</p>
</div>

<!-- Metrics -->
<div class="metrics-grid">
    <div class="metric-card">
        <div class="metric-icon blue">🎓</div>
        <div>
            <div class="metric-value"><?php echo $data['metrics']['total_students']; ?></div>
            <div class="metric-label">Total Students</div>
        </div>
    </div>
    <div class="metric-card">
        <div class="metric-icon green">📋</div>
        <div>
            <div class="metric-value"><?php echo $data['metrics']['total_advisors']; ?></div>
            <div class="metric-label">Total Advisors</div>
        </div>
    </div>
    <div class="metric-card">
        <div class="metric-icon cyan">🔗</div>
        <div>
            <div class="metric-value"><?php echo count($data['assignments']); ?></div>
            <div class="metric-label">Active Assignments</div>
        </div>
    </div>
</div>

<!-- Pending Approvals -->
<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <h3>Pending Student Approvals</h3>
        <span class="card-icon">⏳</span>
    </div>
    <div class="card-body">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Name / ID</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($data['pending_students'])): ?>
                        <tr><td colspan="3" style="text-align:center; color:var(--text-secondary); padding:24px;">No pending approvals.</td></tr>
                    <?php else: ?>
                        <?php foreach($data['pending_students'] as $student): ?>
                        <tr>
                            <td>
                                <div class="td-name"><?php echo htmlspecialchars($student['name']); ?></div>
                                <div class="td-email"><?php echo htmlspecialchars($student['student_number']); ?></div>
                            </td>
                            <td><?php echo htmlspecialchars($student['email']); ?></td>
                            <td>
                                <form action="index.php?action=approve_student" method="POST" style="display:inline;">
                                    <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                                    <button type="submit" class="btn btn-primary" style="padding: 6px 12px; font-size: 0.85rem;">Approve</button>
                                </form>
                                <form action="index.php?action=reject_student" method="POST" style="display:inline;" onsubmit="return confirm('Reject this student?');">
                                    <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                                    <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 0.85rem;">Reject</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Content Grid -->
<div class="content-grid">

    <!-- Advisor Creation -->
    <div class="card">
        <div class="card-header">
            <h3>Create Advisor Account</h3>
            <span class="card-icon">📋</span>
        </div>
        <div class="card-body">
            <form action="index.php?action=create_advisor" method="POST">
                <div class="form-group">
                    <label for="adv_name">Name</label>
                    <input type="text" name="name" id="adv_name" required>
                </div>
                <div class="form-group">
                    <label for="adv_email">University Email</label>
                    <input type="email" name="email" id="adv_email" required>
                </div>
                <div class="form-group">
                    <label for="adv_pass">Temporary Password</label>
                    <input type="password" name="password" id="adv_pass" required>
                </div>
                <button type="submit" class="btn btn-primary">Create Advisor →</button>
            </form>
        </div>
    </div>

    <!-- Send Messages -->
    <div class="card">
        <div class="card-header">
            <h3>Send Message to Advisors</h3>
            <span class="card-icon">✉️</span>
        </div>
        <div class="card-body">
            <form action="index.php?action=send_message" method="POST">
                <div class="form-group">
                    <label for="recipient_mode">Recipient</label>
                    <select name="recipient_mode" id="recipient_mode" required onchange="toggleAdvisorSelect()">
                        <option value="all">Mass Broadcast to All Advisors</option>
                        <option value="specific">Individual Message</option>
                    </select>
                </div>
                <div class="form-group" id="specific_advisor_div" style="display:none;">
                    <label for="msg_advisor_id">Select Advisor</label>
                    <select name="advisor_id" id="msg_advisor_id">
                        <option value="">— Choose Advisor —</option>
                        <?php foreach($data['advisors'] as $advisor): ?>
                            <option value="<?php echo $advisor['id']; ?>">
                                <?php echo htmlspecialchars($advisor['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="msg_title">Title</label>
                    <input type="text" name="title" id="msg_title" required>
                </div>
                <div class="form-group">
                    <label for="msg_body">Message</label>
                    <textarea name="message" id="msg_body" rows="4" style="width:100%; border:1px solid #ddd; border-radius:8px; padding:12px; font-family:inherit; resize:vertical;" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send Message →</button>
            </form>
            <script>
                function toggleAdvisorSelect() {
                    var mode = document.getElementById('recipient_mode').value;
                    var div = document.getElementById('specific_advisor_div');
                    var select = document.getElementById('msg_advisor_id');
                    if (mode === 'specific') {
                        div.style.display = 'block';
                        select.required = true;
                    } else {
                        div.style.display = 'none';
                        select.required = false;
                        select.value = "";
                    }
                }
            </script>
        </div>
    </div>

    <!-- Assignment Form -->
    <div class="card">
        <div class="card-header">
            <h3>Assign Student to Advisor</h3>
            <span class="card-icon">🔗</span>
        </div>
        <div class="card-body">
            <form action="index.php?action=assign_student" method="POST">
                <div class="form-group">
                    <label for="student_id">Select Student</label>
                    <select name="student_id" id="student_id" required>
                        <option value="">— Choose Student (Approved) —</option>
                        <?php foreach($data['students'] as $student): ?>
                            <option value="<?php echo $student['id']; ?>">
                                <?php echo htmlspecialchars($student['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="advisor_id">Select Advisor</label>
                    <select name="advisor_id" id="advisor_id" required>
                        <option value="">— Choose Advisor —</option>
                        <?php foreach($data['advisors'] as $advisor): ?>
                            <option value="<?php echo $advisor['id']; ?>">
                                <?php echo htmlspecialchars($advisor['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Assign Student →</button>
            </form>

            <?php if (!empty($data['assignments'])): ?>
            <div style="margin-top: 28px;">
                <h4 style="font-size:.85rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:var(--text-secondary); margin-bottom:12px;">Recent Assignments</h4>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Advisor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach(array_slice($data['assignments'], 0, 5) as $a): ?>
                            <tr>
                                <td class="td-name"><?php echo htmlspecialchars($a['student_name']); ?></td>
                                <td><?php echo htmlspecialchars($a['advisor_name']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- User Management -->
    <div class="card">
        <div class="card-header">
            <h3>Manage Users</h3>
            <span class="card-icon">👥</span>
        </div>
        <div class="card-body">
            <form action="index.php" method="GET" class="filter-bar">
                <input type="hidden" name="action" value="registrar_dashboard">
                <input type="text" name="search" placeholder="🔍  Search name or email…" value="<?php echo htmlspecialchars($data['search']); ?>">
                <select name="role">
                    <option value="">All Roles</option>
                    <option value="student"   <?php if($data['role_filter']=='student')   echo 'selected'; ?>>Students</option>
                    <option value="advisor"   <?php if($data['role_filter']=='advisor')   echo 'selected'; ?>>Advisors</option>
                    <option value="registrar" <?php if($data['role_filter']=='registrar') echo 'selected'; ?>>Registrars</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Name / Email</th>
                            <th>Role / Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($data['users'])): ?>
                            <tr><td colspan="3" style="text-align:center; color:var(--text-secondary); padding:24px;">No users found.</td></tr>
                        <?php else: ?>
                            <?php foreach($data['users'] as $user): ?>
                            <tr>
                                <td>
                                    <div class="td-name"><?php echo htmlspecialchars($user['name']); ?></div>
                                    <div class="td-email"><?php echo htmlspecialchars($user['email']); ?></div>
                                </td>
                                <td>
                                    <span class="role-badge <?php echo $user['role']; ?>">
                                        <?php echo htmlspecialchars($user['role']); ?>
                                    </span>
                                    <span style="font-size: 0.8rem; color: #666; margin-left: 5px;">(<?php echo htmlspecialchars($user['status']); ?>)</span>
                                </td>
                                <td>
                                    <form action="index.php?action=delete_user" method="POST" style="display:inline;" onsubmit="return confirm('Delete this user permanently?');">
                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div><!-- /.content-grid -->

<?php include __DIR__ . '/../layouts/footer.php'; ?>

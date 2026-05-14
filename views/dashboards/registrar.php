<?php 
require_once __DIR__ . '/../../controllers/RegistrarController.php';
$registrarController = new RegistrarController();
$data = $registrarController->getDashboardData();

include __DIR__ . '/../layouts/header.php'; 
?>

<div class="dashboard-container">
    <h2>Registrar Admin Dashboard</h2>
    <div class="dashboard-content">
        <p>Welcome back, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>!</p>
        
        <!-- Metrics Panel -->
        <div class="card-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 20px; margin-bottom: 20px;">
            <div class="card" style="background: #3498db; color: #fff; padding: 20px; border-radius: 8px; text-align: center;">
                <h3>Total Students</h3>
                <h1 style="font-size: 3rem; margin: 10px 0;"><?php echo $data['metrics']['total_students']; ?></h1>
            </div>
            <div class="card" style="background: #2ecc71; color: #fff; padding: 20px; border-radius: 8px; text-align: center;">
                <h3>Total Advisors</h3>
                <h1 style="font-size: 3rem; margin: 10px 0;"><?php echo $data['metrics']['total_advisors']; ?></h1>
            </div>
        </div>

        <div class="card-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px;">
            <!-- Assignment Form -->
            <div class="card" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                <h3>Assign Student to Advisor</h3>
                <form action="index.php?action=assign_student" method="POST" style="margin-top: 15px;">
                    <div class="form-group">
                        <label for="student_id">Select Student</label>
                        <select name="student_id" id="student_id" required>
                            <option value="">-- Choose Student --</option>
                            <?php foreach($data['students'] as $student): ?>
                                <option value="<?php echo $student['id']; ?>"><?php echo htmlspecialchars($student['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="advisor_id">Select Advisor</label>
                        <select name="advisor_id" id="advisor_id" required>
                            <option value="">-- Choose Advisor --</option>
                            <?php foreach($data['advisors'] as $advisor): ?>
                                <option value="<?php echo $advisor['id']; ?>"><?php echo htmlspecialchars($advisor['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Assign Student</button>
                </form>
            </div>
            
            <!-- User Management & Filter -->
            <div class="card" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                <h3>Manage Users</h3>
                
                <form action="index.php" method="GET" style="display: flex; gap: 10px; margin-top: 15px; margin-bottom: 15px;">
                    <input type="hidden" name="action" value="registrar_dashboard">
                    <input type="text" name="search" placeholder="Search name or email..." value="<?php echo htmlspecialchars($data['search']); ?>" style="flex: 1; padding: 8px;">
                    <select name="role" style="padding: 8px;">
                        <option value="">All Roles</option>
                        <option value="student" <?php if($data['role_filter'] == 'student') echo 'selected'; ?>>Students</option>
                        <option value="advisor" <?php if($data['role_filter'] == 'advisor') echo 'selected'; ?>>Advisors</option>
                    </select>
                    <button type="submit" class="btn btn-primary" style="width: auto;">Filter</button>
                </form>

                <table style="width: 100%; text-align: left; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid #ccc;">
                            <th style="padding: 8px;">Name</th>
                            <th style="padding: 8px;">Role</th>
                            <th style="padding: 8px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($data['users'])): ?>
                            <tr><td colspan="3" style="padding: 8px;">No users found.</td></tr>
                        <?php else: ?>
                            <?php foreach($data['users'] as $user): ?>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding: 8px;">
                                        <?php echo htmlspecialchars($user['name']); ?><br>
                                        <small style="color: #666;"><?php echo htmlspecialchars($user['email']); ?></small>
                                    </td>
                                    <td style="padding: 8px;">
                                        <span style="background: #eee; padding: 3px 8px; border-radius: 12px; font-size: 12px; text-transform: capitalize;">
                                            <?php echo htmlspecialchars($user['role']); ?>
                                        </span>
                                    </td>
                                    <td style="padding: 8px;">
                                        <form action="index.php?action=delete_user" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <button type="submit" style="background: #e74c3c; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">Delete</button>
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
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

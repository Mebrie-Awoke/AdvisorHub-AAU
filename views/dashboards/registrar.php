<?php 
require_once __DIR__ . '/../../controllers/RegistrarController.php';
$registrarController = new RegistrarController();
$data = $registrarController->getDashboardData();

include __DIR__ . '/../layouts/header.php'; 
?>

<div class="dashboard-container">
    <h2>Registrar Dashboard</h2>
    <div class="dashboard-content">
        <p>Welcome back, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>!</p>
        
        <div class="card-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px; margin-top: 20px;">
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
            
            <div class="card" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                <h3>Current Assignments</h3>
                <table style="width: 100%; text-align: left; margin-top: 15px; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid #ccc;">
                            <th style="padding: 10px;">Student</th>
                            <th style="padding: 10px;">Advisor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($data['assignments'])): ?>
                            <tr><td colspan="2" style="padding: 10px;">No assignments found.</td></tr>
                        <?php else: ?>
                            <?php foreach($data['assignments'] as $assignment): ?>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding: 10px;"><?php echo htmlspecialchars($assignment['student_name']); ?></td>
                                    <td style="padding: 10px;"><?php echo htmlspecialchars($assignment['advisor_name']); ?></td>
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

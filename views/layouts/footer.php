<?php
$isAuthPage = in_array($currentAction ?? '', ['login', 'register']) || !isset($_SESSION['user_id']);
?>

<?php if ($isAuthPage): ?>
</div><!-- /.auth-page -->
<?php else: ?>
        </div><!-- /.page-content -->
    </div><!-- /.main-content -->
</div><!-- /.layout -->
<?php endif; ?>

<div class="toast-container" aria-live="polite" aria-atomic="true"></div>
<script src="public/js/app.js"></script>
</body>
</html>

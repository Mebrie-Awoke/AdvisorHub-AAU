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

</body>
</html>

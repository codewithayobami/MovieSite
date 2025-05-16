
<?php 


// Define the security code
$securityCode = "HeyWhyWiz";

// Check if the user has already verified the security code this session
if (!isset($_SESSION['is_verified']) || !$_SESSION['is_verified']) {
    // If not verified, show the modal
    $showModal = true;
} else {
    $showModal = false;
}

// Handle the security code verification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['security_code'])) {
    if ($_POST['security_code'] === $securityCode) {
        $_SESSION['is_verified'] = true; // Mark as verified
        $showModal = false;
    } else {
        header("Location: login.php");
        exit();
    }
}


?>
<!-- Security Code Modal -->
<?php if ($showModal): ?>
        <div class="modal fade show" id="securityModal" tabindex="-1" aria-labelledby="securityModalLabel" style="display: block; background: rgba(0,0,0,0.8);" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-lighr" id="securityModalLabel">One Time Verification Code</h5>
                    </div>
                    <form method="POST">
                        <div class="modal-body text-light">
                            <p class='text-light'>Please enter the your code to access this page:</p>
                            <input type="password" name="security_code" class="form-control" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Verify</button>
                        </div>
                        <a href="https://wa.me/+2348029074974">Click To get ne</a>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- JavaScript to handle the modal display -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (<?php echo json_encode($showModal); ?>) {
                var modal = new bootstrap.Modal(document.getElementById('securityModal'), {
                    backdrop: 'static',
                    keyboard: false
                });
                modal.show();
            }
        });
    </script>
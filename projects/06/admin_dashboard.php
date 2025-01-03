<?php
// Step 1: Include config.php file
include 'config.php';

// Step 2: Secure and only allow 'admin' users to access this page
if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== 'admin') {
   
    // Error message for non admins and redirects
    $_SESSION['messages'][] = "You must be an admin to access this page.";
    header('Location: login.php');
   
    exit;
}
?>

<?php include 'templates/head.php'; ?>
<?php include 'templates/nav.php'; ?>
<!-- BEGIN YOUR CONTENT -->
<section class="section">
    <h1 class="title">Admin Dashboard</h1>
    <p>Admin dashboard content will be created in a future project...</p>
</section>
<!-- END YOUR CONTENT -->
<?php include 'templates/footer.php'; ?>
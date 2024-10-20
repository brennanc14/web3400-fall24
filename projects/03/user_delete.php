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
// Step 3: Check if the $_GET['id'] exists; if it does, get the user the record from the database and store it in the associative array $user. If a user record with that ID does not exist, display the message "A user with that ID did not exist."
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // SQL query to grab the user ID
    $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `id` = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Error message if the query doesn't find anything
    if (!$user) {
        $_SESSION['messages'][] = "That user ID does not exist.";
    }

}
// Step 4: Check if $_GET['confirm'] == 'yes'. This means they clicked the 'yes' button to confirm the removal of the record. Prepare and execute a SQL DELETE statement where the user id == the $_GET['id']. Else (meaning they clicked 'no'), return them to the users_manage.php page.
if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    // Prepare and execute the DELETE
    $deleteStmt = $pdo->prepare("DELETE FROM `users` WHERE `id` = ?");
    $deleteStmt->execute([$user_id]);

    // Set a success message and redirect when yes is clicked
    $_SESSION['messages'][] = "User account for {$user['full_name']} has been deleted.";
    header('Location: users_manage.php');
    exit;
} 
elseif (isset($_GET['confirm']) && $_GET['confirm'] === 'no') {
    // Redirect back to the manage users page if no is clicked
    header('Location: users_manage.php');
    exit;
}
?>

<?php include 'templates/head.php'; ?>
<?php include 'templates/nav.php'; ?>
<!-- BEGIN YOUR CONTENT -->
<section class="section">
    <h1 class="title">Delete User Account</h1>
    <p class="subtitle">Are you sure you want to delete the user: <?= $user['full_name'] ?></p>
    <div class="buttons">
        <a href="?id=<?= $user['id'] ?>&confirm=yes" class="button is-success">Yes</a>
        <a href="?id=<?= $user['id'] ?>&confirm=no" class="button is-danger">No</a>
    </div>
</section>
<!-- END YOUR CONTENT -->
<?php include 'templates/footer.php'; ?>
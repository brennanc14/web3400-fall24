<?php
// Include config.php file
include 'config.php';

// Secure and only allow 'admin' users to access this page
if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== 'admin') {
    // Redirect user to login page or display an error message
    $_SESSION['messages'][] = "You must be an admin to access this page.";
    header('Location: login.php');
    exit;
}
// Check if the $_GET['id'] exists; if it does, get the ticket record from the database and store it in the associative array $ticket. If a ticket with that ID does not exist, display the message "A ticket with that ID did not exist."
if (!isset($_GET['id'])) {
    // ID not provided
    echo "Missing ticket ID";
    exit;
}
$ticket_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM tickets WHERE id = ?");
$stmt->execute([$ticket_id]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the ticket exists
if (!$ticket) {
    //Ticket not found
    echo "A ticket with that ID does not exist.";
    exit;
}

// Check if $_GET['confirm'] == 'yes'. This means they clicked the 'yes' button to confirm the removal of the record.
if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    if (isset($_GET['confirm']) && $_GET['confirm'] == 'yes') {
        //"Yes" = Execute an SQL DELETE statement to remove the ticket
        $stmt = $pdo->prepare("DELETE FROM tickets WHERE id = ?");
        $stmt->execute([$ticket_id]);
    
       //Delete all comments on the ticket.
        $stmt = $pdo->prepare("DELETE FROM ticket_comments WHERE ticket_id = ?");
        $stmt->execute([$ticket_id]);
    
        //"No" = return them to tickets.php
        header("Location: tickets.php");
        exit;
    }
}
?>

<?php include 'templates/head.php'; ?>
<?php include 'templates/nav.php'; ?>



<!-- BEGIN YOUR CONTENT -->
<section class="section">
    <h1 class="title">Delete Ticket</h1>
    <p class="subtitle">Are you sure you want to delete ticket: <?= htmlspecialchars_decode($ticket['title']) ?></p>
    <div class="buttons">
        <a href="?id=<?= $ticket['id'] ?>&confirm=yes" class="button is-success">Yes</a>
        <a href="tickets.php" class="button is-danger">No</a>
    </div>
</section>
<!-- END YOUR CONTENT -->


<?php include 'templates/footer.php'; ?>
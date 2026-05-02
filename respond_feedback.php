<?php
require_once 'config.php';

if (!isAdminLoggedIn()) {
    redirect('admin_login.php');
}

$feedback_id = $_GET['id'] ?? 0;

// Get feedback details
$stmt = $pdo->prepare("SELECT * FROM vw_FeedbackDetails WHERE FeedbackID = ?");
$stmt->execute([$feedback_id]);
$feedback = $stmt->fetch();

if (!$feedback) {
    redirect('admin_dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response_text = $_POST['response_text'];
    $new_status = $_POST['status'];
    
    try {
        $pdo->beginTransaction();
        
        // Add response
        $stmt = $pdo->prepare("INSERT INTO Response (ResponseText, FeedbackID, AdminID) VALUES (?, ?, ?)");
        $stmt->execute([$response_text, $feedback_id, $_SESSION['admin_id']]);
        
        // Update feedback status
        $stmt = $pdo->prepare("UPDATE Feedback SET Status = ? WHERE FeedbackID = ?");
        $stmt->execute([$new_status, $feedback_id]);
        
        $pdo->commit();
        
        $_SESSION['success_message'] = "Response submitted successfully!";
        redirect('admin_dashboard.php');
        
    } catch(Exception $e) {
        $pdo->rollBack();
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Respond to Feedback</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Respond to Feedback</h1>
            <div class="nav-links">
                <a href="admin_dashboard.php">Back to Dashboard</a>
                <a href="logout.php">Logout</a>
            </div>
        </header>

        <main>
            <div class="feedback-detail">
                <h3>Original Feedback</h3>
                <div class="feedback-card">
                    <div class="feedback-header">
                        <strong><?= htmlspecialchars($feedback['UserName']) ?></strong>
                        <span class="rating"><?= str_repeat('★', $feedback['Rating']) ?></span>
                    </div>
                    <div class="feedback-meta">
                        <span>Product: <?= htmlspecialchars($feedback['ProductName']) ?></span>
                        <span>Category: <?= htmlspecialchars($feedback['CategoryName']) ?></span>
                    </div>
                    <div class="feedback-text">
                        <?= nl2br(htmlspecialchars($feedback['FeedbackText'])) ?>
                    </div>
                </div>
            </div>

            <?php if (isset($error)): ?>
                <div class="error-message"><?= $error ?></div>
            <?php endif; ?>

            <div class="response-form">
                <h3>Your Response</h3>
                <form method="POST">
                    <div class="form-group">
                        <label for="response_text">Response *</label>
                        <textarea id="response_text" name="response_text" rows="5" required placeholder="Write your response here..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Update Status *</label>
                        <select id="status" name="status" required>
                            <option value="Pending" <?= $feedback['Status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="Resolved" <?= $feedback['Status'] == 'Resolved' ? 'selected' : '' ?>>Resolved</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn-submit">Submit Response</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
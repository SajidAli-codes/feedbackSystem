<?php
require_once 'config.php';

if (!isAdminLoggedIn()) {
    redirect('admin_login.php');
}

// Get statistics
$stats = $pdo->query("SELECT * FROM vw_DashboardStats")->fetch();
$pending_feedback = $pdo->query("SELECT COUNT(*) FROM Feedback WHERE Status = 'Pending'")->fetchColumn();
$sentiments = $pdo->query("SELECT * FROM vw_SentimentSummary")->fetchAll();
$recent_feedback = $pdo->query("SELECT * FROM vw_FeedbackDetails ORDER BY SubmissionDate DESC LIMIT 10")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Admin Dashboard</h1>
            <div class="nav-links">
                <span>Welcome, <?= htmlspecialchars($_SESSION['admin_name']) ?></span>
                <a href="admin_dashboard.php">Dashboard</a>
                <a href="logout.php">Logout</a>
            </div>
        </header>

        <main>
            <div class="dashboard-stats">
                <div class="stat-card">
                    <div class="stat-value"><?= $stats['TotalFeedback'] ?></div>
                    <div class="stat-label">Total Feedback</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $pending_feedback ?></div>
                    <div class="stat-label">Pending Response</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $stats['AvgRating'] ?></div>
                    <div class="stat-label">Average Rating</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $stats['TotalUsers'] ?></div>
                    <div class="stat-label">Total Users</div>
                </div>
            </div>

            <div class="sentiment-summary">
                <h3>Sentiment Analysis Summary</h3>
                <div class="sentiment-bars">
                    <?php foreach($sentiments as $sentiment): ?>
                        <div class="sentiment-item">
                            <span class="sentiment-label"><?= $sentiment['SentimentType'] ?></span>
                            <div class="bar-container">
                                <div class="bar" style="width: <?= $sentiment['Count'] * 10 ?>%"></div>
                                <span class="count"><?= $sentiment['Count'] ?> (<?= $sentiment['AvgConfidence'] ?>%)</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="recent-feedback">
                <h3>Recent Feedback</h3>
                <table class="feedback-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Rating</th>
                            <th>Feedback</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($recent_feedback as $feedback): ?>
                            <tr>
                                <td><?= htmlspecialchars($feedback['UserName']) ?></td>
                                <td><?= htmlspecialchars($feedback['ProductName']) ?></td>
                                <td><?= htmlspecialchars($feedback['CategoryName']) ?></td>
                                <td>
                                    <span class="rating"><?= str_repeat('★', $feedback['Rating']) ?><?= str_repeat('☆', 5 - $feedback['Rating']) ?></span>
                                </td>
                                <td><?= htmlspecialchars(substr($feedback['FeedbackText'], 0, 50)) ?>...</td>
                                <td>
                                    <span class="status <?= strtolower($feedback['Status']) ?>"><?= $feedback['Status'] ?></span>
                                </td>
                                <td>
                                    <a href="respond_feedback.php?id=<?= $feedback['FeedbackID'] ?>" class="btn-small">Respond</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
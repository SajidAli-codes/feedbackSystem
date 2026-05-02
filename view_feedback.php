<?php
require_once 'config.php';

$search = $_GET['search'] ?? '';
$product_filter = $_GET['product'] ?? '';
$status_filter = $_GET['status'] ?? '';

$sql = "SELECT * FROM vw_FeedbackDetails WHERE 1=1";
$params = [];

if ($search) {
    $sql .= " AND (FeedbackText LIKE ? OR UserName LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if ($product_filter) {
    $sql .= " AND ProductID = ?";
    $params[] = $product_filter;
}
if ($status_filter) {
    $sql .= " AND Status = ?";
    $params[] = $status_filter;
}

$sql .= " ORDER BY SubmissionDate DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$feedbacks = $stmt->fetchAll();

$products = $pdo->query("SELECT ProductID, ProductName FROM Product")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Customer Feedback</h1>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="view_feedback.php">View Feedbacks</a>
                <a href="admin_login.php">Admin Login</a>
            </div>
        </header>

        <main>
            <div class="filter-section">
                <form method="GET">
                    <input type="text" name="search" placeholder="Search feedback..." value="<?= htmlspecialchars($search) ?>">
                    <select name="product">
                        <option value="">All Products</option>
                        <?php foreach($products as $product): ?>
                            <option value="<?= $product['ProductID'] ?>" <?= $product_filter == $product['ProductID'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($product['ProductName']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select name="status">
                        <option value="">All Status</option>
                        <option value="Pending" <?= $status_filter == 'Pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="Resolved" <?= $status_filter == 'Resolved' ? 'selected' : '' ?>>Resolved</option>
                    </select>
                    <button type="submit">Filter</button>
                </form>
            </div>

            <div class="feedback-list">
                <?php if (count($feedbacks) > 0): ?>
                    <?php foreach($feedbacks as $feedback): ?>
                        <div class="feedback-card">
                            <div class="feedback-header">
                                <strong><?= htmlspecialchars($feedback['UserName']) ?></strong>
                                <span class="rating"><?= str_repeat('★', $feedback['Rating']) ?></span>
                                <span class="date"><?= date('M d, Y', strtotime($feedback['SubmissionDate'])) ?></span>
                            </div>
                            <div class="feedback-meta">
                                <span class="badge">Product: <?= htmlspecialchars($feedback['ProductName']) ?></span>
                                <span class="badge">Category: <?= htmlspecialchars($feedback['CategoryName']) ?></span>
                                <span class="badge sentiment <?= strtolower($feedback['SentimentType']) ?>">
                                    Sentiment: <?= $feedback['SentimentType'] ?>
                                </span>
                            </div>
                            <div class="feedback-text">
                                <?= nl2br(htmlspecialchars($feedback['FeedbackText'])) ?>
                            </div>
                            <?php
                            // Check if there's a response
                            $stmt = $pdo->prepare("SELECT * FROM Response WHERE FeedbackID = ?");
                            $stmt->execute([$feedback['FeedbackID']]);
                            $response = $stmt->fetch();
                            ?>
                            <?php if ($response): ?>
                                <div class="response-box">
                                    <strong>Admin Response:</strong>
                                    <p><?= nl2br(htmlspecialchars($response['ResponseText'])) ?></p>
                                    <small>Responded on: <?= date('M d, Y', strtotime($response['ResponseDate'])) ?></small>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No feedback found.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
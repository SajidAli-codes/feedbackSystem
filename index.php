<?php
require_once 'config.php';

// Fetch products and categories for the form
$products = $pdo->query("SELECT ProductID, ProductName FROM Product ORDER BY ProductName")->fetchAll();
$categories = $pdo->query("SELECT CategoryID, CategoryName FROM Category ORDER BY CategoryName")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>📝 Customer Feedback System</h1>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="view_feedback.php">View Feedbacks</a>
                <a href="admin_login.php">Admin Login</a>
            </div>
        </header>

        <main>
            <div class="feedback-form-container">
                <h2>Submit Your Feedback</h2>
                <form action="submit_feedback.php" method="POST" class="feedback-form">
                    <div class="form-group">
                        <label for="fullname">Full Name *</label>
                        <input type="text" id="fullname" name="fullname" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone">
                    </div>

                    <div class="form-group">
                        <label for="product">Product *</label>
                        <select id="product" name="product_id" required>
                            <option value="">Select Product</option>
                            <?php foreach($products as $product): ?>
                                <option value="<?= $product['ProductID'] ?>"><?= htmlspecialchars($product['ProductName']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="category">Category *</label>
                        <select id="category" name="category_id" required>
                            <option value="">Select Category</option>
                            <?php foreach($categories as $category): ?>
                                <option value="<?= $category['CategoryID'] ?>"><?= htmlspecialchars($category['CategoryName']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="rating">Rating *</label>
                        <div class="rating-stars">
                            <input type="radio" name="rating" value="5" id="star5"><label for="star5">★</label>
                            <input type="radio" name="rating" value="4" id="star4"><label for="star4">★</label>
                            <input type="radio" name="rating" value="3" id="star3"><label for="star3">★</label>
                            <input type="radio" name="rating" value="2" id="star2"><label for="star2">★</label>
                            <input type="radio" name="rating" value="1" id="star1"><label for="star1">★</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="feedback">Your Feedback *</label>
                        <textarea id="feedback" name="feedback_text" rows="5" required placeholder="Please share your experience with us..."></textarea>
                    </div>

                    <button type="submit" class="btn-submit">Submit Feedback</button>
                </form>
            </div>

            <div class="stats-container">
                <h3>Quick Stats</h3>
                <?php
                $stats = $pdo->query("SELECT * FROM vw_DashboardStats")->fetch();
                ?>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-value"><?= $stats['TotalFeedback'] ?></div>
                        <div class="stat-label">Total Feedback</div>
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
            </div>
        </main>
    </div>
</body>
</html>
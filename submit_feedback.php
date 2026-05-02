<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $product_id = $_POST['product_id'];
    $category_id = $_POST['category_id'];
    $rating = $_POST['rating'];
    $feedback_text = $_POST['feedback_text'];
    
    try {
        // Start transaction
        $pdo->beginTransaction();
        
        // Check if user exists
        $stmt = $pdo->prepare("SELECT UserID FROM Users WHERE Email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user) {
            $user_id = $user['UserID'];
        } else {
            // Create new user
            $password = password_hash('temp123', PASSWORD_DEFAULT); // Temporary password
            $stmt = $pdo->prepare("INSERT INTO Users (FullName, Email, PhoneNo, Password) VALUES (?, ?, ?, ?)");
            $stmt->execute([$fullname, $email, $phone, $password]);
            $user_id = $pdo->lastInsertId();
        }
        
        // Save feedback
        $stmt = $pdo->prepare("INSERT INTO Feedback (FeedbackText, Rating, Status, UserID, ProductID, CategoryID) VALUES (?, ?, 'Pending', ?, ?, ?)");
        $stmt->execute([$feedback_text, $rating, $user_id, $product_id, $category_id]);
        $feedback_id = $pdo->lastInsertId();
        
        // Simple sentiment analysis based on rating and keywords
        $sentiment = 'Neutral';
        $confidence = 0.70;
        
        if ($rating >= 4) {
            $sentiment = 'Positive';
            $confidence = 0.85;
        } elseif ($rating <= 2) {
            $sentiment = 'Negative';
            $confidence = 0.80;
        }
        
        // Check for keywords
        $lower_text = strtolower($feedback_text);
        if (strpos($lower_text, 'great') !== false || strpos($lower_text, 'excellent') !== false) {
            $sentiment = 'Positive';
            $confidence = 0.90;
        } elseif (strpos($lower_text, 'bad') !== false || strpos($lower_text, 'issue') !== false) {
            $sentiment = 'Negative';
            $confidence = 0.85;
        }
        
        // Insert sentiment analysis
        $stmt = $pdo->prepare("INSERT INTO SentimentAnalysis (SentimentType, ConfidenceScore, FeedbackID) VALUES (?, ?, ?)");
        $stmt->execute([$sentiment, $confidence, $feedback_id]);
        
        $pdo->commit();
        
        $_SESSION['success_message'] = "Thank you for your feedback!";
        redirect('index.php');
        
    } catch(Exception $e) {
        $pdo->rollBack();
        $_SESSION['error_message'] = "Error submitting feedback: " . $e->getMessage();
        redirect('index.php');
    }
} else {
    redirect('index.php');
}
?>
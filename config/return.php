<?php
session_start();
include 'db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$user_id = $_SESSION['user_id'];
$title = $data['title'] ?? '';

if (empty($title)) {
    echo json_encode(['success' => false, 'message' => 'Invalid book data']);
    exit();
}

try {
    // Start transaction
    $conn->begin_transaction();
    
    // 1. Find the borrowed book record
    $stmt = $conn->prepare("
        SELECT bb.id, bb.book_id 
        FROM borrowed_books bb
        JOIN books b ON bb.book_id = b.book_id
        WHERE bb.user_id = ? AND b.title = ? AND bb.return_date IS NULL
        LIMIT 1
    ");
    $stmt->bind_param("is", $user_id, $title);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception("No active borrowing record found for this book");
    }
    
    $record = $result->fetch_assoc();
    $borrow_id = $record['id'];
    $book_id = $record['book_id'];
    
    // 2. Mark book as available
    $stmt = $conn->prepare("UPDATE books SET available = 1 WHERE book_id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    
    // 3. Update return date
    $stmt = $conn->prepare("UPDATE borrowed_books SET return_date = NOW() WHERE id = ?");
    $stmt->bind_param("i", $borrow_id);
    $stmt->execute();
    
    // Commit transaction
    $conn->commit();
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
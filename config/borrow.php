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
$author = $data['author'] ?? '';

if (empty($title) || empty($author)) {
    echo json_encode(['success' => false, 'message' => 'Invalid book data']);
    exit();
}

try {
    // Start transaction
    $conn->begin_transaction();
    
    // 1. Check if book exists and is available
    $stmt = $conn->prepare("SELECT book_id FROM books WHERE title = ? AND author = ? AND available = 1 LIMIT 1");
    $stmt->bind_param("ss", $title, $author);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception("Book not available for borrowing");
    }
    
    $book = $result->fetch_assoc();
    $book_id = $book['book_id'];
    
    // 2. Mark book as unavailable
    $stmt = $conn->prepare("UPDATE books SET available = 0 WHERE book_id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    
    // 3. Create borrowed record
    $stmt = $conn->prepare("INSERT INTO borrowed_books (user_id, book_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $book_id);
    $stmt->execute();
    
    // Commit transaction
    $conn->commit();
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
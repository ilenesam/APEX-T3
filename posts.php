<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$db = 'blog';
$port = 3308;

$conn = new mysqli($host, $user, $pass, $db, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Post ID is missing.";
    exit;
}

$post_id = (int) $_GET['id'];

$sql = "SELECT * FROM posts WHERE id = $post_id";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $post = $result->fetch_assoc();
} else {
    echo "Post not found.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($post['title']) ?> - My Blog</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: auto;
            background: #f4f4f4;
            padding: 30px;
        }
        h1 {
            color: #333;
        }
        .post-content {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .post-content p {
            line-height: 1.6;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            color: #2980b9;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <div class="post-content">
        <h1><?= htmlspecialchars($post['title']) ?></h1>
        <p><small>Posted on <?= $post['created_at'] ?></small></p>
        <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
        <a href="index.php">← Back to Blog</a>
    </div>

</body>
</html>

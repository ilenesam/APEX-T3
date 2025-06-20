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

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

// Count total rows
$count_sql = "SELECT COUNT(*) as total FROM posts";
if (!empty($search)) {
    $count_sql .= " WHERE title LIKE '%$search%' OR content LIKE '%$search%'";
}
$count_result = $conn->query($count_sql);
$total_rows = $count_result ? $count_result->fetch_assoc()['total'] : 0;
$total_pages = ceil($total_rows / $limit);

// Main post query
$sql = "SELECT * FROM posts";
if (!empty($search)) {
    $sql .= " WHERE title LIKE '%$search%' OR content LIKE '%$search%'";
}
$sql .= " ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Blog</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 30px;
            max-width: 800px;
            margin: auto;
        }
        h1 {
            text-align: center;
        }
        form {
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 8px;
            width: 60%;
        }
        button {
            padding: 8px 16px;
            background-color: #2980b9;
            color: white;
            border: none;
            cursor: pointer;
        }
        .post {
            background: #fff;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 5px;
            box-shadow: 0px 2px 4px rgba(0,0,0,0.1);
        }
        .post h2 {
            margin-top: 0;
        }
        .post a {
            text-decoration: none;
            color: #2980b9;
        }
        .pagination {
            text-align: center;
            margin-top: 20px;
        }
        .pagination a {
            padding: 6px 12px;
            margin: 0 5px;
            text-decoration: none;
            border: 1px solid #2980b9;
            color: #2980b9;
            border-radius: 4px;
        }
        .pagination a:hover {
            background-color: #2980b9;
            color: white;
        }
    </style>
</head>
<body>

<h1>Blog Posts</h1>

<form method="get">
    <input type="text" name="search" placeholder="Search posts..." value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
</form>

<?php
if (!$result) {
    echo "<p>Error in query: " . $conn->error . "</p>";
} elseif ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        ?>
        <div class="post">
            <h2>
                <a href="post.php?id=<?php echo $row['id']; ?>">
                    <?php echo htmlspecialchars($row['title']); ?>
                </a>
            </h2>
            <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
            <small>Posted on <?php echo $row['created_at']; ?></small>
        </div>
        <?php
    }
} else {
    echo "<p>No posts found.</p>";
}
?>

<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $page - 1; ?>">← Previous</a>
    <?php endif; ?>
    
    <?php if ($page < $total_pages): ?>
        <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $page + 1; ?>">Next →</a>
    <?php endif; ?>
</div>

</body>
</html>

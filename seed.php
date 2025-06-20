<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$db = 'blog';
$port = 3308;

$conn = new mysqli($host, $user, $pass, $db, $port);

$posts = [
    ["Exploring Coffee Varieties", "Discover Arabica, Robusta, and Liberica coffees..."],
    ["The Future of PHP", "Why PHP is still evolving in 2025..."],
    ["Building a Blog in PHP", "Step-by-step guide on building a blog from scratch."]
];

foreach ($posts as $p) {
    $title = $conn->real_escape_string($p[0]);
    $content = $conn->real_escape_string($p[1]);
    $conn->query("INSERT INTO posts (title, content) VALUES ('$title', '$content')");
}

echo "✅ Sample blog posts inserted!";
?>

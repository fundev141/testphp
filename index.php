<?php
// Simple bulletin board system
// File: index.php

// Path to store posts
$posts_file = __DIR__ . '/posts.json';

// Handle new post submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $comment = trim($_POST['comment'] ?? '');

    if ($name !== '' && $comment !== '') {
        // Read existing posts
        $posts = [];
        if (file_exists($posts_file)) {
            $json = file_get_contents($posts_file);
            $posts = json_decode($json, true) ?: [];
        }

        // Add new post
        $posts[] = [
            'name' => htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
            'comment' => htmlspecialchars($comment, ENT_QUOTES, 'UTF-8'),
            'time' => date('Y-m-d H:i:s')
        ];

        // Save back to file
        file_put_contents($posts_file, json_encode($posts, JSON_PRETTY_PRINT));
    }

    // Redirect to avoid form resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Load posts
$posts = [];
if (file_exists($posts_file)) {
    $json = file_get_contents($posts_file);
    $posts = json_decode($json, true) ?: [];
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>簡単な掲示板</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .post { border-bottom: 1px solid #ccc; padding: 10px 0; }
        .post:last-child { border-bottom: none; }
        form { margin-top: 20px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], textarea { width: 100%; }
    </style>
</head>
<body>
    <h1>掲示板</h1>
    <?php foreach (array_reverse($posts) as $post): ?>
        <div class="post">
            <strong><?php echo $post['name']; ?></strong>
            <em>(<?php echo $post['time']; ?>)</em>
            <p><?php echo nl2br($post['comment']); ?></p>
        </div>
    <?php endforeach; ?>
    <form method="post" action="">
        <label>名前: <input type="text" name="name" required></label>
        <label>コメント: <textarea name="comment" rows="4" required></textarea></label>
        <button type="submit">投稿</button>
    </form>
</body>
</html>

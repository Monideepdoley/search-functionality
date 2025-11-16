<?php
include "db.php";

$search = "";

// Default query (shows all posts)
$sql = "SELECT posts.title, posts.content, posts.created_at, users.name AS author
        FROM posts
        JOIN users ON posts.author_id = users.id";

// If search term submitted
if (isset($_GET['search']) && $_GET['search'] != "") {
    $search = $_GET['search'];
    $sql .= " WHERE posts.title LIKE ? OR posts.content LIKE ?";
}

$stmt = mysqli_prepare($conn, $sql);

if ($search != "") {
    $searchTerm = "%" . $search . "%";
    mysqli_stmt_bind_param($stmt, "ss", $searchTerm, $searchTerm);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Posts</title>
</head>
<body>

<h2>Search Blog Posts</h2>

<form method="GET" action="">
    <input type="text" name="search" placeholder="Search..." value="<?php echo $search; ?>">
    <button type="submit">Search</button>
</form>

<hr>

<?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <div style="border:1px solid #ddd; padding:10px; margin:15px 0;">
        <h3><?php echo $row['title']; ?></h3>
        <p><?php echo $row['content']; ?></p>
        <small>
            Author: <b><?php echo $row['author']; ?></b> |
            Date: <?php echo $row['created_at']; ?>
        </small>
    </div>
<?php } ?>

</body>
</html>

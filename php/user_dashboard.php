<?php
include 'database_user.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    // Redirect to the login page if the user is not logged in
    header("Location: login_user.php");
    exit();
}

// Handle comment submission for tbl_comments
if (isset($_POST["submit_comment"])) {
    $comment = $_POST["comment"];
    $user_id = $_SESSION["user_id"];

    // Insert the comment into the database
    $insert_sql = "INSERT INTO tbl_comments (user_id, comment_text) VALUES (?, ?)";
    $insert_stmt = mysqli_prepare($conn, $insert_sql);

    if ($insert_stmt) {
        mysqli_stmt_bind_param($insert_stmt, "is", $user_id, $comment);
        mysqli_stmt_execute($insert_stmt);
        mysqli_stmt_close($insert_stmt);
    }
}

// Handle comment editing for tbl_comments
if (isset($_POST["edit_comment"])) {
    $edited_comment = $_POST["edited_comment"];
    $comment_id = $_POST["comment_id"];

    // Update the comment in the database
    $update_sql = "UPDATE tbl_comments SET comment_text = ? WHERE comment_id = ?";
    $update_stmt = mysqli_prepare($conn, $update_sql);

    if ($update_stmt) {
        mysqli_stmt_bind_param($update_stmt, "si", $edited_comment, $comment_id);
        mysqli_stmt_execute($update_stmt);
        mysqli_stmt_close($update_stmt);
    }
}

// Handle comment deletion for tbl_comments
if (isset($_POST["delete_comment"])) {
    $comment_id = $_POST["comment_id"];

    // Delete the comment from the database
    $delete_sql = "DELETE FROM tbl_comments WHERE comment_id = ?";
    $delete_stmt = mysqli_prepare($conn, $delete_sql);

    if ($delete_stmt) {
        mysqli_stmt_bind_param($delete_stmt, "i", $comment_id);
        mysqli_stmt_execute($delete_stmt);
        mysqli_stmt_close($delete_stmt);
    }
}

// Handle comment submission for tbl_comments2
if (isset($_POST["submit_comment_2"])) {
    $comment_2 = $_POST["comment_2"];
    $user_id = $_SESSION["user_id"];

    // Insert the comment into the database
    $insert_sql_2 = "INSERT INTO tbl_comments2 (user_id, comment_text) VALUES (?, ?)";
    $insert_stmt_2 = mysqli_prepare($conn, $insert_sql_2);

    if ($insert_stmt_2) {
        mysqli_stmt_bind_param($insert_stmt_2, "is", $user_id, $comment_2);
        mysqli_stmt_execute($insert_stmt_2);
        mysqli_stmt_close($insert_stmt_2);
    }
}

// Handle comment editing for tbl_comments2
if (isset($_POST["edit_comment_2"])) {
    $edited_comment_2 = $_POST["edited_comment_2"];
    $comment_id_2 = $_POST["comment_id_2"];

    // Update the comment in the database
    $update_sql_2 = "UPDATE tbl_comments2 SET comment_text = ? WHERE comment_id = ?";
    $update_stmt_2 = mysqli_prepare($conn, $update_sql_2);

    if ($update_stmt_2) {
        mysqli_stmt_bind_param($update_stmt_2, "si", $edited_comment_2, $comment_id_2);
        mysqli_stmt_execute($update_stmt_2);
        mysqli_stmt_close($update_stmt_2);
    }
}

// Handle comment deletion for tbl_comments2
if (isset($_POST["delete_comment_2"])) {
    $comment_id_2 = $_POST["comment_id_2"];

    // Delete the comment from the database
    $delete_sql_2 = "DELETE FROM tbl_comments2 WHERE comment_id = ?";
    $delete_stmt_2 = mysqli_prepare($conn, $delete_sql_2);

    if ($delete_stmt_2) {
        mysqli_stmt_bind_param($delete_stmt_2, "i", $comment_id_2);
        mysqli_stmt_execute($delete_stmt_2);
        mysqli_stmt_close($delete_stmt_2);
    }
}

// Retrieve existing comments from the first table
$select_sql = "SELECT c.comment_id, c.comment_text, u.USER_ID, u.FIRST_NAME, u.LAST_NAME
               FROM tbl_comments c 
               JOIN tbl_user u ON c.user_id = u.USER_ID 
               ORDER BY c.comment_id DESC";
$result = mysqli_query($conn, $select_sql);

// Retrieve existing comments from the second table
$select_sql_2 = "SELECT c.comment_id, c.comment_text, u.USER_ID, u.FIRST_NAME, u.LAST_NAME
               FROM tbl_comments2 c 
               JOIN tbl_user u ON c.user_id = u.USER_ID 
               ORDER BY c.comment_id DESC";
$result_2 = mysqli_query($conn, $select_sql_2);

// Fetch the user's information from the database
$user_id = $_SESSION["user_id"];
$sqlUser = "SELECT FIRST_NAME FROM tbl_user WHERE USER_ID = ?";
$stmtUser = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmtUser, $sqlUser)) {
    mysqli_stmt_bind_param($stmtUser, "i", $user_id);
    mysqli_stmt_execute($stmtUser);
    mysqli_stmt_bind_result($stmtUser, $first_name);

    if (mysqli_stmt_fetch($stmtUser)) {
        // $first_name now contains the user's first name
    }

    // Close the statement
    mysqli_stmt_close($stmtUser);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="user_dashboard.css">
</head>
<body>

<div class="header">
    <div class="header-content">
        <?php if (isset($first_name)) : ?>
            <h1>Welcome, <?php echo $first_name; ?>!</h1>
        <?php else : ?>
            <h1>Welcome to User Dashboard</h1>
        <?php endif; ?>
    </div>
    <a href="logout_user.php" class="btn btn-warning">Logout</a>
</div>


<div class="container mt-3">

    <div class="about-info">
                <h4 style="color: #329C4E;">FEEDBACK SECTION</h4>
                <br>
                <p  style="text-align: justify;">
                Hey there, visitors! Your feedbacks are greatly appreciated. Whether you stumble upon a bug, have a suggestion, or just want to share your thoughts, this is the place. Your feedback shapes the evolution of my website, and I can't wait to hear what you think. Feel free to have discussions with other users! - Gab 
    </div>


    <!-- Form for users to submit new comments -->
    <form action="user_dashboard.php" method="post">
        <div class="form-group mt-3">
            <label for="comment" style = "color: white;">Submit Comment:</label>
            <textarea name="comment" class="form-control" rows="4" required></textarea>
        </div>
        <div>
            <button type="submit" name="submit_comment" class="btn btn-success" style="margin-bottom: 50px;">Submit</button>
        </div>

    </form>

    <button id="hideBtn" class="toggle-btn" onclick="toggleAllComments()">Hide All Comments</button>
    <button id="showBtn" class="toggle-btn"  onclick="showAllComments()">Show All Comments</button>


    
 <!-- Display existing comments -->
<?php while ($row = mysqli_fetch_assoc($result)) : ?>
    <div class="card mb-3">
        <div class="card-body">
            <strong><?php echo $row['FIRST_NAME'] . ' ' . $row['LAST_NAME']; ?>:</strong>
            <div class="comment-text"><?php echo $row['comment_text']; ?></div>
            <!-- Show edit and delete buttons for the user's own comments -->
            <?php if ($row['USER_ID'] == $_SESSION["user_id"]) : ?>
                <div class="comment-actions mt-2">
                    <div class="dropdown">
                        <button class="ellipsis-btn btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <button class="btn btn-primary mt-2 dropdown-item" onclick="editComment('<?php echo $row['comment_id']; ?>', '<?php echo $row['comment_text']; ?>')">Edit</button>
                            <form action="user_dashboard.php" method="post">
                                <input type="hidden" name="comment_id" value="<?php echo $row['comment_id']; ?>">
                                <button type="submit" name="delete_comment" class="btn btn-danger dropdown-item">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="edit-comment mt-2" id="edit-comment-<?php echo $row['comment_id']; ?>" style="display: none;">
                    <form action="user_dashboard.php" method="post">
                        <input type="hidden" name="comment_id" value="<?php echo $row['comment_id']; ?>">
                        <input type="text" name="edited_comment" class="form-control" value="<?php echo $row['comment_text']; ?>" required>
                        <button type="submit" name="edit_comment" class="btn btn-primary mt-2">Save</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php endwhile; ?>

 <!------------------------------------------------ INSERT IT HERE ----------------------------------------------------------------------------------->

 <div class="about-info">
                <h4 style="color: #329C4E;">FREEDOM WALL</h4>
                <br>
                <p  style="text-align: justify;">
                Hey there, visitors! This is your spot to speak up and share your thoughts. Feel free to introduce yourself or drop whatever's on your mind. You can ask random questions or interact with your fellow users!   </div>

    <!-- Form for users to submit new comments for tbl_comments2 -->
    <form action="user_dashboard.php" method="post">
        <div class="form-group mt-3">
            <label for="comment_2" style="color: white;">Submit Comment:</label>
            <textarea name="comment_2" class="form-control" rows="4" required></textarea>
        </div>
        <div>
            <button type="submit" name="submit_comment_2" class="btn btn-success" style="margin-bottom: 50px;">Submit
            </button>
        </div>
    </form>

    <button id="hideBtn2" class="toggle-btn" onclick="toggleAllComments2()">Hide All Comments</button>
    <button id="showBtn2" class="toggle-btn" onclick="showAllComments2()">Show All Comments</button>

    <!-- Display existing comments for tbl_comments2 -->
    <?php while ($row_2 = mysqli_fetch_assoc($result_2)) : ?>
        <div class="card mb-4">
            <div class="card-body">
                <strong><?php echo $row_2['FIRST_NAME'] . ' ' . $row_2['LAST_NAME']; ?>:</strong>
                <div class="comment-text"><?php echo $row_2['comment_text']; ?></div>
                <!-- Show edit and delete buttons for the user's own comments -->
                <?php if ($row_2['USER_ID'] == $_SESSION["user_id"]) : ?>
                    <div class="comment-actions mt-2">
                        <div class="dropdown">
                            <button class="ellipsis-btn btn btn-secondary dropdown-toggle" type="button"
                                    id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                <button class="btn btn-primary mt-2 dropdown-item"
                                        onclick="editComment2('<?php echo $row_2['comment_id']; ?>', '<?php echo $row_2['comment_text']; ?>')">Edit
                                </button>
                                <form action="user_dashboard.php" method="post">
                                    <input type="hidden" name="comment_id_2" value="<?php echo $row_2['comment_id']; ?>">
                                    <button type="submit" name="delete_comment_2"
                                            class="btn btn-danger dropdown-item">Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="edit-comment mt-2" id="edit-comment-<?php echo $row_2['comment_id']; ?>"
                         style="display: none;">
                        <form action="user_dashboard.php" method="post">
                            <input type="hidden" name="comment_id_2" value="<?php echo $row_2['comment_id']; ?>">
                            <input type="text" name="edited_comment_2" class="form-control"
                                   value="<?php echo $row_2['comment_text']; ?>" required>
                            <button type="submit" name="edit_comment_2" class="btn btn-primary mt-2">Save</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endwhile; ?>






 <!---------------------------------------------------------------------------------------------------------------------------------------------------------------------->


 
<script>
    function toggleAllComments() {
        var commentCards = document.querySelectorAll('.card.mb-3');

        commentCards.forEach(function (card) {
            var cardBody = card.querySelector('.card-body');
            
            if (cardBody.style.display !== 'none') {
                // Hide the entire card
                card.style.display = 'none';
            }
        });
    }

    function showAllComments() {
        var commentCards = document.querySelectorAll('.card.mb-3');
        var hideBtn = document.getElementById('hideBtn');
        var showBtn = document.getElementById('showBtn');

        commentCards.forEach(function (card) {
            // Show the entire card
            card.style.display = 'block';
        });


    }
</script>


<!-- JavaScript to toggle display of edit-comment div -->
<script>
    function editComment(commentId, commentText) {
        var editCommentDiv = document.getElementById('edit-comment-' + commentId);
        var editedCommentField = editCommentDiv.querySelector('input[name="edited_comment"]');
        editedCommentField.value = commentText;
        editCommentDiv.style.display = 'block';
    }
</script>

<!-- JavaScript to toggle display of edit-comment div -->
<script>
    function editComment2(commentId, commentText) {
        var editCommentDiv = document.getElementById('edit-comment-' + commentId);
        var editedCommentField = editCommentDiv.querySelector('input[name="edited_comment_2"]');
        editedCommentField.value = commentText;
        editCommentDiv.style.display = 'block';
    }
</script>



<script>
    function toggleAllComments2() {
        var commentCards = document.querySelectorAll('.card.mb-4');

        commentCards.forEach(function (card) {
            var cardBody = card.querySelector('.card-body');

            if (cardBody.style.display !== 'none') {
                // Hide the entire card
                card.style.display = 'none';
            }
        });
    }

    function showAllComments2() {
        var commentCards = document.querySelectorAll('.card.mb-4');
        var hideBtn = document.getElementById('hideBtn2');
        var showBtn = document.getElementById('showBtn2');

        commentCards.forEach(function (card) {
            // Show the entire card
            card.style.display = 'block';
        });
    }
</script>

</div>

<!-- Bootstrap JS and Popper.js CDN -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<!-- Bootstrap JS CDN -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {
    var ellipsisBtns = document.querySelectorAll('.ellipsis-btn');

    ellipsisBtns.forEach(function (ellipsisBtn) {
        ellipsisBtn.addEventListener('click', function (event) {
            // Hide all other actions menus
            var allActionsMenus = document.querySelectorAll('.actions-menu');
            allActionsMenus.forEach(function (actionsMenu) {
                if (actionsMenu !== this.nextElementSibling) {
                    actionsMenu.style.display = 'none';
                }
            }, this);

            // Toggle display for the clicked actions menu
            var actionsMenu = this.nextElementSibling;
            actionsMenu.style.display = (actionsMenu.style.display === 'none' || actionsMenu.style.display === '')
                ? 'block'
                : 'none';

            // Prevent the click event from bubbling up to document click
            event.stopPropagation();
        });
    });

    // Close actions menus when clicking outside
    document.addEventListener('click', function () {
        var allActionsMenus = document.querySelectorAll('.actions-menu');
        allActionsMenus.forEach(function (actionsMenu) {
            actionsMenu.style.display = 'none';
        });
    });

    // Add click event listener to edit buttons
    var editButtons = document.querySelectorAll('[name="edit_comment"]');
    editButtons.forEach(function (editButton) {
        editButton.addEventListener('click', function (event) {
            // Hide the parent actions menu when edit is clicked
            var actionsMenu = this.closest('.actions-menu');
            actionsMenu.style.display = 'none';

            // Prevent the click event from bubbling up to document click
            event.stopPropagation();
        });
    });
});

</script>


</body>
</html>
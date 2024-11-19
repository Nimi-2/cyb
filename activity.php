<?php 
session_start();

if (isset($_SESSION['session_expire'])) {
    if (time() - $_SESSION['session_expire'] > (60 * 5)) {
        session_destroy();
    } else {
        $_SESSION['session_expire'] = time();
    }
}

?><h5><?php
    if (!empty($_SESSION['login'])) {
        echo $_SESSION['login'];
    } else {
        echo 'niezalogowany';
    }
?></h5><?php
include_once "classes/Page.php";
include_once "classes/Pdo.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Users Activities</title>
</head>
<body>
    <h1>Users Activities</h1>
    
    <?php
    $pdo = new Pdo_();
    // $users = 1; // Zastąp tym konkretnym ID użytkownika
    $activities = $pdo->get_activity();

    
    foreach($activities as $activity){
        echo '<form action="revert_change.php" method="POST">';
        echo "<p>ID: {$activity['id']}</p>";
        echo "<p>USER ID: {$activity['id_user']}</p>";
        echo "<p>ACTION TAKEN: {$activity['action_taken']}</p>";
        echo "<p>AFFECTED TABLE: {$activity['table_affected']}</p>";
        echo "<p>ROW NUMBER: {$activity['row_number']}</p>";
        echo "<p>PREVIOUS DATA: {$activity['previous_data']}</p>";
        echo "<p>PRESENT DATA: {$activity['new_data']}</p>";
        if ($activity['action_taken'] == 'edit_msg') {
            echo '<input type="hidden" name="activity_id" value="' . $activity['id'] . '" />';
            echo '<input type="submit" value="Revert This change" />';
        }
        echo "</br>";
        echo '</form>';
    }
    ?>
<P>Navigation</P>
    <?php
        Page::display_navigation();
    ?>
</body>
</html>
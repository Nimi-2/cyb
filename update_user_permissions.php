<?php
include_once "classes/Pdo.php";
$pdo = new Pdo_();
$user_id = $_POST['user_id']; // Zastąp tym konkretnym ID użytkownika
$permissions = $_POST['permissions'];

// Usuń wszystkie uprawnienia użytkownika
$pdo->remove_all_user_permissions($user_id);

// Dodaj zaznaczone uprawnienia
foreach($permissions as $permission_id){
    $pdo->add_user_permission($user_id, $permission_id);
}

header("Location: user_permissions.php?user_id={$user_id}");
exit;
?>
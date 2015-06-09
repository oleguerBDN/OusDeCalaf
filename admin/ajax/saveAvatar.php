<?php

require_once '../includes/general_settings.php';

$user = new User($_POST['itemid']);

echo $user->saveItem($_POST, $_FILES);

if ($_POST['userAlias']) {
    $_SESSION['user']['userAlias'] = $_POST['userAlias'];
}
if ($_POST['userEmail']) {
    $_SESSION['user']['userEmail'] = $_POST['userEmail'];
}

if ($_FILES['userAvatar']['error'] == 0) {
    $user->load($_POST['itemid']);
    echo '-TheSeparator-';
    echo $user->userAvatar;
}

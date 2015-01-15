<?php

$expire = 60 * 60 * 24 * 60 + time();
setcookie('activeToken', $_POST['cval'], $expire, '/');
?>
<?php
/*
session_start();
$attach = (array)$_SESSION['attach'];
unset($_SESSION['attach']);
*/

// We retrieve the cookies and delete them
$attachFilesNum = $_COOKIE['MagnetAttached_number'];
$attachMessage = "";
setcookie('MagnetAttached_number', "", time() - 3600, '/');

for ($i = 1; $i <= $attachFilesNum; $i++) {
 $attachMessage .= "<br />" . $_POST['url'] . str_replace(" ", "%20", $_COOKIE['MagnetAttached_' . $i]);
 setcookie('MagnetAttached_' . $i, "", time() - 3600, '/');
}

include('../../../../wp-load.php');

//Job poster's email
if (empty($_POST['notes'])) {
 $notes = __('No notes.', '9to5');
}
else
{
 $notes = $_POST['notes'];
}

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=utf-8" . "\r\n";
$headers .= 'From: ' . get_bloginfo("name") . '<' . get_bloginfo("admin_email") . '>';

$message = "<html>
		<body>
		<p><b>" . __('Applicant Name:', '9to5') . "</b> " . $_POST['name'] . "</p>
		<p><b>" . __('Applicant Email:', '9to5') . "</b> " . $_POST['email'] . "</p>
		<p><b>" . __('Notes:', '9to5') . "</b> " . nl2br(stripslashes($notes)) . "</p>
		<p><b>" . __('Files attached:', '9to5');

if ($attachMessage == "") {
 $message = $message . ' No files was attached';
}
else
{
 $message = $message . $attachMessage;
}

$message = $message . '</body></html>';
mail($_POST['to'], __('Job Application for', '9to5') . " " . $_POST['subject'], $message, $headers);


//Application confirmation email

$innerMessage = "<br/><br/>" . __('This is a confirmation to let you know that your application has been sent. This is an automatically-generated email so please do not respond.', '9to5') . "";

$message = "<html>
<body>
<p>" . __('Hi', '9to5') . " " . $_POST['name'] . ",
" . $innerMessage . "<br/><br/>" . __('Thanks,', '9to5') . "<br/>" . get_bloginfo('name') . "</p>
</body>
</html>";

mail($_POST['email'], __('Job Application for', '9to5') . " " . $_POST['subject'], $message, $headers);

echo "{success:true}";

//session_destroy();
?>

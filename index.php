<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<title>Smedia</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" media="screen" href="Web/CSS/index.css" />
</head>
<body>
<form action="indexMidle.php" method="POST">
<div class="main_div">
	<div class="inputs_div" >
		<?php
                //$msg = $_GET;
                $message = $_GET['message'];
                if (!empty($message)) {echo "<p>" . $message . "</p>";} 
                ?>	
		<table class="inputs_table">
			<tr id="usernameRow">
				<td><p>Username:</p></td>
                                <td>
                                    <input type="text" name="username" id="userID" size="25" maxlength="50">
                                    <input type="hidden" name="usernameHidden" id="userIDhidden" size="25" maxlength="50">
                                </td>
			</tr>
			<tr id="passwordRow">
				<td><p>Password:</p></td>
				<td><input type="password" name="password" size="25" maxlength="50"></td>
			</tr>
			<tr>
				<td></td>
                                <td style="text-align:left;"><input type="submit" name="loginme" value="LOGIN" class="button"></td>
			</tr>

                </table>
	</div>
</div>
</form>
</body>
</html>
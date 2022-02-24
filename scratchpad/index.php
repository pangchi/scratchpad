<?php

function escapeJavaScriptText($string)
{
    return str_replace("\n", '\n', str_replace('"', '\"', addcslashes(str_replace("\r", '', (string)$string), "\0..\37'\\")));
}

function printPath($host) {
	$uri ="http://" . $host . $_SERVER['REQUEST_URI'];
	return substr($uri, 0, strrpos($uri, "/"));
}

$URI = printPath(gethostname());
$URI_IP = printPath(getHostByName(getHostName()));

$SERVER_ADDR = $_SERVER['SERVER_ADDR'];

if ($SERVER_ADDR == "127.0.0.1") {
	$displayBlock = "#clientBtns {display: none;}\n";
	$input_type = "password";
	$input_placeholder = "Text will appear here";
	$setPollIntervalJS = <<<HEREDOC
<SCRIPT>
	const poll = setInterval(fetchContents, 1000);
</SCRIPT>
HEREDOC;
	 $pre_uri = "Join at ";
}
else {
	$displayBlock = "#hostBtns {display: none;}\n #showPasswordBox {display: none;}\n";	
	$input_type = "text";
	$input_placeholder = "Enter text here";
	$setPollIntervalJS = <<<HEREDOC
<script src="js/dragdrop.js"></script>
HEREDOC;
	$pre_uri = "Connected to ";
}

?>

<HTML>
<HEAD>
<TITLE>Scratchpad</TITLE>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">-->
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/custom.css">
<STYLE>
<?php
echo $displayBlock;
?>
</STYLE>

<SCRIPT>
</SCRIPT>
</HEAD>

<BODY onload="update('', true)">
<div class="container">
<H1>
Scratchpad
</H1>
<P>
ℹ️ Client must be in same network.<BR>
<?php
echo $pre_uri;
echo "<B>" . $URI . "</B>";
?>
</P>
<div class="mb-3">
<P>

<div class="input-group input-group-lg">
	<input class="form-control" type="<?php echo $input_type; ?>" id="scratchpad_text" name="scratchpad_text" placeholder="<?php echo $input_placeholder; ?>" onkeypress="submit(event);"  
	<?php if ($SERVER_ADDR == "127.0.0.1") { echo "readonly"; } ?>>
	</input>
	<span class="input-group-text" id="showPasswordBox">
	<A HREF="#" class="link-dark" onclick="togglePassword()" id="showPassword"></A>
	</span>
</div>

</P>	
<DIV id="clientBtns">
<BUTTON class="btn btn-primary btn-lg" id="send" onclick="send();">SEND</BUTTON>
&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;
</DIV>

<DIV id="hostBtns">
<BUTTON class="btn btn-secondary btn-lg" id="copy" onclick="copy()";>COPY</BUTTON>
</DIV>

<P id="countdown" style="height:20px;text-align:right;position: relative; margin-top: -20px;">
</P>

<P id="buttonresponse" style="height:20px;">
</P>

<P id="debug">
</P>

<?php 
if ($SERVER_ADDR == "127.0.0.1") {
$encodedURI_IP = urlencode($URI_IP);
echo <<<HEREDOC
<P>
<img 
src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$encodedURI_IP&choe=UTF-8" title="Link to this page" />
</P>

<P id="pictures" style="relative; margin-left: 300px; margin-top: -300px;">
</P>
HEREDOC;
}
else {
echo <<<HEREDOC
<table border=0>
	<tr>
		<td>
			<div class="form-control drag-area">
				<div class="icon"><i class="fas fa-cloud-upload-alt"></i></div>
				<header>Drag & Drop to Upload File</header>
				<span>OR</span>
				<button class="btn btn-secondary">Browse File</button>
				<input type="file" hidden>
			</div>
		</td>
		<td>
			<div id="preview"></div>
		</td>
	</td>
</table>
HEREDOC;
}
?>

</div>

<script src="js/custom.js"></script>

<?php
echo $setPollIntervalJS;
?>

</BODY>
</HTML>

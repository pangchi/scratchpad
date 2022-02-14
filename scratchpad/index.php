<?php

function escapeJavaScriptText($string)
{
    return str_replace("\n", '\n', str_replace('"', '\"', addcslashes(str_replace("\r", '', (string)$string), "\0..\37'\\")));
}

$URI = "http://" . gethostname() . $_SERVER['REQUEST_URI'];
$URI = substr($URI, 0, strrpos($URI, "/"));
$SERVER_ADDR = $_SERVER['SERVER_ADDR'];

if ($SERVER_ADDR == "127.0.0.1") {
	$displayBlock = "#clientBtns {display: none;}\n";
	$input_type = "password";
	$input_placeholder = "Text will appear here";
	$setPollIntervalJS = <<<HEREDOC
<SCRIPT>
	const poll = setInterval(fetch, 1000);
</SCRIPT>
HEREDOC;
	 $pre_uri = "Join at ";
}
else {
	$displayBlock = "#hostBtns {display: none;}\n #showPasswordBox {display: none;}\n";	
	$input_type = "text";
	$input_placeholder = "Enter text here";
	$setPollIntervalJS = "";
	$pre_uri = "Connected to ";
}

?>

<HTML>
<HEAD>
<TITLE>Scratchpad</TITLE>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">-->
<link rel="stylesheet" href="css/bootstrap.min.css">
<STYLE>

<?php
echo $displayBlock;
?>

#scratchpad_text, #showPassword {
	font-family: Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif;
	font-size: large;
};
</STYLE>

<SCRIPT>
function send() {
	var value = document.getElementById("scratchpad_text").value;
	update(value);
	dispButtonResponse("Sent");
	setTimeout(clearButtonResponse, 5000);
	startCountDownTimer();
}

function update(value) {
	var xhr = new XMLHttpRequest();
	xhr.open("POST", 'save.php', true);

	//Send the proper header information along with the request
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

	xhr.onreadystatechange = function() { // Call a function when the state changes.
		if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
			// Request finished. Do processing here.
			document.getElementById("debug").innerHTML = xhr.responseText;
		}
	}
	xhr.send("value=" + encodeURIComponent(value) + "&random=" + Math.random());
	// xhr.send(new Int8Array());
}

var timerId = null;
var timerCount;

function startCountDownTimer() {
	if (timerId != null) {
		clearTimeout(timerId);
	}
	timerCount = 20; //cannot be > save.php time
	timerId = setInterval(tick, 1000);
	timerId = timerId;
}

function tick() {
	timerCount--;
	var c = document.getElementById("countdown");

	if (timerCount < 1) {
		clearTimeout(timerId);
		update("");
		document.getElementById("scratchpad_text").value = "";
		c.innerHTML = "";
		timerId = null;
	}
	else {
		c.innerHTML = '<IMG src="xqxmsg.svg" height="' + c.clientHeight + '">' + String(timerCount).padStart(2, '0');
		//document.getElementById("debug").innerHTML = timerCount;
	}
}

var fetchTimeStamp;

function fetch() {
	var xhr = new XMLHttpRequest();
	xhr.open("POST", 'fetch.php', true);

	//Send the proper header information along with the request
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

	xhr.onreadystatechange = function() { // Call a function when the state changes.
		if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
			// Request finished. Do processing here.
			var response = JSON.parse(xhr.responseText);
			document.getElementById("scratchpad_text").value = response.value;
			
			if (response.time != fetchTimeStamp && response.value != "") {
				startCountDownTimer();
				togglePassword(hide = true);
			}
			
			if (response.value == "") {
				document.getElementById("countdown").innerHTML = "";
				togglePassword(hide = true);
			}
			
			fetchTimeStamp = response.time;

		}
	}
	
	contents = "random=" + Math.random();
	xhr.send(contents);
	// xhr.send(new Int8Array());
	// xhr.send(document);
}

function copy() {
    var content = document.getElementById("scratchpad_text").value;

    navigator.clipboard.writeText(content)
        .then(() => {
        console.log("Text copied to clipboard...")
        //update(""); removed since timer implementation
		dispButtonResponse("Copied");
		setTimeout(clearButtonResponse, 5000);
    })
        .catch(err => {
        console.log('Something went wrong', err);
    })
}

function togglePassword(hide = false) {
	var t = document.getElementById("scratchpad_text");
	var s = document.getElementById("showPassword");
	
	if (hide == false && t.type === "password" && t.value != "") {
		t.type = "text";
		s.innerHTML = '<IMG src="showEye.svg"></IMG>';
	}
	
	else {
		t.type = "password";
		s.innerHTML = '<IMG src="hideEye.svg"></IMG>';
	}
}

function dispButtonResponse(text) {
	document.getElementById("buttonresponse").innerHTML = text;
}

function clearButtonResponse() {
	document.getElementById("buttonresponse").innerHTML = "";
}

function submit(e){
    if(e.keyCode === 13){
		send();
    }
}

</SCRIPT>
</HEAD>

<BODY onload="update('')">
<div class="container">
<H1>
Scratchpad
</H1>
<P>
ℹ️ Client must be in same network.<BR>
<?php
echo $setPollIntervalJS;
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
$encodedURI = urlencode($URI);
echo <<<HEREDOC
<P> 
<img 
src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$encodedURI&choe=UTF-8" title="Link to this page" />
</P>
HEREDOC;
}
?>
</div>

<SCRIPT>
window.addEventListener('beforeunload', function (e) {
	update("");
	//e.preventDefault();
	//e.returnValue = '';
});
</SCRIPT>

</BODY>
</HTML>

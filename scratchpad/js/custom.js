function send() {
	var value = document.getElementById("scratchpad_text").value;
	update(value);
	dispButtonResponse("Sent");
	setTimeout(clearButtonResponse, 5000);
	startCountDownTimer();
}

function update(value, clear = false) {
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
	xhr.send("value=" + encodeURIComponent(value) + "&clear=" + encodeURIComponent(clear) + "&random=" + Math.random());
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
		update("", true);
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

function fetchContents() {
	var xhr = new XMLHttpRequest();
	xhr.open("POST", 'fetchContents.php', true);

	//Send the proper header information along with the request
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

	xhr.onreadystatechange = function() { // Call a function when the state changes.
		if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
			// Request finished. Do processing here.
			var response = JSON.parse(xhr.responseText);
			document.getElementById("scratchpad_text").value = response.value;
			
			//document.getElementById("debug").innerHTML = response.files.length;
			
			let picturesNewHTML = "";
			response.files.forEach((item) => {
				picturesNewHTML = picturesNewHTML + `<IMG src='files/` + item + `' style='height:300px' />`; 
			});
			
			if (document.getElementById("pictures").innerHTML == "" && response.files.length > 0) {
				startCountDownTimer();
			}
			
			document.getElementById("pictures").innerHTML = picturesNewHTML;
			
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

window.addEventListener('beforeunload', function (e) {
	update("");
	//e.preventDefault();
	//e.returnValue = '';
});

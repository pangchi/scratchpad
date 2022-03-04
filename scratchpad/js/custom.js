function send() {
	var value = document.getElementById("scratchpad_text").value;
	update(value);
	dispButtonResponse("Sent");
	setTimeout(clearButtonResponse, 5000);
	startCountDownTimer();
}

async function update(value, clear = false) {
	
	var data = new FormData();
	data.append('value', value);
	data.append('clear', clear);
	data.append('random', Math.random());

	await fetch('save.php', {
		method:'POST',
		body: data
	}).then(function(r) {
		if (r.status != 200) {
			return;
		}
		else return r.text().then(function(text) {
			//document.getElementById("debug").innerHTML = text;
		});
	});
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

async function fetchContents() {
	var data = new FormData();
	data.append('random', Math.random());
	
	await fetch('fetchContents.php', {
		method:'POST',
		body: data
	}).then(function(r) {
		if (r.status != 200) {
			return;
		}
		else return r.text().then(function(text) {
			var res = JSON.parse(text);
			document.getElementById("scratchpad_text").value = res.value;
			
			//document.getElementById("debug").innerHTML = res.files.length;
			
			let picturesNewHTML = "";
			res.files.forEach((item) => {
				picturesNewHTML = picturesNewHTML + `<A HREF='files/` + item + `' download><IMG src='files/` + item + `' style='height:300px' /></A>`; 
			});
			
			if (document.getElementById("pictures").innerHTML == "" && res.files.length > 0) {
				startCountDownTimer();
			}
			
			document.getElementById("pictures").innerHTML = picturesNewHTML;
			
			if (res.time != fetchTimeStamp && res.value != "") {
				startCountDownTimer();
				togglePassword(hide = true);
			}
			
			if (res.value == "") {
				document.getElementById("countdown").innerHTML = "";
				togglePassword(hide = true);
			}
			
			fetchTimeStamp = res.time;
		});
	});
	
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

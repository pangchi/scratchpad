//Credit Miguel Nunez https://medium.com/@iamcodefoxx
//selecting all required elements
const dropArea = document.querySelector(".drag-area"),
dragText = dropArea.querySelector("header"),
button = dropArea.querySelector("button"),
input = dropArea.querySelector("input");
let file; //this is a global variable and we'll use it inside multiple functions

button.onclick = ()=>{
  input.click(); //if user click on the button then the input also clicked
}

input.addEventListener("change", function(){
  //getting user select file and [0] this means if user select multiple files then we'll select only the first one
  file = this.files[0];
  //dropArea.classList.add("active");
  uploadFile();
  //showFile(); //calling function
});

//If user Drag File Over DropArea
dropArea.addEventListener("dragover", (event)=>{
  event.preventDefault(); //preventing from default behaviour
  //dropArea.classList.add("active");
  dragText.textContent = "Release to Upload File";
});

//If user leave dragged File from DropArea
dropArea.addEventListener("dragleave", ()=>{
  dropArea.classList.remove("active");
  dragText.textContent = "Drag & Drop to Upload File";
});

//If user drop File on DropArea
dropArea.addEventListener("drop", (event)=>{
  event.preventDefault(); //preventing from default behaviour
  //getting user select file and [0] this means if user select multiple files then we'll select only the first one
  file = event.dataTransfer.files[0];
  uploadFile();
  //showFile(); //calling function
});

async function uploadFile() {     
	var data = new FormData()
	data.append('file', file)
	await fetch('uploadFiles.php', {
	method: 'POST',
	body: data
	});
	let text = document.getElementById("scratchpad_text").value;
	update(text);
	startCountDownTimer();
}


function showFile(){
  let fileType = file.type; //getting selected file type
  let preview = document.querySelector('#preview');
  let fileReader = new FileReader(); //creating new FileReader object
    /*fileReader.onload = ()=>{
      let fileURL = fileReader.result; //passing user file source in fileURL variable
        // UNCOMMENT THIS BELOW LINE. I GOT AN ERROR WHILE UPLOADING THIS POST SO I COMMENTED IT
      // let imgTag = `<img src="${fileURL}" alt="image">`; //creating an img tag and passing user selected file source inside src attribute
      dropArea.innerHTML = imgTag; //adding that created img tag inside dropArea container
    }*/

	fileReader.addEventListener("load", function () {		
		var image = new Image();
		image.height = 300;
		image.title = file.name;
		image.src = this.result;
		preview.appendChild( image );
    }, false);

    fileReader.readAsDataURL(file);	
}


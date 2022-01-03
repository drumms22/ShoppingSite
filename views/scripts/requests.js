
document.getElementById("contactForm").onsubmit = contactFromData;
function contactFromData(e) {
  e.preventDefault();
  let request = document.getElementById("request");
  let email = document.getElementById("email");
  let firstname = document.getElementById("firstname");
  let lastname = document.getElementById("lastname");
  let message = document.getElementById("message");
  let data = {
    request: request.value,
    email: email.value,
    firstname: firstname.value,
    lastname: lastname.value,
    message: message.value,
    request: "user/contact"
  }; 
   
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "http://localhost/cis-222/p1/api.php");
  xhr.onload = function () {
    let res = JSON.parse(this.responseText);

    email.value = "";
    firstname.value = "";
    lastname.value = "";
    message.value = "";
    if (this.status === 200) {

        document.getElementById("contactMessage").innerHTML = "<p>" + res.success[0].message + "</p>";
        document.getElementById("contactMessage").style.color = "green";
        

    }else{

        for(let i = 0;i < res.errors.length;i++){

            document.getElementById("contactMessage").innerHTML += "<p>" + res.errors[i].message + "</p>";

        }
        document.getElementById("contactMessage").style.color = "red";

    }
  };

  xhr.send(JSON.stringify(data));
}


// function checkInputs(inputs){
//     let isValid = {};

//     for(let i = 0;i < inputs.length;i++){
//         let id = document.getElementById(inputs[i].id);
//        //console.log(document.getElementById(inputs[i].id).value);
//        let name = inputs[i].id
//         isValid[id] = id.value;

//     }
//     console.log(isValid);
//     return isValid;
// }


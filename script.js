let name = "";
let email;
let uin;



function checkPassword(){
    const password = document.getElementById("password").value;
    const confirm = document.getElementById("password_confirm").value;

    console.log(password);
        console.log(confirm);

    if (password !== confirm) {
        alert("Passwords do not match!");
        return false;   
    }

    return true;       
}

const displayInfo = document.getElementById("displayInfo");
const innerBody = document.getElementById("boody");

const userData = document.createElement("div");


userData.setAttribute("id", "userData");
let disOnAndOff = 0;

displayInfo.addEventListener('click', async () => {
    if(disOnAndOff == 0){
        await fetch("getUserData.php")
        .then(res => res.json())
        .then(data => {
            name = data[0] + " " + data[1];
            email = data[2];
            uin = data[3];      
        });

        innerBody.appendChild(userData);
        userData.textContent = "Name: "+ name + "\n Email: " + email + "\n  UIN: " + uin;
        disOnAndOff = 1;
    } else {
        disOnAndOff = 0;
        innerBody.removeChild(userData);
    }
});


const changeInfo = document.getElementById("changeInfo");
const changeData = document.createElement("form");
const changeFirst = document.createElement("input");
const changeLast = document.createElement("input");
const changePassword = document.createElement("input");
const changeUIN = document.createElement("input");
const first = document.createElement("lable");
const last = document.createElement("lable");

const cpassword = document.createElement("lable");
const cu = document.createElement("lable");
const submit = document.createElement("button");


changeData.setAttribute("action", "/user.php");
changeData.setAttribute("method", "POST");
changeData.setAttribute("id", "changeData");
submit.setAttribute("id", "submit");

changeFirst.setAttribute("type", "first");
changeLast.setAttribute("type", "last");
changePassword.setAttribute("type", "password");
changeUIN.setAttribute("type", "uin");
changeFirst.setAttribute("name", "first");
changeLast.setAttribute("name", "last");
changePassword.setAttribute("name", "password");
changeUIN.setAttribute("name", "uin");

let changeOnAndOff = 0;
first.style.whiteSpace = "pre-line";
first.textContent = "Leave Blank to keep the same \n Change First:";
last.style.whiteSpace = "pre-line";
last.textContent = " Change Last:";
cpassword.textContent = "Change Password:";
cu.textContent = "Change UIN:"

submit.textContent = "Submit";
submit.setAttribute('type', 'submit');
changeData.appendChild(first);
changeData.appendChild(changeFirst);
changeData.appendChild(last);
changeData.appendChild(changeLast);
changeData.appendChild(cpassword);
changeData.appendChild(changePassword);
changeData.appendChild(cu);
changeData.appendChild(changeUIN);
changeData.appendChild(submit);


changeInfo.addEventListener('click', async () => {
    if(changeOnAndOff == 0){


        innerBody.appendChild(changeData);
        changeOnAndOff = 1;
    } else {

        innerBody.removeChild(changeData);
        changeOnAndOff = 0;
    }
});


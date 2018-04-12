/* Lab 5 JavaScript File 
   Place variables and functions in this file */

function validateSignIn(formObj) {
  // put your validation code here
  // it will be a series of if statements
  
  if (formObj.my_username.value == "") {
    alert("You must enter a username");
    formObj.my_username.focus();
    return false;
  }
  if (formObj.my_password.value == "") {
    alert("You must enter a password");
    formObj.my_password.focus();
    return false;
  }
  alert("User " + my_username.value + " has successfully signed in.");
  alert("Actually not really...");
  return true;
}

function validateSignUp(formObj) {
  // put your validation code here
  // it will be a series of if statements
  
  if (formObj.new_username.value == "") {
    alert("You must enter a username");
    formObj.new_username.focus();
    return false;
  }
  if (formObj.new_password.value == "") {
    alert("You must enter a password");
    formObj.new_passeword.focus();
    return false;
  }
  if (formObj.new_email.value == "") {
    alert("You must enter a valid email");
    formObj.new_email.focus();
    return false;
  }
  if (formObj.year.value == "") {
    alert("Please enter your current year");
    formObj.year.focus();
    return false;
  }
  if (formObj.subject.value == "") {
    alert("Please enter your subject(s)");
    formObj.subject.focus();
    return false;
  }
  if (formObj.description.value == "") {
    alert("Please enter a fitting description of yourself");
    formObj.description.focus();
    return false;
  }
  if (formObj.student.value == false && formObj.tutor.value == false) {
    alert("Please choose one or both positions");
    return false;
  }
  alert("New User " + new_username.value + "has successfully signed up.");
  alert("Actually not really...");
  return true;
}


function clearContents(element){
	if(element.value == "Please enter your comments"){
		element.value = '';
	}
}

function blankRefill(element){
	if(element.value == ''){
		element.value = "Please enter your comments";
	}
}


//



///////////////////////////// Collapse //////////////////////
function redBorder(id) { // activate red border w/ id
    let elem = document.getElementById(id);
    elem.removeAttribute("class");
    elem.setAttribute("class", "form-control border-danger");
}

function removeRedBorder(id) { // remove red border w/ id
    let elem = document.getElementById(id);
    elem.removeAttribute("class");
    elem.setAttribute("class", "form-control");
}

function showMsg(id) { // show collapse msg w/ id
    let elementCol = document.getElementById(id);
    let myCollapse = new bootstrap.Collapse(elementCol);

    myCollapse.show();
}

function hideMsg(id) { // hide collapsed messages w/ id
    let element = document.getElementById(id);
    let myCollapse = new bootstrap.Collapse(element);

    myCollapse.hide();
}

function fillAllFieldsLogin() {
    hideSpinner("mySpinner");
    redBorder("login-user");
    redBorder("login-password");
    if(!$('#pass-collapse').is('.collapse.show')) {
        showMsg("pass-collapse");
    }
}

function clearAllFields() {
    removeRedBorder("login-user");
    removeRedBorder("login-password");
    // hideMsg("pass-collapse");
}



//////////////////////////// log in ////////////////////////////////
function signUp() {
    $("#myModal").modal('hide');
    $("#myModal2").modal('show');
}

function showModal() {
    $(document).ready(function(){
        $("#myModal").modal('show');
    });
}

function checkResponse(data) {
    // console.log(data);
    switch (data) {
        case "userErr":
            hideSpinner("mySpinner");
            redBorder("login-user");
            redBorder("login-password");
            showMsg("pass-collapse");
            document.getElementById("btn-login").removeAttribute("disabled");
            grecaptcha.reset();
        break;
        case "passErr":
            redBorder("login-user");
            hideSpinner("mySpinner");
            redBorder("login-password");
            showMsg("pass-collapse");
            document.getElementById("btn-login").removeAttribute("disabled");
            grecaptcha.reset();
        break;
        case "reCAPTCHA verification failed. Try again.":
            hideSpinner("mySpinner");
            errPopup("Something went wrong.");
            document.getElementById("btn-login").removeAttribute("disabled");
            grecaptcha.reset();
        break;
        case "Successful":
            document.getElementById("login-user").setAttribute("disabled", "");
            document.getElementById("login-password").setAttribute("disabled", "");
            document.getElementById("login-checkbox").setAttribute("disabled", "");
            document.getElementById("signup-link").setAttribute("style", "pointer-events: none;");
        break;
        case "no more attempts":
            hideSpinner("mySpinner");
            loginErr();
            document.getElementById("login-user").setAttribute("disabled", "");
            document.getElementById("login-password").setAttribute("disabled", "");
            document.getElementById("login-checkbox").setAttribute("disabled", "");
        break;
        case false:
            console.log("No response.");
        break;
    }
}

function loadUser(captchaResponse) { // second
    let userIn = document.getElementById("login-user").value
    let passIn = document.getElementById("login-password").value

    const postParameters =  new URLSearchParams();
    postParameters.append("function", "login");
    postParameters.append("user", userIn);
    postParameters.append("password", passIn);
    postParameters.append("g-recaptcha-response", captchaResponse);

    $.ajax({
        url: "http://localhost:3000/final/request.php",
        type: "POST",
        data: postParameters.toString(),
        success: function(result) {
            // console.log(result);
            const newResult = JSON.parse(result);
            if (newResult == "You are blocked") {
                errPopup(newResult);
            } else {
                checkResponse(newResult);
                if (newResult == 'Successful') {
                    location.replace("http://localhost:3000/final/home.php");
                }
                // console.log(newResult);
            }
            
        },
        error: function(xhr, status, error) {
            console.log("Error: " + error);
        },
    });
}

function showSpinner(id) {
    let spinner = document.getElementById(id);
    spinner.removeAttribute("hidden");
}
function hideSpinner(id) {
    let spinner = document.getElementById(id);
    spinner.setAttribute("hidden", "");
}

function logIn() { // first
    // Perform reCAPTCHA verification
    const recaptchaResponse = grecaptcha.getResponse();
    // console.log(recaptchaResponse); // test
    if (recaptchaResponse === '') {
        completeRecaptcha();
    } else {
        // If reCAPTCHA is completed, submit the form
        showSpinner("mySpinner");
        clearAllFields();
        if (document.getElementById("login-user").value == "" && document.getElementById("login-password").value == "") {
            fillAllFieldsLogin();
        } else {
            if($('#pass-collapse').is('.collapse.show')) {
                showMsg("pass-collapse");
            }
            document.getElementById("btn-login").setAttribute("disabled", "");
            loadUser(recaptchaResponse);
        }
    }
}

function showPassword(id) {
    var elem = document.getElementById(id);
    if (elem.type === "password") {
        elem.type = "text";
    } else {
        elem.type = "password";
    }
}



//////////////// Sign up //////////////////////////////
function clearAllSignUpFields() {
    removeRedBorder("signup-firstName");
    removeRedBorder("signup-lastName");
    removeRedBorder("signup-mail");
    removeRedBorder("signup-username");
    removeRedBorder("signup-password");
}

function fillAllSignUp() {
    hideSpinner("mySpinner2");
    redBorder("signup-firstName");
    redBorder("signup-lastName");
    redBorder("signup-mail");
    redBorder("signup-username");
    redBorder("signup-password");
    if(!$('#fname-collapse').is('.collapse.show')) {
            showMsg("fname-collapse");
    }
    if(!$('#lname-collapse').is('.collapse.show')) {
        showMsg("lname-collapse");
    }
    if(!$('#username-collapse').is('.collapse.show')) {
        showMsg("username-collapse");
    }
    if(!$('#pass2-collapse').is('.collapse.show')) {
        showMsg("pass2-collapse");
    }
}

function checkSignUpResponse(data) {
    // console.log(data);
    switch (data) {
        case "nameErr":
            hideSpinner("mySpinner2");
            redBorder("signup-firstName");
            showMsg("fname-collapse");
            document.getElementById("btn-signup").removeAttribute("disabled");
        break;
        case "surnameErr":
            hideSpinner("mySpinner2");
            redBorder("signup-lastName");
            showMsg("lname-collapse");
            document.getElementById("btn-signup").removeAttribute("disabled");
        break;
        case "emailErr":
            hideSpinner("mySpinner2");
            redBorder("signup-mail");
            showMsg("username-collapse");
            document.getElementById("btn-signup").removeAttribute("disabled");
        break;
        case "This E-mail is already in use.":
            hideSpinner("mySpinner2");
            redBorder("signup-mail");
            showMsg("username-collapse");
            document.getElementById("btn-signup").removeAttribute("disabled");
            // errPopup(data); // check does occur
        break;
        case "passErr":
            hideSpinner("mySpinner2");
            redBorder("signup-password");
            showMsg("pass2-collapse");
            document.getElementById("btn-signup").removeAttribute("disabled");
        break;
        case "userErr":
            hideSpinner("mySpinner2");
            redBorder("signup-username");
            showMsg("username-collapse");
            document.getElementById("btn-signup").removeAttribute("disabled");
        break;
        case "This username already exists.":
            hideSpinner("mySpinner2");
            redBorder("signup-username");
            showMsg("username-collapse");
            document.getElementById("btn-signup").removeAttribute("disabled");
            // errPopup(data); // check does occur
        break;
        case 1:
            // loginSuccessSweetAlert();
            document.getElementById("signup-firstName").setAttribute("disabled", "");
            document.getElementById("signup-lastName").setAttribute("disabled", "");
            document.getElementById("signup-mail").setAttribute("disabled", "");
            document.getElementById("signup-username").setAttribute("disabled", "");
            document.getElementById("signup-password").setAttribute("disabled", "");
            document.getElementById("signup-checkbox").setAttribute("disabled", "");
            location.replace('http://localhost:3000/Final/pin.php');
            // document.getElementById("btn-back").setAttribute("style", "pointer-events: none;");
        break;
    }
}

function saveUser() { // captchaResponse
    let fname = document.getElementById("signup-firstName").value
    let lname = document.getElementById("signup-lastName").value
    let email = document.getElementById("signup-mail").value
    let username = document.getElementById("signup-username").value
    let password = document.getElementById("signup-password").value

    const postParameters =  new URLSearchParams();
    postParameters.append("function", "signup");
    postParameters.append("firstName", fname);
    postParameters.append("lastName", lname);
    postParameters.append("email", email);
    postParameters.append("username", username);
    postParameters.append("password", password);
    // postParameters.append("g-recaptcha-response", captchaResponse);

    $.ajax({
        url: "http://localhost:3000/final/request.php",
        type: "POST",
        data: postParameters.toString(),
        success: function(result) {
            const newResult = JSON.parse(result);
            checkSignUpResponse(newResult);
            // console.log(newResult);
        },
        error: function(xhr, status, error) {
            console.log("Error: " + error);
        },
    });
}

function signUpForm() {
    // const recaptchaResponseSign = grecaptcha.getResponse();
    const pass1 = document.getElementById('signup-password').value;
        const pass2 = document.getElementById('retype-password').value;
        if (pass1 !== pass2) {
            errPopup("Please type your password again.");
        } else {
            if($('#fname-collapse').is('.collapse.show')) {
                showMsg("fname-collapse");
            }
            if($('#lname-collapse').is('.collapse.show')) { 
                showMsg("lname-collapse");
            }
            if($('#email-collapse').is('.collapse.show')) { 
                showMsg("email-collapse");
            }
            if($('#username-collapse').is('.collapse.show')) {
                showMsg("username-collapse");
            }
            if($('#pass2-collapse').is('.collapse.show')) {
                showMsg("pass2-collapse");
            }
            document.getElementById("btn-signup").setAttribute("disabled", "");
            saveUser();
        }
    // if (recaptchaResponseSign === '') {
    //     completeRecaptcha();
    // } else {
    //     showSpinner("mySpinner2");
    //     clearAllSignUpFields();
    //     if (document.getElementById("signup-firstName").value == "" && document.getElementById("signup-lastName").value == "" && document.getElementById("signup-mail").value == "" && document.getElementById("signup-username").value == "" && document.getElementById("signup-password").value == "") {
    //         fillAllSignUp();
    //     } else {
    //         if($('#fname-collapse').is('.collapse.show')) {
    //             showMsg("fname-collapse");
    //         }
    //         if($('#lname-collapse').is('.collapse.show')) { 
    //             showMsg("lname-collapse");
    //         }
    //         if($('#email-collapse').is('.collapse.show')) { 
    //             showMsg("email-collapse");
    //         }
    //         if($('#username-collapse').is('.collapse.show')) {
    //             showMsg("username-collapse");
    //         }
    //         if($('#pass2-collapse').is('.collapse.show')) {
    //             showMsg("pass2-collapse");
    //         }
    //         document.getElementById("btn-signup").setAttribute("disabled", "");
    //         saveUser(recaptchaResponseSign);
    //     }
    // }
}



////////////////// profile /////////////////////
function createImageTag(src, altText, id) { // create image tag
    // width="50" height="50" class="rounded-circle bg-light img-thumbnail"
    const imgElement = document.createElement('img');
    imgElement.src = src;
    imgElement.alt = altText;
    imgElement.classList.add('bg-light', 'profile-image');
    return imgElement;
}

function setProfileImg(img, id) {
      const imageUrl = 'https://example.com/image.jpg'; // Replace with your image URL
      const altText = 'An example image'; // Replace with appropriate alt text
      const imageTag = createImageTag(imageUrl, altText);
      
      document.body.appendChild(imageTag); // Appending the image to the body
}


///////////////// posts ///////////////////////////////
function sendPost() {
    const image = document.getElementById("post-img").files[0];
    const titlePost = document.getElementById("title-input").value;
    const msgPost = document.getElementById("msg-input").value;

    // create a new formData object to send the image data
    const formData = new FormData();
    formData.append("function", "submit");
    formData.append("title", titlePost);
    formData.append("message", msgPost);
    formData.append("image", image);

    // Send the image data to the PHP server using AJAX
    fetch("http://localhost:3000/Final/request.php", {
        method: "POST",
        body: formData,
    })
        .then((response) => response.json())
        .then((data) => {
        // Display the uploaded image on the web page
        // console.log(data);
        if (data == "Image uploaded successfully.") {
            document.getElementById("uploadform").reset();
            loadAllPosts();
            location.reload();
        }
        })
        .catch((error) => console.error("Error uploading post: ", error));
}


/**
 * fetches all rows for table
 */
function loadAllPosts() {
    $.ajax({
        url: 'http://localhost:3000/final/request.php?type=all',
        success: function(data) {
            document.getElementById("content").replaceWith(createTableFromObjects(JSON.parse(data)));
        },
        error: function(xhr, status, error) {
            console.log("Error: " + error);
        }
    });
}


function createCard(img, title, msg, time) {
    // Create card elements

    const cardRow = document.createElement("div");
    cardRow.classList.add('row');

    const card = document.createElement('div');
    card.classList.add('col', 'center-container');

    const innerCard = document.createElement('div');
    innerCard.classList.add('card', 'shadow-sm', 'text-white', 'bg-dark');
    innerCard.setAttribute("style", "width: 35rem");

    if (img !== '') {
        const cardImage = document.createElement("img");
        cardImage.setAttribute("src", "data:image/jpeg;base64,".concat(img));
        cardImage.classList.add('rounded');
        innerCard.appendChild(cardImage);
    }

    const cardBody = document.createElement('div');
    cardBody.classList.add('card-body');

    const cardTitle = document.createElement('h5');
    cardTitle.classList.add('card-title');
    cardTitle.textContent = title;

    const cardContent = document.createElement('p');
    cardContent.classList.add('card-text');
    cardContent.textContent = msg;

    const cardTimeStamp = document.createElement('div');
    cardTimeStamp.classList.add('card-footer');
    cardTimeStamp.textContent = time;

    // Assemble card elements
    cardBody.appendChild(cardTitle);
    cardBody.appendChild(cardContent);
    cardBody.appendChild(cardTimeStamp);

    //
    innerCard.appendChild(cardBody);
    
    card.appendChild(innerCard);
    cardRow.appendChild(card);

    return cardRow;
}


/**
 * returns a table
 * @param {Array} data 
 * @returns 
 */
function createTableFromObjects(data) {
    table = document.createElement('table');
    table.setAttribute("id", "person");
    att = document.createAttribute("class");
    att.value = "table table-dark table-hover";
    table.setAttributeNode(att);
    tableBody = document.createElement('tbody');
    headerRow = document.createElement('tr');
    headerRow.setAttribute("id", "headerRow");

    // Create table data rows
    if (Array.isArray(data) && data.length) {
        for (obj of data) {
            dataRow = document.createElement('tr');
            dataCell = document.createElement('td');
            myCard = createCard(obj[0], obj[1], obj[2], obj[3]);
            // create profile header
            profileHeader = document.createElement("a");
            profileHeader.setAttribute('href', '#');
            profileHeader.classList.add('p-2', 'd-flex', 'align-items-center', 'text-outline-primary', 'text-decoration-none');
            //create profile img 
            profilePic = document.createElement('img');
            profilePic.setAttribute("src", "data:image/jpeg;base64,".concat(obj[5]));
            profilePic.setAttribute('alt', 'profile_picture');
            profilePic.setAttribute('width', '45');
            profilePic.setAttribute('height', '45');
            profilePic.classList.add('rounded-circle');
            // create profile username
            profileName = document.createElement('span');
            profileName.classList.add('d-none', 'd-sm-inline', 'mx-1');
            let textNode= document.createTextNode(obj[4]);
            profileName.appendChild(textNode);
            //append photo and name
            profileHeader.appendChild(profilePic);
            profileHeader.appendChild(profileName);
            // append to table element
            dataCell.appendChild(profileHeader);
            dataCell.appendChild(myCard);
            dataRow.appendChild(dataCell);
            tableBody.appendChild(dataRow);
        }
    } else {
        dataRow = document.createElement('tr');
        dataCell = document.createElement('td');
        dataCell.textContent = "No Results found";
        dataCell.setAttribute("colspan","auto");
        dataRow.appendChild(dataCell);
        tableBody.appendChild(dataRow);
    }

    table.appendChild(tableBody);
    return table;
}



/////////////////////////// profile ///////////////////////////
function checkChangeFields(name, surname, email, user, password) {
    if (name.trim() == "" || surname.trim() == "" || email.trim() == "" || user.trim() == "" || password.trim() == "") {
        return true;
    } else {
        return false;
    }
}

function receivedProfile(obj) { // not in use
    if (obj != "err") {
        //
    } else {
        errPopup("Something went wrong.");
    }
}

function fetchProfilePicture() { 
    const postParameters =  new URLSearchParams();
    postParameters.append("function", "getProfilePicture");

    $.ajax({
        url: "http://localhost:3000/final/request.php",
        type: "POST",
        data: postParameters.toString(),
        success: function(result) {
            console.log(result);
            const newResult = JSON.parse(result);
            text = 'data:image/jpeg;base64,';
            text2 = text.concat(newResult);
            imgElem = document.getElementById('img-loggedin');
            imgElem.removeAttribute('src');
            imgElem.setAttribute('src', text2);
            console.log(newResult);
            // receivedProfile(newResult);
        },
        error: function(xhr, status, error) {
            console.log("Error: " + error);
            console.log("Error here");
        },
    });
}

function saveProfile() { // change profile
    const image = document.getElementById("change-profileImage").files[0];
    
    const fname = document.getElementById("change-name").value;
    const lname = document.getElementById("change-surname").value;
    const email = document.getElementById("change-email-address").value;
    const username = document.getElementById("change-username").value;
    const password = document.getElementById("change-password").value;

    if (checkChangeFields(fname, lname, email, username, password)) {
        errPopup("Something went wrong. Please try again.");
    } else {
        // create a new formData object to send the image data
        const formData = new FormData();
        formData.append("function", "changeProfile");
        formData.append("name", fname);
        formData.append("surname", lname);
        formData.append("email", email);
        formData.append("username", username);
        formData.append("password", password);
        formData.append("image", image);

        // Send the image data to the PHP server using AJAX
        fetch("http://localhost:3000/Final/request.php", {
            method: "POST",
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
            // Display the uploaded image on the web page
            // console.log(data);
            if (data === "success") {
                location.replace("http://localhost:3000/final/home.php");
            } else {
                errPopup(data);
                console.log(data);
            }
            })
            .catch((error) => console.error("Error uploading post: ", error));
    }
}



///////////////////////////// sweet alert //////////////////////////
// function loginSuccessSweetAlert() {
//     const Toast = Swal.mixin({
//         toast: true,
//         position: 'top-end',
//         showConfirmButton: false,
//         timer: 2000,
//         timerProgressBar: true,
//         didOpen: (toast) => {
//           toast.addEventListener('mouseenter', Swal.stopTimer)
//           toast.addEventListener('mouseleave', Swal.resumeTimer)
//         }
//       })
      
//       Toast.fire({
//         icon: 'success',
//         title: 'Welcome!'
//       })
// }

// function postSuccess() {
//     const Toast = Swal.mixin({
//         toast: true,
//         position: 'top-end',
//         showConfirmButton: false,
//         timer: 2000,
//         timerProgressBar: true,
//         didOpen: (toast) => {
//           toast.addEventListener('mouseenter', Swal.stopTimer)
//           toast.addEventListener('mouseleave', Swal.resumeTimer)
//         }
//       })
      
//       Toast.fire({
//         icon: 'success',
//         title: 'Post uploaded successfully!'
//       })
// }

function errPopup(msg) {
    text = "Sorry, ";
    newMsg = text.concat(msg);
    Swal.fire({
        icon: 'error',
        title: newMsg
      })
}

function loginErr() {
    Swal.fire({
        icon: 'error',
        title: 'Oops! There was a problem signing in.',
        html: 'whatever'
      })
}

function signOutSwal() {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: 'btn btn-primary',
          cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
      })
      
      swalWithBootstrapButtons.fire({
        title: 'Are you sure you want to Sign out?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: "Yes, I'm sure",
        cancelButtonText: 'Cancel',
        reverseButtons: false
      }).then((result) => {
        if (result.isConfirmed) {
            location.replace("http://localhost:3000/final/log_out.php");
        } 
      })
}

function discardChange() {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: 'btn btn-primary',
          cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
      })
      
      swalWithBootstrapButtons.fire({
        title: 'Are you sure you want to cancel?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: "Yes, I'm sure",
        cancelButtonText: 'Cancel',
        reverseButtons: false
      }).then((result) => {
        if (result.isConfirmed) {
            location.replace("http://localhost:3000/final/home.php");
        } 
        })
}

function saveChange() { // change profile
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: 'btn btn-primary',
          cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
      })
      
      swalWithBootstrapButtons.fire({
        title: 'Are you sure you want to save changes?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: "Yes, I'm sure",
        cancelButtonText: 'Cancel',
        reverseButtons: false
      }).then((result) => {
        if (result.isConfirmed) {
            pass1 = document.getElementById('change-password').value;
            pass2 = document.getElementById('match-change-password').value;
            if (pass1 == pass2) {
                saveProfile();
            } else {
                errPopup("Please type your password again.");
            }
        }
      })
}

function completeRecaptcha() {
    Swal.fire({
        title: 'Oops! Please complete the reCAPTCHA.',
        icon: 'info',
        showCancelButton: false,
        confirmButtonColor: '#3085d6'
    })
}

function forgotPass() {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })
      
    swalWithBootstrapButtons.fire({
    title: 'Should we email you an OTP?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: "Yes please",
    cancelButtonText: 'No thanks',
    reverseButtons: false
    }).then((result) => {
        if (result.isConfirmed) {
            location.replace("http://localhost:3000/Final/email.php");
        }
    })
}

function backToLogin() { // go back to login page
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })
      
    swalWithBootstrapButtons.fire({
    title: 'Do you want to go back to the login form?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: "Yes",
    cancelButtonText: 'No',
    reverseButtons: false
    }).then((result) => {
        if (result.isConfirmed) {
            location.replace("http://localhost:3000/Final/login.php");
        }
    })
}

function pinEmail() { //forgot password
    const emailInput = document.getElementById('email-pin').value;
    let email = emailInput.trim();
    if (email == "" || email ==" ") {
        errPopup('Please enter your email address.');
        return;
    }
    let text = "would you like us to send the OTP to: ".concat(email);
    let finalText = text.concat("?");

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })
      
    swalWithBootstrapButtons.fire({
    title: finalText,
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: "Yes",
    cancelButtonText: 'No',
    reverseButtons: false
    }).then((result) => {
        if (result.isConfirmed) {
            const postParameters =  new URLSearchParams();
            postParameters.append("function", "EmailPin");
            postParameters.append("email", email);

            $.ajax({
                url: "http://localhost:3000/final/request.php",
                type: "POST",
                data: postParameters.toString(),
                success: function(result) {
                    const newResult = JSON.parse(result);
                    if (newResult === "Please enter a valid email address") {
                        errPopup(newResult);
                    } else {
                        signInPin(newResult);
                    }
                    console.log(newResult);
                },
                error: function(xhr, status, error) {
                    console.log("Error: " + error);
                },
            });
        }
    })
}

function isCorrectPin() { // signup pin
    const PinInput = document.getElementById('OTP').value;
    let pin = PinInput.trim();
    if (pin == "" || pin ==" ") {
        errPopup('Please enter your pin.');
        return;
    }
      
    const postParameters =  new URLSearchParams();
    postParameters.append("function", "SIgnUpPin");
    postParameters.append("pin", pin);

    $.ajax({
        url: "http://localhost:3000/final/request.php",
        type: "POST",
        data: postParameters.toString(),
        success: function(result) {
            const newResult = JSON.parse(result);
            if (newResult === "Successful") {
                location.reload('http://localhost:3000/final/login.php');
            } else {
                errPopup("Incorrect pin");
            }
            console.log(newResult);
        },
        error: function(xhr, status, error) {
            console.log("Error: " + error);
        },
    });
}

function pinSignUp() { //verify user sign up
    const email = document.getElementById('signup-mail').value;
    const fname = document.getElementById('signup-firstName').value;
    const lname = document.getElementById('signup-lastName').value;

    $.ajax({
        url: "http://localhost:3000/final/request.php",
        type: "POST",
        data: postParameters.toString(),
        success: function(result) {
            const newResult = JSON.parse(result);
            if (newResult === "Please enter a valid email address") {
                errPopup(newResult);
            } else {
                signInPin(newResult);
            }
            console.log(newResult);
        },
        error: function(xhr, status, error) {
            console.log("Error: " + error);
        },
    });
}

function signInPin(msg) {
    (async () => {

        const { value: pin } = await Swal.fire({
          title: msg,
          input: 'text',
          inputPlaceholder: 'Enter OTP here'
        })
        
        if (pin) {
            const postParameters =  new URLSearchParams();
            postParameters.append("function", "pin");
            postParameters.append("pin", pin);

            $.ajax({
                url: "http://localhost:3000/final/request.php",
                type: "POST",
                data: postParameters.toString(),
                success: function(result) {
                    const newResult = JSON.parse(result);
                    if (newResult == 'Successful') {
                        location.replace("http://localhost:3000/final/home.php");
                    } else {
                        errPopup("something went wrong.");
                    }
                    console.log(newResult);
                },
                error: function(xhr, status, error) {
                    console.log("Error: " + error);
                },
            });
        }
        
    })()
}

function successPopup(msg) {
    text = "Success! ";
    newMsg = text.concat(msg);
    Swal.fire({
        icon: 'success',
        title: newMsg
      })
}
let CURRENT_EVENT_ID = null;
var fades = document.querySelectorAll(".fade-in");
window.addEventListener("load", function () {
    for (var i = 0; i < fades.length; i++) {
        fades[i].classList.add("visible");
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('joinForm');
    if (form) {
        form.addEventListener('submit', submitForm);
    }
});
function openEvent(title, location, date, description, id) {
    CURRENT_EVENT_ID = id;
    console.log("🔵 Event opened, ID =", CURRENT_EVENT_ID);

    document.getElementById("eventList").style.display = "none";
    document.getElementById("eventDetails").style.display = "block";

    document.getElementById("detailTitle").innerHTML = title;
    document.getElementById("detailLocation").innerHTML = location;
    document.getElementById("detailDate").innerHTML = date;
    document.getElementById("detailDescription").innerHTML = description;
    document.getElementById("event_id").value = id;
    
}


function goBack() {
    document.getElementById("eventDetails").style.display = "none";
    document.getElementById("eventList").style.display = "block";
    document.getElementById("confirmation").innerHTML = "";
}

function validateFirstName(fname) {
    var length = fname.length;
    if (length < 2 || length > 20) return false;
    var upper = fname.toUpperCase();
    for (var i = 0; i < length; i++) {
        var ch = upper[i];
        if (ch < "A" || ch > "Z") return false;
    }
    return true;
}

function validateLastName(lname) {
    var length = lname.length;
    if (length < 2 || length > 20) return false;
    var upper = lname.toUpperCase();
    for (var i = 0; i < length; i++) {
        var ch = upper[i];
        if (ch < "A" || ch > "Z") return false;
    }
    return true;
}

function validateEmail(email) {
    var atPos = email.indexOf("@");
    var dotPos = email.lastIndexOf(".");
    if (atPos < 1) return false;
    if (dotPos < atPos + 2) return false;
    if (dotPos >= email.length - 1) return false;
    return true;
}

function validatePhone(phone) {
    if (phone.length !== 8) return false;
    for (var i = 0; i < phone.length; i++) {
        var ch = phone[i];
        if (ch < "0" || ch > "9") return false;
    }
    return true;
}


function submitForm(e) {
    e.preventDefault();

    console.log("🔵 SUBMIT BUTTON CLICKED");

    let fname = document.getElementById("firstName").value.trim();
    let lname = document.getElementById("lastName").value.trim();
    let email = document.getElementById("userEmail").value.trim();
    let phone = document.getElementById("userPhone").value.trim();

    let event_id = CURRENT_EVENT_ID;

    // Build FormData FIRST
    let formData = new FormData();
    formData.append("firstName", fname);
    formData.append("lastName", lname);
    formData.append("userEmail", email);
    formData.append("userPhone", phone);
    formData.append("event_id", event_id);
    console.log("===== DEBUG BEFORE SEND =====");
    console.log("fname:", fname);
    console.log("lname:", lname);
    console.log("email:", email);
    console.log("phone:", phone);
    console.log("event_id (JS):", event_id);
    formData.forEach((v, k) => console.log(k, "=>", v));
    console.log("=============================");

    // SEND TO PHP
    fetch("../../controller/addParticipants.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(txt => {
        console.log("===== SERVER RESPONSE =====");
        console.log(txt);
        alert(txt);
    })
    .catch(err => console.error("FETCH ERROR:", err));
}





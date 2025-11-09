var fades = document.querySelectorAll(".fade-in");

window.addEventListener("load", function () {
    for (var i = 0; i < fades.length; i++) {
        fades[i].classList.add("visible");
    }
});

function openEvent(title, location, date, description) {
    document.getElementById("eventList").style.display = "none";
    document.getElementById("eventDetails").style.display = "block";
    document.getElementById("detailTitle").innerHTML = title;
    document.getElementById("detailLocation").innerHTML = location;
    document.getElementById("detailDate").innerHTML = date;
    document.getElementById("detailDescription").innerHTML = description;
}

function goBack() {
    document.getElementById("eventDetails").style.display = "none";
    document.getElementById("eventList").style.display = "block";
    document.getElementById("confirmation").style.display = "none";
}

function submitForm(e) {
    e.preventDefault();
    var name = document.getElementById("userName").value;
    var email = document.getElementById("userEmail").value;
    var role = document.getElementById("userRole").value;
    var msg = "<strong> Participation Submitted!</strong><br>" +
              "Thank you <b>" + name + "</b>, you joined as a <b>" + role + "</b>.<br>" +
              "We will email you at <b>" + email + "</b>.";
    var box = document.getElementById("confirmation");
    box.innerHTML = msg;
    box.style.display = "block";
    document.getElementById("userName").value = "";
    document.getElementById("userEmail").value = "";
    document.getElementById("userRole").value = "Volunteer";
}

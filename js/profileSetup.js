/**
 * Fetches the profile of the specified User ID
 */
function fetchProfile() {
  const id = $("#userID").val();
  $.ajax({
    method: "POST",
    url: "../api/profile/profileSetup.php",
    data: {
      function: "fetch_profile",
      id: id,
    },
    success: (response) => {
      let data = JSON.parse(response);
      if (data.status == 200) {
        document.getElementById("employment").value = data[0].employment;
        document.getElementById("college").value = data[0].college;
        document.getElementById("degree").value = data[0].degree;
        document.getElementById("town").value = data[0].town;
        delete data[0].employment;
        delete data[0].college;
        delete data[0].degree;
        delete data[0].town;
        fillProfileDetails(data[0]);
        fetchInterests();
        document.getElementById("spinner").remove();
        document.getElementById("hide").removeAttribute("hidden");
      } else {
        let newChild = document.createElement("div");
        newChild.id = "warning";
        newChild.classList.add("alert", "alert-danger");
        newChild.innerHTML = data.message;
        let parentElement = document.getElementById("hide");
        parentElement.prepend(newChild);
        document.getElementById("spinner").remove();
        document.getElementById("form").remove();
        document.getElementById("hide").removeAttribute("hidden");
      }
    },
  });
}

/**
 * Fetches the Interests of the specified User ID
 */
function fetchInterests() {
  const id = $("#userID").val();
  $.ajax({
    method: "POST",
    url: "../api/profile/profileSetup.php",
    data: {
      function: "fetch_interests",
      id: id,
    },
    success: (response) => {
      let data = JSON.parse(response);
      if (data.status == 200) fillInterestDetails(data[0]);
      else {
        let newChild = document.createElement("div");
        newChild.id = "warning";
        newChild.classList.add("alert", "alert-danger");
        newChild.innerHTML = data.message;
        let parentElement = document.getElementById("hide");
        parentElement.prepend(newChild);
        document.getElementById("spinner").remove();
        document.getElementById("form").remove();
        document.getElementById("hide").removeAttribute("hidden");
      }
    },
  });
}

/**
 * Fills all details for the profile details section where the input is a select box except Interests
 * @param {Object} data
 */
function fillProfileDetails(data) {
  for (const [key, value] of Object.entries(data)) {
    let parentElement = document.getElementById(key);
    let nodes = parentElement.childNodes;
    let newChild = document.createElement("option");
    if (!value) {
      newChild.value = "---Select An Option---";
      newChild.innerHTML = "---Select An Option---";
      newChild.setAttribute("selected", "");
      parentElement.prepend(newChild);
    } else {
      nodes.forEach((node) => {
        if (node.value == value) parentElement.removeChild(node);
        return;
      });
      newChild.value = value;
      newChild.innerHTML = value;
      newChild.setAttribute("selected", "");
      parentElement.prepend(newChild);
    }
  }
}

/**
 *
 * @param {Object} data
 */
function fillInterestDetails(data) {
  for (const [key, value] of Object.entries(data)) {
    let parentElement = document.getElementById(key);
    let nodes = parentElement.childNodes;
    let newChild = document.createElement("option");
    if (!value) {
      newChild.value = "---Select An Interest---";
      newChild.innerHTML = "---Select An Interest---";
      newChild.setAttribute("selected", "");
      parentElement.prepend(newChild);
      nodes.forEach((node) => {
        if (node.value == "del") parentElement.removeChild(node);
      });
    } else {
      nodes.forEach((node) => {
        if (node.value == value) parentElement.removeChild(node);
      });
      newChild.value = value;
      newChild.innerHTML = value;
      newChild.setAttribute("selected", "");
      parentElement.prepend(newChild);
    }
  }
}

/**
 *
 * @param {Event} event
 */
function updateProfile(event) {
  event.preventDefault();
  const id = $("#userID").val();
  $.ajax({
    method: "POST",
    url: "../api/profile/profileSetup.php",
    data: {
      function: "fetch_interests",
      id: id,
    },
    success: (response) => {
      let data = JSON.parse(response);
      if (data.status == 200) sendUpdate(data[0]);
      else {
        let newChild = document.createElement("div");
        newChild.id = "warning";
        newChild.classList.add("alert", "alert-danger");
        newChild.innerHTML = data.message;
        let parentElement = document.getElementById("hide");
        parentElement.prepend(newChild);
        document.getElementById("spinner").remove();
        document.getElementById("form").remove();
        document.getElementById("hide").removeAttribute("hidden");
      }
    },
  });
}

function sendUpdate(interestStored) {
  const id = $("#userID").val();
  const gender = $("#gender").val();
  const seeking = $("#seeking").val();
  const smoker = $("#smoker").val();
  const drinker = $("#drinker").val();
  const employment = $("#employment").val();
  const student = $("#student").val();
  const college = $("#college").val();
  const degree = $("#degree").val();
  const interest1 = $("#interest1").val();
  const interest2 = $("#interest2").val();
  const interest3 = $("#interest3").val();
  const interest4 = $("#interest4").val();
  const county = $("#county").val();
  const town = $("#town").val();
  const description = $("#description").val();
  $.ajax({
    method: "POST",
    url: "../api/profile/profileSetup.php",
    data: {
      function: "update_profile",
      id: id,
      gender: gender,
      seeking: seeking,
      smoker: smoker,
      drinker: drinker,
      employment: employment,
      student: student,
      college: college,
      degree: degree,
      interest1: interest1,
      interest2: interest2,
      interest3: interest3,
      interest4: interest4,
      county: county,
      town: town,
      description: description,
      interestStored: interestStored,
    },
    success: (response) => {
      let data = JSON.parse(response);
      if (data.status == 200) {
        let newChild = document.createElement("div");
        newChild.id = "warning";
        newChild.classList.add("alert", "alert-success");
        newChild.innerHTML = data.message;
        // let parentElement = document.getElementById("main");
        // parentElement.prepend(newChild);
        document.getElementById("main").replaceWith(newChild);
        document.body.classList.add("d-flex", "flex-column", "min-vh-100");
        // document.getElementById("main").classList.add("vh-100");
        // document.getElementById("hide").removeAttribute("hidden");
        window.setTimeout(
          () => (window.location.href = "../profile.php"),
          1125
        );
      } else {
        let newChild = document.createElement("div");
        newChild.id = "warning";
        newChild.classList.add("alert", "alert-danger");
        newChild.innerHTML = data.message;
        let parentElement = document.getElementById("hide");
        parentElement.prepend(newChild);
        document.getElementById("spinner").remove();
        document.getElementById("form").remove();
        document.getElementById("hide").removeAttribute("hidden");
      }
    },
  });
}

$(document).on("ready", fetchProfile());

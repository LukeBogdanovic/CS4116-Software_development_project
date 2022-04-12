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
        if (!data[0].username && !data[0].firstname && !data[0].surname) {
          $.ajax({
            method: "POST",
            url: "../api/profile/profileSetup.php",
            data: { function: "fetch_user_data", id: id },
            success: (response) => {
              let data = JSON.parse(response);
              if (data.status == 200) {
                document.getElementById(
                  "userFirst"
                ).innerHTML = `Create ${data[0].firstname} ${data[0].surname}'s Profile`;
                document.getElementById("username").innerHTML =
                  data[0].username;
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
        } else {
          document.getElementById("username").innerHTML = data[0].username;
          document.getElementById(
            "userFirst"
          ).innerHTML = `Edit ${data[0].firstname} ${data[0].surname}'s Profile`;
        }
        document.getElementById("employment").value = data[0].employment;
        if (data[0].student != "Yes") {
          document.getElementById("college").disabled = true;
          document.getElementById("degree").disabled = true;
        } else {
          document.getElementById("college").value = data[0].college;
          document.getElementById("degree").value = data[0].degree;
        }
        document.getElementById("town").value = data[0].town;
        document.getElementById("description").innerHTML = data[0].description;
        delete data[0].username;
        delete data[0].firstname;
        delete data[0].surname;
        delete data[0].dob;
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
  if (checkNullFields()) {
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
}

function sendUpdate(interestStored) {
  const id = $("#userID").val();
  const updatedGender = $("#gender").val();
  const updatedSeeking = $("#seeking").val();
  const updatedSmoker = $("#smoker").val();
  const updatedDrinker = $("#drinker").val();
  const updatedEmployment = $("#employment").val();
  const updatedStudent = $("#student").val();
  const updatedCollege = $("#college").val();
  const updatedDegree = $("#degree").val();
  const updatedInterest1 = $("#interest1").val();
  const updatedInterest2 = $("#interest2").val();
  const updatedInterest3 = $("#interest3").val();
  const updatedInterest4 = $("#interest4").val();
  const updatedCounty = $("#county").val();
  const updatedTown = $("#town").val();
  const updatedDescription = $("#description").val();
  $.ajax({
    method: "POST",
    url: "../api/profile/profileSetup.php",
    data: {
      function: "update_profile",
      id: id,
      gender: updatedGender,
      seeking: updatedSeeking,
      smoker: updatedSmoker,
      drinker: updatedDrinker,
      employment: updatedEmployment,
      student: updatedStudent,
      college: updatedCollege,
      degree: updatedDegree,
      interest1: updatedInterest1,
      interest2: updatedInterest2,
      interest3: updatedInterest3,
      interest4: updatedInterest4,
      county: updatedCounty,
      town: updatedTown,
      description: updatedDescription,
      interestStored: interestStored,
    },
    success: (response) => {
      let data = JSON.parse(response);
      if (data.status == 200) {
        let newChild = document.createElement("div");
        newChild.id = "warning";
        newChild.classList.add("alert", "alert-success");
        newChild.innerHTML = data.message;
        document.getElementById("main").replaceWith(newChild);
        document.body.classList.add("d-flex", "flex-column", "min-vh-100");
        window.setTimeout(
          () => (window.location.href = "../profile.php?profile="+id),
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

/**
 * Checks if all the fields that are required are filled
 */
function checkNullFields() {
  let phrase = "---Select An Option---";
  let gender = true,
    seeking = true,
    smoker = true,
    drinker = true,
    description = true,
    county = true;
  if ($("#gender").val() == phrase) {
    document.getElementById("gender").classList.add("is-invalid");
    gender = false;
  }
  if ($("#seeking").val() == phrase) {
    document.getElementById("seeking").classList.add("is-invalid");
    seeking = false;
  }
  if ($("#smoker").val() == phrase) {
    document.getElementById("smoker").classList.add("is-invalid");
    smoker = false;
  }
  if ($("#drinker").val() == phrase) {
    document.getElementById("drinker").classList.add("is-invalid");
    drinker = false;
  }
  if (!$("#description").val()) {
    document.getElementById("description").classList.add("is-invalid");
    description = false;
  }
  if ($("#county").val() == phrase) {
    document.getElementById("county").classList.add("is-invalid");
    county = false;
  }
  let validity =
    gender && seeking && smoker && drinker && description && county;
  return validity;
}

$(document).on("ready", fetchProfile());

$("#student").on("change", function () {
  if ($(this).val() == "Yes") {
    document.getElementById("college").disabled = false;
    document.getElementById("degree").disabled = false;
  } else {
    document.getElementById("college").disabled = true;
    document.getElementById("degree").disabled = true;
  }
});

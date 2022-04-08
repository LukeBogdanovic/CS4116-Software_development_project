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
        fetchInterests(id);
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
 * @param {number} id
 */
function fetchInterests(id) {
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

$(document).on("ready", fetchProfile());

/**
 *
 * @param {number} id
 */
function isPageEditable(id) {
  let editable = false;
  if (id && (id == sessionID || admin)) {
    editable = true;
  }
  if (editable) {
    let editDiv = document.createElement("div");
    editDiv.classList.add(
      "align-items-center",
      "justify-content-center",
      "h-25"
    );
    let editBtn = document.createElement("a");
    editBtn.classList.add("btn", "btn-lg", "submit");
    editBtn.setAttribute("role", "button");
    editBtn.setAttribute("href", "profileSetup.php");
    editBtn.style = "background-color: #6D071A;";
    editBtn.innerHTML = "Edit";
    editDiv.appendChild(editBtn);
    document.getElementById("edit").appendChild(editDiv);
  }
}

/**
 *
 */
function fetchProfile() {
  const queryString = window.location.search;
  const urlParams = new URLSearchParams(queryString);
  const id = urlParams.get("profile");
  isPageEditable(id);
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
        document.title = `${data[0].firstname} ${data[0].surname}`;
        document.getElementById(
          "fullname"
        ).innerHTML = `${data[0].firstname} ${data[0].surname}`;
        document.getElementById("age").innerHTML = `${getAge(data[0].dob)}`;
        document.getElementById(
          "description"
        ).innerHTML = `${data[0].description}`;
        document.getElementById("smoker").innerHTML = `${data[0].smoker}`;
        document.getElementById("drinker").innerHTML = `${data[0].drinker}`;
        document.getElementById("gender").innerHTML = `${data[0].gender}`;
        document.getElementById("seeking").innerHTML = `${data[0].seeking}`;
        document.getElementById("county").innerHTML = `${data[0].county}`;
        if (data[0].town) {
          document.getElementById("town").innerHTML = `${data[0].town}`;
        } else {
          let parentElement = document.getElementById("town").parentElement;
          parentElement.parentElement.remove();
          document.getElementById("hzTown").remove();
        }
        if (data[0].employment) {
          document.getElementById(
            "employment"
          ).innerHTML = `${data[0].employment}`;
        } else {
          let parentElement =
            document.getElementById("employment").parentElement;
          parentElement.parentElement.remove();
          document.getElementById("hzEmployment").remove();
        }
        document.getElementById("student").innerHTML = `${data[0].student}`;
        if (data[0].student == "Yes") {
          const template = document.querySelector("[data-template]");
          let collegeArray = {
            College: data[0].college,
            Degree: data[0].degree,
          };
          if (
            !Object.values(collegeArray).every((element) => element === null)
          ) {
            const card = document.getElementById("card");
            for (const [key, value] of Object.entries(collegeArray)) {
              if (!value) {
                continue;
              }
              const hzLine = document.createElement("hr");
              const bar = template.content.cloneNode(true).children[0];
              const name = bar.querySelector("[data-name]");
              const nameValue = bar.querySelector("[data-value]");
              name.textContent = key;
              nameValue.textContent = value;
              card.appendChild(hzLine);
              card.appendChild(bar);
            }
          }
        }
        fetchInterests(id);
        document.getElementById("spinner").remove();
        let likedUsers;
        $.ajax({
          method: "POST",
          async: false,
          url: "../api/Connections/connections.php",
          data: {
            function: "get_Liked_Users",
            id: sessionID,
          },
          success: (response) => {
            likedUsers = JSON.parse(response);
          },
        });
        if (likedUsers[0]) {
          likedUsers = likedUsers[0];
          for (const [key, value] of Object.entries(likedUsers)) {
            if (value.userID == id || sessionID == id) {
              document.getElementById("userLike").remove();
              break;
            }
          }
          if (document.getElementById("userLike")) {
            document.getElementById("userLike").removeAttribute("hidden");
          }
        }
        document.getElementById("cont").removeAttribute("hidden");
      }
    },
  });
}

/**
 *
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
      if (data.status == 200) {
        const interestsDiv = document.getElementById("interests");
        if (!Object.values(data[0]).every((element) => element === null)) {
          for (const [key, value] of Object.entries(data[0])) {
            if (!value) {
              continue;
            }
            const interestSpan = document.createElement("span");
            interestSpan.classList.add("badge", "rounded-pill", "bg-primary");
            interestSpan.innerHTML = value;
            interestsDiv.appendChild(interestSpan);
          }
        } else {
          interestsDiv.parentElement.remove();
          document.getElementById("hzInterests").remove();
        }
      }
    },
  });
}

/**
 *
 * @param {string} dob
 * @returns age
 */
function getAge(dob) {
  let today = new Date();
  let birthDate = new Date(dob);
  let age = today.getFullYear() - birthDate.getFullYear();
  let month = today.getMonth() - birthDate.getMonth();
  if (month < 0 || (month === 0 && today.getDate() < birthDate.getDate())) {
    age--;
  }
  return age;
}

document.addEventListener("load", fetchProfile());

/**
 *
 * @param {Event} event
 */
function likeUser(event) {
  event.preventDefault();
  const queryString = window.location.search;
  const urlParams = new URLSearchParams(queryString);
  const id = urlParams.get("profile");
  $.ajax({
    method: "POST",
    url: "../api/Connections/connections.php",
    data: {
      function: "like_user",
      userID1: sessionID,
      userID2: id,
    },
    success: (response) => {
      let data = JSON.parse(response);
      if (data.status == 200) {
        document
          .getElementById("likeButton")
          .classList.replace("submit", "btn-success");
        document.getElementById("likeButton").innerHTML = "User Liked";
        document.getElementById("likeButton").setAttribute("disabled", "");
        window.setTimeout(() => {
          document.getElementById("likeButton").remove();
        }, 2500);
        checkUserConnection(id);
      }
    },
  });
}

/**
 *
 * @param {String} userID2
 */
function checkUserConnection(userID2) {
  $.ajax({
    method: "POST",
    url: "../api/Connections/connections.php",
    data: {
      function: "check_connection",
      userID1: sessionID,
      userID2: userID2,
    },
    success: (response) => {
      let data = JSON.parse(response);
      if (data.status == 200) {
        let newNode = document.createElement("div");
        newNode.id = "warning";
        newNode.classList.add("alert", "alert-success");
        newNode.innerHTML = data.message;
        let parentDiv = document.getElementById("body");
        parentDiv.insertBefore(newNode, document.getElementById("cont"));
        window.setTimeout(() => {
          parentDiv.removeChild(newNode);
        }, 2500);
      } else {
        let newNode = document.createElement("div");
        newNode.id = "warning";
        newNode.classList.add("alert", "alert-danger");
        newNode.innerHTML = data.message;
        let parentDiv = document.getElementById("body");
        parentDiv.insertBefore(newNode, document.getElementById("cont"));
        window.setTimeout(() => {
          parentDiv.removeChild(newNode);
        }, 2500);
      }
    },
  });
}

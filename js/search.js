let search = $("#search");
let typingTimer;
let interval = 500;
/**
 * Sends the user request from the frontend to the server and returns
 * the data retrieved from the database
 * @param {Event} event
 */
function getSearchResults(event) {
  try {
    event.preventDefault();
  } catch (error) {
    console.error();
  }
  const queryString = window.location.search;
  const urlParams = new URLSearchParams(queryString);
  let searchTerm;
  if (urlParams.get("searchType")) {
    searchTerm = urlParams.get("search");
    document.getElementById("search").value = searchTerm;
    urlParams.delete("searchType");
  } else {
    searchTerm = $("#search").val();
  }
  urlParams.delete("search");
  let urlSplit = window.location.href.split("?");
  let obj = { Title: "Search", URL: urlSplit[0] + `?search=${searchTerm}` };
  history.pushState(obj, obj.Title, obj.URL);
  $.ajax({
    method: "POST",
    url: "../api/Search/getSearchResult.php",
    data: {
      function: "get_Search_result",
      search: searchTerm,
    },
    success: (response) => {
      let data = JSON.parse(response);
      if (data.status == 200) {
        if (document.getElementById("warning"))
          document
            .getElementById("user-cards")
            .parentElement.removeChild(document.getElementById("warning"));
        if (document.getElementById("user-cards").children) {
          document.getElementById("user-cards").innerHTML = "";
        }
        addUserCards(data[0]);
      } else {
        if (!document.getElementById("warning")) {
          var newNode = document.createElement("div");
          newNode.id = "warning";
          newNode.classList.add("alert", "alert-danger");
          newNode.innerHTML = data.message;
          var parentDiv = document.getElementById("user-cards").parentElement;
          parentDiv.appendChild(newNode);
          document.getElementById("user-cards").innerHTML = "";
        }
      }
    },
  });
}

/**
 * Creates the user cards for the search functionality
 * @param {Array} data
 */
function addUserCards(data) {
  const userCardTemplate = document.querySelector("[data-user-template]");
  const userCardContainer = document.querySelector(
    "[data-user-cards-container]"
  );
  const userID = $("#userID").val();
  let likedUsers;
  $.ajax({
    method: "POST",
    async: false,
    url: "../api/Connections/connections.php",
    data: {
      function: "get_Liked_Users",
      id: userID,
    },
    success: (response) => {
      likedUsers = JSON.parse(response);
    },
  });
  data.forEach((user) => {
    const card = userCardTemplate.content.cloneNode(true).children[0];
    const header = card.querySelector("[data-header]");
    const username = card.querySelector("[data-username]");
    const age = card.querySelector("[data-age]");
    const body = card.querySelector("[data-body]");
    const userID = card.querySelector("[data-userid]");
    const userLike = card.querySelector("[data-like]");
    const userProfile = card.querySelector("[data-profile]");
    card.setAttribute("id", `userCard${user.userID}`);
    header.textContent = `${user.firstname} ${user.surname}`;
    username.textContent = user.username;
    age.textContent = user.age;
    body.textContent = user.description;
    userID.setAttribute("value", user.userID);
    userLike.setAttribute("onclick", `likeUser(event,${user.userID});`);
    userLike.setAttribute("id", `user${user.userID}`);
    if (user.description.includes("has not created their profile yet")) {
      userProfile.remove();
    } else {
      userProfile.setAttribute("href", `/profile.php?profile=${user.userID}`);
    }
    if (likedUsers[0]) {
      likedUsers[0].forEach((likedUser) => {
        if (
          likedUser.userID == user.userID ||
          user.userID == $("#userID").val()
        ) {
          userLike.remove();
        }
      });
    }
    userCardContainer.append(card);
  });
}

$(document).on("ready", getSearchResults());

search.on("keyup", () => {
  clearTimeout(typingTimer);
  typingTimer = setTimeout(getSearchResults, interval);
});

/**
 *
 * @param {Event} event
 */
function likeUser(event, userID2) {
  event.preventDefault();
  const userID1 = $("#userID").val();
  $.ajax({
    method: "POST",
    url: "../api/Connections/connections.php",
    data: {
      function: "like_user",
      userID1: userID1,
      userID2: userID2,
    },
    success: (response) => {
      let data = JSON.parse(response);
      if (data.status == 200) {
        document
          .getElementById(`user${userID2}`)
          .classList.replace("submit", "btn-success");
        document.getElementById(`user${userID2}`).innerHTML = "User Liked";
        document.getElementById(`user${userID2}`).setAttribute("disabled", "");
        window.setTimeout(() => {
          document
            .getElementById(`user${userID2}`)
            .classList.replace("btn-success", "submit");
          document.getElementById(`user${userID2}`).innerHTML = "Like User";
          document.getElementById(`user${userID2}`).removeAttribute("disabled");
          window.setTimeout(() => {
            document.getElementById(`user${userID2}`).remove();
          }, 2500);
        }, 2500);
        checkUserConnection(userID2);
      }
    },
  });
}

/**
 *
 * @param {String} userID2
 */
function checkUserConnection(userID2) {
  const userID1 = $("#userID").val();
  $.ajax({
    method: "POST",
    url: "../api/Connections/connections.php",
    data: {
      function: "check_connection",
      userID1: userID1,
      userID2: userID2,
    },
    success: (response) => {
      let data = JSON.parse(response);
      if (data.status == 200) {
        let user;
        $.ajax({
          method: "POST",
          async: false,
          url: "../api/Search/getSearchResult.php",
          data: {
            function: "get_user",
            id: userID2,
          },
          success: (response) => {
            let data = JSON.parse(response);
            if (data.status == 200) {
              user = data.user;
            }
          },
        });
        const userCardTemplate = document.querySelector("[data-user-template]");
        const modalBody = document.getElementById("modal-body");
        const modalHeader = document.getElementById("modalHeader");
        const card = userCardTemplate.content.cloneNode(true).children[0];
        const header = card.querySelector("[data-header]");
        const username = card.querySelector("[data-username]");
        const age = card.querySelector("[data-age]");
        const body = card.querySelector("[data-body]");
        const userID = card.querySelector("[data-userid]");
        const userProfile = document.querySelector("[data-view-profile]");
        const userLike = card.querySelector("[data-like]");
        const profile = card.querySelector("[data-profile]");
        userLike.remove();
        profile.remove();
        card.setAttribute("id", `userCard${user.userID}`);
        header.textContent = `${user.firstname} ${user.surname}`;
        username.textContent = user.username;
        age.textContent = getAge(user.dob);
        body.textContent = user.description;
        userID.setAttribute("value", user.userID);
        modalHeader.innerHTML = `Connected to ${user.firstname} ${user.surname}`;
        userProfile.setAttribute("href", `/profile.php?profile=${user.userID}`);
        modalBody.appendChild(card);
        let modal = new bootstrap.Modal(document.getElementById("Modal"), {});
        modal.show();
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

/**
 *
 * @param {Event} event
 */
async function getSearchFilters(event) {
  event.preventDefault();
  $("#applyFilters").offcanvas("hide");
  await updateQueryString();
  const searchTerm = $("#search").val();
  const queryString = window.location.search;
  const urlParams = new URLSearchParams(queryString);
  const studentVal = urlParams.get("studentVal");
  const genderVal = urlParams.get("genderVal");
  const ageUpper = urlParams.get("ageUpper");
  const ageLower = urlParams.get("ageLower");
  const drinksVal = urlParams.get("drinksVal");
  const smokesVal = urlParams.get("smokesVal");
  const county = urlParams.get("county");
  $.ajax({
    method: "POST",
    url: "../api/Search/getSearchResult.php",
    data: {
      function: "get_filtered_users",
      search: searchTerm,
      student: studentVal,
      gender: genderVal,
      ageUpper: ageUpper,
      ageLower: ageLower,
      drinker: drinksVal,
      smoker: smokesVal,
      county: county,
    },
    success: (response) => {
      let data = JSON.parse(response);
      if (data.status == 200) {
        if (document.getElementById("warning"))
          document
            .getElementById("user-cards")
            .parentElement.removeChild(document.getElementById("warning"));
        if (document.getElementById("user-cards").children) {
          document.getElementById("user-cards").innerHTML = "";
        }
        addUserCards(data.filtered_users);
      } else {
        if (!document.getElementById("warning")) {
          var newNode = document.createElement("div");
          newNode.id = "warning";
          newNode.classList.add("alert", "alert-danger");
          newNode.innerHTML = data.message;
          var parentDiv = document.getElementById("user-cards").parentElement;
          parentDiv.appendChild(newNode);
          document.getElementById("user-cards").innerHTML = "";
        }
      }
    },
  });
}

/**
 * Updates the querystring to allow the
 */
async function updateQueryString() {
  const queryString = window.location.search;
  const urlParams = new URLSearchParams(queryString);
  urlParams.delete("studentVal");
  urlParams.delete("genderVal");
  urlParams.delete("ageLower");
  urlParams.delete("ageUpper");
  urlParams.delete("drinksVal");
  urlParams.delete("smokesVal");
  urlParams.delete("county");
  urlParams.delete("search");
  //try catch blocks are for allowing the query string to be updated
  try {
    urlParams.append(
      "studentVal",
      document.querySelector('input[name="studentVal"]:checked').value
    );
  } catch (exception) {
    console.error(exception);
  }
  try {
    urlParams.append(
      "genderVal",
      document.querySelector('input[name="genderVal"]:checked').value
    );
  } catch (exception) {
    console.error(exception);
  }
  urlParams.append("ageUpper", document.getElementById("ageUpper").value);
  urlParams.append("ageLower", document.getElementById("ageLower").value);
  try {
    urlParams.append(
      "drinksVal",
      document.querySelector('input[name="drinksVal"]:checked').value
    );
  } catch (exception) {
    console.error(exception);
  }
  try {
    urlParams.append(
      "smokesVal",
      document.querySelector('input[name="smokesVal"]:checked').value
    );
  } catch (exception) {
    console.error(exception);
  }
  urlParams.append("county", document.getElementById("county").value);
  urlParams.append("search", document.getElementById("search").value);
  let urlSplit = window.location.href.split("?");
  let obj = { Title: "Search", URL: urlSplit[0] + `?${urlParams.toString()}` };
  history.pushState(obj, obj.Title, obj.URL);
}

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
  const searchTerm = $("#search").val();
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
        addUserCards(data[0], "[data-user-cards-container]");
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
  data.forEach((user) => {
    const card = userCardTemplate.content.cloneNode(true).children[0];
    const header = card.querySelector("[data-header]");
    const username = card.querySelector("[data-username]");
    const age = card.querySelector("[data-age]");
    const body = card.querySelector("[data-body]");
    const userID = card.querySelector("[data-userid]");
    const userLike = card.querySelector("[data-like]");
    const userProfile = card.querySelector("[data-profile]");
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
          .classList.replace("btn-danger", "btn-success");
        document.getElementById(`user${userID2}`).innerHTML = "User Liked";
        document.getElementById(`user${userID2}`).setAttribute("disabled", "");
        window.setTimeout(() => {
          document
            .getElementById(`user${userID2}`)
            .classList.replace("btn-success", "btn-danger");
          document.getElementById(`user${userID2}`).innerHTML = "Like User";
          document.getElementById(`user${userID2}`).removeAttribute("disabled");
        }, 2500);
        checkUserConnection(userID2);
      } else {
        document.getElementById(`user${userID2}`).innerHTML = "Try Again Later";
        document.getElementById(`user${userID2}`).setAttribute("disabled", "");
        window.setTimeout(() => {
          document.getElementById(`user${userID2}`).innerHTML = "Like User";
          document.getElementById(`user${userID2}`).removeAttribute("disabled");
        }, 2500);
      }
    },
  });
}

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
      } else {
      }
    },
  });
}

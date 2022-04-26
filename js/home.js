let seeking;
let suggestedUsers = [];
let cardArrayUsers = [];
let likedUsers = [];

/**
 * Gets the seeking value of the current user and stores
 * the value within a global variable for use in later functions
 */
function getSeeking() {
  $.ajax({
    method: "POST",
    url: "../api/Search/filterUsers.php",
    data: {
      function: "get_seeking",
      id: sessionID,
    },
    success: (response) => {
      let data = JSON.parse(response);
      if (data.status == 200) {
        seeking = data.seeking;
        getSuggestedUsers();
      } else {
        let newChild = document.createElement("div");
        newChild.classList.add("alert", "alert-danger");
        newChild.innerHTML = data.message;
        document.getElementById("user-cards").replaceChildren(newChild);
      }
    },
  });
}

/**
 * Gets the suggested users for the current user and the
 * selected filters that are active and filled in by the user
 */
function getSuggestedUsers() {
  const queryString = window.location.search;
  const urlParams = new URLSearchParams(queryString);
  const studentVal = urlParams.get("studentVal");
  const ageUpper = urlParams.get("ageUpper");
  const ageLower = urlParams.get("ageLower");
  const drinksVal = urlParams.get("drinksVal");
  const smokesVal = urlParams.get("smokesVal");
  const county = urlParams.get("county");
  $.ajax({
    method: "POST",
    url: "../api/Search/filterUsers.php",
    data: {
      function: "get_suggested_users",
      id: sessionID,
      seeking: seeking,
      ageLower: ageLower,
      ageUpper: ageUpper,
      studentVal: studentVal,
      drinksVal: drinksVal,
      smokesVal: smokesVal,
      county: county,
    },
    success: (response) => {
      let data = JSON.parse(response);
      if (data.status == 200) {
        suggestedUsers = data.suggested_users;
        $.ajax({
          method: "POST",
          async: false,
          url: "../api/Connections/connections.php",
          data: {
            function: "get_Liked_Users",
            id: sessionID,
          },
          success: (response) => {
            let data = JSON.parse(response);
            if (data.status == 200) {
              likedUsers = data[0];
              for (let i = suggestedUsers.length - 1; i >= 0; i--) {
                for (let j = likedUsers.length - 1; j >= 0; j--) {
                  if (suggestedUsers[i].userID == likedUsers[j].userID) {
                    suggestedUsers.splice(i, 1);
                    break;
                  }
                }
              }
            } else {
            }
          },
        });
        if (suggestedUsers.length > 4) {
          suggestedUsers.reverse();
          for (let i = suggestedUsers.length - 1; i > 0; i--) {
            if (cardArrayUsers.length >= 4) {
              break;
            }
            cardArrayUsers.push(suggestedUsers[i]);
            suggestedUsers.splice(i, 1);
          }
        } else {
          cardArrayUsers = suggestedUsers;
        }
        addUserCards(cardArrayUsers);
      } else {
        const userCardContainer = document.querySelector(
          "[data-user-cards-container]"
        );
        let newChild = document.createElement("div");
        newChild.classList.add("alert", "alert-danger");
        newChild.innerHTML = data.message;
        userCardContainer.appendChild(newChild);
      }
    },
  });
}

/**
 * Adds the user data to a card and adds that card to the user card
 * array
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
    const img = card.querySelector("[data-img]");
    const username = card.querySelector("[data-username]");
    const age = card.querySelector("[data-age]");
    const body = card.querySelector("[data-body]");
    const userID = card.querySelector("[data-userid]");
    const userLike = card.querySelector("[data-like]");
    const userProfile = card.querySelector("[data-profile]");
    const dismissUser = card.querySelector("[data-dismiss]");
    const interests = card.querySelector("[data-interests]");
    card.setAttribute("id", `userCard${user.userID}`);
    header.textContent = `${user.firstname} ${user.surname}`;
    if (user.photo) {
      img.setAttribute("src", `assets/images/${user.photo}`);
    }
    username.textContent = user.username;
    age.textContent = user.age;
    body.textContent = user.description;
    userID.setAttribute("value", user.userID);
    userLike.setAttribute("onclick", `likeUser(event,${user.userID});`);
    userLike.setAttribute("id", `user${user.userID}`);
    let interestsSpan = document.createElement("span");
    interestsSpan.innerHTML = "Interests In Common: ";
    interests.appendChild(interestsSpan);
    for (i = 0; i < user.interests_in_common.length; i++) {
      let span = document.createElement("span");
      span.classList.add("badge", "rounded-pill", "bg-primary");
      span.innerHTML = user.interests_in_common[i];
      interests.appendChild(span);
    }
    if (user.description.includes("has not created their profile yet")) {
      userProfile.remove();
    } else {
      userProfile.setAttribute("href", `/profile.php?profile=${user.userID}`);
    }
    dismissUser.setAttribute("onclick", `dismissUser(event,${user.userID});`);
    userCardContainer.append(card);
  });
}

/**
 * Likes the user that the current user has clicked.
 * Adds a new user to the card array after the liked user has been
 * @param {Event} event
 * @param {number} userID2
 */
function likeUser(event, userID2) {
  event.preventDefault();
  $.ajax({
    method: "POST",
    url: "../api/Connections/connections.php",
    data: {
      function: "like_user",
      userID1: sessionID,
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
        }, 2500);
        checkUserConnection(userID2);
        dismissUser(event, userID2);
      }
    },
  });
}

/**
 * Checks if both users (current and liked user) have liked each other.
 * If both have liked each other, connects both users and sends the users
 * a notification that they have liked each other
 * @param {number} userID2
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
        const dismiss = card.querySelector("[data-dismiss]");
        const userLike = card.querySelector("[data-like]");
        const profile = card.querySelector("[data-profile]");
        dismiss.remove();
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

document.addEventListener("load", getSeeking());

/**
 * Dismisses the user selected by the current user and removes them from the suggested user array.
 * Adds new user to the suggested users if there are available users within the suggestedUsers array
 * Otherwise grabs new suggested users from the database and adds them until they have been liked and
 * permanently removed from rotation.
 * @param {Event} event
 * @param {number} userID
 */
function dismissUser(event, userID) {
  try {
    event.preventDefault();
  } catch {
    console.error(event);
  }
  document.getElementById(`userCard${userID}`).remove();
  for (let i = cardArrayUsers.length - 1; i > 0; i--) {
    if (cardArrayUsers[i].userID == userID) {
      cardArrayUsers.splice(i, 1);
      break;
    }
  }
  let temp = [];
  for (let i = suggestedUsers.length - 1; i >= 0; i--) {
    for (let j = cardArrayUsers.length - 1; j >= 0; j--) {
      if (suggestedUsers[i].userID == cardArrayUsers[j].userID) {
        suggestedUsers.splice(i, 1);
        break;
      }
    }
  }
  if (suggestedUsers.length == 0) {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const studentVal = urlParams.get("studentVal");
    const ageUpper = urlParams.get("ageUpper");
    const ageLower = urlParams.get("ageLower");
    const drinksVal = urlParams.get("drinksVal");
    const smokesVal = urlParams.get("smokesVal");
    const county = urlParams.get("county");
    $.ajax({
      method: "POST",
      async: false,
      url: "../api/Search/filterUsers.php",
      data: {
        function: "get_suggested_users",
        id: sessionID,
        seeking: seeking,
        ageLower: ageLower,
        ageUpper: ageUpper,
        studentVal: studentVal,
        drinksVal: drinksVal,
        smokesVal: smokesVal,
        county: county,
      },
      success: (response) => {
        let data = JSON.parse(response);
        if (data.status == 200) {
          suggestedUsers = data.suggested_users;
        } else {
          let newChild = document.createElement("div");
          newChild.classList.add("alert", "alert-danger");
          newChild.innerHTML = data.message;
          let parentDiv = document.getElementById("user-cards").parentElement;
          parentDiv.prepend(newChild);
        }
      },
    });
    $.ajax({
      method: "POST",
      async: false,
      url: "../api/Connections/connections.php",
      data: {
        function: "get_Liked_Users",
        id: sessionID,
      },
      success: (response) => {
        let data = JSON.parse(response);
        if (data.status == 200) {
          likedUsers = data[0];
          for (let i = suggestedUsers.length - 1; i >= 0; i--) {
            for (let j = likedUsers.length - 1; j >= 0; j--) {
              if (suggestedUsers[i].userID == likedUsers[j].userID) {
                suggestedUsers.splice(i, 1);
                break;
              }
            }
          }
        } else {
        }
      },
    });
  }
  temp.push(suggestedUsers[suggestedUsers.length - 1]);
  addUserCards(temp);
  cardArrayUsers.push(suggestedUsers[suggestedUsers.length - 1]);
  suggestedUsers.splice(suggestedUsers.length - 1, 1);
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

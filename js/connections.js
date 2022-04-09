/**
 * Gets all users with a connection to the current logged in user
 */
function getConnectedUsers() {
  const id = $("#userID").val();
  $.ajax({
    method: "POST",
    url: "../api/Connections/connections.php",
    data: {
      function: "get_Connected_Users",
      id: id,
    },
    success: (response) => {
      let data = JSON.parse(response);
      if (data.status == 200) {
        if (document.getElementById("warning"))
          document
            .getElementById("searchForm")
            .parentElement.removeChild(document.getElementById("warning"));
        addUserCards(data[0]);
      } else {
        if (!document.getElementById("warning")) {
          let newNode = document.createElement("div");
          newNode.id = "warning";
          newNode.classList.add("alert", "alert-danger");
          newNode.innerHTML = data.message;
          let parentDiv = document.getElementById("container").parentElement;
          parentDiv.insertBefore(newNode, document.getElementById("container"));
          document.getElementById("user-cards").innerHTML = "";
        }
      }
    },
  });
}

/**
 * Adds data retrieved from the connection ajax request to the user card template
 * @param {JSON} data
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
    const connectionDate = card.querySelector("[data-connection]");
    header.textContent = `${user.firstname} ${user.surname}`;
    username.textContent = user.username;
    age.textContent = user.age;
    body.textContent = user.description;
    connectionDate.textContent = user.daysSinceConnection;
    userCardContainer.append(card);
  });
}

$(document).on("ready", getConnectedUsers());

/**
 * Formats the data from yyyymmdd to ddmmyyyy
 * @param {Date} inputDate
 * @returns Date
 */
function formatDate(inputDate) {
  let datePart = inputDate.match(/\d+/g);
  let year = datePart[0];
  let month = datePart[1];
  let day = datePart[2];
  return day + "/" + month + "/" + year;
}

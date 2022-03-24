/**
 *
 * @param {Event} event
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
      var data = JSON.parse(response);
      if (data.status == 200) {
        if (document.getElementById("warning"))
          document
            .getElementById("searchForm")
            .parentElement.removeChild(document.getElementById("warning"));
        addUserCards(data[0]);
      } else {
        if (!document.getElementById("warning")) {
          var newNode = document.createElement("div");
          newNode.id = "warning";
          newNode.classList.add("alert", "alert-danger");
          newNode.innerHTML = data.message;
          var parentDiv = document.getElementById("container").parentElement;
          parentDiv.insertBefore(newNode, document.getElementById("container"));
          document.getElementById("user-cards").innerHTML = "";
        }
      }
    },
  });
}

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
    connectionDate.textContent = `Date Connected: ${user.connectionDate}`;
    userCardContainer.append(card);
  });
}

$(document).on("ready", getConnectedUsers());

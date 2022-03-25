var search = $("#search");
var typingTimer;
var interval = 500;
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
    data: { function: "get_Search_result_username", search: searchTerm },
    success: (response) => {
      var data = JSON.parse(response);
      if (data.status == 200) {
        if (document.getElementById("warning"))
          document
            .getElementById("searchbox")
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
          var parentDiv = document.getElementById("searchbox").parentElement;
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
    header.textContent = `${user.firstname} ${user.surname}`;
    username.textContent = user.username;
    age.textContent = user.age;
    body.textContent = user.description;
    userCardContainer.append(card);
  });
}

$(document).on("ready", getSearchResults());

search.on("keyup", () => {
  clearTimeout(typingTimer);
  typingTimer = setTimeout(getSearchResults, interval);
});

/**
 *
 * @param {Event} event
 */
function getSearchResults(event) {
  event.preventDefault();
  const searchTerm = $("#search").val();
  $.ajax({
    method: "POST",
    url: "../api/Search/getSearchResult.php",
    data: { function: "get_Search_result_username", search: searchTerm },
    success: (response) => {
      var data = JSON.parse(response);
      if (data.status == 200) {
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
          var parentDiv = document.getElementById("searchForm").parentElement;
          parentDiv.appendChild(newNode);
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
    const age = card.querySelector("[data-age]");
    const body = card.querySelector("[data-body]");
    header.textContent = `${user.firstname} ${user.surname}`;
    age.textContent = user.age;
    body.textContent = user.description;
    userCardContainer.append(card);
  });
}

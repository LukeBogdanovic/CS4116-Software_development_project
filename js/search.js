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
        console.log(data[0]);
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

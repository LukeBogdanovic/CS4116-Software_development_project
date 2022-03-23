/**
 *
 * @param {Event} event
 */
function getConnectedUsers(event) {
  event.preventDefault();
  $.ajax({
    method: "POST",
    url: "",
    data: {},
    success: (response) => {
      var data = JSON.parse(response);
      if (data.status == 200) {
        if (document.getElementById("warning"))
          document
            .getElementById("searchForm")
            .parentElement.removeChild(document.getElementById("warning"));
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

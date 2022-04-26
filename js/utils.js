function goToSearch() {
  const searchTerm = $("#searchBar").val();
  window.location.href = `/search.php?search=${searchTerm}&searchType=navbar`;
}

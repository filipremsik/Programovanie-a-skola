$profileMenu = document.getElementById("profileMenu")
$avatarIcon = document.getElementById("avatarIcon")

$avatarIcon.addEventListener("click", () => {
    $profileMenu.classList.toggle("hidden")
})
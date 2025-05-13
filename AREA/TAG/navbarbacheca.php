<div class="container fixed-bottom">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="btn btn-primary" id="toggleAllButton">Mostra Tutto</a>
            <a class="btn btn-success" href="/AREA/Script/simula.php">Simula Tutto</a>
            <a class="btn btn-danger" href="/AREA/Script/puliscibacheca.php">Cancella Tutto</a>
            <a class="btn btn-info" href="/AREA/Script/creaeuropa.php" style="color:white !important">Fase Europea</a>
        </div>
    </nav>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var toggleAllButton = document.getElementById("toggleAllButton");

    toggleAllButton.addEventListener("click", function() {
        var cardBodies = document.querySelectorAll(".card-body");
        var isHidden = cardBodies[0].style.display === "none" || cardBodies[0].style.display === "";

        cardBodies.forEach(function(cardBody) {
            cardBody.style.display = isHidden ? "block" : "none";
        });

        toggleAllButton.innerText = isHidden ? "Nascondi Tutto" : "Mostra Tutto";
    });

    var cards = document.querySelectorAll(".card");

    cards.forEach(function(card) {
        var cardBody = card.querySelector(".card-body");
        cardBody.style.display = "none"; // Nascondi il card-body inizialmente

        card.addEventListener("click", function() {
            if (cardBody.style.display === "none" || cardBody.style.display === "") {
                cardBody.style.display = "block";
            } else {
                cardBody.style.display = "none";
            }
        });
    });
});
</script>
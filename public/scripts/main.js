const header = document.querySelector('h1');
console.log(header);

header.addEventListener('click', () => {
    header.style.color = 'green';
});
const search = document.querySelector('input[placeholder="search card"]');
const cardsContainer = document.querySelector(".cards");

search.addEventListener("keyup", function (event) {
    if (event.key === "Enter") {
        const data = { search: this.value };

        fetch("/search-cards", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(cards => {
            cardsContainer.innerHTML = "";

            cards.forEach(card => createCard(card));
        });
    }
});
function createCard(card) {
    const template = document.querySelector("#card-template");
    const clone = template.content.cloneNode(true);

    clone.querySelector("img").src = card.image;
    clone.querySelector("h2").innerText = card.title;
    clone.querySelector("p").innerText = card.description;

    cardsContainer.appendChild(clone);
}

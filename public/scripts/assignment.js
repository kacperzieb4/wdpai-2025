const video = document.getElementById("assignmentVideo");
const checkbox = document.getElementById("withTimestamp");
const timestampInput = document.getElementById("timestampInput");

/* TIMESTAMP WHEN ADDING COMMENT */
if (video && checkbox && timestampInput) {
    checkbox.addEventListener("change", () => {
        if (!checkbox.checked) {
            timestampInput.value = "";
            return;
        }

        timestampInput.value = Math.floor(video.currentTime);
    });
}

document.addEventListener("click", (e) => {
    const target = e.target;

    if (target.classList.contains("comment-timestamp")) {
        const seconds = parseInt(target.dataset.seconds, 10);

        if (!isNaN(seconds) && video) {
            video.currentTime = seconds;
            video.play();
        }
    }
});

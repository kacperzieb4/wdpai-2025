document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('.comment-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            btn.closest('.comment')?.classList.add('editing');
        });
    });

    document.querySelectorAll('.cancel-comment').forEach(btn => {
        btn.addEventListener('click', () => {
            btn.closest('.comment')?.classList.remove('editing');
        });
    });

    document.querySelectorAll('.save-comment').forEach(btn => {
        btn.addEventListener('click', () => {
            const comment = btn.closest('.comment');
            if (!comment) return;

            const id = comment.dataset.commentId;
            const input = comment.querySelector('.comment-edit-input');
            if (!id || !input) return;

            fetch('/edit-comment/' + id, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'content=' + encodeURIComponent(input.value)
            }).then(() => location.reload());
        });
    });

});

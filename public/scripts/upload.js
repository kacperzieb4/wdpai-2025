document.addEventListener('DOMContentLoaded', () => {

    const form = document.querySelector('form');
    const fileInput = form?.querySelector('input[type="file"]');

    const uploadBox = document.getElementById('uploadBox');
    const progressBar = document.getElementById('uploadProgress');
    const percentText = document.getElementById('uploadPercent');

    if (!form || !fileInput || !uploadBox) {
        return;
    }

    form.addEventListener('submit', function (e) {

        if (!fileInput.files.length) {
            return;
        }

        e.preventDefault();

        const xhr = new XMLHttpRequest();
        const formData = new FormData(form);

        uploadBox.style.display = 'block';

        xhr.upload.addEventListener('progress', function (e) {
            if (e.lengthComputable) {
                const percent = Math.round((e.loaded / e.total) * 100);
                progressBar.style.width = percent + '%';
                percentText.textContent = percent + '%';
            }
        });

        xhr.addEventListener('load', function () {
            if (xhr.status === 200) {
                window.location.href = '/assignments';
            } else {
                alert('Upload failed');
            }
        });

        xhr.open('POST', window.location.href);
        xhr.send(formData);
    });

});

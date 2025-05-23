document.getElementById('imageInput').addEventListener('change', function (event) {
    const [file] = event.target.files;
    const preview = document.getElementById('imagePreview');

    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
    } else {
        preview.src = '#';
        preview.style.display = 'none';
    }
});
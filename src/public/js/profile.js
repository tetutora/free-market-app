document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('profile_picture');
    const previewImg = document.querySelector('.profile-picture-preview');

    if (fileInput && previewImg) {
        fileInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImg.src = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                previewImg.src = previewImg.dataset.default;
            }
        });
    }
});

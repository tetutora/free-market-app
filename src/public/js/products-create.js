document.addEventListener('DOMContentLoaded', function () {
    const imageInput = document.getElementById('imageInput');
    const container = document.getElementById('imagePreviewContainer');
    let selectedFiles = [];

    imageInput.addEventListener('change', function(event) {
        const files = Array.from(event.target.files);

        if (selectedFiles.length + files.length > 10) {
            alert('画像は最大10枚までです');
            imageInput.value = '';
            return;
        }
        selectedFiles = selectedFiles.concat(files);
        updatePreview();
    });

    function updatePreview() {
        container.innerHTML = '';

        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.style.display = 'inline-block';
                div.style.position = 'relative';
                div.style.margin = '5px';

                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '150px';
                img.style.maxHeight = '150px';
                img.style.border = '1px solid #ccc';
                img.style.borderRadius = '4px';
                img.style.display = 'block';

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = '×';
                btn.style.position = 'absolute';
                btn.style.top = '2px';
                btn.style.right = '2px';
                btn.style.background = 'rgba(0,0,0,0.5)';
                btn.style.color = 'white';
                btn.style.border = 'none';
                btn.style.borderRadius = '50%';
                btn.style.width = '20px';
                btn.style.height = '20px';
                btn.style.cursor = 'pointer';
                btn.addEventListener('click', () => {
                    removeImage(index);
                });

                div.appendChild(img);
                div.appendChild(btn);
                container.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }

    function removeImage(index) {
        selectedFiles.splice(index, 1);
        updatePreview();
    }
});

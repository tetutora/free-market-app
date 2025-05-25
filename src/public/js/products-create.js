document.addEventListener('DOMContentLoaded', function () {
    const imageInput = document.getElementById('imageInput');
    const container = document.getElementById('imagePreviewContainer');

    // 選択済みファイルを保持（FormData送信用ではなく、プレビュー用）
    let selectedFiles = [];

    imageInput.addEventListener('change', function(event) {
        const files = Array.from(event.target.files);

        if (selectedFiles.length + files.length > 10) {
            alert('画像は最大10枚までです');
            imageInput.value = '';
            return;
        }

        // 追加分をselectedFilesにpush
        selectedFiles = selectedFiles.concat(files);

        // プレビュー更新
        updatePreview();

        // inputの値はリセットして再選択可能に
        imageInput.value = '';
    });

    // プレビュー表示関数
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

                // 削除ボタン
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

    // 画像削除処理
    function removeImage(index) {
        selectedFiles.splice(index, 1);
        updatePreview();
    }

    // フォーム送信は通常通り行うので、submitイベントの上書きは不要
});

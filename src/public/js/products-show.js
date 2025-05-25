document.addEventListener('DOMContentLoaded', function () {
    const container = document.querySelector('.product-images');
    if (!container) return;

    // data-images 属性に JSON で画像パスの配列が入っている
    const images = JSON.parse(container.dataset.images);
    if (!images || images.length === 0) return;

    let currentIndex = 0;

    const mainImage = document.getElementById('main-image');
    const prevArrow = document.getElementById('prev-arrow');
    const nextArrow = document.getElementById('next-arrow');

    function showImage(index) {
        currentIndex = index;
        mainImage.src = images[currentIndex];
    }

    if (prevArrow && nextArrow) {
        prevArrow.addEventListener('click', () => {
            let newIndex = currentIndex - 1;
            if (newIndex < 0) newIndex = images.length - 1;
            showImage(newIndex);
        });

        nextArrow.addEventListener('click', () => {
            let newIndex = currentIndex + 1;
            if (newIndex >= images.length) newIndex = 0;
            showImage(newIndex);
        });
    }
});

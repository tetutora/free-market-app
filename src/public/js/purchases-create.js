document.addEventListener('DOMContentLoaded', () => {
    const productImagesDiv = document.querySelector('.product-images');
    if (!productImagesDiv) return;

    const imgPathsData = productImagesDiv.dataset.images;
    if (!imgPathsData) return;

    const imgPaths = JSON.parse(imgPathsData);
    if (!Array.isArray(imgPaths) || imgPaths.length === 0) return;

    const mainImage = document.getElementById('main-image');
    if (!mainImage) return;

    let currentIndex = 0;

    const showImage = (index) => {
        mainImage.src = imgPaths[index];
    };

    const prevArrow = document.getElementById('prev-arrow');
    const nextArrow = document.getElementById('next-arrow');

    if (prevArrow) {
        prevArrow.addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + imgPaths.length) % imgPaths.length;
            showImage(currentIndex);
        });
    }

    if (nextArrow) {
        nextArrow.addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % imgPaths.length;
            showImage(currentIndex);
        });
    }
});

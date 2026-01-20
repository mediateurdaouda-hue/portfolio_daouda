const images = [
    "../image/image1.jpg", "../image/image2.jpg", "../image/image3.jpg", "../image/image4.jpg", "../image/image5.jpg",
    "../image/image6.jpg", "../image/image7.jpg", "../image/image8.jpg", "../image/image9.jpg", "../image/img1.jpg", 
    "../image/img2.jpg", "../image/img3.jpg", "../image/img10.jpg", "../image/image13.jpeg", 
    // Ajoutez les chemins des images supplémentaires ici
];
let currentIndex = 0;

function showImage(index) {
    currentIndex = index;
    document.getElementById('main-image').src = images[index];
}

function prevImage() {
    currentIndex = (currentIndex > 0) ? currentIndex - 1 : images.length - 1;
    showImage(currentIndex);
}

function nextImage() {
    currentIndex = (currentIndex < images.length - 1) ? currentIndex + 1 : 0;
    showImage(currentIndex);
}

// Initial call to set the main image
showImage(currentIndex);


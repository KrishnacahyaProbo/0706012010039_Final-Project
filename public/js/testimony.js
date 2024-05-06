const stars = document.querySelectorAll('.star');
const ratingInput = document.getElementById('ratingInput');

stars.forEach(star => {
    star.addEventListener('click', () => {
        const rating = parseInt(star.dataset.rating);
        highlightStars(rating);
        ratingInput.value = rating;
    });
});
console.log(ratingInput);

function highlightStars(selectedRating) {
    stars.forEach((star, index) => {
        const starRating = parseInt(star.dataset.rating);
        if (starRating <= selectedRating) {
            star.classList.remove('bi-star', 'text-primary');
            star.classList.add('bi-star-fill', 'text-warning');
        } else {
            star.classList.remove('bi-star-fill', 'text-warning');
            star.classList.add('bi-star', 'text-primary');
        }
    });
}

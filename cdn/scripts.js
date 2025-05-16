
document.addEventListener('DOMContentLoaded', () => {
    const movieContainer = document.getElementById('movie-container');

    function displayMovies(movies) {
        movieContainer.innerHTML = '';
        movies.forEach(movie => {
            const movieCard = `
                <div class="movie-card">
                    <img src="${movie.image}" alt="${movie.title}">
                    <h3>${movie.title}</h3>
                    <a href="${movie.file}" class="download-btn" download="${movie.title}.mp4">Download</a>
                </div>
            `;
            movieContainer.innerHTML += movieCard;
        });
    }

    function shuffleArray(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
        return array;
    }

    var them = document.getElementById('shuffle-btn').addEventListener('click', () => {
        const shuffledMovies = shuffleArray([...movies]);
        displayMovies(shuffledMovies);
    });

    document.querySelectorAll('.navbar a').forEach(link => {
        link.addEventListener('click', function () {
            const section = this.getAttribute('data-section');
            const filteredMovies = section === 'all' ? movies : movies.filter(movie => movie.section === section);
            displayMovies(filteredMovies);
        });
    });

    // Initial display of movies
    displayMovies(movies);
});

them();



/*!
 * Cinema Management System
 */

// Toggle the side navigation
window.addEventListener('DOMContentLoaded', event => {
    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

    // Close alerts automatically after 5 seconds
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Initialize tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

    // Add smooth scrolling to all links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

    // Search functionality
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const searchInput = this.querySelector('input[type="search"]');
            if (searchInput.value.trim() === '') {
                e.preventDefault();
                searchInput.focus();
            }
        });
    }
});

// Movie search autocomplete
function initializeMovieSearch() {
    const searchInput = document.getElementById('movieSearch');
    if (!searchInput) return;

    searchInput.addEventListener('input', debounce(function() {
        const query = this.value.trim();
        if (query.length < 2) return;

        fetch(`/api/search?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                displaySearchResults(data);
            })
            .catch(error => console.error('Error searching movies:', error));
    }, 300));
}

// Debounce function to limit API calls
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Display search results
function displaySearchResults(data) {
    const resultsContainer = document.getElementById('searchResults');
    if (!resultsContainer) return;

    resultsContainer.innerHTML = '';
    
    if (data.movies.length === 0 && data.theaters.length === 0) {
        resultsContainer.innerHTML = '<p class="text-center py-3">No results found</p>';
        return;
    }

    // Display movies
    if (data.movies.length > 0) {
        const moviesSection = document.createElement('div');
        moviesSection.innerHTML = '<h6 class="dropdown-header">Movies</h6>';
        
        data.movies.forEach(movie => {
            const item = document.createElement('a');
            item.className = 'dropdown-item';
            item.href = `/movies/${movie.id}`;
            item.innerHTML = `
                <div class="d-flex align-items-center">
                    <img src="${movie.poster_url || '/img/movie-placeholder.jpg'}" alt="${movie.title}" class="me-2" style="width: 30px; height: 45px; object-fit: cover;">
                    <div>
                        <div>${movie.title}</div>
                        <small class="text-muted">${movie.genre}</small>
                    </div>
                </div>
            `;
            moviesSection.appendChild(item);
        });
        
        resultsContainer.appendChild(moviesSection);
    }

    // Display theaters
    if (data.theaters.length > 0) {
        const theatersSection = document.createElement('div');
        theatersSection.innerHTML = '<h6 class="dropdown-header">Theaters</h6>';
        
        data.theaters.forEach(theater => {
            const item = document.createElement('a');
            item.className = 'dropdown-item';
            item.href = `/theaters#${theater.id}`;
            item.innerHTML = `
                <div>
                    <div>${theater.name}</div>
                    <small class="text-muted">${theater.location}</small>
                </div>
            `;
            theatersSection.appendChild(item);
        });
        
        resultsContainer.appendChild(theatersSection);
    }
}

// Initialize functions when document is ready
document.addEventListener('DOMContentLoaded', function() {
    initializeMovieSearch();
});
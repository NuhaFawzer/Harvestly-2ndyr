document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.favorite-btn').forEach((button) => {
        button.addEventListener('click', () => {
            button.classList.toggle('active');
            const icon = button.querySelector('.material-symbols-outlined');
            if (icon) {
                icon.textContent = button.classList.contains('active')
                    ? 'favorite'
                    : 'favorite_border';
            }
        });
    });
});

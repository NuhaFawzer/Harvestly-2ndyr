document.addEventListener('DOMContentLoaded', () => {
    const menuButton = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');

    if (menuButton && mobileMenu) {
        menuButton.addEventListener('click', () => {
            const open = mobileMenu.classList.toggle('active');
            mobileMenu.setAttribute('aria-hidden', String(!open));
            const icon = menuButton.querySelector('.material-symbols-outlined');
            if (icon) icon.textContent = open ? 'close' : 'menu';
        });

        mobileMenu.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('active');
                mobileMenu.setAttribute('aria-hidden', 'true');
                const icon = menuButton.querySelector('.material-symbols-outlined');
                if (icon) icon.textContent = 'menu';
            });
        });
    }

    const email = document.getElementById('newsletterEmail');
    const subscribe = document.getElementById('subscribeBtn');

    if (email && subscribe) {
        const notify = (message) => {
            let box = document.getElementById('harvestlyNotification');
            if (!box) {
                box = document.createElement('div');
                box.id = 'harvestlyNotification';
                box.className = 'landing-notification';
                document.body.appendChild(box);
            }
            box.textContent = message;
            box.classList.add('show');
            clearTimeout(box.hideTimer);
            box.hideTimer = setTimeout(() => box.classList.remove('show'), 1800);
        };

        subscribe.addEventListener('click', () => {
            const value = email.value.trim();
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                notify('Please enter a valid email address.');
                email.focus();
                return;
            }
            email.value = '';
            notify('Successfully subscribed!');
        });

        email.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
                subscribe.click();
            }
        });
    }

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768 && mobileMenu) {
            mobileMenu.classList.remove('active');
            mobileMenu.setAttribute('aria-hidden', 'true');
            const icon = menuButton?.querySelector('.material-symbols-outlined');
            if (icon) icon.textContent = 'menu';
        }
    });
});

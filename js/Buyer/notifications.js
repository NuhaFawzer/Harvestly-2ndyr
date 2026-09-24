'use strict';

document.addEventListener('DOMContentLoaded', () => {
    const controllerUrl = '/Harvestly/Controller/Buyer/NotificationsController.php';
    const notificationCards = [...document.querySelectorAll('.notification-card')];
    const filterTabs = [...document.querySelectorAll('.filter-tab')];
    const searchInput = document.getElementById('searchInput');
    const markAllReadButton = document.getElementById('markAllRead');
    const cartButton = document.getElementById('cartButton');
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');
    const settingsButton = document.getElementById('settingsButton');
    const notificationButton = document.getElementById('notificationButton');

    function unreadCards() {
        return document.querySelectorAll('.notification-card.unread');
    }

    function updateUnreadCount() {
        const unread = unreadCards().length;
        const number = document.querySelector('.unread-card .summary-number');

        if (number) {
            number.textContent = unread;
        }

        // Keep the header/mobile badge synchronized with the current unread count.
        document.querySelectorAll('.notification-count-badge').forEach(badge => {
            if (unread === 0) {
                badge.remove();
                return;
            }

            badge.textContent = unread > 99 ? '99+' : String(unread);
        });

        if (markAllReadButton) {
            markAllReadButton.disabled = unread === 0;
            markAllReadButton.innerHTML = unread === 0
                ? '<span class="material-symbols-outlined">done</span> All Read'
                : '<span class="material-symbols-outlined">done_all</span> Mark All as Read';
        }
    }

    function applyFilters() {
        const activeTab = document.querySelector('.filter-tab.active');
        const filter = activeTab?.dataset.filter || 'All';
        const search = (searchInput?.value || '').trim().toLowerCase();

        notificationCards.forEach(card => {
            const isUnread = card.classList.contains('unread');
            const text = card.textContent.toLowerCase();

            let matchesFilter = true;

            if (filter === 'Unread') {
                matchesFilter = isUnread;
            } else if (filter === 'Read') {
                matchesFilter = !isUnread;
            }

            const matchesSearch = search === '' || text.includes(search);
            card.style.display = matchesFilter && matchesSearch ? 'flex' : 'none';
        });
    }

    async function sendAction(action, id = '') {
        const data = new FormData();
        data.append('action', action);

        if (id !== '') {
            data.append('id', id);
        }

        const response = await fetch(controllerUrl, {
            method: 'POST',
            body: data,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Notification action failed.');
        }

        return result;
    }

    function markCardAsRead(card) {
        card.classList.remove('unread', 'high-priority');
        card.classList.add('read');
        card.querySelector('.unread-dot')?.remove();
        updateUnreadCount();
        applyFilters();
    }

    async function markOneRead(card) {
        if (!card || !card.classList.contains('unread')) {
            return true;
        }

        const id = card.dataset.id;
        if (!id) {
            return false;
        }

        await sendAction('read', id);
        markCardAsRead(card);
        return true;
    }

    filterTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            filterTabs.forEach(item => item.classList.remove('active'));
            tab.classList.add('active');
            applyFilters();
        });
    });

    searchInput?.addEventListener('input', applyFilters);

    // Clicking a notification marks it as read.
    notificationCards.forEach(card => {
        card.addEventListener('click', async event => {
            if (event.target.closest('.notification-action')) {
                return;
            }

            try {
                await markOneRead(card);
            } catch (error) {
                console.error(error);
            }
        });
    });

    // Mark every notification as read.
    markAllReadButton?.addEventListener('click', async () => {
        try {
            await sendAction('read_all');

            notificationCards.forEach(card => {
                card.classList.remove('unread', 'high-priority');
                card.classList.add('read');
                card.querySelector('.unread-dot')?.remove();
            });

            updateUnreadCount();
            applyFilters();
        } catch (error) {
            alert(error.message || 'Unable to mark notifications as read.');
        }
    });

    // Action buttons mark the notification read first, then navigate.
    document.querySelectorAll('.notification-action').forEach(button => {
        button.addEventListener('click', async event => {
            event.stopPropagation();

            const card = button.closest('.notification-card');
            const target = button.dataset.url;

            try {
                if (card) {
                    await markOneRead(card);
                }

                if (target) {
                    window.location.href = target;
                }
            } catch (error) {
                console.error(error);
                if (target) {
                    window.location.href = target;
                }
            }
        });
    });

    cartButton?.addEventListener('click', () => {
        window.location.href = '/Harvestly/Controller/Buyer/CartController.php';
    });

    notificationButton?.addEventListener('click', () => {
        window.location.reload();
    });

    mobileMenuButton?.addEventListener('click', () => {
        mobileMenu?.classList.toggle('open');
        const icon = mobileMenuButton.querySelector('.material-symbols-outlined');

        if (icon) {
            icon.textContent = mobileMenu?.classList.contains('open') ? 'close' : 'menu';
        }
    });

    settingsButton?.addEventListener('click', () => {
        window.location.href = '/Harvestly/Controller/Buyer/ProfileController.php';
    });

    updateUnreadCount();
    applyFilters();
});

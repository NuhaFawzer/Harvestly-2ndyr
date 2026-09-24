/**
 * Local Material Symbols fallback for Harvestly.
 * Keeps the Buyer UI independent of external icon/font CDNs.
 */
document.addEventListener('DOMContentLoaded', function () {
    const icons = {
        add: '+', add_a_photo: '▣', admin_panel_settings: '⚙', agriculture: '♣',
        arrow_back: '←', arrow_forward: '→', bolt: 'ϟ', calendar_month: '▦', call: '☎',
        cancel: '×', chat: '◌', check: '✓', check_circle: '✓', chevron_right: '›',
        credit_card: '▭', dashboard: '▦', delete: '⌫', done_all: '✓', eco: '⌁',
        error: '!', favorite: '♥', favorite_border: '♡', gpp_bad: '!', grass: '⌁',
        handshake: '↔', healing: '+', inbox: '▣', info: 'i', inventory_2: '□',
        local_fire_department: '♨', local_shipping: '▻', location_on: '●', lock: '▣',
        logout: '↪', mail: '✉', mark_email_unread: '✉', menu: '☰', monitor_weight: '▥',
        notifications: '♢', nutrition: '◉', open_in_new: '↗', password: '•••', payments: 'Rs',
        person: '●', person_add: '+', phonelink_lock: '▣', photo_camera: '▣',
        rate_review: '★', receipt_long: '▤', remove: '−', report_problem: '!', save: '▣',
        search: '⌕', search_off: '×', settings: '⚙', shopping_bag: '▣', shopping_cart: '▱',
        speed: '◴', star: '★', star_half: '☆', storefront: '▥', tune: '☷',
        verified: '✓', verified_user: '✓', visibility: '◉', visibility_off: '⊘'
    };

    document.querySelectorAll('.material-symbols-outlined').forEach(function (el) {
        const key = (el.textContent || '').trim();
        el.dataset.iconName = key;
        el.textContent = icons[key] || '•';
        el.style.fontFamily = 'Arial, sans-serif';
        el.style.fontStyle = 'normal';
        el.style.fontWeight = '600';
        el.style.lineHeight = '1';
    });
});

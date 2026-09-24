// ============================================================ */
// SIDEBAR TOGGLE (Mobile)
// ============================================================ */
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sidebarOverlay');
const menuToggle = document.getElementById('menuToggle');

if (menuToggle && sidebar && overlay) {
    menuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('open');
        overlay.classList.toggle('show');
    });

    overlay.addEventListener('click', () => {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
    });
}

// ============================================================ */
// LOGIN / LOGOUT (only if login elements exist)
// ============================================================ */
const loginPage = document.getElementById('loginPage');
const loginBtn = document.getElementById('loginBtn');
const logoutBtn = document.getElementById('logoutBtn');

if (loginBtn && loginPage) {
    loginBtn.addEventListener('click', () => {
        loginPage.classList.add('hidden');
    });
}

if (logoutBtn) {
    logoutBtn.addEventListener('click', () => {
        if (loginPage) loginPage.classList.remove('hidden');
        window.location.href = '/Harvestly/index.php?action=logout';
    });
}

// Press Enter to login (only if login inputs exist)
document.querySelectorAll('.login-card input').forEach(inp => {
    inp.addEventListener('keydown', e => {
        if (e.key === 'Enter' && loginBtn) loginBtn.click();
    });
});

// ============================================================ */
// ACCEPT / REJECT BUTTONS
// ============================================================ */
document.querySelectorAll('.btn-sm.btn-accept').forEach(btn => {
    btn.addEventListener('click', function() {
        const card = this.closest('.request-card');
        if (card) {
            const badge = card.querySelector('.status-badge');
            if (badge) {
                badge.textContent = 'Accepted';
                badge.className = 'status-badge completed';
            }
        }
        this.disabled = true;
        this.style.opacity = '0.6';
        alert('✅ Delivery request accepted!');
    });
});

document.querySelectorAll('.btn-sm.btn-reject').forEach(btn => {
    btn.addEventListener('click', function() {
        const card = this.closest('.request-card');
        if (card) {
            const badge = card.querySelector('.status-badge');
            if (badge) {
                badge.textContent = 'Rejected';
                badge.className = 'status-badge pending';
            }
        }
        this.disabled = true;
        this.style.opacity = '0.6';
        alert('❌ Request rejected.');
    });
});

// ============================================================ */
// DELIVERY TRACKING – 7‑STEP STEpper
// ============================================================ */
const stages = [
    'Assigned',
    'Picked Up',
    'At Origin Hub',
    'In Transit',
    'At Destination Hub',
    'Out for Delivery',
    'Delivered'
];

let currentStageIndex = 2;
const stepperContainer = document.getElementById('timelineStepper');
const updateBtn = document.getElementById('updateStageBtn');
const statusBadge = document.getElementById('trackingStatusBadge');
const rescheduleBtn = document.getElementById('rescheduleBtn');

function renderStepper() {
    if (!stepperContainer) return;
    let html = '';
    stages.forEach((stage, idx) => {
        let dotClass = 'dot';
        if (idx < currentStageIndex) dotClass += ' completed';
        else if (idx === currentStageIndex) dotClass += ' active';
        const time = idx === 0 ? 'Oct 24, 2023 - 08:30 AM' :
                     idx === 1 ? 'Oct 24, 2023 - 10:15 AM' :
                     idx === 2 ? 'Expected by 2:30 PM' :
                     idx === 3 ? 'In progress' :
                     idx === 4 ? 'Pending' :
                     idx === 5 ? 'Pending' :
                     'Pending arrival';
        const desc = idx === 0 ? 'Confirmed by Harvestly' :
                     idx === 1 ? 'Picked up from Green Valley' :
                     idx === 2 ? '4.2km from delivery location.' :
                     idx === 3 ? 'En route to destination hub' :
                     idx === 4 ? 'Arrived at hub' :
                     idx === 5 ? 'On the way to buyer' :
                     'Delivery complete';
        html += `
            <div class="timeline-item">
                <div class="${dotClass}"></div>
                <div class="content">
                    <div class="title">${stage}</div>
                    <div class="time">${time}</div>
                    <div class="desc">${desc}</div>
                </div>
            </div>
        `;
    });
    stepperContainer.innerHTML = html;
    if (statusBadge) {
        statusBadge.textContent = stages[currentStageIndex];
        statusBadge.className = 'status-badge ' + (currentStageIndex === stages.length - 1 ? 'completed' : 'transit');
    }
    if (updateBtn) {
        if (currentStageIndex === stages.length - 1) {
            updateBtn.innerHTML = '<i class="fas fa-check"></i> Mark as Delivered';
        } else {
            updateBtn.innerHTML = '<i class="fas fa-arrow-right"></i> Update Stage';
        }
    }
}

if (stepperContainer) renderStepper();

if (updateBtn) {
    updateBtn.addEventListener('click', function() {
        if (currentStageIndex < stages.length - 1) {
            currentStageIndex++;
            renderStepper();
            if (currentStageIndex === stages.length - 1) {
                alert('✅ Delivery marked as Delivered! Payment released.');
            } else {
                alert(`📦 Stage updated to "${stages[currentStageIndex]}"`);
            }
        } else {
            alert('✅ Delivery already marked as Delivered.');
        }
    });
}

if (rescheduleBtn) {
    rescheduleBtn.addEventListener('click', function() {
        if (currentStageIndex === stages.length - 1) {
            alert('📅 Delivery rescheduled for tomorrow.');
        } else {
            alert('⚠️ Reschedule is only available at the final stage (Delivered).');
        }
    });
}

// ============================================================ */
// COMPLAINTS
// ============================================================ */
const submitComplaintBtn = document.getElementById('submitComplaintBtn');
if (submitComplaintBtn) {
    submitComplaintBtn.addEventListener('click', function() {
        const category = document.querySelector('.complaint-form select')?.value;
        const description = document.querySelector('.complaint-form textarea')?.value;
        if (!category || category === 'Select Category') {
            alert('⚠️ Please select a complaint category.');
            return;
        }
        if (!description || description.trim() === '') {
            alert('⚠️ Please describe your complaint.');
            return;
        }
        alert('✅ Complaint submitted! We will review it within 24-48 hours.');
        document.querySelector('.complaint-form textarea').value = '';
        document.querySelector('.complaint-form input[type="text"]').value = '';
    });
}

document.querySelectorAll('.response-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const row = this.closest('tr');
        const input = row.querySelector('.response-input');
        const response = input.value.trim();
        if (!response) {
            alert('⚠️ Please enter a response.');
            return;
        }
        alert(`✅ Response sent: "${response}"`);
        input.value = '';
    });
});

const complaintFile = document.getElementById('complaintFile');
if (complaintFile) {
    complaintFile.addEventListener('change', function(e) {
        if (this.files.length > 0) {
            const label = this.closest('.file-upload').querySelector('span');
            if (label) label.textContent = this.files.length + ' file(s) selected';
        }
    });
}

// ============================================================ */
// PROFILE
// ============================================================ */
const editBtn = document.getElementById('editProfileBtn');
const saveBtn = document.getElementById('saveProfileBtn');
const cancelBtn = document.getElementById('cancelProfileBtn');
if (editBtn) {
    editBtn.addEventListener('click', () => alert('✏️ Profile edit mode enabled.'));
}
if (saveBtn) {
    saveBtn.addEventListener('click', () => alert('✅ Profile updated successfully!'));
}
if (cancelBtn) {
    cancelBtn.addEventListener('click', () => alert('❌ Changes cancelled.'));
}

// ============================================================ */
// AVAILABILITY TOGGLE
// ============================================================ */
const toggleSwitch = document.querySelector('#availabilityToggle input[type="checkbox"]');
const toggleStatus = document.getElementById('availStatus');
if (toggleSwitch && toggleStatus) {
    toggleSwitch.addEventListener('change', function() {
        if (this.checked) {
            toggleStatus.textContent = 'Available';
            toggleStatus.style.color = 'var(--md-primary)';
        } else {
            toggleStatus.textContent = 'Unavailable';
            toggleStatus.style.color = '#EF4444';
        }
    });
}

// ============================================================ */
// EARNINGS CHART – native Canvas implementation (no external library)
// ============================================================ */
function initEarningsChart() {
    const canvas = document.getElementById('earningsChart');
    if (!canvas) return;

    const rect = canvas.getBoundingClientRect();
    if (rect.width === 0) {
        setTimeout(initEarningsChart, 100);
        return;
    }

    const dpr = window.devicePixelRatio || 1;
    const width = Math.max(320, Math.floor(rect.width));
    const height = 230;
    canvas.width = width * dpr;
    canvas.height = height * dpr;
    canvas.style.height = height + 'px';

    const ctx = canvas.getContext('2d');
    if (!ctx) return;
    ctx.scale(dpr, dpr);
    ctx.clearRect(0, 0, width, height);

    const labels = ['May','Jun','Jul','Aug','Sep','Oct'];
    const values = [4500, 4900, 5200, 4800, 5600, 6100];
    const max = Math.max(...values) * 1.15;
    const left = 44, right = 16, top = 18, bottom = 34;
    const chartW = width - left - right;
    const chartH = height - top - bottom;
    const slot = chartW / values.length;
    const barW = Math.min(34, slot * 0.56);

    ctx.font = '12px Arial, sans-serif';
    ctx.textAlign = 'right';
    ctx.textBaseline = 'middle';
    ctx.fillStyle = '#667066';
    ctx.strokeStyle = 'rgba(0,0,0,.08)';

    for (let i = 0; i <= 4; i++) {
        const value = (max / 4) * i;
        const y = top + chartH - (value / max) * chartH;
        ctx.beginPath(); ctx.moveTo(left, y); ctx.lineTo(width-right, y); ctx.stroke();
        ctx.fillText('Rs. ' + Math.round(value).toLocaleString('en-LK'), left-6, y);
    }

    values.forEach((value, i) => {
        const x = left + slot*i + (slot-barW)/2;
        const h = (value/max)*chartH;
        const y = top + chartH - h;
        ctx.fillStyle = '#2E7D32';
        ctx.fillRect(x, y, barW, h);
        ctx.fillStyle = '#667066';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'top';
        ctx.fillText(labels[i], x + barW/2, top + chartH + 8);
    });
}

window.initEarningsChart = initEarningsChart;

document.addEventListener('DOMContentLoaded', function() {
    const earningsPage = document.getElementById('page-earnings');
    if (earningsPage && earningsPage.classList.contains('active')) {
        setTimeout(initEarningsChart, 100);
    }
});

console.log('🍃 Harvestly Courier Portal loaded successfully!');
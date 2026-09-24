/**
 * Harvestly Master Vanilla JavaScript (Zero External Libraries)
 * Handles modals, tabs, table filtering, local CSV export, and printing.
 */

document.addEventListener('DOMContentLoaded', () => {
  initModals();
  initTabs();
  initTableSearch();
  initMicroInteractions();
  initCSVExport();
  initPDFPrint();
});

initSidebarScroll();

function initSidebarScroll() {
  const sidebarNav = document.querySelector('.sidebar-nav');
  if (!sidebarNav) return;

  const savedScrollTop = sessionStorage.getItem('harvestly-admin-sidebar-scroll');
  if (savedScrollTop !== null) {
    sidebarNav.scrollTop = Number(savedScrollTop);
  }

  window.addEventListener('beforeunload', () => {
    sessionStorage.setItem('harvestly-admin-sidebar-scroll', String(sidebarNav.scrollTop));
  });
}

/**
 * Modal Dialog Handlers
 */
function initModals() {
  // Triggers to open modals
  document.querySelectorAll('[data-modal-target]').forEach(trigger => {
    trigger.addEventListener('click', (e) => {
      e.preventDefault();
      const targetId = trigger.getAttribute('data-modal-target');
      const modal = document.getElementById(targetId);
      if (modal) {
        modal.classList.add('open');
      }
    });
  });

  // Triggers to close modals
  document.querySelectorAll('[data-modal-close]').forEach(closeBtn => {
    closeBtn.addEventListener('click', (e) => {
      e.preventDefault();
      const modal = closeBtn.closest('.modal-backdrop');
      if (modal) {
        modal.classList.remove('open');
      }
    });
  });

  // Close when clicking background backdrop
  document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
    backdrop.addEventListener('click', (e) => {
      if (e.target === backdrop) {
        backdrop.classList.remove('open');
      }
    });
  });
}

/**
 * Tab Controller
 */
function initTabs() {
  document.querySelectorAll('.tabs-header').forEach(header => {
    const tabBtns = header.querySelectorAll('.tab-btn');
    tabBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        const tabTarget = btn.getAttribute('data-tab');
        
        // Remove active class from sibling buttons
        tabBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        // Toggle corresponding content panes
        const container = header.closest('.tabs-container') || document;
        container.querySelectorAll('.tab-pane').forEach(pane => {
          if (pane.getAttribute('id') === tabTarget) {
            pane.style.display = 'block';
          } else {
            pane.style.display = 'none';
          }
        });
      });
    });
  });
}

/**
 * Table Search & Dropdown Filter Handler
 */
function initTableSearch() {
  const searchInputs = document.querySelectorAll('[data-table-search]');
  searchInputs.forEach(input => {
    const tableId = input.getAttribute('data-table-search');
    const table = document.getElementById(tableId);
    if (!table) return;

    input.addEventListener('keyup', () => {
      const query = input.value.toLowerCase();
      const rows = table.querySelectorAll('tbody tr');
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(query)) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    });
  });
}

/**
 * Landing Page Micro-interactions
 */
function initMicroInteractions() {
  // Cart button click animation
  document.querySelectorAll('.btn-add-cart').forEach(button => {
    button.addEventListener('click', (e) => {
      e.stopPropagation();
      const originalText = button.innerHTML;
      button.innerHTML = '✔ Added';
      button.style.backgroundColor = 'var(--color-secondary)';
      setTimeout(() => {
        button.innerHTML = originalText;
        button.style.backgroundColor = '';
      }, 1200);
    });
  });
}

function initCSVExport() {
  document.querySelectorAll('[data-export-csv]').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const table = document.getElementById(btn.getAttribute('data-export-csv'));
      if (!table) return;

      const rows = Array.from(table.querySelectorAll('tr')).filter(row => row.style.display !== 'none');
      const csv = rows.map(row => Array.from(row.querySelectorAll('th, td')).map(col => {
        const text = col.innerText.replace(/(\r\n|\n|\r)/gm, ' ').replace(/"/g, '""').trim();
        return '"' + text + '"';
      }).join(',')).join('\n');

      const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
      const url = URL.createObjectURL(blob);
      const link = document.createElement('a');
      link.href = url;
      link.download = `Harvestly_Report_${new Date().toISOString().slice(0, 10)}.csv`;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      URL.revokeObjectURL(url);
    });
  });
}

function initPDFPrint() {
  document.querySelectorAll('[data-trigger-print]').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      window.print();
    });
  });
}


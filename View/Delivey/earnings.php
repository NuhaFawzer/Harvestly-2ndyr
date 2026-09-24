<div class="page <?= ($page === 'earnings' ? 'active' : '') ?>" id="page-earnings">

    <div class="section-header">
        <h3>Earnings Dashboard</h3>
        <button class="btn-outline"><i class="fas fa-download"></i> Download Report</button>
    </div>

    <!-- Earnings Summary -->
    <div class="earnings-summary">
        <div class="earning-box">
            <div class="amount green">Rs. 12,450.80</div>
            <div class="label">Total Earnings</div>
        </div>
        <div class="earning-box">
            <div class="amount orange">Rs. 840.25</div>
            <div class="label">Pending</div>
        </div>
        <div class="earning-box">
            <div class="amount blue">Rs. 11,610.55</div>
            <div class="label">Paid</div>
        </div>
        <div class="earning-box">
            <div class="amount green">Rs. 3,210.40</div>
            <div class="label">This Month</div>
        </div>
    </div>

    <!-- Chart + Regional -->
    <div class="earnings-grid">
        <div class="chart-container">
            <div class="chart-header">
                <span class="chart-title">Income Trends</span>
                <span class="chart-subtitle">Monthly</span>
            </div>
            <canvas id="earningsChart"></canvas>
        </div>
        <div>
            <div class="table-wrap">
                <div class="panel-title">Regional Performance</div>
                <div class="regional-list">
                    <div><span class="status-badge completed">45%</span> Downtown Logistics</div>
                    <div><span class="status-badge transit">32%</span> North Valley Routes</div>
                    <div><span class="status-badge pending">23%</span> Westside Hub</div>
                </div>
                <div class="referral-box">
                    <i class="fas fa-share-alt"></i>
                    <p><strong>Refer a Courier</strong></p>
                    <p style="font-size:12px;color:var(--gray-500);">Earn up to Rs. 250</p>
                    <button class="btn-sm btn-primary">Get Link</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending vs Paid per Delivery -->
    <div class="section-header" style="margin-top:24px;">
        <h3>Pending vs Paid per Delivery</h3>
    </div>
    <div class="table-wrap">
        <div class="table-scroll">
            <table>
                <thead>
                    <tr><th>Order ID</th><th>Delivery</th><th>Amount</th><th>Status</th><th>Release Note</th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>#HLY-8845</strong></td>
                        <td>Green Valley → WholeFoods</td>
                        <td>Rs. 8.75</td>
                        <td><span class="status-badge completed">Paid</span></td>
                        <td>Confirmed by buyer</td>
                    </tr>
                    <tr>
                        <td><strong>#HLY-8832</strong></td>
                        <td>Sunny Ridge → Urban Market</td>
                        <td>Rs. 0.00</td>
                        <td><span class="status-badge pending">Pending</span></td>
                        <td>Awaiting confirmation</td>
                    </tr>
                    <tr>
                        <td><strong>#HLY-8821</strong></td>
                        <td>O'Brien → Mercy Hospital</td>
                        <td>Rs. 15.40</td>
                        <td><span class="status-badge completed">Paid</span></td>
                        <td>Auto-release after 48h</td>
                    </tr>
                    <tr>
                        <td><strong>#HLY-8801</strong></td>
                        <td>Black Soil → Local Harvest</td>
                        <td>Rs. 12.78</td>
                        <td><span class="status-badge pending">Pending</span></td>
                        <td>Auto-release in 2h</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Payment History -->
    <div class="section-header" style="margin-top:24px;">
        <h3>Recent Payment History</h3>
    </div>
    <div class="table-wrap">
        <div class="table-scroll">
            <table>
                <thead>
                    <tr><th>Date</th><th>Transaction</th><th>Amount</th><th>Status</th><th>Method</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Oct 24, 2023</td>
                        <td>#TXN-9921</td>
                        <td>Rs. 1,420.50</td>
                        <td><span class="status-badge completed">Paid</span></td>
                        <td>Weekly Settlement</td>
                        <td><button class="btn-sm btn-outline">Details</button></td>
                    </tr>
                    <tr>
                        <td>Oct 21, 2023</td>
                        <td>#TXN-9844</td>
                        <td>Rs. 890.15</td>
                        <td><span class="status-badge progress">Processing</span></td>
                        <td>Digital Wallet</td>
                        <td><button class="btn-sm btn-outline">Details</button></td>
                    </tr>
                    <tr>
                        <td>Oct 18, 2023</td>
                        <td>#TXN-9750</td>
                        <td>Rs. 1,240.00</td>
                        <td><span class="status-badge pending">Scheduled</span></td>
                        <td>Weekly Settlement</td>
                        <td><button class="btn-sm btn-outline">Details</button></td>
                    </tr>
                    <tr>
                        <td>Oct 14, 2023</td>
                        <td>#TXN-9620</td>
                        <td>Rs. 2,100.30</td>
                        <td><span class="status-badge completed">Paid</span></td>
                        <td>Weekly Settlement</td>
                        <td><button class="btn-sm btn-outline">Details</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    (function() {
        const earningsPage = document.getElementById('page-earnings');
        if (earningsPage && earningsPage.classList.contains('active')) {
            if (typeof window.initEarningsChart === 'function') {
                setTimeout(window.initEarningsChart, 300);
            }
        }
    })();
</script>
<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <span class="stat-icon green"><i class="fas fa-clock"></i></span>
        <div class="stat-value">8</div>
        <div class="stat-label">Active Assignments</div>
    </div>
    <div class="stat-card">
        <span class="stat-icon blue"><i class="fas fa-check-circle"></i></span>
        <div class="stat-value">47</div>
        <div class="stat-label">Completed This Week</div>
    </div>
    <div class="stat-card">
        <span class="stat-icon orange"><i class="fas fa-dollar-sign"></i></span>
        <div class="stat-value">Rs. 840.25</div>
        <div class="stat-label">Pending Earnings</div>
    </div>
    <div class="stat-card">
        <span class="stat-icon purple"><i class="fas fa-star"></i></span>
        <div class="stat-value">4.8 ★</div>
        <div class="stat-label">Rating</div>
    </div>
</div>

<!-- Availability Toggle -->
<div class="availability-toggle-wrap">
    <span class="toggle-label">Availability</span>
    <div class="toggle-switch" id="availabilityToggle">
        <input type="checkbox" checked />
        <span class="slider"></span>
    </div>
    <span class="toggle-status" id="availStatus">Available</span>
</div>

<!-- Recent Delivery Requests -->
<div class="section-header">
    <h3>Recent Delivery Requests</h3>
    <a href="?page=requests" class="btn-outline">
        View All <i class="fas fa-arrow-right"></i>
    </a>
</div>
<div class="table-wrap">
    <div class="table-scroll">
        <table>
            <thead>
                <tr><th>ID</th><th>Pickup</th><th>Delivery</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>#HV-9982</strong></td>
                    <td>Sunny Valley Farm</td>
                    <td>Metropolis Market</td>
                    <td><button class="btn-sm btn-accept">Accept</button></td>
                </tr>
                <tr>
                    <td><strong>#HV-9985</strong></td>
                    <td>Oakwood Dairy</td>
                    <td>Green Grove Dist.</td>
                    <td><button class="btn-sm btn-accept">Accept</button></td>
                </tr>
                <tr>
                    <td><strong>#HV-9989</strong></td>
                    <td>Riverbend Orchards</td>
                    <td>Downtown Food Co.</td>
                    <td><button class="btn-sm btn-accept">Accept</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Recent Assigned Deliveries -->
<div class="section-header">
    <h3>Recent Assigned Deliveries</h3>
    <a href="?page=assigned" class="btn-outline">View All</a>
</div>
<div class="table-wrap">
    <div class="table-scroll">
        <table>
            <thead>
                <tr><th>Order ID</th><th>Status</th><th>ETA</th><th>View Status</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>#HV-9712</strong></td>
                    <td><span class="status-badge transit">IN TRANSIT</span></td>
                    <td>25 min</td>
                    <td><button class="btn-sm btn-primary"><i class="fas fa-route"></i> Status</button></td>
                </tr>
                <tr>
                    <td><strong>#HV-9715</strong></td>
                    <td><span class="status-badge picked">PICKED UP</span></td>
                    <td>15 min</td>
                    <td><button class="btn-sm btn-primary"><i class="fas fa-route"></i> Status</button></td>
                </tr>
                <tr>
                    <td><strong>#HV-9721</strong></td>
                    <td><span class="status-badge transit">IN TRANSIT</span></td>
                    <td>40 min</td>
                    <td><button class="btn-sm btn-primary"><i class="fas fa-route"></i> Status</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Bonus Banner -->
<div class="bonus-banner">
    <div>
        <h4><i class="fas fa-star"></i> Earn extra bonuses this weekend!</h4>
        <p>Complete 10 more deliveries to unlock the Rs. 150 Partner Reward.</p>
    </div>
    <button class="btn-white">View Performance</button>
</div>
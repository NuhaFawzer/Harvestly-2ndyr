<div class="section-header">
    <h3>Order Tracking: #HLY-9821</h3>
    <span class="status-badge transit" id="trackingStatusBadge">In Transit</span>
</div>

<div class="tracking-grid">
    <!-- Left Column -->
    <div>
        <!-- Journey Progress (7-step stepper) -->
        <div class="table-wrap">
            <div class="panel-title"><i class="fas fa-route"></i> Journey Progress</div>
            <div class="timeline" id="timelineStepper">
                <!-- Steps injected by JS -->
            </div>
        </div>

        <!-- Order Details -->
        <div class="table-wrap">
            <div class="order-details-grid">
                <div><strong>Organic Carrots</strong><br>Bulk <span class="text-muted">20kg</span></div>
                <div><strong>Honeycrisp Apples</strong><br>Retail <span class="text-muted">10ct</span></div>
                <div><strong>Weight</strong><br>24.5 kg</div>
                <div><strong>Handling</strong><br>Rs. 5.00</div>
                <div><strong>Delivery Fee</strong><br>Rs. 45.00</div>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div>
        <div class="table-wrap">
            <div class="farm-contact">
                <i class="fas fa-store"></i>
                <strong>Green Valley Organic Farms</strong>
                <span class="contact-name">Contact: Arthur</span>
            </div>
            <div class="action-buttons">
                <button class="btn-sm btn-primary full-width" id="updateStageBtn">
                    <i class="fas fa-arrow-right"></i> Update Stage
                </button>
                <button class="btn-sm btn-warning full-width" id="rescheduleBtn">
                    <i class="fas fa-home"></i> Buyer Not Home? Reschedule
                </button>
            </div>
        </div>

        <div class="table-wrap">
            <div class="live-location">
                <i class="fas fa-route" style="color:var(--md-primary);"></i>
                <strong>District Route Status</strong>
                <div class="map-placeholder">
                    <i class="fas fa-route"></i> <span>No live map — district route tracking</span>
                </div>
                <div class="buyer-info">
                    <strong>Harvestly Buyer</strong><br>
                    Sarah Thompson<br>
                    <span class="text-muted">3410 Arden Way, Colombo</span>
                </div>
            </div>
        </div>
    </div>
</div>
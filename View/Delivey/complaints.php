<div class="section-header">
    <h3>Courier Complaint Submission</h3>
    <span class="status-badge transit"><i class="fas fa-headset"></i> Support</span>
</div>

<div class="complaints-grid">

    <!-- Form -->
    <div class="complaint-form">
        <h4>Submit a New Complaint</h4>
        <div class="form-group">
            <label>Complaint Category</label>
            <select>
                <option>Select Category</option>
                <option>Farmer Behavior</option>
                <option>Payment Discrepancy</option>
                <option>App Bug</option>
                <option>Delivery Issue</option>
            </select>
        </div>
        <div class="form-group">
            <label>Delivery Order ID</label>
            <input type="text" placeholder="e.g. HV-88291" />
        </div>
        <div class="form-group">
            <label>Complaint Description</label>
            <textarea placeholder="Provide detailed information..."></textarea>
        </div>
        <div class="form-group">
            <label>Upload Evidence</label>
            <div class="file-upload" onclick="document.getElementById('complaintFile').click()">
                <i class="fas fa-cloud-upload-alt"></i>
                <span>Upload Evidence</span>
                <input type="file" id="complaintFile" style="display:none;" />
            </div>
        </div>
        <button class="btn-primary" style="width:auto;padding:10px 32px;" id="submitComplaintBtn">
            <i class="fas fa-paper-plane"></i> Submit Complaint
        </button>
    </div>

    <!-- Right Side -->
    <div>

        <!-- Commitment -->
        <div class="commitment-box">
            <h4><i class="fas fa-shield-alt"></i> Our Commitment</h4>
            <p>Reviewed within 24-48 hours.</p>
            <div class="commitment-tags">
                <span><i class="fas fa-check-circle"></i> Transparent</span>
                <span><i class="fas fa-check-circle"></i> Safety Protocols</span>
                <span><i class="fas fa-check-circle"></i> Fair Payment</span>
            </div>
        </div>

        <!-- Previous Complaints with Response -->
        <h4 style="margin:20px 0 12px;">Previous Complaints</h4>
        <div class="table-wrap">
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr><th>Case ID</th><th>Category</th><th>Date</th><th>Status</th><th>Response</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#CAS-88210</td>
                            <td>Farmer Behavior</td>
                            <td>Oct 12, 2023</td>
                            <td><span class="status-badge completed">RESOLVED</span></td>
                            <td><input type="text" class="response-input" placeholder="Add response..." /></td>
                            <td><button class="btn-sm btn-primary response-btn">Send</button></td>
                        </tr>
                        <tr>
                            <td>#CAS-89012</td>
                            <td>Payment</td>
                            <td>Nov 04, 2023</td>
                            <td><span class="status-badge progress">IN PROGRESS</span></td>
                            <td><input type="text" class="response-input" placeholder="Add response..." /></td>
                            <td><button class="btn-sm btn-primary response-btn">Send</button></td>
                        </tr>
                        <tr>
                            <td>#CAS-89155</td>
                            <td>App Bug</td>
                            <td>Nov 15, 2023</td>
                            <td><span class="status-badge review">UNDER REVIEW</span></td>
                            <td><input type="text" class="response-input" placeholder="Add response..." /></td>
                            <td><button class="btn-sm btn-primary response-btn">Send</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
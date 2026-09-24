const HARVESTLY_BASE = document.body.dataset.baseUrl || '/Harvestly';
const ORDER_ID = document.body.dataset.orderId || '';
window.ORDER_ID = ORDER_ID;

document.addEventListener(
    "DOMContentLoaded",
    function () {

        /*
         * CONFIRM RECEIVED
         */

        const confirmButton =
            document.getElementById(
                "confirmReceived"
            );

        if (confirmButton) {

            confirmButton.addEventListener(
                "click",
                function () {

                    if (
                        confirmButton.disabled
                    ) {
                        return;
                    }

                    const confirmed =
                        confirm(
                            "Have you received this order?"
                        );

                    if (!confirmed) {
                        return;
                    }

                    confirmButton.disabled = true;

                    fetch('/Harvestly/Controller/Buyer/OrderTrackingController.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: new URLSearchParams({action:'received', order_id: ORDER_ID})
                    }).catch(function(error){ console.error(error); });

                    confirmButton.innerHTML =
                        "<span>✓</span> Received";

                    const note =
                        document.querySelector(
                            ".confirm-note"
                        );

                    if (note) {

                        note.textContent =
                            "Order received successfully.";
                    }

                }
            );
        }


        /*
         * COMPLAINT MODAL
         */

        const modal =
            document.getElementById(
                "complaintModal"
            );

        const form =
            document.getElementById(
                "complaintForm"
            );


        window.openComplaint =
            function () {

                if (!modal) {
                    return;
                }

                modal.style.display =
                    "flex";

                document.body.classList.add(
                    "modal-open"
                );
            };


        window.closeComplaint =
            function () {

                if (!modal) {
                    return;
                }

                modal.style.display =
                    "none";

                document.body.classList.remove(
                    "modal-open"
                );
            };


        /*
         * Close by clicking outside
         */

        if (modal) {

            modal.addEventListener(
                "click",
                function (event) {

                    if (
                        event.target === modal
                    ) {

                        window.closeComplaint();

                    }

                }
            );
        }


        /*
         * Complaint submit
         */

        if (form) {

            form.addEventListener(
                "submit",
                async function (event) {

                    event.preventDefault();

                    const formData = new FormData(form);
                    formData.append('submit_complaint', '1');
                    formData.set('order_id', ORDER_ID);
                    try {
                        const response = await fetch('/Harvestly/Controller/Buyer/FeedbackController.php', {
                            method: 'POST',
                            body: formData,
                            headers: {'X-Requested-With': 'XMLHttpRequest'}
                        });
                        const data = await response.json();
                        if (!response.ok || !data.success) {
                            throw new Error(data.message || 'Unable to submit complaint.');
                        }
                        alert(data.message || 'Your complaint has been submitted successfully.');
                        form.reset();
                        window.closeComplaint();
                    } catch (error) {
                        alert(error.message || 'Unable to submit complaint.');
                    }

                }
            );
        }


        /*
         * ESC closes modal
         */

        document.addEventListener(
            "keydown",
            function (event) {

                if (
                    event.key === "Escape"
                ) {

                    window.closeComplaint();

                }

            }
        );

    }
);
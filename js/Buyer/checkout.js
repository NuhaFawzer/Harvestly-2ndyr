"use strict";

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("checkoutForm");
    const confirmButton = document.getElementById("confirmOrderBtn");
    const paymentMethods = document.querySelectorAll(".payment-method");
    const cardDetails = document.getElementById("cardDetails");
    const bankDetails = document.getElementById("bankDetails");
    const phone = document.getElementById("phone") || form?.querySelector('[name="phone"]');
    const modal = document.getElementById("successModal");
    const successOrderId = document.getElementById("successOrderId");
    const viewOrders = document.getElementById("viewOrders");

    if (!form || !confirmButton) return;

    function setPaymentMethod(method) {
        paymentMethods.forEach(option => {
            const radio = option.querySelector('input[type="radio"]');
            option.classList.toggle("selected", radio?.value === method);
        });

        // No card number / expiry / CVV fields are collected by Harvestly.
        cardDetails?.classList.toggle("hidden", method !== "card");
        bankDetails?.classList.toggle("hidden", method !== "bank");
    }

    paymentMethods.forEach(option => {
        option.addEventListener("click", () => {
            const radio = option.querySelector('input[type="radio"]');
            if (!radio) return;
            radio.checked = true;
            setPaymentMethod(radio.value);
        });
    });

    const selected = form.querySelector('input[name="payment"]:checked');
    setPaymentMethod(selected?.value || "card");

    phone?.addEventListener("input", function () {
        this.value = this.value.replace(/[^\d+ ]/g, "").slice(0, 15);
    });

    form.addEventListener("submit", async event => {
        event.preventDefault();

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const phoneDigits = (phone?.value || "").replace(/\D/g, "");
        if (phoneDigits.length < 9 || phoneDigits.length > 12) {
            alert("Please enter a valid phone number.");
            phone?.focus();
            return;
        }

        const payment = form.querySelector('input[name="payment"]:checked');
        if (!payment) {
            alert("Please select a payment method.");
            return;
        }

        confirmButton.disabled = true;
        const originalHTML = confirmButton.innerHTML;
        confirmButton.innerHTML = '<span>Processing...</span><span class="material-symbols-outlined">hourglass_top</span>';

        try {
            const response = await fetch("/Harvestly/Controller/Buyer/CheckoutController.php", {
                method: "POST",
                body: new FormData(form),
                headers: { "X-Requested-With": "XMLHttpRequest" }
            });

            const data = await response.json();
            if (!response.ok || !data.success) throw new Error(data.message || "Unable to place the order.");

            if (successOrderId) successOrderId.textContent = "#" + data.order.id;
            if (modal) {
                modal.classList.remove("hidden");
            } else {
                window.location.href = "/Harvestly/Controller/Buyer/OrdersController.php";
            }
        } catch (error) {
            alert(error.message || "Something went wrong.");
            confirmButton.disabled = false;
            confirmButton.innerHTML = originalHTML;
        }
    });

    viewOrders?.addEventListener("click", () => {
        window.location.href = "/Harvestly/Controller/Buyer/OrdersController.php";
    });
});

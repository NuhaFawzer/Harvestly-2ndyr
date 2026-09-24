document.addEventListener("DOMContentLoaded", function () {

    const imageInput = document.getElementById("profileImage");
    const profilePreview = document.getElementById("profilePreview");
    const navProfileImage = document.getElementById("navProfileImage");

    if (imageInput) {

        imageInput.addEventListener("change", function () {

            const file = this.files[0];

            if (!file) {
                return;
            }

            const allowedTypes = [
                "image/jpeg",
                "image/png",
                "image/webp"
            ];

            if (!allowedTypes.includes(file.type)) {

                alert(
                    "Please upload a JPG, PNG or WEBP image."
                );

                this.value = "";
                return;
            }

            if (file.size > 5 * 1024 * 1024) {

                alert(
                    "Image size must be less than 5MB."
                );

                this.value = "";
                return;
            }

            const reader = new FileReader();

            reader.onload = function (event) {

                profilePreview.src = event.target.result;

                if (navProfileImage) {
                    navProfileImage.src =
                        event.target.result;
                }
            };

            reader.readAsDataURL(file);

        });

    }

});


function resetProfile() {

    if (
        confirm(
            "Discard your changes?"
        )
    ) {

        window.location.reload();

    }

}
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("profileForm");

    if (!form) return;

    form.addEventListener("submit", function (event) {
        const email = form.querySelector('[name="email"]');
        const phone = form.querySelector('[name="phone"]');

        if (email && !email.checkValidity()) {
            event.preventDefault();
            email.reportValidity();
            return;
        }

        if (phone && phone.value.trim() !== '' && !/^[0-9+\-\s()]{7,20}$/.test(phone.value.trim())) {
            event.preventDefault();
            alert("Please enter a valid contact number.");
            phone.focus();
        }
    });
});


function deleteBuyerAccount() {
    if (!window.confirm(
        "Delete your Buyer account permanently? This action cannot be undone."
    )) {
        return;
    }

    const form = document.getElementById("deleteAccountForm");

    if (form) {
        form.submit();
    }
}

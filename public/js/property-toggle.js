document.addEventListener("DOMContentLoaded", function() {
    // Function to initialize toggle buttons
    function initToggleButtons() {
        // Remove existing event listeners to prevent duplicates
        document.removeEventListener("click", handleToggleClick);
        document.addEventListener("click", handleToggleClick);
    }

    // Handle toggle button clicks
    function handleToggleClick(e) {
        if (e.target.closest(".toggle-active-btn")) {
            e.preventDefault();
            const button = e.target.closest(".toggle-active-btn");
            console.log(button);
            const propertyId = button.getAttribute("data-property-id");
            const currentState = button.getAttribute("data-current-state");

            // AJAX request
            fetch(`/admin/property/${propertyId}/toggle-active`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update button state
                    const newState = data.is_active ? "1" : "0";
                    button.setAttribute("data-current-state", newState);

                    if (data.is_active) {
                        // If now active, next action is deactivation
                        button.className = "btn btn-sm btn-warning toggle-active-btn";
                        button.innerHTML = '<i class="la la-times"></i> Deaktiválás';
                    } else {
                        // If now inactive, next action is activation
                        button.className = "btn btn-sm btn-success toggle-active-btn";
                        button.innerHTML = '<i class="la la-check"></i> Aktiválás';
                    }

                    // Success message
                    if (typeof toastr !== "undefined") {
                        toastr.success(data.message);
                    } else {
                        alert(data.message);
                    }
                }
            })
            .catch(error => {
                console.error("Error:", error);
                if (typeof toastr !== "undefined") {
                    toastr.error("Hiba történt a váltás során");
                } else {
                    alert("Hiba történt a váltás során");
                }
            });
        }
    }

    // Initialize on page load
    initToggleButtons();

    // Re-initialize when DataTable redraws (for AJAX-loaded content)
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        $(document).on('draw.dt', '#crudTable', function() {
            initToggleButtons();
        });
    }
});


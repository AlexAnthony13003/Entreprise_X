
    $(document).ready(function () {
        const productsCheckboxes = document.querySelectorAll('.form-check-input');
        const totalPriceField = document.getElementById('invoice_totalPrice');
        const totalPriceDisplay = document.getElementById('totalPriceDisplay');

        function updateTotalPrice() {
            let total = 0;
            productsCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    total += parseFloat(checkbox.dataset.price);
                }
            });

            // Mettre à jour la valeur du champ "totalPrice"
            totalPriceField.value = total.toFixed(2);
            totalPriceDisplay.textContent = 'Montant total : ' + total.toFixed(2) + '€';
        }

        productsCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateTotalPrice);
        });

        // Ajoutez un gestionnaire d'événement sur la soumission du formulaire
        document.querySelector('form').addEventListener('submit', function (event) {
            // Mettez à jour le champ "totalPrice" avant de soumettre le formulaire
            updateTotalPrice();
        });
    });
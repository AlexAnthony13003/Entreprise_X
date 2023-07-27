
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
        if (totalPriceDisplay) {
            totalPriceDisplay.textContent = 'Montant total : ' + total.toFixed(2) + '€';
        }

    }

    productsCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateTotalPrice);
    });

    // Ajoutez un gestionnaire d'événement sur la soumission du formulaire

});


// Code JavaScript pour gérer la recherche et le filtrage
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const cards = document.querySelectorAll('.card');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const searchText = searchInput.value.toLowerCase().trim();

            cards.forEach(card => {
                // Vérifier si l'élément possède la classe .customer-name
                const customerNameElement = card.querySelector('.invoice-name');
                const customerName = customerNameElement ? customerNameElement.innerText.toLowerCase() : '';

                // Vérifier si l'élément possède la classe .invoice-id
                const invoiceIdElement = card.querySelector('.invoice-id');
                const invoiceId = invoiceIdElement ? invoiceIdElement.innerText.toLowerCase() : '';

                if (customerName.includes(searchText) || invoiceId.includes(searchText)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
});

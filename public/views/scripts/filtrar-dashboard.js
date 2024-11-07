document.addEventListener('DOMContentLoaded', function() {
    const filterPrice = document.getElementById('filter-price');
    const filterLikes = document.getElementById('filter-likes');
    const productList = document.getElementById('product-list');

    filterPrice.addEventListener('click', function() {
        setActive(filterPrice);
        sortProducts('data-price');
    });

    filterLikes.addEventListener('click', function() {
        setActive(filterLikes);
        sortProducts('data-likes');
    });

    function setActive(activeElement) {
        document.querySelectorAll('.course-box ul li').forEach(li => li.classList.remove('active'));
        activeElement.classList.add('active');
    }

    function sortProducts(attribute) {
        const rows = Array.from(productList.getElementsByClassName('product-row'));
        rows.sort((a, b) => {
            const aValue = parseFloat(a.getAttribute(attribute));
            const bValue = parseFloat(b.getAttribute(attribute));
            if (attribute === 'data-likes') {
                return bValue - aValue; // Ordenar de mayor a menor
            } else {
                return aValue - bValue; // Ordenar de menor a mayor
            }
        });

        // Clear the current product list
        productList.innerHTML = '';

        // Append sorted rows
        rows.forEach(row => productList.appendChild(row));
    }
});
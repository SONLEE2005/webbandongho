<!DOCTYPE html>
<html lang="vi">
<head>  
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Our Collection | Timepiece</title>
    <link rel="stylesheet" href="public/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body>
    <header>
        <div class="container">
            <nav class="navbar">
                <a href="index.php" class="logo">Timepiece</a>
                <ul class="nav-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="products.php">Shop</a></li>
                    <li><a href="#">Collections</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="cart.php"><i class="fas fa-shopping-cart"></i> Cart</a></li>
                    <li>
                        <input type="text" id="search-input" placeholder="Tìm kiếm sản phẩm..." style="padding:5px; border-radius:4px; border:1px solid #ccc; margin-left:10px;">
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="products">
        <div class="container">
            <h2 class="section-title">Our Collection</h2>
            <div class="product-filters">
                <select id="category-filter" name="category">
                    <option value="all">Tất cả phân loại</option>
                    <option value="1">Đồng hồ nam</option>
                    <option value="2">Đồng hồ nữ</option>
                </select>
                <select id="brand-filter" name="brand">
                    <option value="all">Tất cả thương hiệu</option>
                    <option value="Casio">Casio</option>
                    <option value="Citizen">Citizen</option>
                    <option value="Orient">Orient</option>
                </select>
                <select id="price-filter" name="price">
                    <option value="all">Tất cả mức giá</option>
                    <option value="0-1000000">Dưới 1 triệu</option>
                    <option value="1000000-3000000">1 triệu - 3 triệu</option>
                    <option value="3000000-5000000">3 triệu - 5 triệu</option>
                </select>
                <button id="clear-filters-btn" style="margin-left: 10px; padding: 5px 10px; border-radius: 4px; border: 1px solid #ccc; background-color: #f0f0f0; cursor: pointer;">Xóa bộ lọc</button>
            </div>
            <div class="product-grid" id="product-grid">
                <!-- Products will be loaded dynamically here -->
            </div>
            <div id="pagination-container"></div>
        </div>
    </section>

    <footer>
        <div class="container">
            <ul class="footer-links">
                <li><a href="#">About Us</a></li>
                <li><a href="#">Contact</a></li>
                <li><a href="#">Shipping Policy</a></li>
                <li><a href="#">Returns</a></li>
            </ul>
            <p class="copyright">© 2023 Timepiece. All rights reserved.</p>
        </div>
    </footer>

    <script src="public/js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoryFilter = document.getElementById('category-filter');
            const brandFilter = document.getElementById('brand-filter');
            const priceFilter = document.getElementById('price-filter');
            const searchInput = document.getElementById('search-input');
            const productGrid = document.querySelector('.product-grid');
            const paginationContainer = document.getElementById('pagination-container');
            const clearFiltersBtn = document.getElementById('clear-filters-btn');

            // Set search input value from URL parameter if present
            const urlParams = new URLSearchParams(window.location.search);
            const searchParam = urlParams.get('search') || '';
            if (searchParam !== '') {
                searchInput.value = searchParam;
            }

            // Debounce function to limit the rate of function calls
            function debounce(func, delay) {
                let timeoutId;
                return function(...args) {
                    clearTimeout(timeoutId);
                    timeoutId = setTimeout(() => {
                        func.apply(this, args);
                    }, delay);
                };
            }

            function fetchProducts(page = 1) {
                const category = categoryFilter.value;
                const brand = brandFilter.value;
                const price = priceFilter.value;
                const searchQuery = searchInput.value.trim();

                let min_price = null;
                let max_price = null;
                if (price !== 'all') {
                    const prices = price.split('-');
                    min_price = prices[0];
                    max_price = prices[1];
                }

                const params = new URLSearchParams();
                if (category !== 'all') {
                    params.append('category', category);
                }
                if (brand !== 'all') {
                    params.append('brand', brand);
                }
                if (min_price !== null) {
                    params.append('min_price', min_price);
                }
                if (max_price !== null) {
                    params.append('max_price', max_price);
                }
                if (searchQuery !== '') {
                    params.append('search', searchQuery);
                }
                params.append('page', page);

                fetch('includes/get_products.php?' + params.toString())
                    .then(response => response.text())
                    .then(html => {
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = html;

                        const newProductCards = tempDiv.querySelector('#product-cards');
                        if (newProductCards) {
                            productGrid.innerHTML = newProductCards.innerHTML;
                        } else {
                            productGrid.innerHTML = '<p>Không tìm thấy sản phẩm nào.</p>';
                        }

                        const newPagination = tempDiv.querySelector('#pagination-controls');
                        if (newPagination) {
                            if (paginationContainer) {
                                paginationContainer.innerHTML = newPagination.innerHTML;
                                setupPaginationButtons();
                            }
                        } else {
                            if (paginationContainer) {
                                paginationContainer.innerHTML = '';
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching products:', error);
                    });
            }

            function setupPaginationButtons() {
                const buttons = paginationContainer.querySelectorAll('.pagination-btn');
                buttons.forEach(button => {
                    // Remove previous listeners by cloning the node
                    const newButton = button.cloneNode(true);
                    button.parentNode.replaceChild(newButton, button);
                    newButton.addEventListener('click', function() {
                        const page = this.getAttribute('data-page');
                        fetchProducts(page);
                    });
                });
            }

            // Event listeners with debounce for search input
            categoryFilter.addEventListener('change', () => fetchProducts(1));
            brandFilter.addEventListener('change', () => fetchProducts(1));
            priceFilter.addEventListener('change', () => fetchProducts(1));
            searchInput.addEventListener('input', debounce(() => fetchProducts(1), 300));

            // Clear filters button event
            clearFiltersBtn.addEventListener('click', () => {
                categoryFilter.value = 'all';
                brandFilter.value = 'all';
                priceFilter.value = 'all';
                searchInput.value = '';
                fetchProducts(1);
            });

            // Initial fetch
            fetchProducts();
        });
    </script>
</body>
</html>

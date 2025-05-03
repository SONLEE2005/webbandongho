<?php
    session_start();
    $tenNguoiDung = isset($_SESSION["hoTen"]) ? htmlspecialchars($_SESSION["hoTen"]) : null;
    $isLoggedIn = isset($_SESSION["email"]);
?> <!-- Thêm khúc này để lấy tên người dùng, email bắt đầu phiên làm việc-->
<!DOCTYPE html>
<html lang="vi">
<head>  
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Our Collection | Timepiece</title>
    <link rel="stylesheet" href="public/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style> /* style cho phần menu tài khoản */
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropbtn {
            cursor: pointer;
            padding: 10px;
            text-decoration: none;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 120px;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 4px;
        }
        .dropdown-content a {
            color: black;
            padding: 10px 16px;
            text-decoration: none;
            display: block;
        }
        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 999;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            width: 400px;
            height: 500px;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }
        .modal .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 20px;
            color: #0e0d0d;
            cursor: pointer;
            transition: color 0.3s;
            border-radius: 8px;
            border: 1px solid red;
            width: 30px;
            text-align: center;
            background-color: red;
        }
        .close-btn:hover {
            transform: scale(1.1);
        } 
    </style>
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
                    <li class="dropdown"> <!-- hiển thị tên người dùng sau khi đăng nhập -->
                        <a href="#" class="dropbtn">
                            <i class="fas fa-user"></i> 
                            <?php echo $isLoggedIn ? $tenNguoiDung : "Account"; ?>
                        </a>
                        <div class="dropdown-content">
                            <?php if (!$isLoggedIn): ?>
                                <a href="#" onclick="showModal('loginModal')">Login</a>
                                <a href="#" onclick="showModal('registerModal')">Register</a>
                            <?php else: ?>
                                <a href="./includes/logout.php">Logout</a>
                            <?php endif; ?>
                        </div>
                    </li> <!--   tới đây -->
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
    <!-- Modal đăng nhập -->
    <div id="loginModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close-btn" onclick="hideModal('loginModal')">&times;</span>
            <iframe src="includes/login.php" frameborder="0" style="width:100%; height:100%;"></iframe>
        </div>
    </div>

    <!-- Modal đăng ký -->
    <div id="registerModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close-btn" onclick="hideModal('registerModal')">&times;</span>
            <iframe src="includes/register.php" frameborder="0" style="width:100%; height:100%;"></iframe>
        </div>
    </div>
    <script> // script cho phần thông báo, xin chào, chuyển đổi giữa đăng nhập đăng ký
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);

            

            // Search form submit event to redirect to products.php with search query
            const searchForm = document.getElementById('search-form');
            searchForm.addEventListener('submit', function(event) {
                event.preventDefault();
                const query = document.getElementById('search-input').value.trim();
                if (query !== '') {
                    window.location.href = 'products.php?search=' + encodeURIComponent(query);
                }
            });
        };
        function showModal(id) {
            document.getElementById(id).style.display = 'flex';
        }
        function hideModal(id) {
            document.getElementById(id).style.display = 'none';
        }
    </script>

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

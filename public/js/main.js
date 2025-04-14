// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
  const mobileMenuBtn = document.createElement('button');
  mobileMenuBtn.className = 'mobile-menu-btn';
  mobileMenuBtn.innerHTML = '☰';
  document.querySelector('.navbar').appendChild(mobileMenuBtn);
  
  mobileMenuBtn.addEventListener('click', function() {
    const navLinks = document.querySelector('.nav-links');
    navLinks.style.display = navLinks.style.display === 'flex' ? 'none' : 'flex';
  });

  // Product carousel functionality
  const productCards = document.querySelectorAll('.product-card');
  productCards.forEach(card => {
    card.addEventListener('click', function() {
      const productId = this.getAttribute('data-product-id');
      window.location.href = `product-detail.php?id=${productId}`;
    });
  });

  // Cart functionality
  const addToCartButtons = document.querySelectorAll('.add-to-cart');
  addToCartButtons.forEach(button => {
    button.addEventListener('click', function(e) {
      e.stopPropagation();
      const productId = this.getAttribute('data-product-id');
      addToCart(productId);
    });
  });

  function addToCart(productId) {
    // This will be implemented with PHP later
    console.log(`Added product ${productId} to cart`);
    alert('Product added to cart!');
  }
});

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

  // Product click functionality - moved to bottom of DOMContentLoaded
  function setupProductCardClicks() {
    const productCards = document.querySelectorAll('.product-card');
    console.log('Found product cards:', productCards.length); // Debug log
    
    productCards.forEach(card => {
      // Remove any existing click handlers to avoid duplicates
      card.removeEventListener('click', handleProductCardClick);
      // Add new click handler
      card.addEventListener('click', handleProductCardClick);
    });
  }

  function handleProductCardClick(e) {
    console.log('Product card clicked - event:', e); // Detailed debug
    console.log('Clicked element:', this); // Log the clicked element
    const productId = this.getAttribute('data-product-id');
    if (!productId) {
      console.error('No product ID found on card:', this);
      return;
    }
    console.log('Redirecting to product ID:', productId);
    console.log('Full URL:', `product-detail.php?id=${productId}`);
    window.location.href = `product-detail.php?id=${productId}`;
  }

  // Initial setup with a slight delay
  setTimeout(setupProductCardClicks, 100); // Delay to ensure DOM is fully loaded

  // MutationObserver to detect dynamically added product cards
  const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
      if (mutation.addedNodes.length) {
        setupProductCardClicks();
      }
    });
  });

  // Start observing the product grid container
  const productGrid = document.querySelector('.product-grid');
  if (productGrid) {
    observer.observe(productGrid, {
      childList: true,
      subtree: true
    });
  }

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

  // Hero ads image rotation
  const heroAdImages = [
    "public/images/ads2.jpg",
    "public/images/ads3.jpg",
    
  ];

  let currentHeroAdIndex = 0;
  const heroAdImageElement = document.getElementById('heroAdImage');

  if (heroAdImageElement) {
    setInterval(() => {
      currentHeroAdIndex = (currentHeroAdIndex + 1) % heroAdImages.length;
      heroAdImageElement.src = heroAdImages[currentHeroAdIndex];
    }, 5000);
  }
});

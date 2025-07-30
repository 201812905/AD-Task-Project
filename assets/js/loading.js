function createLoadingOverlay() {
  if (!document.getElementById('loading-overlay')) {
    const overlay = document.createElement('div');
    overlay.id = 'loading-overlay';
    overlay.className = 'loading-overlay';
    overlay.innerHTML = `
      <div class="loading-cogwheel">
        <i class="fas fa-cog"></i>
      </div>
      <div class="loading-text">Loading Sacred Data...</div>
    `;
    document.body.appendChild(overlay);
  }
}

function addLoadingStyles() {
  if (!document.getElementById('loading-styles')) {
    const style = document.createElement('style');
    style.id = 'loading-styles';
    style.textContent = `
      .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 50%, #1a1a1a 100%);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
      }

      .loading-overlay.active {
        opacity: 1;
        visibility: visible;
      }

      .loading-cogwheel {
        margin-bottom: 20px;
      }

      .loading-cogwheel i {
        font-size: 60px;
        color: #d4af37;
        animation: cogRotate 1s linear infinite;
        filter: drop-shadow(0 0 10px rgba(212, 175, 55, 0.5));
      }

      @keyframes cogRotate {
        from {
          transform: rotate(0deg);
        }
        to {
          transform: rotate(360deg);
        }
      }

      .loading-text {
        font-family: 'Cinzel', serif;
        font-size: 1.3em;
        color: #d4af37;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        animation: pulse 1.5s ease-in-out infinite alternate;
      }

      @keyframes pulse {
        from {
          opacity: 0.7;
        }
        to {
          opacity: 1;
        }
      }
    `;
    document.head.appendChild(style);
  }
}

function initializeLoading() {
  createLoadingOverlay();
  addLoadingStyles();
  
  const loadingOverlay = document.getElementById('loading-overlay');
  
  // Show loading overlay initially
  loadingOverlay.classList.add('active');
  
  window.addEventListener('load', function() {
    loadingOverlay.classList.remove('active');
  });

  document.addEventListener('click', function(e) {
    const link = e.target.closest('a');
    if (link && link.href && !link.href.startsWith('#') && !link.target) {
      const currentDomain = window.location.origin;
      if (link.href.startsWith(currentDomain) || 
          link.href.startsWith('/') || 
          link.href.startsWith('./') || 
          link.href.startsWith('../')) {
        
        loadingOverlay.classList.add('active');
        
        setTimeout(function() {
          loadingOverlay.classList.remove('active');
        }, 5000);
      }
    }
  });

  window.addEventListener('popstate', function() {
    loadingOverlay.classList.add('active');
    setTimeout(function() {
      loadingOverlay.classList.remove('active');
    }, 500);
  });

  document.addEventListener('submit', function(e) {
    const form = e.target;
    if (form.method && form.method.toLowerCase() === 'post') {
      loadingOverlay.classList.add('active');
      
      setTimeout(function() {
        loadingOverlay.classList.remove('active');
      }, 10000);
    }
  });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initializeLoading);
} else {
  initializeLoading();
}

window.showLoading = function() {
  const overlay = document.getElementById('loading-overlay');
  if (overlay) overlay.classList.add('active');
};

window.hideLoading = function() {
  const overlay = document.getElementById('loading-overlay');
  if (overlay) overlay.classList.remove('active');
};

<div class="container-fluid bg-breadcrumb">
  <div class="overlay"></div>
  <div class="container text-center py-5 position-relative" style="max-width: 900px;">
    <h3 class="title-breadcrumb display-3 mb-4" style="padding-top: 20%;">
      <?php echo $pageTitle; ?>
    </h3>
    <ol class="breadcrumb justify-content-center mb-0">
      <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
      <li class="breadcrumb-item"><a href="contact.php">Kontak</a></li>
    </ol>
  </div>
  <div class="wave-container">
    <svg viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg">
    <path fill="rgb(90, 34, 150)" fill-opacity="1" d="M0,192L60,192C120,192,240,192,360,208C480,224,600,256,720,240C840,224,960,160,1080,128C1200,96,1320,96,1380,96L1440,96L1440,320L1380,320C1320,320,1200,320,1080,320C960,320,840,320,720,320C600,320,480,320,360,320C240,320,120,320,60,320L0,320Z"></path>
    </svg>
  </div>
</div>
<style>
  .bg-breadcrumb {
    position: relative;
    min-height: 750px;
    background: 
      url("admin/images/logo/502247371Logo LMM Black list White.png") no-repeat center center,
      radial-gradient(circle closest-side, rgb(153, 0, 255), rgba(128, 0, 255, 0.6));
    background-size: contain, cover;
    background-position: center 36%, center center;
    background-attachment: fixed;
  }
  .bg-breadcrumb .overlay {
    position: absolute;
    inset: 0;
    background-color: rgba(18, 18, 18, 0.81);
    z-index: 1;
    transform: translateX(-100%);
    transition: transform 2s ease;
  }
  .bg-breadcrumb .overlay.active {
    transform: translateX(0);
  }
  .bg-breadcrumb .container.position-relative {
    z-index: 3;
  }
  .wave-container {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    line-height: 0;
    z-index: 2;
  }
  .title-breadcrumb {
    color: #fff;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
  }
  .breadcrumb {
    background-color: rgba(18, 18, 18, 0.8);
    border-radius: 10px;
    padding: 0.5rem 1rem;
  }
  @media (max-width: 992px) {
    .bg-breadcrumb {
      min-height: 200px;
      background-size: contain, cover;
      background-position: center 20%, center center;
      background-attachment: scroll !important;
    }
    .bg-breadcrumb .container.position-relative {
      padding: 1rem;
      padding-top: 1rem !important;
    }
    .title-breadcrumb {
      font-size: 1.8rem;
    }
    .breadcrumb {
      font-size: 0.9rem;
      padding: 0.4rem 0.8rem;
    }
  }
  .py-5 {
    padding-top: 3rem !important;
    padding-bottom: 7rem !important;
  }
</style>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const overlay = document.querySelector('.bg-breadcrumb .overlay');
    overlay.classList.add("active");
    let lastScrollTop = window.pageYOffset || document.documentElement.scrollTop;
    window.addEventListener("scroll", function() {
      let st = window.pageYOffset || document.documentElement.scrollTop;
      if (st < lastScrollTop) {
        overlay.classList.add("active");
      } else {
        overlay.classList.remove("active");
      }
      lastScrollTop = st <= 0 ? 0 : st;
    });
  });
</script>
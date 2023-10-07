// Made By : Yusuf Limited
document.addEventListener("DOMContentLoaded", () => {
   const nav = document.querySelector("#nav");
   const hamburger = document.querySelector("#hamburger");
   const sidebar = document.querySelector(".sidebar");
   const closeSidebarButton = document.querySelector("#closeSidebar");

   const closeSidebar = () => {
      console.log("Closing sidebar");
      sidebar.classList.remove("active");
   };

   const openSidebar = () => {
      console.log("Opening sidebar");
      sidebar.classList.add("active");
   };

   hamburger.addEventListener("click", openSidebar);

   document.addEventListener("click", (event) => {
      if (sidebar.classList.contains("active")) {
         if (
            event.target == closeSidebarButton &&
            !sidebar.contains(event.target)
         ) {
            closeSidebar();
         }
      }
   });

   document.addEventListener("click", (event) => {
      if (event.target && event.target.id === "closeSidebar") {
         console.log("Close icon clicked");
         closeSidebar();
      }
   });

   const handleViewportChange = () => {
      if (window.innerWidth <= 768) {
         hamburger.style.display = "block";
         nav.querySelector(".links").style.display = "none";
      } else {
         hamburger.style.display = "none";
         nav.querySelector(".links").style.display = "flex";
      }
   };

   window.addEventListener("resize", handleViewportChange);
   handleViewportChange();

   const onScroll = (event) => {
      const scrollPosition = event.target.scrollingElement.scrollTop;
      if (scrollPosition > 10) {
         if (!nav.classList.contains("scrolled-down")) {
            nav.classList.add("scrolled-down");
         }
      } else {
         if (nav.classList.contains("scrolled-down")) {
            nav.classList.remove("scrolled-down");
         }
      }
   };

   document.addEventListener("scroll", onScroll);

   // carousel
   const carousel = document.querySelector('.carousel');
   const container = document.querySelector('.carousel-container');
   const items = document.querySelectorAll('.carousel-item');
   const dotsContainer = document.querySelector('.carousel-dots');
   const prevArrow = document.querySelector('.carousel-arrow.left');
   const nextArrow = document.querySelector('.carousel-arrow.right');
   const itemWidth = items[0].offsetWidth;
   const totalItems = items.length;
   let currentIndex = 0;
   let intervalId;

   function updateCarousel() {
      const offset = -currentIndex * itemWidth;
      container.style.transform = `translateX(${offset}px)`;
      updateDots();
   }

   function nextSlide() {
      clearInterval(intervalId);
      currentIndex = (currentIndex + 1) % totalItems;
      updateCarousel();
      resetInterval();
   }

   function prevSlide() {
      clearInterval(intervalId);
      currentIndex = (currentIndex - 1 + totalItems) % totalItems;
      updateCarousel();
      resetInterval();
   }

   prevArrow.addEventListener('click', prevSlide);
   nextArrow.addEventListener('click', nextSlide);

   function startAutoplay() {
      intervalId = setInterval(nextSlide, 5000);
   }

   function resetInterval() {
      clearInterval(intervalId);
      startAutoplay();
   }

   function createDots() {
      for (let i = 0; i < totalItems; i++) {
         const dot = document.createElement('div');
         dot.classList.add('carousel-dot');
         dot.addEventListener('click', () => {
            clearInterval(intervalId);
            currentIndex = i;
            updateCarousel();
            resetInterval();
         });
         dotsContainer.appendChild(dot);
      }
      updateDots();
   }

   function updateDots() {
      const dots = document.querySelectorAll('.carousel-dot');
      dots.forEach((dot, index) => {
         if (index === currentIndex) {
            dot.classList.add('active');
         } else {
            dot.classList.remove('active');
         }
      });
   }

   createDots();
   startAutoplay();

});

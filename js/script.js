// Hamburger menu
document.addEventListener("DOMContentLoaded", () => {
  const nav = document.querySelector(".navigation"),
        burger = document.querySelector(".hamburger");

  burger.addEventListener("click", () => {
    nav.classList.toggle("nav--open");
    burger.classList.toggle("hamburger--open");
  });
  nav.querySelectorAll(".nav_link").forEach(link =>
    link.addEventListener("click", () => {
      nav.classList.remove("nav--open");
      burger.classList.remove("hamburger--open");
    })
  );
});



//for smooth scroll to anchor links

document.addEventListener("DOMContentLoaded", () => {
  const btn = document.getElementById("btnScrollToTop");
  if (!btn) return;

  window.addEventListener("scroll", () => {
    if (window.pageYOffset > 100) {
      btn.style.display = "block";
    } else {
      btn.style.display = "none";
    }
  });

  btn.addEventListener("click", () => { window.scrollTo({top: 0, behavior: "smooth"});});
});


//using this in main.php for question cards for the slider
document.addEventListener('DOMContentLoaded', () => {
  const win = document.querySelector('.slider-window');
  const prev = document.querySelector('.slider-arrow.prev');
  const next = document.querySelector('.slider-arrow.next');
  if (!win || !prev || !next) return;

  prev.addEventListener('click', () => {
    win.scrollBy({ left: -win.clientWidth, behavior: 'smooth' });
  });

  next.addEventListener('click', () => {
    win.scrollBy({ left: win.clientWidth, behavior: 'smooth' });
  });
});

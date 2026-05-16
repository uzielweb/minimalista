// Sticky Header logic using Vanilla JS
window.addEventListener('scroll', function () {
    const header = document.querySelector('header');
    if (header) {
        if (window.scrollY > 1) {
            header.classList.add("sticky");
        } else {
            header.classList.remove("sticky");
        }
    }
});

// Dropdown Menu hover logic using Vanilla JS
document.querySelectorAll('.deeper.parent').forEach(function (element) {
    element.addEventListener('mouseenter', function () {
        const dropdown = this.querySelector('.dropdown-menu');
        if (dropdown) {
            dropdown.style.display = 'block';
            dropdown.style.opacity = '0';
            dropdown.style.transition = 'opacity 0.2s ease-in-out';
            setTimeout(() => dropdown.style.opacity = '1', 10);
        }
    });

    element.addEventListener('mouseleave', function () {
        const dropdown = this.querySelector('.dropdown-menu');
        if (dropdown) {
            dropdown.style.opacity = '0';
            setTimeout(() => dropdown.style.display = 'none', 200);
        }
    });
});

// Back to Top Logic
const backToTop = document.getElementById('back-top');
if (backToTop) {
    // Hide initially
    backToTop.style.display = 'none';
    backToTop.style.transition = 'opacity 0.3s ease-in-out';

    window.addEventListener('scroll', function () {
        if (window.scrollY > 300) {
            if (backToTop.style.display === 'none') {
                backToTop.style.display = 'block';
                setTimeout(() => backToTop.style.opacity = '1', 10);
            }
        } else {
            backToTop.style.opacity = '0';
            setTimeout(() => {
                if (window.scrollY <= 300) backToTop.style.display = 'none';
            }, 300);
        }
    });

    backToTop.addEventListener('click', function (e) {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// Check for empty divs
document.querySelectorAll('div').forEach(function(div) {
    if (div.innerHTML.trim() === '') {
        div.classList.add('is-empty');
    }
});

// Animate on Scroll using Intersection Observer
document.addEventListener('DOMContentLoaded', function () {
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const element = entry.target;
                element.classList.add('animate__animated');
                element.style.visibility = 'visible';
                element.style.opacity = '1';
                observer.unobserve(element);
            }
        });
    }, observerOptions);

    const animatedElements = document.querySelectorAll('.animate__on-scroll');
    animatedElements.forEach(el => {
        el.style.visibility = 'hidden';
        el.style.opacity = '0';
        observer.observe(el);
    });
});

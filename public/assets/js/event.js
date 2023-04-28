const slidingNewsletter = document.querySelector('.slide-article');

window.addEventListener('scroll', () => {
    const { scrollTop, clientHeight } = document.documentElement;

    const topElementToTopViewport = slidingNewsletter.getBoundingClientRect().top;

    if (scrollTop > topElementToTopViewport - clientHeight * 0.80) {
        slidingNewsletter.classList.add('active');
    }
});
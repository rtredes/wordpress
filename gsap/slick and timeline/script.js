


const timeline = gsap.timeline()
const stackImages = document.querySelectorAll(".stack-wrap > div")
const initial = [1, 1.9, 2.1, 2.9, 3.5, 5.6]

gsap.defaults({
    ease: "power1.inOut",
    duration: .5,
});

stackImages.forEach((img, index) => {
    timeline.to(img, {
        y: (-100 * initial[index - 1]),
        duration: 0
    })
})

timeline.pause()

timeline.to(stackImages[6], {
    y: (-100 * initial[5]),
    opacity: 1,
    duration: 0,
}, "part0").to(stackImages[6], {
    y: -100 * 7,
    opacity: 0
}).to(stackImages[5], {
    y: -100 * 5,
    opacity: 1,
}, "<")

.to(stackImages[5], {
    y: -100 * 7,
    opacity: 0
}, "part1").to(stackImages[4], {
    y: -100 * 5,
    ease: "power2.inOut",
    opacity: 1,
}, "<")

.to(stackImages[4], {
    y: -100 * 7,
    opacity: 0
}, "part2").to(stackImages[3], {
    y: -100 * 5,
    opacity: 1,
}, "<")

.to(stackImages[3], {
    y: -100 * 7,
    opacity: 0
}, "part3").to(stackImages[2], {
    y: -100 * 5,
    opacity: 1,
}, "<")

.to(stackImages[2], {
    y: -100 * 7,
    opacity: 0
}, "part4").to(stackImages[1], {
    y: -100 * 5,
    opacity: 1,
}, "<")

.to(stackImages[1], {
    y: -100 * 7,
    opacity: 0
}, "part5").to(stackImages[0], {
    y: -100 * 2,
    opacity: 1,
}, "<")


jQuery(function ($) {
    $(document).ready(function () {
        const selector = $('#slider-container')

        // selector.css('display', 'inline-block') // Prevents display Error
        selector.slick({
            dots: false,
            arrows: false,
            speed: 300,
            infinite: false,
            autoplay: false,
            autoplaySpeed: 2000,
            slidesToShow: 1,
            slidesToScroll: 1,
        });

        const images = [...document.querySelectorAll(".stack-wrap div")].reverse()
        images.forEach((img, i) => {
            img.onclick = function(){
                selector.slick('slickGoTo', i)
            }
        })

        selector.on('beforeChange', function (event, slick, currentSlide, nextSlide) {
            timeline.tweenTo(`part${nextSlide}`)
        });
    });
});
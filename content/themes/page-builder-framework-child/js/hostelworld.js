document.addEventListener('DOMContentLoaded', function () {
	document.querySelector("#wpbf-mobile-search-toggle").addEventListener('click', () => {
		document.querySelector('body').classList.toggle('mobile-search-open');
		document.querySelector("#wpbf-mobile-search-toggle").classList.toggle('wpbff-search');
		document.querySelector("#wpbf-mobile-search-toggle").classList.toggle('wpbff-times');
		document.querySelector('.wpbf-mobile-menu-container').style.display = 'none';
		document.querySelector('.wpbf-mobile-menu-container').classList.remove('active');
		document.querySelector("#wpbf-mobile-menu-toggle").classList.add('wpbff-hamburger');
		document.querySelector("#wpbf-mobile-menu-toggle").classList.remove('wpbff-times');
	});

	document.querySelector("#wpbf-mobile-menu-toggle").addEventListener('click', () => {
		document.querySelector('body').classList.remove('mobile-search-open');
		document.querySelector("#wpbf-mobile-search-toggle").classList.add('wpbff-search');
		document.querySelector("#wpbf-mobile-search-toggle").classList.remove('wpbff-times');
	});

	const scrollHandler = () => {
		const bodyHeight = document.body.scrollHeight;
		if (bodyHeight > 2 * window.innerHeight) {
			if(document.documentElement.scrollTop > 2 * window.innerHeight) {
				document.querySelector('.qr-wrapper').classList.add('visible');
				document.querySelector('.getapp-wrapper').classList.add('visible');
				document.removeEventListener('scroll',scrollHandler);
			}
		} else {
			document.querySelector('.qr-wrapper').classList.add('visible');
			document.querySelector('.getapp-wrapper').classList.add('visible');
			document.removeEventListener('scroll',scrollHandler);
		}
		console.log(bodyHeight, window.innerHeight, document.documentElement.scrollTop);
	};

	document.addEventListener('scroll',scrollHandler);
	document.dispatchEvent(new Event('scroll'));
});
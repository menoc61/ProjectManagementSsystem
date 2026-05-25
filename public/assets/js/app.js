document.addEventListener('click', function (event) {
    const link = event.target.closest('[data-confirm]');
    if (!link) {
        return;
    }

    if (!window.confirm(link.getAttribute('data-confirm'))) {
        event.preventDefault();
    }
});

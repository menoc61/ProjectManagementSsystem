document.addEventListener('click', function (event) {
    const link = event.target.closest('[data-confirm]');
    if (link) {
        if (!window.confirm(link.getAttribute('data-confirm'))) {
            event.preventDefault();
        }
    }

    const toggle = event.target.closest('[data-toggle-password]');
    if (toggle) {
        const input = document.querySelector(toggle.getAttribute('data-toggle-password'));
        const icon = toggle.querySelector('i');
        if (input) {
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            if (icon) {
                icon.className = isHidden ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
            }
        }
    }

    const roleOption = event.target.closest('[data-username][data-password]');
    if (roleOption) {
        const username = document.getElementById('username');
        const password = document.getElementById('password');
        if (username && password) {
            username.value = roleOption.getAttribute('data-username') || '';
            password.value = roleOption.getAttribute('data-password') || '';
            username.focus();
        }
    }
});

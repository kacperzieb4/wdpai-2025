document.addEventListener('DOMContentLoaded', () => {

    const roleSelect = document.getElementById('roleSelect');
    const companySelect = document.getElementById('companySelect');

    if (!roleSelect || !companySelect) {
        return;
    }

    function updateCompanyField() {
        const roleText = roleSelect.options[roleSelect.selectedIndex]?.text;

        if (roleText === 'ADMIN' || roleText === 'MODERATOR') {
            companySelect.disabled = true;
            companySelect.removeAttribute('required');
        } else {
            companySelect.disabled = false;
            companySelect.setAttribute('required', 'required');
        }
    }

    roleSelect.addEventListener('change', updateCompanyField);
    updateCompanyField();

});

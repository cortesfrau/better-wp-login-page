document.addEventListener('DOMContentLoaded', function() {

  const loginForm = document.getElementById('login');
  const languageSwitcher = document.getElementById('language-switcher');

  /**
   * Move language switcher
   */
  if (languageSwitcher) {
    loginForm.appendChild(languageSwitcher);
  }

  /**
   * Center Login Form Vertically
   */
  const offset = loginForm.offsetHeight * 0.5;
  loginForm.style.top = `calc(50vh - ${offset}px)`;

});

import jQuery from './jquery';

/**
 * З Bootstrap 3 потрібен лише collapse (кнопка меню на мобільних),
 * тому не підключаємо весь бандл.
 */
import 'bootstrap/js/transition.js';
import 'bootstrap/js/collapse.js';

/**
 * Додаємо CSRF-токен до кожного AJAX-запиту, щоб Laravel міг його перевірити.
 */
let tokenElement = document.head.querySelector('meta[name="csrf-token"]');

if (tokenElement) {
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': tokenElement.content,
        },
    });
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// resources/js/admin/admin.js

// وارد کردن کتابخانه‌های اصلی
import $ from 'jquery';
window.jQuery = window.$ = $;

import 'bootstrap';
import 'jquery.easing';
import '@fortawesome/fontawesome-free/css/all.min.css'; // (اگر از روش npm برای فونت آسام استفاده کردید)

import Swal from 'sweetalert2';

// چک می‌کنیم آیا پیغامی برای نمایش وجود دارد یا نه
const swalSuccess = document.body.getAttribute('data-swal-success');
if (swalSuccess) {
    Swal.fire({
        title: 'عالی!',
        text: swalSuccess,
        icon: 'success',
        confirmButtonText: 'باشه'
    });
}



// resources/js/admin/admin.js

// وارد کردن کتابخانه‌های اصلی
import $ from 'jquery';
window.jQuery = window.$ = $;

import 'bootstrap';
import 'jquery.easing';
import '@fortawesome/fontawesome-free/css/all.min.css'; // (اگر از روش npm برای فونت آسام استفاده کردید)
import 'bootstrap-select';
import 'bootstrap-select/dist/css/bootstrap-select.min.css';
import 'bootstrap-select/dist/js/i18n/defaults-fa_IR.js';
import './category-form';
import './product';
import './files/jquery.czMore-latest.js';

$(function () {
  const $cz = $('#czContainer');
  console.log('czMore?', typeof $.fn.czMore, 'container:', $cz.length);

  if ($cz.length && typeof $.fn.czMore === 'function') {
    $cz.czMore({
      max: 20,
      min: 0,
      styleOverride: true, // استایل پیشفرض پلاگین خاموش؛ آیکن‌ها را با CSS می‌دهیم
      onLoad(i){ console.log('cz onLoad', i); },
      onAdd(i){ console.log('cz onAdd', i); },
      onDelete(i){ console.log('cz onDelete', i); },
    });
  }
});

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


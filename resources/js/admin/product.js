import $ from 'jquery';
window.$ = window.jQuery = $;

try {
  require('bootstrap-select/dist/js/bootstrap-select.min.js');
  require('bootstrap-select/dist/css/bootstrap-select.min.css');
} catch (e) {}

$(function () {
  console.log('[product.js] ready');

  const $sel   = $('#categorySelect');
  const $attrs = $('#attributes_section');     // باکس ویژگی‌ها
  const $wrap  = $('#attributesContainer');    // عنوان + czContainer
  const $span  = $('#variationName');          // نام متغیّر

  // اولِ کار، «هیچ‌چیز» نمایش داده نشود
  $attrs.hide().empty();
  $wrap.hide();
  $span.text('');

  if (!$sel.length) return;

  // برای selectpicker و معمولی
  $sel.off('change.product changed.bs.select.product')
      .on('change.product changed.bs.select.product', handleCategoryChange);

  function handleCategoryChange() {
    const id = $sel.val();

    // اگر کاربر انتخاب را پاک کرد: همه‌چیز پنهان و تمیز شود
    if (!id) {
      $attrs.hide().empty();
      $wrap.hide();
      $span.text('');
      return;
    }

    // ساخت آدرس
    let url = $sel.data('url');
    url = url
      ? String(url).replace('PLACEHOLDER', id)
      : `/admin-panel/management/category-attributes/${id}`;

    // فقط روی باکس ویژگی‌ها لودر بگذار (خودِ باکس هم فعلاً پنهان می‌ماند)
    setLoading($attrs.empty(), 'در حال دریافت ویژگی‌ها...');

    $.get(url)
      .done((res) => {
        const list = res?.attrubtes || res?.attributes || [];

        // نام متغیّر (اگر سرور برگرداند)
        const vName =
          res?.variation?.name ??
          (list.find(a => a?.pivot?.is_variation == 1)?.name) ?? '';
        $span.text(vName || '');

        // رندر ویژگی‌ها — مطابق rule: attributes_section و (اختیاری) attribute_ids[]
        $attrs.html(renderAttributes(list));

        // بعد از موفقیت، هر دو بخش را نمایش بده
        $attrs.stop(true,true).slideDown(150);
        $wrap.stop(true,true).slideDown(150);
      })
      .fail((xhr) => {
        $attrs.html(
          `<div class="col-12 alert alert-danger">مشکل در دریافت ویژگی‌ها (HTTP ${xhr.status})</div>`
        );
        // در خطا، همه‌چیز پنهان بماند
        $wrap.hide();
        $span.text('');
      });
  }
});

/* ---------------- کمک‌کننده‌ها ---------------- */

function renderAttributes(attributes = []) {
  if (!attributes.length) {
    return `<div class="col-12 text-muted py-2">هیچ ویژگی‌ای برای این دسته‌بندی ثبت نشده است.</div>`;
  }
  return attributes.map((attr) => {
    const id   = attr?.id ?? attr?.attribute_id ?? attr?.slug ?? attr?.name ?? '';
    const name = attr?.name ?? attr?.title ?? 'ویژگی';

    return `
      <div class="form-group col-md-3">
        <label>${escapeHtml(name)}</label>
        <!-- طبق rule: attributes_section[] -->
        <input type="text" class="form-control" name="attributes_section[]" />
        ${id ? `<input type="hidden" name="attribute_ids[]" value="${escapeHtml(id)}">` : ''}
      </div>
    `;
  }).join('');
}

function escapeHtml(s) {
  return String(s ?? '')
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#039;');
}

function setLoading($el, text) {
  $el.html(`
    <div class="col-12 text-center py-3">
      <div class="spinner-border" role="status" aria-hidden="true"></div>
      <div class="small mt-2 text-muted">${escapeHtml(text || 'در حال بارگذاری...')}</div>
    </div>
  `);
}

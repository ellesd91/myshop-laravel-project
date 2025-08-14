// document.addEventListener('DOMContentLoaded', function () {
//     // انتخاب سلکت باکس اصلی ویژگی‌ها
//     const attributesDropdown = $('#attributeSelect');

//     // اگر سلکت باکس اصلی در این صفحه وجود نداشت، هیچ کاری نکن
//     if (!attributesDropdown.length) {
//         return;
//     }

//     // انتخاب سلکت باکس‌های وابسته
//     const filterableDropdown = $('#attributeIsFilterSelect');
//     const variationDropdown = $('#variationsSelect');

//     // تابعی برای هماهنگ‌سازی دراپ‌داون‌های وابسته
//     function syncDependentDropdowns() {
//         // ابتدا دراپ‌داون‌های وابسته را خالی می‌کنیم
//         filterableDropdown.empty();
//         variationDropdown.empty();

//         // گزینه‌های انتخاب شده در دراپ‌داون اصلی را می‌گیریم
//         const selectedOptions = attributesDropdown.find('option:selected');

//         // اگر چیزی انتخاب نشده بود، آن‌ها را رفرش کرده و خارج می‌شویم
//         if (selectedOptions.length < 1) {
//             filterableDropdown.selectpicker('refresh');
//             variationDropdown.selectpicker('refresh');
//             return;
//         }

//         // یک گزینه پیش‌فرض برای دراپ‌داون «ویژگی متغیر» اضافه می‌کنیم
//         variationDropdown.append($('<option>', { value: '', text: 'انتخاب کنید' }));

//         // به ازای هر گزینه انتخاب شده، یک کپی از آن را در دو دراپ‌داون دیگر می‌سازیم
//         selectedOptions.each(function () {
//             const optionValue = $(this).val();
//             const optionText = $(this).text();

//             filterableDropdown.append($('<option>', { value: optionValue, text: optionText }));
//             variationDropdown.append($('<option>', { value: optionValue, text: optionText }));
//         });

//         // به پلاگین bootstrap-select می‌گوییم که خودش را رفرش کند
//         filterableDropdown.selectpicker('refresh');
//         variationDropdown.selectpicker('refresh');
//     }

//     // وقتی انتخاب دراپ‌داون اصلی تغییر کرد، تابع هماهنگ‌سازی را اجرا کن
//     attributesDropdown.on('changed.bs.select', syncDependentDropdowns);

//     // یک بار هم در ابتدای بارگذاری صفحه تابع را اجرا کن (برای خطاهای validation)
//     syncDependentDropdowns();
// });


document.addEventListener('DOMContentLoaded', function () {
  const $attr      = $('#attributeSelect');
  if (!$attr.length) return;

  const $filters   = $('#attributeIsFilterSelect');
  const $variation = $('#variationsSelect');

  // نکته: برای سبک‌شدن رندرهای بعدی، روی دو سلکت وابسته سرچ زنده رو خاموش کن
  // <select class="selectpicker" data-live-search="false" ...>

  // اطمینان از اینکه selectpicker روی attributeSelect فعال شده
  // (معمولاً layout اینو انجام می‌ده؛ اگر نه، خط زیر لازمه)
  $attr.selectpicker();

  function rebuild($select, html) {
    // خاموش کردن موقت پلاگین برای سرعت
    try { $select.selectpicker('destroy'); } catch(e) {}
    $select.html(html);
    // روشن کردن مجدد
    $select.selectpicker();
  }

  function sync() {
    const selected = $attr.val() || [];        // آیدی‌های انتخاب‌شده
    if (selected.length === 0) {
      rebuild($filters, '');
      rebuild($variation, '');
      return;
    }

    // یک‌باره HTML بساز (به‌جای append تکی)
    const allOpts = $attr[0].options;
    let optsHtml = '';
    for (let i = 0; i < allOpts.length; i++) {
      const o = allOpts[i];
      if (selected.includes(o.value)) {
        // از متن و مقدار همون option استفاده می‌کنیم
        optsHtml += `<option value="${o.value}">${o.text}</option>`;
      }
    }

    // برای متغیر یک گزینه‌ی پیش‌فرض هم اضافه کن
    const variationHtml = `<option value="">انتخاب کنید</option>${optsHtml}`;

    // یک‌باره تزریق و یک‌باره init (سریع‌تر از refreshهای متعدد)
    rebuild($filters, optsHtml);
    rebuild($variation, variationHtml);
  }

  // وقتی bootstrap-select خودش بارگذاری شد یا انتخاب‌ها عوض شد
  $attr.on('loaded.bs.select changed.bs.select', () => {
    // موکول به فریم بعد تا UI لاک نشه
    requestAnimationFrame(sync);
  });

  // اجرای اولیه—بعد از اینکه selectpicker روی attribute رندر شد
  setTimeout(sync, 0);
});

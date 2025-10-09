// window.filter = function () {
//   setTimeout(function () {
//     var selected = $('.variation:checked').map(function () {
//       return this.value;
//     }).get().join('-');

//     var $v = $('#filter-variation');

//     if (selected.length) {
//       // انتخاب داریم → مقدار بده و name را داشته باشه تا ارسال بشه
//       $v.val(selected).attr('name', 'variation');
//     } else {
//       // هیچ انتخابی نیست → مقدار خالی و name را بردار تا اصلاً توی URL نیاد
//       $v.val('').removeAttr('name');
//     }

//     $('#filter-form').trigger('submit');
//   }, 0);
// };

// console.log('Filter form submitted');


// window.filterAttributes = function () {
//   setTimeout(function () {
//     var joined = $('.attr:checked').map(function () { return this.value; }).get().join('-');

//     var $hidden = $('#filter-attr');
//     if (joined.length) {
//       $hidden.val(joined).attr('name', 'attribute');
//     } else {
//       $hidden.val('').removeAttr('name'); // مهم: وقتی چیزی انتخاب نیست، name برداشته شود
//     }

//     $('#filter-form').trigger('submit');
//   }, 0);
// };

// //برای مرتب سازی
// window.filter = function () {
//   setTimeout(function () {
//     // مدیریت ویژگی‌ها (variation)
//     var selected = $('.variation:checked').map(function () {
//       return this.value;
//     }).get().join('-');
//     var $v = $('#filter-variation');
//     if (selected.length) {
//       $v.val(selected).attr('name', 'variation');
//     } else {
//       $v.val('').removeAttr('name');
//     }

//     // مدیریت sortBy
//     var sortBy = $('#sort-by').val();
//     if (sortBy && sortBy !== 'default') {
//       $('#filter-sort-by').val(sortBy).attr('name', 'sortBy');
//     } else {
//       $('#filter-sort-by').val('').removeAttr('name');
//     }

//     $('#filter-form').trigger('submit');
//   }, 0);
// };


// window.filter = function () {
//   setTimeout(function () {
//     // 1) Attribute ها (ویژگی‌ها)
//     var attrJoined = $('.attr:checked').map(function () { return this.value; }).get().join('-');
//     var $attr = $('#filter-attr');
//     if (attrJoined.length) {
//       $attr.val(attrJoined).attr('name', 'attribute');
//     } else {
//       $attr.val('').removeAttr('name'); // نذار ?attribute= خالی ساخته بشه
//     }

//     // 2) Variation ها (سایز)
//     var varJoined = $('.variation:checked').map(function () { return this.value; }).get().join('-');
//     var $var = $('#filter-variation');
//     if (varJoined.length) {
//       $var.val(varJoined).attr('name', 'variation');
//     } else {
//       $var.val('').removeAttr('name'); // نذار ?variation= خالی ساخته بشه
//     }

//     // 3) مرتب‌سازی
//     var sortBy = $('#sort-by').val();
//     var $sort = $('#filter-sort-by');
//     if (sortBy && sortBy !== 'default') {
//       $sort.val(sortBy).attr('name', 'sortBy');
//     } else {
//       $sort.val('').removeAttr('name'); // نذار ?sortBy= خالی ساخته بشه
//     }

//     //برای سرچ
//       let search = $('#search-input').val();
//             if (search == "") {
//                 $('#filter-search').prop('disabled', true);
//             } else {
//                 $('#filter-search').val(search);
//             }

//     // submit
//     $('#filter-form').trigger('submit');
//   }, 0); // صبر کن تیک چک‌باکس ثبت شود
// };



document.addEventListener('click', function (e) {
  if (e.target.matches('.sidebar-widget-list-left a')) {
    e.preventDefault();
    const wrap = e.target.closest('.sidebar-widget-list-left');
    const cb = wrap && wrap.querySelector('input[type="checkbox"]');
    if (cb) {
      cb.checked = !cb.checked;
      window.filter(); // همون تابع یکی‌شده‌ی خودت
    }
  }
});

//این کد جاوا اسکریپت ایدی ها رو نمایش میده
// یک تابع واحد: اتربیوت‌ها + سایز + مرتب‌سازی + جست‌وجو + submit
// === فیلتر اصلی (اتربیوت + سایز + مرتب‌سازی + جستجو) ===
window.filter = function () {
  // صبر کن تیک/برداشت تیک روی چک‌باکس در DOM اعمال شود
  setTimeout(function () {
    // --- 1) Attribute ها: گروه‌بندی بر اساس data-attr-id ---
    var groups = {}; // { attrId: ["سفید","مشکی"], ... }

    $('.attr:checked').each(function () {
      var aid = $(this).data('attr-id');   // مثلا 3 یا 2
      if (!groups[aid]) groups[aid] = [];
      groups[aid].push(this.value);
    });

    // همه‌ی hiddenهای attribute را خالی و غیرفعال کن
    $('input[id^="filter-attribute-"]')
      .val('')
      .prop('disabled', true)
      .removeAttr('name');

    // هر گروه انتخاب‌شده را در hidden خودش بریز
    Object.keys(groups).forEach(function (aid) {
      $('#filter-attribute-' + aid)
        .val(groups[aid].join('-'))           // مثل "سفید-مشکی"
        .prop('disabled', false)              // تا در URL ارسال شود
        .attr('name', 'attribute[' + aid + ']');
    });

    // --- 2) Variation ها (سایز) ---
    var varJoined = $('.variation:checked').map(function () { return this.value; }).get().join('-');
    var $var = $('#filter-variation');
    if (varJoined.length) {
      $var.val(varJoined).prop('disabled', false).attr('name', 'variation');
    } else {
      $var.val('').prop('disabled', true).removeAttr('name');
    }

    // --- 3) مرتب‌سازی ---
    var sortBy = $('#sort-by').val();
    var $sort = $('#filter-sort-by');
    if (sortBy && sortBy !== 'default') {
      $sort.val(sortBy).prop('disabled', false).attr('name', 'sortBy');
    } else {
      $sort.val('').prop('disabled', true).removeAttr('name');
    }

    // --- 4) جستجو ---
    var search = $('#search-input').val();
    var $search = $('#filter-search');
    if (search && search.trim().length) {
      $search.val(search.trim()).prop('disabled', false).attr('name', 'search');
    } else {
      $search.val('').prop('disabled', true).removeAttr('name');
    }

    // ارسال فرم
    $('#filter-form').trigger('submit');
  }, 0);
};

// === ساخت URL خوانا مثل attribute[3]=... (بدون %5B %5D) ===
$(document).on('submit', '#filter-form', function (e) {
  e.preventDefault();
  var currentUrl = window.location.origin + window.location.pathname;
  var qs = $(this).serialize();                          // attribute%5B3%5D=%D8%B3%D9%81%DB%8C%D8%AF-...
  var pretty = decodeURIComponent(qs).replace(/\+/g, ' '); // attribute[3]=سفید-...
  window.location.href = pretty ? (currentUrl + '?' + pretty) : currentUrl;
});

// === کلیک روی متن هم مثل کلیک روی چک‌باکس عمل کند ===
$(document).on('click', '.sidebar-widget-list-left a', function (e) {
  e.preventDefault();
  var $cb = $(this).siblings('input[type="checkbox"]').first();
  if ($cb.length) {
    $cb.prop('checked', !$cb.prop('checked'));
    window.filter();
  }
});


// بخش مدال


  $('.variation-select').on('change' , function(){
            let variation = JSON.parse(this.value);
            let variationPriceDiv = $('.variation-price');
            variationPriceDiv.empty();

            if(variation.is_sale){
                let spanSale = $('<span />' , {
                    class : 'new',
                    text : toPersianNum(number_format(variation.sale_price)) + ' تومان'
                });
                let spanPrice = $('<span />' , {
                    class : 'old',
                    text : toPersianNum(number_format(variation.price)) + ' تومان'
                });

                variationPriceDiv.append(spanSale);
                variationPriceDiv.append(spanPrice);
            }else{
                let spanPrice = $('<span />' , {
                    class : 'new',
                    text : toPersianNum(number_format(variation.price)) + ' تومان'
                });
                variationPriceDiv.append(spanPrice);
            }
            $('.quantity-input').attr('data-max' , variation.quantity);
            $('.quantity-input').val(1);

        });

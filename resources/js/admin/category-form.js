

// قدم ۱: ما jQuery را مستقیماً در همین فایل وارد می‌کنیم
import $ from 'jquery';

// قدم ۲: حالا که jQuery در همین فایل شناخته شده، بقیه کد را داخل تابع document.ready قرار می‌دهیم
$(function() {
    // انتخاب سلکت باکس اصلی
    const attributesDropdown = $('#attributeSelect');

    // اگر سلکت باکس اصلی در این صفحه وجود نداشت، هیچ کاری نکن
    if (!attributesDropdown.length) {
        return;
    }

    // انتخاب سلکت باکس‌های وابسته
    const filterableDropdown = $('#attributeIsFilterSelect');
    const variationDropdown = $('#variationsSelect');

    // خواندن مقادیر پیش‌فرض (که از کنترلر آمده) فقط یک بار در ابتدا
    const preSelectedFilterIds = normalizeIds(filterableDropdown.data('selected-ids'));
    const preSelectedVariationId = variationDropdown.data('selected-id') ? String(variationDropdown.data('selected-id')) : null;

    // تابعی که فقط مسئول ساختن گزینه‌هاست
    function syncDependentDropdowns() {
        const selectedOptions = attributesDropdown.find('option:selected');

        let filterableOptions = [];
        let variationOptions = [new Option('انتخاب کنید', '')];

        selectedOptions.each(function() {
            const optionValue = $(this).val();
            const optionText = $(this).text();
            filterableOptions.push(new Option(optionText, optionValue));
            variationOptions.push(new Option(optionText, optionValue));
        });

        filterableDropdown.empty().append(filterableOptions).selectpicker('refresh');
        variationDropdown.empty().append(variationOptions).selectpicker('refresh');
    }

    // اجرای اولیه برای ساخت گزینه‌ها
    syncDependentDropdowns();

    // حالا که گزینه‌ها ساخته شده‌اند، مقادیر پیش‌فرض را انتخاب کن
    if (preSelectedFilterIds.length > 0) {
        filterableDropdown.selectpicker('val', preSelectedFilterIds);
    }
    if (preSelectedVariationId) {
        variationDropdown.selectpicker('val', preSelectedVariationId);
    }

    // وقتی انتخاب کاربر در دراپ‌داون اصلی تغییر کرد، گزینه‌ها را بازسازی کن
    attributesDropdown.on('changed.bs.select', syncDependentDropdowns);
});


// تابع کمکی برای نرمال‌سازی آی‌دی‌ها (بدون تغییر)
function normalizeIds(x) {
    if (x == null) return [];
    if (Array.isArray(x)) return x.map(String);
    if (typeof x === 'string') {
        try {
            const parsed = JSON.parse(x);
            if (Array.isArray(parsed)) return parsed.map(String);
        } catch (_) {}
        return x.split(',').map(s => s.trim()).filter(Boolean).map(String);
    }
    return [String(x)];
}










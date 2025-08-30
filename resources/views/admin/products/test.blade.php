    DB::transaction(function () use ($request) {

        // 1) ایجاد محصول
        $product = \App\Models\Product::create([
            'name'        => $request->input('name'),
            'brand_id'    => $request->input('brand_id'),
            'category_id' => $request->input('category_id'),
            'is_active'   => (int) $request->input('is_active', 1),
            'description' => $request->input('description'),
            'delivery_amount'             => (int) $request->input('delivery_amount', 0),
            'delivery_amount_per_product' => (int) $request->input('delivery_amount_per_product', 0),
        ]);

        // 2) تگ‌ها (pivot)
        // name="tag_ids[]" → در request با کلید 'tag_ids' میاد
        $product->tags()->sync($request->input('tag_ids', []));

        // 3) تصویر اصلی
        if ($request->hasFile('primary_image')) {
            $path = $request->file('primary_image')->store('products', 'public'); // storage/app/public/products
            // اگر ستون primary_image در جدول products داری:
            $product->primary_image = $path;
            $product->save();
        }

        // 4) گالری تصاویر
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $p = $img->store('products', 'public');
                // اگر مدل ProductImage داری:
                $product->images()->create(['path' => $p]);
            }
        }

        // 5) ویژگی‌ها (attributes_section[] + اختیاری: attribute_ids[])
        // rules فقط attributes_section رو لازم کرده؛
        // ولی اگر attribute_ids[] هم داری، با همان ترتیب attach می‌کنیم.
        $values = $request->input('attributes_section', []);     // ['قرمز','XL',...]
        $ids    = $request->input('attribute_ids', []);          // [5,7,...] ممکنه خالی باشه

        if (!empty($ids)) {
            // جفت‌کردن id و value بر اساس ایندکس
            $n = min(count($ids), count($values));
            for ($i = 0; $i < $n; $i++) {
                $id = $ids[$i];
                $val = $values[$i];
                if ($id !== null && $val !== null && $val !== '') {
                    // جدول pivot: attribute_product با ستون value
                    $product->attributes()->attach($id, ['value' => $val]);
                }
            }
        } else {
            // اگر ids نفرستادی ولی values داری، (بسته به طراحی)
            // می‌تونی موقتاً به صورت متن جمع کنی یا ردش کنی.
            // من کاری نمی‌کنم تا مطابق آموزش استاد بمونه.
        }

        // 6) متغیّرها (variation_values[value|price|quantity|sku][])
        $vv = $request->input('variation_values', []);
        $vals = $vv['value']    ?? [];
        $prices = $vv['price']  ?? [];
        $qtys   = $vv['quantity'] ?? [];
        $skus   = $vv['sku']    ?? [];

        $rows = max(count($vals), count($prices), count($qtys), count($skus));
        for ($i = 0; $i < $rows; $i++) {
            $value    = $vals[$i]   ?? null;
            $price    = $prices[$i] ?? null;
            $quantity = $qtys[$i]   ?? null;
            $sku      = $skus[$i]   ?? null;

            // با توجه به rules همه باید پر باشن؛
            // برای احتیاط فقط value را چک می‌کنیم
            if ($value === null || $value === '') continue;

            $product->variations()->create([
                'value'    => $value,
                'price'    => (int) $price,
                'quantity' => (int) $quantity,
                'sku'      => $sku,
            ]);
        }
    });

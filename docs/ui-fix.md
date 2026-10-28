# Koosha UI Fix

افزونه WordPress نصب‌شده روی `www.kooshatajhiz.com` برای اصلاح کارت‌های فروشگاه، overlay، فاصله دکمه‌ها، کارت مقاله، لینک دامنه/تلفن و فونت یکان بخ.

## نصب

1. آپلود zip پوشه `plugins/koosha-ui-fix`
2. فعال‌سازی از افزونه‌ها
3. نسخه فعلی: **1.2.1**

## فایل‌ها

- `koosha-ui-fix.php` — enqueue CSS، override قالب محصول، linkify دامنه/تلفن
- `assets/ui-fix.css` — استایل‌ها + `@font-face` یکان بخ
- `assets/fonts/*.woff` — فونت self-host
- `woocommerce/content-product.php` — کارت با عنوان، دسته، توضیح کوتاه، قیمت، shade

## تأیید دستی

- `/shop/` — هر کارت اسم + قیمت + توضیح کوتاه دارد
- خانه — دامنه بدون `https://` لینک است؛ تلفن `tel:` است
- کارت مقاله — بج دسته، excerpt کمرنگ، ادامه مطلب سبز primary
- فونت body شامل `Yekan Bakh`


## 1.2.1

- تصویر شاخص برای مقالات بدون thumbnail
- بازطراحی صفحه درباره ما با عکس
- فاصله از هدر ثابت
- خوانایی کارت‌های مینی در راه‌اندازی کافی‌شاپ

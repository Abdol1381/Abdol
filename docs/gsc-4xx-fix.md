# رفع ارور Search Console: Blocked due to other 4xx issue

## علت واقعی (تأییدشده ۲۰۲۶-۱۰-۲۲)

گوگل‌بات به جای صفحهٔ سایت، از سرور **LiteSpeed روی هاست نت‌افراز** پاسخ می‌گیرد:

- وضعیت HTTP: **`418`**
- بدنه: چالش جاوااسکریپت با کوکی **`_dgjsc`**
- متا: `noindex,nofollow`

Search Console این را به‌صورت **Blocked due to other 4xx issue** گزارش می‌کند (۴۱۸ جزو ۴xx است و «Not found» جداست).

مرورگر کاربر عادی بعد از ست‌کردن کوکی صفحه را می‌بیند؛ **گوگل‌بات چالش JS را کامل نمی‌کند** و ایندکس گیر می‌کند.

محصولات و مقالات در مرورگر واقعی **HTTP 200** هستند؛ مشکل محتوا/۴۰۴ محصول نیست.

## کار لازم در پنل هاست نت‌افراز (اصلی)

1. وارد پنل کاربری/DirectAdmin نت‌افراز شوید.
2. بخش امنیتی مرتبط با یکی از این نام‌ها را پیدا کنید:
   - Bot Protection
   - JS Challenge
   - Anti-Bot / محافظت ربات
   - LiteSpeed Security / ModSecurity سفارشی
3. یکی از این‌ها را انجام دهید:
   - **Whitelist** برای `Googlebot` و `Google-InspectionTool`
   - یا خاموش کردن JS Challenge برای موتورهای جستجو
4. بعد از تغییر، در Search Console روی یک URL مهم (مثلاً صفحه اصلی) **Inspect → Test live URL → Request indexing** بزنید.
5. سایت‌مپ را دوباره Submit کنید: `https://www.kooshatajhiz.com/wp-sitemap.xml`

اگر گزینه را پیدا نکردید، تیکت به پشتیبانی نت‌افراز با این متن:

> لطفاً JS Challenge / Bot Protection سرور LiteSpeed که با کوکی `_dgjsc` و کد 418 به کراولرها پاسخ می‌دهد را برای Googlebot و Bingbot غیرفعال یا Whitelist کنید تا Search Console خطای Blocked due to other 4xx ندهد.

## کارهایی که از سمت وردپرس انجام شد

- ریدایرکت ۳۰۱ برچسب/دسته‌های حذف‌شده (اسپم قبلی)
- noindex برای آرشیو نویسنده/جستجو/تاریخ
- حذف provider کاربران از سایت‌مپ
- افزونه فعال: `Koosha Crawl Fix`
- اعلان هشدار در پیشخوان ادمین

## بررسی سریع بعد از رفع هاست

باید برای User-Agent گوگل‌بات پاسخ **200** بیاید (نه 418):

```bash
curl -sI -A "Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)" https://www.kooshatajhiz.com/ | head
```

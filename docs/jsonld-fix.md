# رفع خطای JSON-LD صفحه اصلی

## علت واقعی
افزونه Koosha UI Fix با output buffer روی کل HTML، URLهای داخل `<script type="application/ld+json">` را به تگ `<a>` تبدیل می‌کرد. این کار JSON را خراب می‌کرد (کوتیشن داخل رشته و ویرگول نامعتبر).

## اصلاح (v1.2.3)
Linkify دیگر روی `script` / `style` / `a` و attributeها اجرا نمی‌شود.

## وضعیت
هر دو بلوک JSON-LD صفحه اصلی بعد از اصلاح `JSON.parse` می‌شوند.

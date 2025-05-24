# Simple PHP Micro Framework for Beginners | إطار عمل PHP خفيف للمبتدئين

## 🎯 Purpose | الهدف

This micro-framework is designed for **beginners** and **small projects (up to 50 pages)**.  
It aims to provide a simple and structured way to build PHP applications using:
- File-based routing via `pages/` folder
- Optional use of Vue.js via CDN
- Clean and organized folder structure

تم تصميم هذا الإطار لمساعدة **المبتدئين** في بناء تطبيقات PHP بسيطة ومنظمة، دون الحاجة إلى استخدام أطر ثقيلة مثل Laravel.  
مناسب للمشاريع الصغيرة التي لا تتجاوز **50 صفحة**، ويعتمد على:
- التوجيه التلقائي بناءً على ملفات مجلد `pages/`
- دعم Vue.js باستخدام CDN
- هيكل مجلدات واضح ومنظم

---

## 🧭 Routing System | نظام التوجيه

- Any file in the `pages/` folder becomes a route.
- For example, `pages/about.php` is accessible via `/about`.
- Dynamic pages like `pages/[id].php` work with URLs like `/123`, where `$id = 123` is available inside the file.

أي ملف داخل مجلد `pages/` يعتبر صفحة مستقلة.
- مثلًا: `pages/about.php` يظهر عند زيارة `/about`
- يمكنك إنشاء صفحات ديناميكية مثل: `pages/[id].php` لعرض روابط مثل `/123` ويصبح المتغير `$id` متاحًا تلقائيًا.

---

## 🔧 Folder Structure | هيكل المجلدات

```
project-1/
│
├── index.php          ← Router entry point
├── .htaccess          ← Apache rewrite rules
├── pages/             ← Static and dynamic pages
├── layouts/           ← Shared layout (e.g. header, footer)
├── assets/            ← CSS, JS, images, fonts
├── api/               ← Optional API endpoints
├── src/               ← Helper functions or classes
└── README.md          ← This file
```
---

## 💻 Vue.js CDN Support | دعم Vue.js من خلال CDN

You can directly include Vue in your layout:

```html
<script src="https://unpkg.com/vue@3"></script>
```

---

## 🚀 How to Run | طريقة التشغيل

Place the project in your local server directory (e.g., XAMPP `htdocs`)  
and visit: `http://localhost/project-1`

ضع المشروع داخل مجلد الخادم المحلي لديك (مثل `htdocs` في XAMPP)  
ثم افتحه عبر المتصفح من خلال: `http://localhost/project-1`
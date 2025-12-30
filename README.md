# Full-Stack Laravel Admin Dashboard for Non-Profit Management

نظام إداري متكامل (Admin Dashboard / ERP) تم تطويره باستخدام **Laravel** لإدارة أعمال منظمة خيرية، يهدف إلى تسهيل إدارة التبرعات، التصنيفات، المستندات، والتقارير بطريقة احترافية وآمنة.

---

## 🚀 Features

- لوحة تحكم إدارية متكاملة
- إدارة التبرعات (عرض – تفاصيل – تتبع)
- إدارة التصنيفات (Categories)
- إدارة المستندات والملفات
- إدارة المحتوى والتأثيرات (Impacts)
- نظام صلاحيات (Admin)
- واجهات استخدام واضحة ومنظمة
- كود منظم وقابل للتوسع (Scalable Architecture)

---

## 🛠️ Tech Stack

- **Backend:** PHP (Laravel)
- **Database:** MySQL
- **Frontend:** Blade, HTML5, CSS3, JavaScript
- **Tools:** Git, GitHub
- **Architecture:** MVC – Laravel Best Practices

---

## 📂 Project Structure

- `app/` → منطق التطبيق (Controllers, Models)
- `resources/views/` → الواجهات (Blade Templates)
- `routes/` → مسارات النظام
- `database/` → Migrations & Seeders
- `public/` → الملفات العامة
- `config/` → إعدادات النظام

---

## ⚙️ Installation & Setup

```bash
git clone https://github.com/Ghadah-696/Full-Stack-Laravel-Admin-Dashboard-for-Non-Profit-Management.git
cd Full-Stack-Laravel-Admin-Dashboard-for-Non-Profit-Management
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve

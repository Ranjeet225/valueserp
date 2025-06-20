# 🔍 Laravel ValueSERP Search & CSV Export Tool

This Laravel application allows users to enter 1 to 5 search queries, fetches Google search results using the [ValueSERP API](https://www.valueserp.com/), displays the results, and allows exporting them into a downloadable CSV file.

---

## 🚀 Features

| Feature                                    | Status |
|-------------------------------------------|--------|
| Laravel project setup                     | ✅     |
| ValueSERP API integration                 | ✅     |
| 1 to 5 search query input support         | ✅     |
| JSON result parsing (title, link, snippet)| ✅     |
| CSV export functionality                  | ✅     |
| Blade frontend with clean UI              | ✅     |
| Error handling (validation & API)         | ✅     |
| Secure API key usage via `.env`           | ✅     |

---

## 📦 Requirements

- PHP >= 8.0
- Composer
- Laravel 10+
- WAMP/XAMPP/Laravel Sail/Valet
- Free [ValueSERP API Key](https://www.valueserp.com/)

---

## 🛠️ Installation

1. **Clone this repository**
   ```bash
   git clone https://github.com/your-username/valueserp-laravel-app.git
   cd valueserp-laravel-app

composer install
cp .env.example .env
php artisan key:generate
# Edit .env and add your free ValueSERP API key
php artisan serve
# Open http://localhost:8000 in your browser


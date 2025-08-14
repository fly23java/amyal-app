# تعليمات إعداد وتشغيل الاختبارات

## المتطلبات الأساسية

### 1. تثبيت PHP
```bash
# Ubuntu/Debian
sudo apt update
sudo apt install php8.1 php8.1-cli php8.1-fpm php8.1-mysql php8.1-xml php8.1-mbstring php8.1-curl php8.1-zip php8.1-gd

# CentOS/RHEL
sudo yum install php php-cli php-fpm php-mysql php-xml php-mbstring php-curl php-zip php-gd

# macOS (using Homebrew)
brew install php

# Windows
# قم بتحميل PHP من الموقع الرسمي: https://windows.php.net/download/
```

### 2. تثبيت Composer
```bash
# Linux/macOS
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Windows
# قم بتحميل Composer-Setup.exe من: https://getcomposer.org/download/
```

### 3. تثبيت PHPUnit
```bash
# تثبيت PHPUnit عالمياً
composer global require phpunit/phpunit

# أو تثبيت PHPUnit محلياً في المشروع
composer require --dev phpunit/phpunit
```

## إعداد المشروع

### 1. تثبيت التبعيات
```bash
cd /path/to/your/project
composer install
```

### 2. نسخ ملف البيئة
```bash
cp .env.example .env
```

### 3. إنشاء مفتاح التطبيق
```bash
php artisan key:generate
```

### 4. إعداد قاعدة البيانات
```bash
# إنشاء قاعدة البيانات
php artisan migrate

# إضافة بيانات تجريبية
php artisan db:seed
```

## تشغيل الاختبارات

### 1. تشغيل جميع الاختبارات
```bash
php artisan test
```

### 2. تشغيل اختبارات معينة
```bash
# اختبارات الوحدات فقط
php artisan test --testsuite=Unit

# اختبارات الميزات فقط
php artisan test --testsuite=Feature

# اختبار معين
php artisan test --filter=UserTest

# اختبار محدد
php artisan test --filter=test_user_can_login
```

### 3. تشغيل الاختبارات مع تغطية الكود
```bash
php artisan test --coverage
```

### 4. تشغيل الاختبارات مع تقرير مفصل
```bash
php artisan test --verbose
```

## استخدام Docker (بديل)

### 1. إنشاء Dockerfile
```dockerfile
FROM php:8.1-fpm

# تثبيت التبعيات
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# تثبيت PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# تعيين مجلد العمل
WORKDIR /var/www

# نسخ ملفات المشروع
COPY . .

# تثبيت التبعيات
RUN composer install

# تعيين الصلاحيات
RUN chown -R www-data:www-data /var/www
```

### 2. إنشاء docker-compose.yml
```yaml
version: '3'
services:
  app:
    build: .
    ports:
      - "8000:8000"
    volumes:
      - .:/var/www
    depends_on:
      - db
    environment:
      - DB_HOST=db
      - DB_DATABASE=laravel
      - DB_USERNAME=root
      - DB_PASSWORD=password

  db:
    image: mysql:8.0
    ports:
      - "3306:3306"
    environment:
      - MYSQL_DATABASE=laravel
      - MYSQL_ROOT_PASSWORD=password
    volumes:
      - db_data:/var/lib/mysql

volumes:
  db_data:
```

### 3. تشغيل الاختبارات في Docker
```bash
# بناء وتشغيل الحاويات
docker-compose up -d

# تشغيل الاختبارات
docker-compose exec app php artisan test
```

## استكشاف الأخطاء

### مشاكل شائعة وحلولها

#### 1. خطأ "PHP not found"
```bash
# تأكد من تثبيت PHP
php --version

# إذا لم يكن مثبتاً
sudo apt install php8.1
```

#### 2. خطأ "Composer not found"
```bash
# تأكد من تثبيت Composer
composer --version

# إذا لم يكن مثبتاً
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

#### 3. خطأ في قاعدة البيانات
```bash
# مسح الكاش
php artisan cache:clear

# مسح التكوين
php artisan config:clear

# إعادة إنشاء قاعدة البيانات
php artisan migrate:fresh --seed
```

#### 4. خطأ في التبعيات
```bash
# حذف مجلد vendor
rm -rf vendor

# حذف composer.lock
rm composer.lock

# إعادة تثبيت التبعيات
composer install
```

#### 5. خطأ في الصلاحيات
```bash
# تعيين الصلاحيات الصحيحة
sudo chown -R $USER:$USER storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

## إعدادات إضافية

### 1. إعداد PHPUnit
```bash
# نسخ ملف PHPUnit
cp phpunit.xml.example phpunit.xml

# تعديل الإعدادات حسب الحاجة
nano phpunit.xml
```

### 2. إعداد قاعدة البيانات للاختبارات
```env
# في ملف .env.testing
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

### 3. إعداد Git Hooks
```bash
# إنشاء pre-commit hook
mkdir -p .git/hooks
cat > .git/hooks/pre-commit << 'EOF'
#!/bin/bash
php artisan test
EOF

chmod +x .git/hooks/pre-commit
```

## المراجع

- [Laravel Installation Guide](https://laravel.com/docs/installation)
- [Composer Installation](https://getcomposer.org/download/)
- [PHPUnit Installation](https://phpunit.de/getting-started.html)
- [Docker Installation](https://docs.docker.com/get-docker/)
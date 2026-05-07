# Atica - Ecommerce de Indumentaria

Plataforma de comercio electrónico desarrollada a medida para **Atica**, un emprendimiento de indumentaria femenina.  
Construida con **Laravel 11**, **MySQL** y **Tailwind CSS**, integra el ecosistema completo de venta online: catálogo, carrito, pagos con MercadoPago, panel de administración y seguimiento de órdenes.

## ✨ Funcionalidades

### Tienda
- Catálogo de productos con imágenes, variantes de talle y color
- Categorías navegables con filtros por precio
- Búsqueda de productos
- Página de producto individual con selector de variantes
- Guía de talles
- FAQs y políticas de devolución

### Carrito y Checkout
- Carrito lateral con actualización de cantidades en tiempo real
- Validación y aplicación de cupones de descuento
- Checkout con datos de cliente, envío (Correo Argentino / pickup) y facturación
- Integración con **MercadoPago** (webhook para notificaciones de pago)
- Flujo completo de estados de pago: success, failure, pending

### Panel de Administración
- Dashboard con estadísticas
- CRUD completo de productos y categorías
- Gestión de imágenes por producto
- Visualización de órdenes y detalles de compra
- Visor de logs integrado (Laravel Log Viewer)

### Automatizaciones y Seguimiento
- Correos transaccionales post-compra
- **Facebook Conversion API** para tracking de eventos de compra
- **Google Tag Manager** en entorno productivo
- Beacon de visitas únicas con detección de crawlers
- Job programado de liberación de stock

## 🛠 Stack Tecnológico

- **Backend:** PHP 8.2, Laravel 11
- **Base de datos:** MySQL
- **Frontend:** Blade, Tailwind CSS, JavaScript (Vanilla)
- **Pagos:** MercadoPago SDK
- **Tracking:** Facebook Ads Conversion API, Google Tag Manager
- **Jobs y Colas:** Laravel Queues
- **Logs:** Laravel Log Viewer

## 📁 Estructura del proyecto
app/
├── Http/Controllers/
│ ├── Admin/ # Controladores del panel (Product, Category, Picture, Panel)
│ ├── CartController.php
│ ├── CheckoutController.php
│ ├── IndexController.php
│ ├── MailingListController.php
│ └── MercadopagoWebhookController.php
├── Models/ # Cart, Category, Coupon, Customer, Order, Product, etc.
├── Services/ # CartService, CheckoutService, MercadoPagoService, etc.
├── Events/Listeners/ # Tracking de visitas
└── Jobs/ # Liberación de stock, envío de emails

text

## 🚀 Instalación y uso local

1. Clonar el repositorio:
   ```bash
   git clone https://github.com/MatiasOrcajo/ecommerce.git
   cd ecommerce
Instalar dependencias:

bash
composer install
npm install && npm run build
Configurar entorno:

bash
cp .env.example .env
php artisan key:generate
Editar .env con credenciales de:

Base de datos MySQL

MercadoPago (access token)

Servicio de correo (SMTP)

Facebook Ads API (opcional)

Google Tag Manager ID (opcional)

Migrar y poblar:

bash
php artisan migrate --seed
Iniciar servidor:

bash
php artisan serve
Visitar http://localhost:8000

🔗 Enlaces
Desarrollador: Matías Orcajo

LinkedIn: in/matiasorcajo

Email: matiasorcajo3@gmail.com

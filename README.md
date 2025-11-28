# Sitio web Panadería – Sistema de pedidos y administración

Sitio web desarrollado con **Laravel 11 + Vite + MySQL**.

## Requisitos
- PHP ≥ 8.1
- Composer
- Node.js + npm
- MySQL
- Git

## Instalación paso a paso

```bash
# 1. Clonar el repositorio
git clone https://github.com/Lean998/Sitio-web-panaderia.git
cd Sitio-web-panaderia

# 2. Instalar dependencias PHP
composer install --optimize-autoloader --no-dev

# 3. Instalar y compilar assets
npm install
npm run build

# 4. Configurar entorno
cp .env.example .env
php artisan key:generate

# 5. Editar el archivo `.env` y modificar al menos las siguientes variables:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=panaderia_db # ← crear esta base de datos vacía
DB_USERNAME=root
DB_PASSWORD=

# 6. Ejecutar migraciones + datos de prueba
php artisan migrate --seed

# 7. Levantar el servidor
php artisan serve

```

### Credenciales de acceso (creadas automáticamente)

| Rol           | Email                     | Contraseña   |
|---------------|---------------------------|--------------|
| Administrador | admin@panaderia.com       | admin123     |


### Sobre mí
¡Hola! Soy Leandro, estudiante del 3º año de la Tecnicatura Universitaria en Web – UNSL
Este es mi primer proyecto grande con Laravel y estoy muy orgulloso del resultado

**Tecnologías que más uso**
- PHP
- MySQL
- JavaScript
- Blade + Bootstrap
- Git & GitHub

Siempre aprendiendo y mejorando código  
¡Feedback y oportunidades son más que bienvenidos!

**Contacto**
- LinkedIn → [linkedin.com/in/leandro-jorge-aguero](https://www.linkedin.com/in/leandro-jorge-aguero/)
- Email → leandroagueo83@gmail.com

## Licencia
Este proyecto está bajo la licencia MIT - ver archivo [LICENSE](LICENSE) para más detalles.


# WKC Server

<p align="center">
  <img src="public/Img/KARATE.png" alt="WKC México" width="100"/>
  <!-- <img src="public/Img/strappberry.png" alt="Strapberry" width="200"/> -->
</p>

Este proyecto es un servidor Laravel para la aplicación WKC. A continuación se detallan los pasos para clonar e instalar el proyecto en tu máquina local.

## Requisitos previos

Antes de comenzar, asegúrate de tener instalados los siguientes componentes:

- [Composer](https://getcomposer.org/)
- [Node.js y NPM](https://nodejs.org/)
- Un servidor de base de datos como MySQL o MariaDB

## Instalación

### 1. Clonar el repositorio

Clona el repositorio en tu máquina local:

```bash
# git clone git@github.com:strappberry/wkc-server.git
cd wkc-server
```

### 2. Instalar dependencias de PHP
Instala las dependencias de PHP utilizando Composer:

```bash
composer install
```

### 3. Configurar el archivo .env
Copia el archivo de entorno de ejemplo y configura las variables necesarias:

```bash
cp .env.example .env
```

### Configura la conexión a la base de datos en el archivo .env (DB_DATABASE, DB_USERNAME, DB_PASSWORD, etc.).
### Configura otras variables necesarias según tu entorno (APP_URL, etc.).

### 4. Generar la clave de la aplicación
Genera una clave para la aplicación Laravel:

```bash
php artisan key:generate
```

### 5. Migrar y sembrar la base de datos
Ejecuta las migraciones y los seeders para preparar la base de datos:

```bash
php artisan migrate --seed
```

### 6. Instalar dependencias de Node.js
Instala las dependencias de Node.js necesarias para el frontend:

```bash
npm install
```

### 7. Compilar assets
Compila los assets utilizando:

```bash
npm run dev
```

### 8. Configurar Laravel Herd (o equivalente)
Si utilizas Laravel Herd (si no usas Laravel Herd omite este paso), asegúrate de añadir el proyecto y configurar el dominio local:

Añade el dominio en el archivo hosts:

En Windows:
```bash
127.0.0.1 wkc-server.test
```

En Mac/Linux:
```bash
sudo nano /etc/hosts
```

Añade la línea:
```bash
127.0.0.1 wkc-server.test
```

Reinicia Laravel Herd para aplicar los cambios.

### 9. Iniciar el servidor
Puedes iniciar el servidor utilizando Laravel Herd o el servidor integrado de Laravel:

```bash
php artisan serve
```

### Licencia
Este proyecto está licenciado bajo la MIT License.
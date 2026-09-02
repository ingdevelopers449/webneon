<laravel-boost-guidelines>
# Laravel Application

This repository contains a Laravel application. Complete the following setup before working on the user's request.

## Prerequisites

Verify that PHP and Composer are available:

```sh
php -v
composer -V
```

If either command is unavailable, detect the user's operating system and install the prerequisites with the appropriate command:

macOS:

```sh
/bin/bash -c "$(curl -fsSL https://php.new/install/mac/8.5)"
```

Windows PowerShell:

```powershell
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://php.new/install/windows/8.5'))
```

Linux:

```sh
/bin/bash -c "$(curl -fsSL https://php.new/install/linux/8.5)"
```

After installation, ask the user to restart their terminal. If the agent needs the restarted shell to continue, ask the user to reopen their terminal and rerun their original prompt.

## Agent Setup

Install Laravel Boost from the application root before making application changes:

```sh
composer require laravel/boost --dev
php artisan boost:install
```

Boost replaces these bootstrap instructions with guidelines tailored to the application. After installation, read `AGENTS.md` again and continue with the user's original request using the generated guidelines.
</laravel-boost-guidelines>

# Contexto del Proyecto: Neon Streaming Controller

Neon Streaming Controller es un sistema web para la gestión de cuentas y servicios de streaming. Está dirigido a pequeños emprendedores que revenden acceso a plataformas populares como Netflix, Disney+, Spotify, etc.

El sistema permite a estos revendedores:

- Gestionar su inventario: pueden agregar las cuentas que han adquirido, junto con detalles como fechas de vencimiento y costo.
- Administrar perfiles: para plataformas que usan perfiles (como Netflix), pueden crear y asignar perfiles individuales a sus clientes.
- Registrar ventas: pueden registrar cada venta, asociándola a un cliente y a una cuenta o perfil de su inventario.
- Comunicarse con los clientes: el sistema genera mensajes automatizados para enviar a los clientes vía WhatsApp, con detalles de su compra y recordatorios de vencimiento.
- Monitorear su negocio: ofrece un dashboard con métricas clave como ganancias, cuentas por vencer, etc.

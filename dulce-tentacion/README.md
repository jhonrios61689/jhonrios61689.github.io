# 🍰 Dulce Tentación — Plataforma Web & Sistema de Gestión

[![Universidad de Caldas](https://img.shields.io/badge/Universidad%20de%20Caldas-U%20en%20tu%20pueblo-75404f.svg)](https://www.ucaldas.edu.co/)
[![Estado](https://img.shields.io/badge/Estado-100%25%20Completado-success.svg)](#)
[![Licencia](https://img.shields.io/badge/Licencia-Acad%C3%A9mica-blue.svg)](#)

> **Proyecto Final de Asignatura:** Análisis y Diseño de Soluciones Web  
> **Programa Académico:** Técnico Profesional en Programación Web  
> **Institución:** Universidad de Caldas · Sede U en tu pueblo  
> **Docente / Asesora:** Ingeniera Angela Andrea Suarez Arango  
> **Estudiante / Desarrollador:** Jhon Jairo Ríos Gómez

---

## 🌐 Enlaces en Vivo (GitHub Pages)

| Recurso | Enlace Directo |
| :--- | :--- |
| 🛒 **Tienda Virtual Dulce Tentación** | [https://jhonrios61689.github.io/dulce-tentacion/](https://jhonrios61689.github.io/dulce-tentacion/) |
| ⚙️ **Panel de Administración** | [https://jhonrios61689.github.io/dulce-tentacion/administracion/](https://jhonrios61689.github.io/dulce-tentacion/administracion/) |
| 📄 **Documento Oficial del Proyecto (PDF)** | [Ver / Descargar PDF Oficial](https://jhonrios61689.github.io/dulce-tentacion/documento-proyecto-dulce-tentacion.pdf) |
| 💼 **Portafolio Profesional** | [https://jhonrios61689.github.io/](https://jhonrios61689.github.io/) |

---

## 📖 Descripción del Proyecto

**Dulce Tentación** es una solución web integral diseñada para un establecimiento comercial de repostería artesanal y cafetería de especialidad. El proyecto combina:

1. **Tienda Virtual para Clientes:** Catálogo clasificado de 20 productos (14 postres y 6 cafés), filtrado reactivo sin recarga, carrito de compras persistente, checkout asistido por WhatsApp o autenticación local, y enlace geográfico verificado hacia Google Maps (`Call 8 #5-20 parque principal`).
2. **Portal Administrativo y Operativo (`/administracion/`):** Control de acceso por roles (*Administrador* y *Empleado*), métricas de ventas en tiempo real, gestión completa de productos (*crear, editar y borrar* con confirmación modal), control de stock y auditoría de movimientos con el nombre del empleado responsable.
3. **Visor Interactivo del DOM:** Herramienta pedagógica integrada que permite inspeccionar la jerarquía de nodos del DOM en tiempo real.
4. **Infraestructura Segura:** Configuración de servidor Apache con VirtualHosts, dominio local `https://dulcetentacion.com` y certificados SSL X.509 con Subject Alternative Names (SAN).

---

## 🛠️ Tecnologías Utilizadas

- **Frontend:** HTML5 Semántico, CSS3 modular (Variables CSS, Grid y Flexbox), JavaScript ES6 Modules.
- **Backend:** PHP 8 (Arquitectura Modelo-Vista-Controlador, PDO, API REST en formato JSON).
- **Base de Datos:** MySQL 8 (`sistema_dulcetentacion`) con 7 tablas normalizadas y claves foráneas.
- **Servidor y Seguridad:** Apache HTTP Server, OpenSSL (Certificado X.509 con SAN), `.htaccess` para forzado HTTPS.

---

## 👥 Credenciales de Acceso para Pruebas (Panel Interno)

Puedes probar el panel administrativo tanto en el servidor local como en la versión online de GitHub Pages con los siguientes roles:

| Rol | Correo Electrónico | Contraseña | Capacidades |
| :--- | :--- | :--- | :--- |
| **Administrador** (Gerente General) | `admin1@local.com` | `admin123` | Control total: métricas, gestión de productos (agregar/editar/borrar), stock, auditoría y cambio de contraseña. |
| **Empleado** (Atención y Ventas) | `empleado1@local.com` | `empleado123` | Registro de ventas en mostrador, consulta de pedidos e inventario. |

---

## 🚀 Instalación y Puesta en Marcha Local

### Requisitos Previos
- XAMPP con PHP 8.0+ y MySQL 8.0+.
- Windows 10/11 (para ejecución con dominio local `dulcetentacion.com`).

### Pasos:
1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/jhonrios61689/jhonrios61689.github.io.git
   ```
2. **Copiar la carpeta `dulce-tentacion` en `C:\xampp\htdocs\dulce-tentacion`**.
3. **Importar la Base de Datos:**
   - Abre phpMyAdmin (`http://localhost/phpmyadmin/`).
   - Importa el archivo `sql/sistema_dulcetentacion.sql`.
4. **Configurar el Dominio Local y Certificado SSL:**
   - Haz clic derecho sobre `configurar-dominio-local.bat` y selecciona **Ejecutar como administrador**.
   - Este script agregará `127.0.0.1 dulcetentacion.com` a tu archivo `hosts` e instalará el certificado raíz en Windows.
5. **Acceder en el Navegador:**
   - Tienda: `https://dulcetentacion.com/`
   - Administración: `https://dulcetentacion.com/administracion/`

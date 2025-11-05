# 🎮 Plataforma Videojocs Vulnerable

Proyecto educativo en PHP y MySQL que simula una **plataforma de videojuegos web**, diseñada con fines **didácticos y de seguridad informática**.  
Incluye dos minijuegos, con sistema de usuarios, puntuaciones, ranking y un **script de instalación automática**.

> ⚠️ **Advertencia:** Este proyecto contiene código deliberadamente inseguro.  
> Está destinado únicamente a entornos de prácticas o entornos aislados de seguridad (como máquinas virtuales o redes locales controladas).  
> **No lo uses en producción.**

---

## 📂 Estructura general del proyecto

├── Other/  
│   ├── connexio.php              # Conexión principal a la base de datos  
│   ├── connexio_api.php          # Conexión API (para endpoints del juego)  
│   └── backup_plataformaweb.sql  # Dump opcional de la base de datos  

├── backend/  
│   ├── index.php                 # Interfaz principal del juego “Atrapa Objectes”  
│   ├── classes.js, main.js       # Lógica del juego (JS)  
│   ├── index.css                 # Estilos del juego  
│   └── ...                       # Otros recursos del backend  

├── deploy.sh                     # Script de instalación automática  
├── README.md                     
---

## ⚙️ Requisitos previos

- Sistema operativo: **Ubuntu Server 22.04+**  
- Permisos de **root** o **sudo**  
- Acceso a Internet (para descargar paquetes y clonar el repositorio)  
- Git, Apache, PHP y MySQL (el script los instalará si no existen)
- SSH **(Opcional)** para poder conectarte a la maquina y trabajar de forma comoda
---

## 🚀 Instalación automática

El proyecto incluye un script de despliegue (`script_plataforma.sh`) que automatiza todo el proceso de instalación del servidor y la base de datos (`script.sql`).

### 🧩 Pasos de instalación

1. Conéctate al servidor:  
   `ssh usuario@<IP_SERVIDOR>`

2. Haz un gitclone del repositorio:  
   `https://github.com/ZxMxTeO/plataforma-videojocs-vulnerable.git`

3. Entra a la carpeta correspondiente donde se encuentra el script

4. Da permisos de ejecución y ejecuta el instalador:  
   `chmod +x deploy.sh`  
   `sudo ./deploy.sh`

### 📦 Qué hace el script

- Actualiza e instala dependencias (`Apache2`, `PHP`, `MySQL`, `git`, `ssh`)  
- Crea la base de datos `plataforma_videojocs`  
- Crea el usuario MySQL `plataforma_user` con permisos sobre esa base  
- Importa el dump SQL (`backup_plataformaweb.sql`) si está disponible  
- Copia el proyecto a `/var/www/html`  
- Ajusta permisos y reinicia los servicios

### ✅ Resultado esperado

Una vez completado, podrás acceder desde el navegador a:  
`http://<IP_SERVIDOR>/`

---

## 🔧 Configuración manual (opcional)

Si prefieres hacerlo manualmente o necesitas modificar la conexión:

1. Abre `Other/connexio.php` y revisa las credenciales, por ejemplo:  
   `$user = "plataforma_user";`  
   `$password = "123456789a";`  
   `$database = "plataformaweb";`  
   `$host = "localhost";`

2. Asegúrate de que el usuario y la base de datos existen en MySQL:
   `sudo mysql -u root -p`
   `CREATE DATABASE plataformaweb;`
   `CREATE USER 'plataforma_user'@'localhost' IDENTIFIED BY '123456789a';`
   `GRANT ALL PRIVILEGES ON plataformaweb.* TO 'plataforma_user'@'localhost';`
   `FLUSH PRIVILEGES;`


---

## 🧠 Cómo probar al plataforma

1. Entra en `http://<IP_SERVIDOR>/`
2. Crea tu usuario en el apartado de `registro`
3. Introduce tu usuario y accede a la `plataforma` 
4. Navega por los diferentes apartados de la plataforma como serian: `Perfil, Ranking y los diferentes juegos`

---

## 🧰 Comprobaciones útiles

Verificar que los servicios están activos:  
`sudo systemctl status apache2`  
`sudo systemctl status mysql`

Revisar errores PHP o Apache:  
`sudo tail -n 100 /var/log/apache2/error.log`

---

## 🛡️ Seguridad

Este entorno está diseñado **para estudiar vulnerabilidades web**: SQL Injection, XSS, malas prácticas en sesiones, etc.

Si lo usas para aprendizaje:
- Desactiva el acceso público.  
- Usa una red NAT o entorno virtual.  
- No reutilices contraseñas reales.  

---

## 🧩 Autor y licencia

**Autor:** Matthew Luna y Marc Pimentel
**Fecha:** Noviembre 2025  
**Lenguajes:** PHP, MySQL, JavaScript, HTML, CSS  
**Licencia:** MIT — Uso libre con fines educativos.

---

## 💡 Próximas mejoras

- Hacer el codigo no vulnerable 
- Contenedores Docker para despliegue




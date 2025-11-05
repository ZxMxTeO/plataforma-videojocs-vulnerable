#!/bin/bash
set -euo pipefail
IFS=$'\n\t'

# ======================================================
#  Script de despliegue automático del servidor LAMP + app
#  Compatible con Ubuntu Server 22.04+
#  NOTA: ejecutar como root (sudo) para que funcione correctamente
# ======================================================

# --- Variables de configuración ---
APP_DIR="/var/www/html"
DB_NAME="plataforma_videojocs"
DB_SCHEMA_FILE="script.sql"   # <-- nombre solicitado
# DB_BACKUP_FILE eliminado según petición

echo "🚀 Iniciando despliegue del servidor..."

# --- 1. Actualización del sistema ---
echo "🔄 Actualizando sistema..."
apt update -y && apt upgrade -y

# --- 2. Instalación de dependencias ---
echo "📦 Instalando Apache, PHP, MySQL y SSH..."
apt install -y apache2 php libapache2-mod-php php-mysql php-json php-curl php-mbstring php-gd mysql-server openssh-server git

# --- 3. Activar servicios ---
echo "⚙️ Habilitando e iniciando servicios..."
systemctl enable --now apache2
systemctl enable --now mysql
systemctl enable --now ssh

# --- 4. Clonar/actualizar repositorio ---
echo "📁 Desplegando el repositorio en $APP_DIR ..."
if [ -d "$APP_DIR/.git" ]; then
    echo "🔁 Ya existe un repo en $APP_DIR, actualizando..."
    git -C "$APP_DIR" fetch --all --prune
    git -C "$APP_DIR" reset --hard origin/HEAD
else
    echo "📥 Clonando repo..."
    rm -rf "${APP_DIR:?}"/*    # borrar contenido previo
    git clone "$REPO_URL" "$APP_DIR"
fi

# --- 5. Configurar permisos web (según petició: 777) ---
echo "🔐 Configurando permisos de $APP_DIR (777 solicitado)..."
# dueño www-data pero permisos 777 tal y como pediste
chown -R www-data:www-data "$APP_DIR" || true
chmod -R 777 "$APP_DIR"

# --- 6. Aplicar script SQL (script.sql) si existe ---
echo "🗃️ Buscando $DB_SCHEMA_FILE en $APP_DIR ..."
if [ -f "$APP_DIR/$DB_SCHEMA_FILE" ]; then
    echo "🧩 Encontrado $DB_SCHEMA_FILE — aplicando SQL..."
    if sudo mysql --version >/dev/null 2>&1; then
        # Ejecutar el sql con sudo mysql para respetar autenticación por socket
        sudo mysql < "$APP_DIR/$DB_SCHEMA_FILE"
        echo "✅ SQL aplicado correctamente desde $DB_SCHEMA_FILE"
    else
        echo "❌ No se encontró el cliente mysql. Instala 'mysql-client' o revisa la PATH."
        exit 1
    fi
else
    echo "⚠️ No se encontró $DB_SCHEMA_FILE en $APP_DIR. No se aplicó ningún SQL."
fi

# --- 7. Reiniciar servicios para aplicar configuración ---
echo "🔁 Reiniciando servicios..."
systemctl restart apache2
systemctl restart mysql

# --- 8. Estado final ---
HOST_IP=$(hostname -I 2>/dev/null | awk '{print $1}' || echo "localhost")
echo "✅ Despliegue completado."
echo "🌐 Accede desde tu navegador a: http://$HOST_IP"
echo "📂 Carpeta del proyecto: $APP_DIR"
echo "🗄️ Base de datos objetivo: $DB_NAME"
echo "📄 SQL aplicado: $( [ -f "$APP_DIR/$DB_SCHEMA_FILE" ] && echo "$DB_SCHEMA_FILE" || echo "no aplicado")"

# --- Mensaje de seguridad ---
echo ""
echo "⚠️ AVISO DE SEGURIDAD:"
echo "Has solicitado permisos 777 en $APP_DIR — eso permite escritura/ejecución a cualquier usuario."
echo "Recomiendo revisarlo y reducir permisos (por ejemplo 755/644) en entornos que no sean de pruebas."

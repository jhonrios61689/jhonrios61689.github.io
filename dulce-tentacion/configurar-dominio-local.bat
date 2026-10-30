@echo off
chcp 65001 >nul
cd /d "%~dp0"

echo ====================================================================
echo   CONFIGURADOR DE DOMINIO LOCAL: dulcetentacion.com
echo ====================================================================
echo.

:: 1. Verificar si se ejecuta con permisos de administrador
net session >nul 2>&1
if %errorLevel% neq 0 (
    echo [AVISO] Se requieren permisos de Administrador para editar el archivo hosts.
    echo.
    echo Por favor haz esto:
    echo   1. Cierra esta ventana.
    echo   2. Haz CLIC DERECHO sobre "configurar-dominio-local.bat"
    echo   3. Selecciona "Ejecutar como administrador"
    echo.
    echo ====================================================================
    pause
    exit /b
)

:: 2. Agregar dulcetentacion.com al archivo hosts de Windows
echo [1/3] Configurando archivo hosts de Windows...
findstr /C:"dulcetentacion.com" "%WINDIR%\System32\drivers\etc\hosts" >nul
if %errorLevel% neq 0 (
    echo. >> "%WINDIR%\System32\drivers\etc\hosts"
    echo # Dominio local Dulce Tentacion >> "%WINDIR%\System32\drivers\etc\hosts"
    echo 127.0.0.1 dulcetentacion.com >> "%WINDIR%\System32\drivers\etc\hosts"
    echo 127.0.0.1 www.dulcetentacion.com >> "%WINDIR%\System32\drivers\etc\hosts"
    echo   - Dominios agregados a C:\Windows\System32\drivers\etc\hosts
) else (
    echo   - dulcetentacion.com ya estaba configurado en hosts.
)

:: 3. Instalar Certificado Raiz en el almacen de Windows
echo.
echo [2/3] Instalando Certificado Raiz SSL en Windows...
if exist "ssl\DulceTentacionRootCA.crt" (
    certutil -user -addstore Root "ssl\DulceTentacionRootCA.crt" >nul 2>&1
    certutil -addstore Root "ssl\DulceTentacionRootCA.crt" >nul 2>&1
    echo   - Certificado raiz instalado con exito.
) else (
    echo   - No se encontro el archivo ssl\DulceTentacionRootCA.crt
)

:: 4. Reiniciar Apache
echo.
echo [3/3] Verificando servidor Apache en XAMPP...
echo   Si tienes el panel de control de XAMPP abierto, asegurate de que
echo   el modulo "Apache" este iniciado (en verde).
taskkill /F /IM httpd.exe >nul 2>&1
timeout /t 1 /nobreak >nul
if exist "C:\xampp\apache\bin\httpd.exe" (
    start "" "C:\xampp\apache\bin\httpd.exe"
    echo   - Apache iniciado correctamente.
)

echo.
echo ====================================================================
echo   LISTO: Configuracion completada con exito.
echo.
echo   Cierra el navegador y abre directamente:
echo   - Tienda:        https://dulcetentacion.com/
echo   - Administracion: https://dulcetentacion.com/administracion/
echo ====================================================================
echo.
pause

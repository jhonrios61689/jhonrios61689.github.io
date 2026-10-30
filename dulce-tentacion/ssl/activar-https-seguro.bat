@echo off
chcp 65001 >nul
echo ====================================================================
echo   ACTIVADOR DE HTTPS SEGURO (SSL) · DULCE TENTACION
echo ====================================================================
echo.
echo [1/3] Copiando certificado SSL valido (SAN localhost) a XAMPP Apache...
copy /Y "%~dp0dulce-tentacion.crt" "C:\xampp\apache\conf\ssl.crt\server.crt" >nul
copy /Y "%~dp0dulce-tentacion.key" "C:\xampp\apache\conf\ssl.key\server.key" >nul

echo [2/3] Instalando Autoridad de Certificacion Raiz (Dulce Tentacion Root CA) en Windows...
certutil -user -addstore Root "%~dp0DulceTentacionRootCA.crt"

echo [3/3] Reiniciando Apache para aplicar el nuevo certificado...
taskkill /F /IM httpd.exe >nul 2>&1
timeout /t 1 /nobreak >nul
start "" /B "C:\xampp\apache\bin\httpd.exe"

echo.
echo ====================================================================
echo   LISTO: Cierra todas las ventanas del navegador y vuelve a abrir:
echo   https://localhost/dulce-tentacion/
echo ====================================================================
pause


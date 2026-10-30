# -*- coding: utf-8 -*-
import os
import shutil
from PIL import Image

src_root = r"c:\Users\jrios\OneDrive\Escritorio\dulce-tentacion"
dest_root = os.path.join(os.environ["TEMP"], "repo_check", "dulce-tentacion")

if os.path.exists(dest_root):
    shutil.rmtree(dest_root)
os.makedirs(dest_root, exist_ok=True)

# Copiar recursivamente omitiendo temporales y docs/referencias
exclude_dirs = {"__pycache__", "referencias"}
exclude_exts = {".pyc"}

for root, dirs, files in os.walk(src_root):
    dirs[:] = [d for d in dirs if d not in exclude_dirs]
    rel = os.path.relpath(root, src_root)
    target_dir = os.path.join(dest_root, rel) if rel != "." else dest_root
    os.makedirs(target_dir, exist_ok=True)

    for f in files:
        if any(f.endswith(ext) for ext in exclude_exts):
            continue
        # No subir llaves privadas .key a GitHub (evitar que GitHub Secret Scanning bloquee)
        if f.endswith(".key"):
            continue
        
        src_file = os.path.join(root, f)
        dest_file = os.path.join(target_dir, f)

        # Si es una imagen en assets/img o administracion/assets/img, optimizar para web
        lower_name = f.lower()
        if lower_name.endswith((".jpeg", ".jpg", ".png")) and "img" in root.lower() and f not in ["logo.png", "mascota.png"]:
            try:
                with Image.open(src_file) as img:
                    img = img.convert("RGB")
                    img.thumbnail((900, 900), Image.Resampling.LANCZOS)
                    img.save(dest_file, "JPEG", quality=82, optimize=True)
                continue
            except Exception as e:
                pass

        shutil.copy2(src_file, dest_file)

print("Directorio dulce-tentacion preparado con éxito.")

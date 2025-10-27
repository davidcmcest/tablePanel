# 🇪🇸 Traducción al Español / Spanish Translation

Este script traduce completamente el widget Table a español, incluyendo todas las etiquetas, textos de ayuda y tooltips.

This script completely translates the Table widget to Spanish, including all labels, help texts and tooltips.

## 📋 Qué se traduce / What gets translated

### 1. Etiquetas de campos / Field Labels
- Host groups → Grupos de hosts
- Item filter → Filtro de items
- Layout → Diseño
- Display → Visualización
- Y más de 50 etiquetas más...

### 2. Textos de ayuda (iconos ❓) / Help texts (❓ icons)
Todos los tooltips que aparecen al pasar el mouse sobre los iconos de interrogación.

All tooltips that appear when hovering over question mark icons.

### 3. Placeholders y mensajes / Placeholders and messages
- "Set custom display text" → "Establecer texto de visualización personalizado"
- "calculated" → "calculado"
- etc.

### 4. Botones / Buttons
- Add → Agregar
- Update → Actualizar
- Remove → Eliminar

## 🚀 Cómo usar / How to use

### Opción 1: En el servidor / On the server

```bash
# 1. Descargar el script
cd /tmp
wget https://raw.githubusercontent.com/davidcmcest/tablePanel/claude/zabbix-plugin-version-compatibility-011CURyLXCHhhMgQGeKpz43J/translate_to_spanish.sh

# 2. Dar permisos de ejecución
chmod +x translate_to_spanish.sh

# 3. Ejecutar (requiere sudo)
sudo ./translate_to_spanish.sh

# 4. Reiniciar Apache
sudo systemctl restart httpd
```

### Opción 2: Desde el ZIP descargado / From downloaded ZIP

Si ya descargaste el ZIP del plugin:

```bash
cd /usr/share/zabbix/modules/
sudo chmod +x tableMain/translate_to_spanish.sh
sudo tableMain/translate_to_spanish.sh
sudo systemctl restart httpd
```

## 🔄 Restaurar idioma original / Restore original language

El script crea automáticamente respaldos de todos los archivos modificados con extensión `.backup`.

The script automatically creates backups of all modified files with `.backup` extension.

Para restaurar el idioma original / To restore original language:

```bash
sudo find /usr/share/zabbix/modules/tableMain -name '*.backup' -exec bash -c 'mv "$0" "${0%.backup}"' {} \;
sudo systemctl restart httpd
```

## ⚠️ Notas importantes / Important notes

- El script requiere permisos de root (sudo) / Script requires root permissions (sudo)
- Se crean respaldos automáticamente antes de modificar / Backups are created automatically before modifying
- Compatible con Zabbix 7.0 / Compatible with Zabbix 7.0
- Los archivos modificados son solo PHP (no afecta funcionalidad) / Only PHP files are modified (does not affect functionality)

## 📝 Archivos modificados / Modified files

- `tableMain/includes/WidgetForm.php` - Formulario principal del widget
- `tableMain/views/column.edit.php` - Formulario de edición de columnas

## 🐛 Solución de problemas / Troubleshooting

### El script no ejecuta
```bash
# Verificar permisos
ls -l translate_to_spanish.sh

# Dar permisos si es necesario
chmod +x translate_to_spanish.sh
```

### Quiero volver al inglés
```bash
# Restaurar archivos originales
sudo find /usr/share/zabbix/modules/tableMain -name '*.backup' -exec bash -c 'mv "$0" "${0%.backup}"' {} \;
sudo systemctl restart httpd
```

### Zabbix no muestra cambios
```bash
# Limpiar caché del navegador (Ctrl + F5)
# O reiniciar Apache
sudo systemctl restart httpd
```

## 📸 Ejemplos de traducción / Translation examples

**Antes / Before:**
```
Layout: Horizontal | Vertical | 3 Column | Column per pattern
```

**Después / After:**
```
Diseño: Horizontal | Vertical | 3 Columnas | Columna por patrón
```

---

**Antes / Before:**
```
❓ "If you know the item key pattern, you can specify it instead..."
```

**Después / After:**
```
❓ "Si conoces el patrón de clave de item, puedes especificarlo en lugar..."
```

## 📧 Soporte / Support

Si encuentras algún problema o texto que no se tradujo correctamente, por favor abre un issue en el repositorio de GitHub.

If you find any issue or text that wasn't translated correctly, please open an issue on the GitHub repository.

# Práctica: Desarrollo de un sitio web con Wordpress

## Objetivo y Resumen del Proyecto
Este proyecto tiene como objetivo la creación e implementación de una plataforma de comercio electrónico. Se utilizó **WordPress** como gestor de contenido, junto con el plugin **WooCommerce**, para establecer una tienda virtual operativa.

La práctica enfatiza los procesos de instalación, personalización visual, gestión de seguridad, optimización SEO y la administración detallada del catálogo de productos, siguiendo rigurosos estándares y buenas prácticas de desarrollo web.

El e-commerce está diseñado para la venta de **productos deportivos** tales como Ropa, Tenis y Accesorios para el entrenamiento.

## 💻 Entorno Técnico

| Herramienta | Función Principal |
| :--- | :--- |
| **WordPress** | Core del Sistema de Gestión de Contenidos (CMS). |
| **WooCommerce** | Módulo para las funcionalidades de tienda en línea. |
| **Elementor** | Herramienta para la construcción y diseño visual de páginas. |
| **Astra** | Tema base conocido por su ligereza y velocidad. |
| **Starter Templates for Astra** | Plugin utilizado para la estructura inicial de las plantillas. |

### Temática 
* **Tema Principal Seleccionado:** Articulos Deportivos (Ropa, Tenis, Accesorios).

## Guía de Despliegue Local

El proyecto está configurado y optimizado para ser ejecutado en entornos de servidor local como **XAMPP**.

### I. Preparación del Entorno

1.  **Clonación del Repositorio:** Es necesario clonar el contenido de este repositorio directamente dentro del directorio raíz de tu servidor (típicamente `htdocs` en XAMPP).

    ```bash
    git clone [https://github.com/AlfredoRiveraaa/AlphaSport.git](https://github.com/AlfredoRiveraaa/AlphaSport.git)
    # Asegúrate de que la carpeta resultante se denomine 'AlphaSport'
    ```

2.  **Activación de Servicios:** Accede al panel de control de tu servidor local e inicia los servicios requeridos: **Apache** (servidor web) y **MySQL** (base de datos).

### II. Configuración de la Base de Datos (BD)

1.  **Acceso al Administrador:** Abre tu navegador y dirígete a `http://localhost:8080/phpmyadmin` (o la interfaz de gestión de BD que utilices).
2.  **Creación de la BD:** Genera una nueva base de datos con el nombre exacto de **`alpha_sport_db`**.
3.  **Importación de Datos:** Dentro de la base de datos recién creada, utiliza la función de "Importar" para cargar el archivo **`alpha_sport_db.sql`** incluido en la raíz del proyecto.

### III. Verificación y Acceso

1.  **Credenciales de Conexión:** El archivo de configuración de WordPress (`wp-config.php`) ya viene preconfigurado con las credenciales por defecto de XAMPP:
    * **Nombre de la BD:** `practica_wordpress`
    * **Usuario de MySQL:** `root`
    * **Contraseña de MySQL:** (Vacía)
    
    > **NOTA:** Si tu configuración de MySQL local es diferente, **deberás actualizar** los valores correspondientes dentro del archivo `wp-config.php`.
    > **NOTA:** También se agregó al repositorio la carpeta **assets** donde se encuentran la imagnes utilizadas para el proyecto.

2.  **Inicio del Sitio:** Una vez completados los pasos anteriores, la tienda en línea estará accesible mediante la siguiente URL:
    ```
    http://localhost:8080/practica_wordpress
    ```
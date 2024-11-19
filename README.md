# Quinque

Quinque es una aplicación web desarrollada con Symfony 7.1 para la gestión de solicitudes en las convocatorias de quinquenios para el personal.

## Requisitos

- PHP 8.2 o superior
- Composer
- MySQL/MariaDB
- Extensiones PHP requeridas:
  - ctype
  - iconv
  - pdo
  - pdo_mysql

## Instalación

1. Clonar el repositorio:
```bash
git clone [url-del-repositorio]
```

2. Instalar dependencias:
```bash
composer install
```

3. Copiar el archivo de configuración:
```bash
cp .env.sample .env
```

4. Configurar las variables de entorno en `.env`:
```
DATABASE_URL="mysql://user:password@127.0.0.1:3306/nombre_base_datos"
```

5. Crear la base de datos:
```bash
php bin/console doctrine:database:create
```

6. Ejecutar las migraciones:
```bash
php bin/console doctrine:migrations:migrate
```

## Estructura del Proyecto

- `src/Controller/Quinque/` - Controladores de la aplicación
  - `CategoriaController.php` - Gestión de categorías
  - `ConvocatoriaController.php` - Gestión de convocatorias
  - `DepartamentoController.php` - Gestión de departamentos
  - `EstadoController.php` - Gestión de estados de los meritos
  - `MeritoController.php` - Gestión de méritos
  - `PersonaController.php` - Gestión de solicitantes
  - `SolicitudController.php` - Gestión de solicitudes

- `templates/` - Plantillas Twig
- `config/` - Archivos de configuración
- `migrations/` - Migraciones de base de datos

## Características

- Gestión completa de convocatorias, solicitudes y méritos
- Sistema de estados para seguimiento méritos de solicitudes
- Validación de fechas para evitar solapamientos
- Cálculo de años, meses y días para comprobar si procede la obtención del quinquenio.
- Gestión de estados, departamentos y categorías

## Próximamente
- Generación de las correspondientes resoluciones

## Desarrollo

Para iniciar el servidor de desarrollo:
```bash
symfony server:start
```

### Comandos Útiles

- Limpiar caché:
```bash
php bin/console cache:clear
```

- Crear una nueva entidad:
```bash
php bin/console make:entity
```

- Crear una migración:
```bash
php bin/console make:migration
```

### Estándares de Código

El proyecto utiliza PHP CS Fixer para mantener un estilo de código consistente. Para ejecutar el fixer:

```bash
vendor/bin/php-cs-fixer fix
```

## Contribución

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## Licencia

Este proyecto es software libre.

## Autores

- Juanan Ruiz <juanan@us.es>
- Ramón M. Gómez <ramongomez@us.es>

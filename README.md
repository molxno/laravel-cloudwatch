# Laravel CloudWatch Logging

Este paquete permite enviar logs de Laravel a AWS CloudWatch de manera sencilla utilizando el sistema de logging de Laravel.

## 📦 Instalación

Puedes instalar el paquete mediante Composer:

```sh
composer require tu-namespace/laravel-cloudwatch-logging
```

## ⚙️ Configuración

### 1. Agregar el canal de logging

Laravel permite configurar los canales de logging en el archivo `config/logging.php`. Agrega el siguiente bloque dentro de la sección `channels`:

```php
'channels' => [
    'cloudwatch' => [
            'driver' => 'cloudwatch',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'log_group' => env('CLOUDWATCH_LOG_GROUP'),
            'log_stream' => env('CLOUDWATCH_LOG_STREAM'),
        ],
],
```

### 2. Configurar las variables de entorno

Asegúrate de agregar las credenciales de AWS en tu archivo `.env`:

```ini
LOG_CHANNEL=cloudwatch
AWS_ACCESS_KEY_ID=your-access-key
AWS_SECRET_ACCESS_KEY=your-secret-key
AWS_DEFAULT_REGION=us-east-2
CLOUDWATCH_LOG_GROUP=/app/env
CLOUDWATCH_LOG_STREAM=app-stream
```

Si estás utilizando IAM roles en un servidor EC2 o ECS, puedes omitir `AWS_ACCESS_KEY_ID` y `AWS_SECRET_ACCESS_KEY`.

## 🚀 Uso con Laravel

Una vez configurado, puedes utilizar el helper `Log` de Laravel para enviar logs a CloudWatch:

```php
use Illuminate\Support\Facades\Log;

Log::info('Este es un mensaje informativo en CloudWatch');
Log::error('Este es un mensaje de error en CloudWatch', ['error_code' => 500]);
```

También puedes usar los otros niveles de logging disponibles: `debug`, `warning`, `critical`, etc.

## 🛠 Solución de problemas

Si los logs no se envían a CloudWatch:

1. **Verifica las credenciales de AWS**: Asegúrate de que `AWS_ACCESS_KEY_ID` y `AWS_SECRET_ACCESS_KEY` sean correctos.
2. **Revisa los permisos de IAM**: El usuario debe tener permisos para `logs:PutLogEvents` en el grupo de logs configurado.
3. **Comprueba la región de AWS**: La región en `.env` debe coincidir con la configuración de tu grupo de logs en AWS CloudWatch.
4. **Valida el nombre del grupo y stream de logs**: Asegúrate de que `CLOUDWATCH_LOG_GROUP` y `CLOUDWATCH_LOG_STREAM` sean correctos.
5. **Habilita el modo debug en Laravel**:

   ```ini
   APP_DEBUG=true
   LOG_LEVEL=debug
   ```

Si el problema persiste, revisa los logs de Laravel en `storage/logs/laravel.log` para más detalles.

## 📄 Licencia

Este proyecto está bajo la licencia MIT. ¡Siéntete libre de contribuir y mejorar el paquete! 🚀


# Procesamiento de soportes

Guardar conserva el original y registra el envío en la conexión de cola
`support_uploads`, cola `support-files`. No realiza transferencias desde Livewire,
incluso si la cola predeterminada del entorno usa `sync`.

Cada trabajo procesa hasta tres copias, con un presupuesto de 60 segundos entre
transferencias. Una transferencia ya iniciada puede superar ese presupuesto.
El siguiente trabajo continúa con las copias pendientes. SharePoint conserva
un original y SFTP usa `sftp_geodis` con los nombres documentales.

## Activación en producción

Después de actualizar el código:

Publicar también `public/build/` generado con `npm run build` en local.

```bash
php artisan optimize:clear
php artisan migrate --force
php artisan optimize
php artisan queue:restart
```

Mantener un trabajador adicional, administrado por el supervisor de procesos
del servidor con reinicio automático:

```bash
php artisan queue:work support_uploads --queue=support-files --sleep=2 --timeout=300 --tries=0
```

El trabajador usa el mismo servidor y almacenamiento `local` que la aplicación:
debe poder leer `storage/app/private/support_sources`. No reemplaza al trabajador
de los otros procesos del sistema. La conexión reutiliza la tabla `jobs`; no
requiere Redis ni paquetes adicionales.

El límite del trabajo es 300 segundos, el bloqueo dura 330 y la reserva del
trabajo 360. Mantener ese orden para evitar ejecuciones simultáneas tras un corte.
PHP CLI necesita PCNTL para aplicar el límite del trabajador en Linux.

## Estado y recuperación

En Cargados se consulta el estado cada cinco segundos mientras el modal está
visible. Muestra En espera, Procesando, Requiere reintento o Guardado, junto con
las copias confirmadas y una barra de progreso. Al detectar la finalización,
notifica una vez. Cerrar el modal no detiene el trabajador.

Los fallos de transferencia tienen hasta tres excepciones por trabajo antes de
quedar en Requiere reintento. La espera por un bloqueo ocupado se reprograma sin
iniciar otra transferencia, con un máximo de 30 minutos por trabajo.

Reintentar utiliza el original conservado y omite las copias ya confirmadas.
Si el trabajador no ha actualizado el estado en diez minutos, el botón permite
programar otra ejecución; los trabajos anteriores quedan invalidados por su
identificador de ejecución. No borrar bloqueos ni archivos originales manualmente.

Los soportes pendientes creados antes de este cambio también pueden reintentarse.
Un archivo cuyo original local ya no exista necesita volver a cargarse.

Para el soporte 9: aplicar la migración, arrancar el trabajador y pulsar
Reintentar guardado. Un bloqueo del intento anterior se espera hasta que expire.

Los registros `SupportUpload:attempt_failed`, `SupportUpload:failed` y
`UploadFileForm:retry_failed` incluyen la clase de excepción y el identificador
del soporte. `SupportUpload:waiting_for_lock` identifica la espera por bloqueo.

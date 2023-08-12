## Sobre PosBot v1.0

El Sistema PposBot es un sistema web para gestionar ventas en internet que incluye un agente de ventas (un chatbot para whatsapp), el sistema pueder ser implementado en, restaurantes, hoteles, bares y otros establecimientos comerciales. Los sistemas varían en tamaño y complejidad, desde sistemas de tarjetas de crédito sencillos hasta programación. mas info al 72823861


## Installation & Documentation

### Paso 1
- clonar el repositorio
- editar el archivo .env
- composer install

### paso 2
- correr sql del archivo system.sql
- correr php artisan migrate --force
- corre php artisan db:seed

### paso 3
- desde el frontend crear negocio con usuario luis.flores
- ingresa con el usuario creado
- configurar el panel de super admin

### Video Tutoriales
- https://youtube.com/playlist?list=PL2SoOLWxHIbEHmCyNIR0hY-gY7nnGyUpu

### Documentacion
- https://www.youtube.com/watch?v=wtAmMuXYUb8    (api)
- /docs  (api)

### Get Token
- https://pos.loginweb.dev/oauth/token   (url)
- grant_type : password
- client_id : 1
- username : luis.flores
- password : 123456
- client_secret : {token}
- Luego de obtener el token, puede realizar consultas a la api, lee el manual

## License

The Ultimate POS software is licensed by goSystem @2023.

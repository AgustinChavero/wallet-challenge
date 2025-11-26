# 💳 Virtual Wallet API

API REST con backend SOAP para gestión de billetera virtual con sistema de pagos seguros mediante tokens de confirmación.

## ✨ Características

-   ✅ Registro de clientes
-   ✅ Recarga de billetera
-   ✅ Sistema de pagos con confirmación por token de 6 dígitos
-   ✅ Consulta de saldo
-   ✅ Historial de movimientos
-   ✅ Arquitectura SOAP + REST
-   ✅ Validación de tokens con expiración (10 minutos)
-   ✅ Transacciones atómicas con rollback automático

## 🏗️ Arquitectura

```
┌─────────────┐      ┌──────────────┐      ┌─────────────┐      ┌──────────┐
│   Postman   │─────▶│  REST API    │─────▶│ SOAP Server │─────▶│ Database │
│  (Cliente)  │◀─────│ (Controller) │◀─────│  (Service)  │◀─────│  (MySQL) │
└─────────────┘      └──────────────┘      └─────────────┘      └──────────┘
                            │
                            └─────▶ JSON Response
```

### Capas de la Aplicación

1. **REST API** (`Controllers`): Punto de entrada HTTP, recibe JSON y valida requests
2. **SOAP Client** (`SoapClientTrait`): Conecta el REST con el servidor SOAP interno
3. **SOAP Server** (`routes/web.php /soap`): Expone métodos SOAP
4. **SOAP Service** (`WalletSoapService`): Capa de adaptación SOAP
5. **Business Logic** (`WalletService`): Lógica de negocio y acceso a BD
6. **Database**: MySQL con UUIDs y soft deletes

## 🛠️ Requisitos

-   Docker & Docker Compose
-   Git

## 🚀 Instalación

### 1. Clonar el repositorio

```bash
git clone <https://github.com/AgustinChavero/wallet-challenge.git>
cd wallet-challenge
```

### 2. Levantar los contenedores

```bash
docker compose up -d
```

### 3. Instalar dependencias

```bash
docker compose exec api composer install
```

### 4. Configurar variables de entorno

El archivo `.env` ya está configurado. Verifica que tenga:

```env
DB_CONNECTION=mysql
DB_HOST=dbwallet
DB_PORT=3306
DB_DATABASE=walletdatabase
DB_USERNAME=user
DB_PASSWORD=password

REDIS_HOST=redis
REDIS_PORT=6379
```

### 5. Ejecutar migraciones y seeders

```bash
docker compose exec api php artisan migrate:fresh --seed
```

### 6. Verificar instalación

```bash
docker compose ps
```

Deberías ver 4 contenedores corriendo:

-   `wallet-challenge-nginx` (puerto 8000)
-   `wallet-challenge-api`
-   `dbwallet` (puerto 3307)
-   `redis` (puerto 6379)

## 📡 Uso

La API está disponible en `http://localhost:8000/api`

### Importar colección de Postman

1. Abre Postman
2. Click en **Import**
3. Selecciona el archivo `documentation/Wallet_API.postman_collection.json`
4. La colección se importará con todos los endpoints configurados

### Flujo de uso típico

```bash
# 1. Registrar cliente
POST /api/client/register

# 2. Recargar billetera
POST /api/wallet/recharge

# 3. Consultar saldo
POST /api/wallet/balance

# 4. Iniciar pago (genera session_id y token)
POST /api/payment/transfer

# 5. Confirmar pago (usar session_id y token del paso 4)
POST /api/payment/confirm
```

## 🔌 Endpoints

### Client

#### POST `/api/client/register`

Registra un nuevo cliente y crea su billetera.

**Request:**

```json
{
    "document": "12345678",
    "names": "John Doe",
    "email": "john@example.com",
    "phone": "1234567890"
}
```

**Response:**

```json
{
    "success": true,
    "cod_error": "00",
    "message_error": "Client registered successfully",
    "data": {
        "client_id": "uuid",
        "wallet_id": "uuid",
        "balance": 0
    }
}
```

### Wallet

#### POST `/api/wallet/recharge`

Recarga saldo en la billetera.

**Request:**

```json
{
    "document": "12345678",
    "phone": "1234567890",
    "amount": 1000
}
```

#### POST `/api/wallet/balance`

Consulta el saldo actual.

**Request:**

```json
{
    "document": "12345678",
    "phone": "1234567890"
}
```

### Payment

#### POST `/api/payment/transfer`

Inicia un pago y genera un token de confirmación.

**Request:**

```json
{
    "document": "12345678",
    "phone": "1234567890",
    "amount": 100
}
```

**Response:**

```json
{
    "success": true,
    "cod_error": "00",
    "message_error": "Payment initiated. Token sent to email.",
    "data": {
        "session_id": "uuid",
        "token": "123456",
        "message": "Token sent to email (visible for testing purposes)",
        "expires_in_minutes": 10
    }
}
```

⚠️ **Nota**: El token es visible en la respuesta solo para testing. En producción debe enviarse por email.

#### POST `/api/payment/confirm`

Confirma el pago con el token recibido.

**Request:**

```json
{
    "session_id": "uuid-from-previous-step",
    "token": "123456"
}
```

### Testing manual con Postman

Ver `documentation/Wallet_API.postman_collection.json`

### Verificar logs

```bash
# Logs de la API
docker compose logs -f api

# Logs de Nginx
docker compose logs -f nginx

# Logs de Laravel
docker compose exec api tail -f storage/logs/laravel.log
```

## 📚 Documentación

-   [Diagrama de Base de Datos](documentation/database-diagram.md)
-   [Colección de Postman](documentation/Wallet.postman_collection.json)

### Estructura de Respuestas

Todas las respuestas siguen esta estructura:

```json
{
    "success": true,
    "cod_error": "00",
    "message_error": "Descriptive message",
    "data": {}
}
```

**Códigos de Error:**

-   `00`: Éxito
-   `01`: Error en registro de cliente
-   `02`: Error en recarga de billetera
-   `03`: Error al iniciar pago
-   `04`: Error al confirmar pago
-   `05`: Error al consultar saldo
-   `10`: Error de sistema/SOAP

## 🗄️ Base de Datos

### Tablas principales

-   `clients`: Información de usuarios
-   `wallets`: Billeteras virtuales
-   `wallet_movements`: Historial de transacciones
-   `payment_sessions`: Sesiones de pago con tokens
-   `wallet_movement_types`: Tipos de movimiento (RECHARGE, PURCHASE)
-   `payment_session_statuses`: Estados de sesión (PENDING, COMPLETED)

## 📝 Cómo visualizar el Diagrama de Base de Datos

El archivo `database-diagram.md` contiene un diagrama generado con **Mermaid**.
Para visualizarlo correctamente es necesario usar **Visual Studio Code** con la extensión adecuada.

### ✅ Extensión necesaria

Instalar en Visual Studio Code:

👉 **Markdown Preview Mermaid Support**
ID: `bierner.markdown-mermaid`

Esta extensión permite renderizar diagramas Mermaid incluidos dentro de archivos Markdown.

---

### ✅ Cómo abrir el diagrama

1. Abrir el archivo:
   **`documentation/database-diagram.md`**

2. En Visual Studio Code presionar:
   **Ctrl + Shift + V**
   _(o clic derecho → “Open Preview” / “Abrir Vista Previa”)_

3. El diagrama se renderizará automáticamente en la vista previa.

---

### 📌 Nota importante

Si la extensión no está instalada, el archivo mostrará solo texto y **no se dibujará el diagrama**.

---

## 🔧 Comandos Útiles

```bash
# Reiniciar contenedores
docker compose restart

# Ver rutas disponibles
docker compose exec api php artisan route:list

# Acceder a MySQL
docker compose exec dbwallet mysql -u user -ppassword walletdatabase

# Limpiar caché
docker compose exec api php artisan cache:clear
docker compose exec api php artisan config:clear
docker compose exec api php artisan route:clear

# Recrear base de datos
docker compose exec api php artisan migrate:fresh --seed
```

## 🐛 Troubleshooting

### El servidor no responde

```bash
docker compose ps
docker compose logs api
```

### Error de conexión a la base de datos

```bash
docker compose exec api php artisan config:clear
docker compose restart api
```

### Token expirado

Los tokens expiran después de 10 minutos. Genera un nuevo pago.

## 👨‍💻 Autor

Agustín Daniel Chavero

## 📄 Licencia

Este proyecto es parte de una prueba técnica.

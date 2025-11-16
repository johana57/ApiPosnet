# 💳 API POSNET para Procesamiento de Pagos

API diseñada para gestionar clientes, registrar tarjetas de crédito y procesar pagos en cuotas, aplicando recargos según el número de cuotas.

---

## 💡 Notas Importantes

* **Tarjetas Aceptadas:** Solo se aceptan las marcas **VISA** y **AMEX**.
* **Formato de Tarjeta:** El número de tarjeta debe ser estrictamente de **8 dígitos**.
* **Cuotas:** Los pagos se pueden realizar entre **1 y 6 cuotas**.
* **Recargo:** Cada cuota adicional (a partir de 1) aplica un recargo del **3%** sobre el monto base.
    * *Fórmula del total:* $Total = Monto \times (1 + (N°\ Cuotas - 1) \times 0.03)$
* **Errores:** La API siempre retorna errores controlados en formato JSON con el código de estado `422 Unprocessable Entity` y un mensaje descriptivo.

---

## ⚙️ Endpoints de la API

### 1. Registrar Tarjeta
**`POST /card/register`**

Registra una nueva tarjeta de crédito asociada a un cliente.

#### Parámetros del Request (`Body: application/json`)

| Campo | Tipo | Requerido | Descripción |
| :--- | :--- | :--- | :--- |
| `brand` | String | Sí | Marca de la tarjeta. Solo acepta "VISA" o "AMEX". |
| `bank` | String | Sí | Nombre del banco emisor. |
| `number` | String | Sí | Número de la tarjeta (debe ser de 8 dígitos). |
| `limit` | Number | Sí | Límite de crédito disponible para la tarjeta. |
| `dni` | String | Sí | DNI del titular. |
| `first_name` | String | Sí | Nombre del titular. |
| `last_name` | String | Sí | Apellido del titular. |

#### Respuestas de Ejemplo

| Status | Body (JSON) | Descripción |
| :--- | :--- | :--- |
| `200 OK` | `{"status": "success", "numberCard": "12345678"}` | Registro exitoso. |
| `422 Error` | `{"status": "error", "code": 422, "message": "Disculpe, solo trabajamos con tarjetas VISA o AMEX."}` | Marca no soportada. |

---

### 2. Realizar Pago
**`POST /payment/do`**

Procesa un pago con una tarjeta registrada, aplicando el recargo por cuotas.

#### Parámetros del Request (`Body: application/json`)

| Campo | Tipo | Requerido | Descripción |
| :--- | :--- | :--- | :--- |
| `number` | String | Sí | Número de la tarjeta registrada (8 dígitos). |
| `amount` | Number | Sí | Monto de la compra sin recargo. |
| `installments` | Number | Sí | Número de cuotas (entre 1 y 6). |

#### Respuestas de Ejemplo

| Status | Body (JSON) | Descripción |
| :--- | :--- | :--- |
| `200 OK` | ```json {"status": "success", "ticket": {"client": "Luis Gomez", "total": 5150, "installment": 2575}} ``` | Pago exitoso. El `total` incluye el recargo. |
| `422 Error` | `{"status": "error", "code": 422, "message": "Límite de tarjeta insuficiente."}` | El `total` excede el límite disponible. |

---

## 🚀 Cómo Probar la API (Setup Local)

### Requisitos
* **PHP 8 o superior**
* **Extensión `json`**
* Un cliente HTTP (Insomnia o Postman).

### 1. Iniciar el Servidor
Abre tu terminal en la carpeta raíz del proyecto y ejecuta el servidor de desarrollo CLI:

```bash
php -S localhost:8000 -t public/

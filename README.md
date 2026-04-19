# MSR_AgenticUcpCheckout

Companion module to **MSR_AgenticUcp** that gives AI agents a complete
REST API for e-commerce: browse the catalog, manage a guest cart, go
through checkout, place orders, and track shipments — all over a single
UCP-authenticated session.

---

## Requirements

| Dependency | Version |
|------------|---------|
| Magento Open Source / Adobe Commerce | 2.4.6+  |
| PHP | 8.1+    |
| MSR_AgenticUcp | 1.0.0+  |

---

## Installation

```bash
php bin/magento module:enable MSR_AgenticUcpCheckout
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento cache:flush
```

---

## How it works

Every request must carry a Bearer token obtained from
`POST /rest/V1/ucp/auth` (provided by MSR_AgenticUcp). The token
encodes the agent's DID, which is used to maintain an isolated guest
cart for each agent identity across requests.

```
Agent  →  POST /V1/ucp/auth          →  Bearer token
Agent  →  GET  /V1/ucp/catalog       →  browse products
Agent  →  POST /V1/ucp/cart          →  add item
Agent  →  POST /V1/ucp/checkout/shipping  →  set address + method
Agent  →  POST /V1/ucp/order         →  place order (requires human confirmation header)
Agent  →  GET  /V1/ucp/order/:id     →  track order
```

---

## REST API

All routes are under `/rest/V1/ucp/`.

### Catalog

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/V1/ucp/catalog` | Browse products. Optional `pageSize` param (default 10). Returns name, SKU, price, special price, tier prices, stock status. |
| GET | `/V1/ucp/search` | Full-text product search. Required `q` param. |

### Cart

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/V1/ucp/cart` | View cart contents, item list, subtotal, grand total. |
| POST | `/V1/ucp/cart` | Add item. Body: `{ "sku": "SKU-001", "qty": 2 }`. Creates cart automatically. |
| DELETE | `/V1/ucp/cart/:itemId` | Remove one line item by cart item ID. |
| DELETE | `/V1/ucp/cart` | Clear the entire cart. |

### Checkout

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/V1/ucp/checkout/shipping` | Set shipping address and method. Pass `billing_same_as_shipping: true` (default) to copy to billing. |
| POST | `/V1/ucp/checkout/billing` | Set a separate billing address (only when different from shipping). |
| GET | `/V1/ucp/checkout/shipping-methods` | List available shipping methods with carrier, code, label, and cost. |
| GET | `/V1/ucp/checkout/payment-methods` | List available payment methods with code and title. |
| GET | `/V1/ucp/checkout/totals` | Full totals: subtotal, shipping, tax, discount, grand total, currency. |

### Order

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/V1/ucp/order` | Place the order. Requires `X-UCP-Human-Confirmation` header. Body: `{ "payment_method_code": "checkmo", "email": "customer@example.com" }`. |
| GET | `/V1/ucp/order/:orderId` | Track by increment ID (e.g. `000000001`) or numeric order ID. Returns status, shipping address, tracking numbers, items. |

### Inventory

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/V1/ucp/inventory` | Check stock. `?sku=SKU-001` or `?sku=SKU-001,SKU-002`. Returns in-stock flag and qty per SKU. |

---

## Example: full checkout flow

```bash
TOKEN="Bearer <token-from-auth>"

# Browse
curl -s "$STORE/rest/V1/ucp/catalog" -H "Authorization: $TOKEN"

# Add to cart
curl -s -X POST "$STORE/rest/V1/ucp/cart" \
  -H "Authorization: $TOKEN" -H "Content-Type: application/json" \
  -d '{"sku":"24-MB01","qty":1}'

# Set shipping (billing copied automatically)
curl -s -X POST "$STORE/rest/V1/ucp/checkout/shipping" \
  -H "Authorization: $TOKEN" -H "Content-Type: application/json" \
  -d '{"firstname":"Jane","lastname":"Doe","street":"123 Main St",
       "city":"Austin","region_code":"TX","postcode":"78701",
       "country_id":"US","telephone":"5125550100",
       "shipping_method_code":"flatrate_flatrate",
       "billing_same_as_shipping":true}'

# Place order (human confirmation required)
CONFIRM=$(echo -n "confirm-$(date +%s)" | base64)
curl -s -X POST "$STORE/rest/V1/ucp/order" \
  -H "Authorization: $TOKEN" -H "Content-Type: application/json" \
  -H "X-UCP-Human-Confirmation: $CONFIRM" \
  -d '{"payment_method_code":"checkmo","email":"jane@example.com"}'

# Track
curl -s "$STORE/rest/V1/ucp/order/000000001" -H "Authorization: $TOKEN"
```

---

## Session management

`Model/Cart/SessionManager` maintains an isolated guest cart per agent
identity. The agent DID is extracted from the Bearer token's `sub`
claim and hashed with SHA-256 to form a cache key with a 24-hour TTL.
This means each registered agent always resumes its own cart, even
across separate HTTP requests.

---

## Module structure

```
MSR/AgenticUcpCheckout/
├── Api/
│   ├── Data/                  # Data transfer object interfaces
│   │   ├── CartItemInterface.php
│   │   ├── CartSummaryInterface.php
│   │   ├── OrderResultInterface.php
│   │   └── ShippingAddressInterface.php
│   ├── UcpCatalogInterface.php
│   ├── UcpCartInterface.php
│   ├── UcpCheckoutInterface.php
│   ├── UcpOrderInterface.php
│   └── UcpInventoryInterface.php
├── Model/
│   ├── Cart/
│   │   └── SessionManager.php  # Per-agent cart isolation
│   ├── Data/                   # DTO implementations
│   ├── UcpCatalog.php
│   ├── UcpCart.php
│   ├── UcpCheckout.php
│   ├── UcpOrder.php
│   └── UcpInventory.php
├── Plugin/
│   └── AgentPolicyGuardCheckout.php
├── etc/
│   ├── di.xml
│   ├── module.xml
│   └── webapi.xml
├── CHANGELOG.md
└── README.md
```

---

## See also

- `MSR_AgenticUcp` — core module: authentication, policy engine, admin UI, audit log
- `UCP_Testing_Guide.md` — step-by-step setup and live chat testing instructions
- `local_testing_ollama/` — terminal chat clients (`ucp_chat.py`, `ucp_chat.js`)

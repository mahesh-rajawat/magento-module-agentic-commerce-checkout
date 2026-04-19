# MSR_AgenticUcpCheckout — Changelog

## 1.0.0 — Initial release

### Module overview

Companion module to MSR_AgenticUcp that exposes a complete REST API
for AI agents to autonomously browse products, manage a guest cart,
complete checkout, and track orders — all over a single authenticated
session keyed to the agent's DID.

Requires MSR_AgenticUcp 1.0.0+ for policy enforcement and authentication.

---

### REST API endpoints

All routes are under `/rest/V1/ucp/` and accept the Bearer token issued
by `POST /rest/V1/ucp/auth`.

#### Catalog

| Method | Route | Description |
|--------|-------|-------------|
| GET | `/V1/ucp/catalog` | Browse products with pagination (`pageSize`). Returns name, SKU, price, special price, tier prices, stock status, and a ready-made `price_summary` string. |
| GET | `/V1/ucp/search` | Full-text product search by keyword (`q`). Same response shape as browse. |

#### Cart

| Method | Route | Description |
|--------|-------|-------------|
| GET | `/V1/ucp/cart` | View current cart — items, quantities, subtotal, grand total, currency. |
| POST | `/V1/ucp/cart` | Add a product by SKU and quantity. Creates a new guest cart for the agent session if none exists. |
| DELETE | `/V1/ucp/cart/:itemId` | Remove a specific line item by cart item ID. |
| DELETE | `/V1/ucp/cart` | Clear the entire cart. |

#### Checkout

| Method | Route | Description |
|--------|-------|-------------|
| POST | `/V1/ucp/checkout/shipping` | Set shipping address and delivery method. Accepts `billing_same_as_shipping` flag (default `true`) to copy the address to billing automatically. |
| POST | `/V1/ucp/checkout/billing` | Set a separate billing address (only needed when different from shipping). |
| GET | `/V1/ucp/checkout/shipping-methods` | List available shipping methods with carrier, code, label, and price. |
| GET | `/V1/ucp/checkout/payment-methods` | List available payment methods with code and title. |
| GET | `/V1/ucp/checkout/totals` | Return full totals breakdown: subtotal, shipping, tax, discount, grand total, and currency. |

#### Order

| Method | Route | Description |
|--------|-------|-------------|
| POST | `/V1/ucp/order` | Place the order. Requires `X-UCP-Human-Confirmation` header (enforced by policy middleware). Returns order ID, increment ID, grand total, payment method, and status. |
| GET | `/V1/ucp/order/:orderId` | Track an order by increment ID or numeric order ID. Returns status, shipping address, tracking numbers, and ordered items. |

#### Inventory

| Method | Route | Description |
|--------|-------|-------------|
| GET | `/V1/ucp/inventory` | Check stock for one or more SKUs (`sku=SKU1,SKU2`). Returns in-stock flag and quantity for each SKU. |

---

### Service contracts

- `Api/UcpCatalogInterface` — `browse(int $pageSize): array`, `search(string $query): array`
- `Api/UcpCartInterface` — `view(): CartSummaryInterface`, `addItem(string $sku, float $qty): CartSummaryInterface`, `removeItem(int $itemId): CartSummaryInterface`, `clear(): bool`
- `Api/UcpCheckoutInterface` — `setShipping(...)`, `setBilling(...)`, `getShippingMethods()`, `getPaymentMethods()`, `getTotals()`
- `Api/UcpOrderInterface` — `place(string $paymentMethodCode, string $email): array`, `track(string $orderId): array`
- `Api/UcpInventoryInterface` — `query(string $sku): array`

### Data transfer objects

- `Api/Data/CartItemInterface` — item_id, sku, name, qty, price, row_total
- `Api/Data/CartSummaryInterface` — quote_id, items_count, items, subtotal, grand_total, currency
- `Api/Data/OrderResultInterface` — status, message, order_id, increment_id, grand_total, currency, payment_method, order_status, items_count
- `Api/Data/ShippingAddressInterface` — firstname, lastname, street, city, region_code, postcode, country_id, telephone

### Session management

`Model/Cart/SessionManager` maintains a persistent guest cart per agent
session. The agent DID is extracted from the Bearer token's `sub` claim
and used as a cache key (SHA-256 hash, 24-hour TTL). This ensures each
agent identity gets its own isolated cart across multiple API requests.

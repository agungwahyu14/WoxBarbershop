# 🎯 CLASS DIAGRAM SISTEM BARBERSHOP WOX

## Diagram Kelas Lengkap dengan Relasi Database

---

# 📊 **CLASS DIAGRAM UML**

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                          BARBERSHOP WOX CLASS DIAGRAM                       │
└─────────────────────────────────────────────────────────────────────────────┘

┌──────────────────────────┐         ┌──────────────────────────┐
│        <<Model>>         │         │        <<Model>>         │
│         User             │         │        Service           │
├──────────────────────────┤         ├──────────────────────────┤
│ - id: bigint(PK)        │         │ - id: bigint(PK)        │
│ - name: varchar(255)     │         │ - name: varchar(255)     │
│ - email: varchar(255)    │         │ - description: text      │
│ - email_verified_at:     │         │ - price: decimal(10,2)   │
│   timestamp              │         │ - duration: integer      │
│ - password: varchar(255) │         │ - category: varchar(100) │
│ - phone: varchar(20)     │         │ - image: varchar(255)    │
│ - created_at: timestamp  │         │ - is_active: boolean     │
│ - updated_at: timestamp  │         │ - created_at: timestamp  │
├──────────────────────────┤         │ - updated_at: timestamp  │
│ + getName(): string      │         ├──────────────────────────┤
│ + getEmail(): string     │         │ + getPrice(): float      │
│ + hasRole(): boolean     │         │ + getDuration(): int     │
│ + bookings(): Collection │         │ + isActive(): boolean    │
│ + loyaltyPoints(): int   │         │ + bookings(): Collection │
└──────────────────────────┘         └──────────────────────────┘
            │                                    │
            │                                    │
            │ 1                              ∞   │
            └─────────────┐      ┌───────────────┘
                          │      │
                          ▼      ▼
            ┌──────────────────────────────────────┐
            │            <<Model>>                 │
            │            Booking                   │
            ├──────────────────────────────────────┤
            │ - id: bigint(PK)                    │
            │ - user_id: bigint(FK)               │
            │ - service_id: bigint(FK)            │
            │ - booking_date: date                │
            │ - booking_time: time                │
            │ - status: enum                      │
            │   ('pending','confirmed',           │
            │    'in_progress','completed',       │
            │    'cancelled')                     │
            │ - notes: text                       │
            │ - total_price: decimal(10,2)        │
            │ - created_at: timestamp             │
            │ - updated_at: timestamp             │
            ├──────────────────────────────────────┤
            │ + user(): User                      │
            │ + service(): Service                │
            │ + transaction(): Transaction        │
            │ + getStatus(): string               │
            │ + getTotalPrice(): float            │
            │ + isPending(): boolean              │
            │ + isCompleted(): boolean            │
            └──────────────────────────────────────┘
                          │
                          │ 1
                          │
                          │ 1
                          ▼
            ┌──────────────────────────────────────┐
            │            <<Model>>                 │
            │          Transaction                 │
            ├──────────────────────────────────────┤
            │ - id: bigint(PK)                    │
            │ - booking_id: bigint(FK)            │
            │ - transaction_id: varchar(255)      │
            │ - amount: decimal(10,2)             │
            │ - payment_method: enum              │
            │   ('cash','card','e_wallet',        │
            │    'bank_transfer')                 │
            │ - payment_status: enum              │
            │   ('pending','paid','failed',       │
            │    'refunded')                      │
            │ - payment_date: timestamp           │
            │ - midtrans_response: json           │
            │ - created_at: timestamp             │
            │ - updated_at: timestamp             │
            ├──────────────────────────────────────┤
            │ + booking(): Booking                │
            │ + getAmount(): float                │
            │ + getPaymentMethod(): string        │
            │ + getPaymentStatus(): string        │
            │ + isPaid(): boolean                 │
            │ + isFailed(): boolean               │
            └──────────────────────────────────────┘


┌──────────────────────────┐         ┌──────────────────────────┐
│        <<Model>>         │         │        <<Model>>         │
│        Loyalty           │         │       Hairstyle          │
├──────────────────────────┤         ├──────────────────────────┤
│ - id: bigint(PK)        │         │ - id: bigint(PK)        │
│ - user_id: bigint(FK)   │         │ - name: varchar(255)     │
│ - points: integer       │         │ - description: text      │
│ - points_earned: integer│         │ - image: varchar(255)    │
│ - points_used: integer  │         │ - face_shape: json       │
│ - transaction_type: enum│         │ - hair_type: json        │
│   ('earned','used')     │         │ - gender: enum           │
│ - description: text     │         │   ('male','female','unisex')│
│ - created_at: timestamp │         │ - is_active: boolean     │
│ - updated_at: timestamp │         │ - created_at: timestamp  │
├──────────────────────────┤         │ - updated_at: timestamp  │
│ + user(): User          │         ├──────────────────────────┤
│ + getTotalPoints(): int │         │ + getCompatibilityScore()│
│ + addPoints(): void     │         │ + isActive(): boolean    │
│ + usePoints(): boolean  │         │ + hairstyleScores():     │
│ + getHistory()          │         │   Collection             │
└──────────────────────────┘         └──────────────────────────┘
            │                                    │
            │ ∞                              ∞   │
            │                                    │
            ▼ 1                                  │
┌──────────────────────────┐                    │
│        <<Model>>         │                    │
│         User             │                    │
│     (REFERENCED)         │                    │
└──────────────────────────┘                    │
                                                │
                                                │ 1
                                                ▼
                      ┌──────────────────────────────────────┐
                      │            <<Model>>                 │
                      │        HairstyleScore                │
                      ├──────────────────────────────────────┤
                      │ - id: bigint(PK)                    │
                      │ - hairstyle_id: bigint(FK)          │
                      │ - criteria_id: bigint(FK)           │
                      │ - score: decimal(3,2)               │
                      │ - weight: decimal(3,2)              │
                      │ - created_at: timestamp             │
                      │ - updated_at: timestamp             │
                      ├──────────────────────────────────────┤
                      │ + hairstyle(): Hairstyle            │
                      │ + criteria(): Criteria              │
                      │ + getScore(): float                 │
                      │ + getWeight(): float                │
                      │ + calculateWeightedScore(): float   │
                      └──────────────────────────────────────┘
                                    │
                                    │ ∞
                                    │
                                    │ 1
                                    ▼
                      ┌──────────────────────────────────────┐
                      │            <<Model>>                 │
                      │           Criteria                   │
                      ├──────────────────────────────────────┤
                      │ - id: bigint(PK)                    │
                      │ - name: varchar(255)                │
                      │ - type: enum                        │
                      │   ('face_shape','hair_type',        │
                      │    'preference','style')            │
                      │ - description: text                 │
                      │ - weight: decimal(3,2)              │
                      │ - is_active: boolean                │
                      │ - created_at: timestamp             │
                      │ - updated_at: timestamp             │
                      ├──────────────────────────────────────┤
                      │ + hairstyleScores(): Collection     │
                      │ + pairwiseComparisons(): Collection │
                      │ + getWeight(): float                │
                      │ + isActive(): boolean               │
                      └──────────────────────────────────────┘
                                    │
                                    │ ∞
                                    │
                                    │ ∞
                                    ▼
                      ┌──────────────────────────────────────┐
                      │            <<Model>>                 │
                      │      PairwiseComparison              │
                      ├──────────────────────────────────────┤
                      │ - id: bigint(PK)                    │
                      │ - criteria_1_id: bigint(FK)         │
                      │ - criteria_2_id: bigint(FK)         │
                      │ - comparison_value: decimal(3,2)    │
                      │ - consistency_ratio: decimal(4,3)   │
                      │ - created_at: timestamp             │
                      │ - updated_at: timestamp             │
                      ├──────────────────────────────────────┤
                      │ + criteria1(): Criteria             │
                      │ + criteria2(): Criteria             │
                      │ + getComparisonValue(): float       │
                      │ + getConsistencyRatio(): float      │
                      │ + isConsistent(): boolean           │
                      └──────────────────────────────────────┘


┌──────────────────────────┐         ┌──────────────────────────┐
│       <<Laravel>>        │         │       <<Laravel>>        │
│         Role             │         │      Permission          │
│     (Spatie Package)     │         │    (Spatie Package)      │
├──────────────────────────┤         ├──────────────────────────┤
│ - id: bigint(PK)        │         │ - id: bigint(PK)        │
│ - name: varchar(255)     │         │ - name: varchar(255)     │
│ - guard_name: varchar(255)│        │ - guard_name: varchar(255)│
│ - created_at: timestamp  │         │ - created_at: timestamp  │
│ - updated_at: timestamp  │         │ - updated_at: timestamp  │
├──────────────────────────┤         ├──────────────────────────┤
│ + permissions(): Collection│       │ + roles(): Collection    │
│ + users(): Collection    │         │ + users(): Collection    │
│ + givePermissionTo()     │         │ + assignRole()           │
│ + hasPermissionTo()      │         │ + can()                  │
└──────────────────────────┘         └──────────────────────────┘
            │ ∞                                ∞ │
            │                                    │
            │          ∞            ∞            │
            └─────────────┐      ┌───────────────┘
                          │      │
                          ▼      ▼
            ┌──────────────────────────────────────┐
            │         <<Pivot Table>>              │
            │       RoleHasPermissions             │
            ├──────────────────────────────────────┤
            │ - permission_id: bigint(FK)         │
            │ - role_id: bigint(FK)               │
            └──────────────────────────────────────┘

            ┌──────────────────────────────────────┐
            │         <<Pivot Table>>              │
            │        ModelHasRoles                 │
            ├──────────────────────────────────────┤
            │ - role_id: bigint(FK)               │
            │ - model_type: varchar(255)          │
            │ - model_id: bigint(FK)              │
            └──────────────────────────────────────┘

            ┌──────────────────────────────────────┐
            │         <<Pivot Table>>              │
            │      ModelHasPermissions             │
            ├──────────────────────────────────────┤
            │ - permission_id: bigint(FK)         │
            │ - model_type: varchar(255)          │
            │ - model_id: bigint(FK)              │
            └──────────────────────────────────────┘


┌──────────────────────────┐         ┌──────────────────────────┐
│      <<Controller>>      │         │      <<Controller>>      │
│      UserController      │         │    ServiceController     │
├──────────────────────────┤         ├──────────────────────────┤
│ + index(): View         │         │ + index(): View         │
│ + create(): View        │         │ + create(): View        │
│ + store(Request): Response│        │ + store(Request): Response│
│ + show(User): View      │         │ + show(Service): View   │
│ + edit(User): View      │         │ + edit(Service): View   │
│ + update(Request,User)  │         │ + update(Request,Service)│
│ + destroy(User): Response│        │ + destroy(Service): Response│
└──────────────────────────┘         └──────────────────────────┘

┌──────────────────────────┐         ┌──────────────────────────┐
│      <<Controller>>      │         │      <<Controller>>      │
│    BookingController     │         │   TransactionController  │
├──────────────────────────┤         ├──────────────────────────┤
│ + index(): View         │         │ + index(): View         │
│ + create(): View        │         │ + store(Request): Response│
│ + store(Request): Response│        │ + show(Transaction): View│
│ + show(Booking): View   │         │ + updateStatus(): Response│
│ + updateStatus(): Response│        │ + processPayment(): Response│
│ + destroy(Booking): Response│      │ + handleCallback(): Response│
└──────────────────────────┘         └──────────────────────────┘

┌──────────────────────────┐         ┌──────────────────────────┐
│      <<Controller>>      │         │      <<Controller>>      │
│    LoyaltyController     │         │  RecommendationController │
├──────────────────────────┤         ├──────────────────────────┤
│ + index(): View         │         │ + index(): View         │
│ + addPoints(): Response │         │ + getRecommendation():   │
│ + usePoints(): Response │         │   JsonResponse           │
│ + getHistory(): JsonResponse│     │ + calculate(): array    │
│ + export(): Response    │         │ + savePreference(): Response│
└──────────────────────────┘         └──────────────────────────┘

┌──────────────────────────┐         ┌──────────────────────────┐
│     <<Middleware>>       │         │      <<Request>>         │
│    RoleMiddleware        │         │    BookingRequest        │
├──────────────────────────┤         ├──────────────────────────┤
│ + handle(Request,        │         │ + authorize(): boolean  │
│   Closure, roles): mixed │         │ + rules(): array        │
└──────────────────────────┘         │ + messages(): array     │
                                     └──────────────────────────┘

┌──────────────────────────┐         ┌──────────────────────────┐
│      <<Service>>         │         │      <<Export>>          │
│   PaymentService         │         │     BookingsExport       │
├──────────────────────────┤         ├──────────────────────────┤
│ + processPayment(): array│         │ + collection(): Collection│
│ + handleCallback(): void │         │ + headings(): array     │
│ + refundPayment(): array │         │ + map(Booking): array   │
│ + getTransactionStatus() │         └──────────────────────────┘
│   : string               │
└──────────────────────────┘

┌──────────────────────────┐         ┌──────────────────────────┐
│        <<Mail>>          │         │      <<DataTable>>       │
│       MyEmail            │         │    BookingDataTable      │
├──────────────────────────┤         ├──────────────────────────┤
│ + build(): Mailable     │         │ + dataTable(): mixed    │
│ + envelope(): Envelope  │         │ + query(): Builder      │
│ + content(): Content    │         │ + columns(): array      │
│ + attachments(): array  │         │ + filename(): string    │
└──────────────────────────┘         └──────────────────────────┘
```

---

# 🔗 **RELASI ANTAR ENTITAS**

## **1. User Relations**

```
User (1) ←→ (∞) Booking
User (1) ←→ (∞) Loyalty
User (∞) ←→ (∞) Role (via ModelHasRoles)
User (∞) ←→ (∞) Permission (via ModelHasPermissions)
```

## **2. Booking Relations**

```
Booking (∞) ←→ (1) User
Booking (∞) ←→ (1) Service
Booking (1) ←→ (1) Transaction
```

## **3. Service Relations**

```
Service (1) ←→ (∞) Booking
```

## **4. Transaction Relations**

```
Transaction (1) ←→ (1) Booking
```

## **5. Hairstyle System Relations**

```
Hairstyle (1) ←→ (∞) HairstyleScore
HairstyleScore (∞) ←→ (1) Criteria
Criteria (∞) ←→ (∞) PairwiseComparison
```

## **6. Permission System Relations**

```
Role (∞) ←→ (∞) Permission (via RoleHasPermissions)
User (∞) ←→ (∞) Role (via ModelHasRoles)
User (∞) ←→ (∞) Permission (via ModelHasPermissions)
```

---

# 📋 **ENUM VALUES**

## **Booking Status**

```
- pending: Booking sedang menunggu konfirmasi
- confirmed: Booking telah dikonfirmasi
- in_progress: Layanan sedang berlangsung
- completed: Layanan telah selesai
- cancelled: Booking dibatalkan
```

## **Payment Method**

```
- cash: Pembayaran tunai
- card: Pembayaran kartu kredit/debit
- e_wallet: Pembayaran e-wallet (OVO, DANA, dll)
- bank_transfer: Transfer bank
```

## **Payment Status**

```
- pending: Pembayaran menunggu
- paid: Pembayaran berhasil
- failed: Pembayaran gagal
- refunded: Pembayaran dikembalikan
```

## **Loyalty Transaction Type**

```
- earned: Poin yang diperoleh
- used: Poin yang digunakan
```

## **Gender**

```
- male: Pria
- female: Wanita
- unisex: Untuk semua gender
```

## **Criteria Type**

```
- face_shape: Bentuk wajah
- hair_type: Jenis rambut
- preference: Preferensi pengguna
- style: Gaya rambut
```

---

# 🛠 **DESIGN PATTERNS YANG DIGUNAKAN**

## **1. MVC Pattern (Model-View-Controller)**

-   **Model**: Mengelola data dan business logic
-   **View**: Menampilkan interface pengguna
-   **Controller**: Mengatur komunikasi antara Model dan View

## **2. Repository Pattern**

-   Eloquent ORM sebagai abstraksi database
-   Model sebagai repository untuk data access

## **3. Observer Pattern**

-   Event listeners untuk booking status changes
-   Mail notifications untuk booking confirmations

## **4. Strategy Pattern**

-   PaymentService untuk berbagai metode pembayaran
-   RecommendationService untuk algoritma AHP

## **5. Factory Pattern**

-   Model factories untuk testing dan seeding
-   Export factories untuk berbagai format laporan

---

# 💾 **DATABASE CONSTRAINTS & INDEXES**

## **Foreign Key Constraints**

```sql
-- Booking constraints
ALTER TABLE bookings ADD CONSTRAINT fk_bookings_user_id
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;
ALTER TABLE bookings ADD CONSTRAINT fk_bookings_service_id
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE;

-- Transaction constraints
ALTER TABLE transactions ADD CONSTRAINT fk_transactions_booking_id
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE;

-- Loyalty constraints
ALTER TABLE loyalties ADD CONSTRAINT fk_loyalties_user_id
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

-- Hairstyle Score constraints
ALTER TABLE hairstyle_scores ADD CONSTRAINT fk_hairstyle_scores_hairstyle_id
    FOREIGN KEY (hairstyle_id) REFERENCES hairstyles(id) ON DELETE CASCADE;
ALTER TABLE hairstyle_scores ADD CONSTRAINT fk_hairstyle_scores_criteria_id
    FOREIGN KEY (criteria_id) REFERENCES criterias(id) ON DELETE CASCADE;

-- Pairwise Comparison constraints
ALTER TABLE pairwise_comparisons ADD CONSTRAINT fk_pairwise_criteria_1_id
    FOREIGN KEY (criteria_1_id) REFERENCES criterias(id) ON DELETE CASCADE;
ALTER TABLE pairwise_comparisons ADD CONSTRAINT fk_pairwise_criteria_2_id
    FOREIGN KEY (criteria_2_id) REFERENCES criterias(id) ON DELETE CASCADE;
```

## **Database Indexes**

```sql
-- Performance indexes
CREATE INDEX idx_bookings_user_id ON bookings(user_id);
CREATE INDEX idx_bookings_service_id ON bookings(service_id);
CREATE INDEX idx_bookings_date ON bookings(booking_date);
CREATE INDEX idx_bookings_status ON bookings(status);
CREATE INDEX idx_transactions_booking_id ON transactions(booking_id);
CREATE INDEX idx_transactions_status ON transactions(payment_status);
CREATE INDEX idx_loyalties_user_id ON loyalties(user_id);
CREATE INDEX idx_users_email ON users(email);
```

---

**Class Diagram ini menggambarkan seluruh struktur sistem Barbershop WOX dengan relasi yang jelas antar entitas, termasuk business logic, payment system, loyalty program, dan recommendation engine menggunakan AHP (Analytic Hierarchy Process).**

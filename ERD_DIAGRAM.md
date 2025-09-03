# 🗄️ **ENTITY RELATIONSHIP DIAGRAM (ERD)**

## 🏪 **Sistem Barbershop WOX - Database Design**

---

## 📊 **ERD VISUAL DIAGRAM**

```
╔═══════════════════════════════════════════════════════════════════════════════╗
║                      🏪 BARBERSHOP WOX - ERD DIAGRAM                          ║
╚═══════════════════════════════════════════════════════════════════════════════╝

┏━━━━━━━━━━━━━━━━━━━━━━━━━━┓                      ┏━━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃        👥 USERS         ┃                      ┃       🛠️ SERVICES       ┃
┣━━━━━━━━━━━━━━━━━━━━━━━━━━┫                      ┣━━━━━━━━━━━━━━━━━━━━━━━━━━┫
┃ 🔑 id (PK)             ┃                      ┃ 🔑 id (PK)             ┃
┃ 📝 name                ┃                      ┃ 📝 name                ┃
┃ � email (UNIQUE)      ┃                      ┃ 📄 description         ┃
┃ ⏰ email_verified_at   ┃                      ┃ 💰 price               ┃
┃ 🔒 password            ┃                      ┃ ⏳ duration            ┃
┃ 📱 phone               ┃                      ┃ 🏷️ category            ┃
┃ 🔐 remember_token      ┃                      ┃ 🖼️ image               ┃
┃ 📅 created_at          ┃                      ┃ ✅ is_active           ┃
┃ 🔄 updated_at          ┃                      ┃ 📅 created_at          ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━━┛                      ┃ 🔄 updated_at          ┃
            ┃                                   ┗━━━━━━━━━━━━━━━━━━━━━━━━━━┛
            ┃ 1                                           ┃
            ┃                                            ┃ 1
            ┃ 👤 makes                          provides 🛠️ ┃
            ┃                                            ┃
            ┃ ∞                                          ┃ ∞
            ┃                                            ┃
            ┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛
                                      ┃
                                      ▼
┏━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃                               📅 BOOKINGS                                     ┃
┣━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┫
┃ 🔑 id (PK)                                                                   ┃
┃ 🔗 user_id (FK) → USERS.id                                                  ┃
┃ 🔗 service_id (FK) → SERVICES.id                                            ┃
┃ 📅 booking_date                                                              ┃
┃ ⏰ booking_time                                                              ┃
┃ 🔄 status ENUM('pending','confirmed','in_progress','completed','cancelled') ┃
┃ 📝 notes                                                                     ┃
┃ 💰 total_price                                                               ┃
┃ 📅 created_at                                                                ┃
┃ 🔄 updated_at                                                                ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛
            ┃ 1
            ┃
            ┃ 💳 generates payment
            ┃
            ┃ 1
            ▼
┏━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃                             💳 TRANSACTIONS                                   ┃
┣━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┫
┃ 🔑 id (PK)                                                                   ┃
┃ 🔗 booking_id (FK) → BOOKINGS.id                                            ┃
┃ 🆔 transaction_id                                                            ┃
┃ 💰 amount                                                                    ┃
┃ 💳 payment_method ENUM('cash','card','e_wallet','bank_transfer')           ┃
┃ 📊 payment_status ENUM('pending','paid','failed','refunded')               ┃
┃ ⏰ payment_date                                                              ┃
┃ 📄 midtrans_response (JSON)                                                 ┃
┃ 📅 created_at                                                                ┃
┃ 🔄 updated_at                                                                ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛


┏━━━━━━━━━━━━━━━━━━━━━━━━━━┓                      ┏━━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃      👥 USERS           ┃                      ┃      💇‍♂️ HAIRSTYLES      ┃
┃    (REFERENCED)         ┃                      ┣━━━━━━━━━━━━━━━━━━━━━━━━━━┫
┗━━━━━━━━━━━━━━━━━━━━━━━━━━┛                      ┃ 🔑 id (PK)             ┃
            ┃ 1                                  ┃ 📝 name                ┃
            ┃                                    ┃ 📄 description         ┃
            ┃ 🎁 earns/uses points              ┃ 🖼️ image               ┃
            ┃                                    ┃ 👤 face_shape (JSON)   ┃
            ┃ ∞                                  ┃ 💇‍♂️ hair_type (JSON)    ┃
            ┃                                    ┃ 🚻 gender ENUM         ┃
            ▼                                    ┃    ('male','female',   ┃
┏━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┓ ┃     'unisex')          ┃
┃                    🎁 LOYALTY                ┃ ┃ ✅ is_active           ┃
┣━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┫ ┃ � created_at          ┃
┃ 🔑 id (PK)                                  ┃ ┃ 🔄 updated_at          ┃
┃ 🔗 user_id (FK) → USERS.id                 ┃ ┗━━━━━━━━━━━━━━━━━━━━━━━━━━┛
┃ 🎯 points                                   ┃            ┃ 1
┃ ⬆️ points_earned                            ┃            ┃
┃ ⬇️ points_used                              ┃            ┃ 📊 has scores
┃ 🔄 transaction_type ENUM('earned','used')  ┃            ┃
┃ 📝 description                              ┃            ┃ ∞
┃ 📅 created_at                               ┃            ┃
┃ 🔄 updated_at                               ┃            ▼
┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛ ┏━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┓
                                                  ┃         📊 HAIRSTYLE_SCORES         ┃
┏━━━━━━━━━━━━━━━━━━━━━━━━━━┓                        ┣━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┫
┃      📏 CRITERIAS       ┃                        ┃ 🔑 id (PK)                         ┃
┣━━━━━━━━━━━━━━━━━━━━━━━━━━┫                        ┃ 🔗 hairstyle_id (FK)→HAIRSTYLES.id┃
┃ 🔑 id (PK)             ┃                        ┃ 🔗 criteria_id (FK)→CRITERIAS.id  ┃
┃ 📝 name                ┃                        ┃ 📊 score                           ┃
┃ 🏷️ type ENUM:          ┃                        ┃ ⚖️ weight                          ┃
┃    'face_shape'        ┃                        ┃ 📅 created_at                      ┃
┃    'hair_type'         ┃                        ┃ 🔄 updated_at                      ┃
┃    'preference'        ┃                        ┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛
┃    'style'             ┃                                      ┃ ∞
┃ 📄 description         ┃                                      ┃
┃ ⚖️ weight              ┃                                      ┃ 📝 evaluates
┃ ✅ is_active           ┃                                      ┃
┃ 📅 created_at          ┃                                      ┃ 1
┃ 🔄 updated_at          ┃ 1                                    ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━━┛                                      ┃
            ┃                                                   ┃
            ┃ 🔄 compares with                                  ┃
            ┃                                                   ┃
            ┃ ∞                                                 ┃
            ▼                                                   ┃
┏━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃                       🔄 PAIRWISE_COMPARISONS                                ┃
┣━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┫
┃ 🔑 id (PK)                                                                   ┃
┃ 🔗 criteria_1_id (FK) → CRITERIAS.id                                        ┃
┃ 🔗 criteria_2_id (FK) → CRITERIAS.id                                        ┃
┃ ⚖️ comparison_value                                                          ┃
┃ 📊 consistency_ratio                                                         ┃
┃ 📅 created_at                                                                ┃
┃ 🔄 updated_at                                                                ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛


┏━━━━━━━━━━━━━━━━━━━━━━━━━━┓                    ┏━━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃        🔑 ROLES         ┃                    ┃     🛡️ PERMISSIONS      ┃
┃   (Spatie Package)      ┃                    ┃   (Spatie Package)      ┃
┣━━━━━━━━━━━━━━━━━━━━━━━━━━┫                    ┣━━━━━━━━━━━━━━━━━━━━━━━━━━┫
┃ 🔑 id (PK)             ┃                    ┃ 🔑 id (PK)             ┃
┃ 📝 name                ┃                    ┃ 📝 name                ┃
┃ 🛡️ guard_name          ┃                    ┃ 🛡️ guard_name          ┃
┃ 📅 created_at          ┃                    ┃ 📅 created_at          ┃
┃ 🔄 updated_at          ┃                    ┃ 🔄 updated_at          ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━━┛                    ┗━━━━━━━━━━━━━━━━━━━━━━━━━━┛
            ┃ ∞                                          ∞ ┃
            ┃                                              ┃
            ┃              🔗 Many-to-Many                 ┃
            ┃                                              ┃
            ┗━━━━━━━━━━━━━━━┓                    ┏━━━━━━━━━━━━━┛
                            ┃                    ┃
                            ▼                    ▼
            ┏━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┓
            ┃          🔗 ROLE_HAS_PERMISSIONS             ┃
            ┃             (Pivot Table)                    ┃
            ┣━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┫
            ┃ 🔗 permission_id (FK) → PERMISSIONS.id     ┃
            ┃ 🔗 role_id (FK) → ROLES.id                 ┃
            ┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛

┏━━━━━━━━━━━━━━━━━━━━━━━━━━┓                    ┏━━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃      👥 USERS           ┃                    ┃        🔑 ROLES         ┃
┃    (REFERENCED)         ┃                    ┃    (REFERENCED)         ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━━┛                    ┗━━━━━━━━━━━━━━━━━━━━━━━━━━┛
            ┃ ∞                                          ∞ ┃
            ┃                                              ┃
            ┃              🔗 Many-to-Many                 ┃
            ┃                                              ┃
            ┗━━━━━━━━━━━━━━━┓                    ┏━━━━━━━━━━━━━┛
                            ┃                    ┃
                            ▼                    ▼
            ┏━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┓
            ┃            🔗 MODEL_HAS_ROLES                ┃
            ┃             (Pivot Table)                    ┃
            ┣━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┫
            ┃ 🔗 role_id (FK) → ROLES.id                 ┃
            ┃ 🏷️ model_type                               ┃
            ┃ 🔗 model_id (FK) → USERS.id               ┃
            ┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛

┏━━━━━━━━━━━━━━━━━━━━━━━━━━┓                    ┏━━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃      👥 USERS           ┃                    ┃     🛡️ PERMISSIONS      ┃
┃    (REFERENCED)         ┃                    ┃    (REFERENCED)         ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━━┛                    ┗━━━━━━━━━━━━━━━━━━━━━━━━━━┛
            ┃ ∞                                          ∞ ┃
            ┃                                              ┃
            ┃              🔗 Many-to-Many                 ┃
            ┃           (Direct Permission)                ┃
            ┗━━━━━━━━━━━━━━━┓                    ┏━━━━━━━━━━━━━┛
                            ┃                    ┃
                            ▼                    ▼
            ┏━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┓
            ┃         🔗 MODEL_HAS_PERMISSIONS             ┃
            ┃             (Pivot Table)                    ┃
            ┣━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┫
            ┃ 🔗 permission_id (FK) → PERMISSIONS.id     ┃
            ┃ 🏷️ model_type                               ┃
            ┃ 🔗 model_id (FK) → USERS.id               ┃
            ┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛
```

---

---

## 🔍 **DETAIL ENTITAS & ATRIBUT**

### **📊 Tabel Entitas Database**

| No  | 🏷️ **Entitas**           | 📝 **Deskripsi**                            | 🔢 **Total Atribut** |
| :-- | :----------------------- | :------------------------------------------ | :------------------- |
| 1️⃣  | **USERS**                | Pengguna sistem (admin, pegawai, pelanggan) | 9 atribut            |
| 2️⃣  | **SERVICES**             | Layanan barbershop yang tersedia            | 10 atribut           |
| 3️⃣  | **BOOKINGS**             | Reservasi layanan pelanggan                 | 10 atribut           |
| 4️⃣  | **TRANSACTIONS**         | Transaksi pembayaran                        | 10 atribut           |
| 5️⃣  | **LOYALTY**              | Program loyalitas dan poin                  | 8 atribut            |
| 6️⃣  | **HAIRSTYLES**           | Data gaya rambut                            | 9 atribut            |
| 7️⃣  | **CRITERIAS**            | Kriteria penilaian AHP                      | 8 atribut            |
| 8️⃣  | **HAIRSTYLE_SCORES**     | Skor gaya rambut per kriteria               | 6 atribut            |
| 9️⃣  | **PAIRWISE_COMPARISONS** | Perbandingan berpasangan AHP                | 6 atribut            |

---

## **1️⃣ USERS (Pengguna Sistem)**

```sql
CREATE TABLE users (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name               VARCHAR(255) NOT NULL,
    email              VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at  TIMESTAMP NULL,
    password           VARCHAR(255) NOT NULL,
    phone              VARCHAR(20) NULL,
    remember_token     VARCHAR(100) NULL,
    created_at         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at         TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_users_email (email)
);
```

> **📝 Fungsi**: Menyimpan data pengguna sistem (admin, pegawai, pelanggan)

---

## **2️⃣ SERVICES (Layanan Barbershop)**

```sql
CREATE TABLE services (
    id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(255) NOT NULL,
    description   TEXT NULL,
    price         DECIMAL(10,2) NOT NULL,
    duration      INTEGER NOT NULL COMMENT 'dalam menit',
    category      VARCHAR(100) NOT NULL,
    image         VARCHAR(255) NULL,
    is_active     BOOLEAN DEFAULT TRUE,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_services_category (category),
    INDEX idx_services_active (is_active)
);
```

> **📝 Fungsi**: Menyimpan data layanan yang tersedia (haircut, styling, coloring, dll)

---

## **3️⃣ BOOKINGS (Reservasi Layanan)**

```sql
CREATE TABLE bookings (
    id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id       BIGINT UNSIGNED NOT NULL,
    service_id    BIGINT UNSIGNED NOT NULL,
    booking_date  DATE NOT NULL,
    booking_time  TIME NOT NULL,
    status        ENUM('pending','confirmed','in_progress','completed','cancelled') DEFAULT 'pending',
    notes         TEXT NULL,
    total_price   DECIMAL(10,2) NOT NULL,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
    INDEX idx_booking_user (user_id),
    INDEX idx_booking_service (service_id),
    INDEX idx_booking_date (booking_date),
    INDEX idx_booking_status (status)
);
```

> **📝 Fungsi**: Menyimpan data reservasi pelanggan untuk layanan tertentu

---

## **4️⃣ TRANSACTIONS (Transaksi Pembayaran)**

```sql
CREATE TABLE transactions (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_id          BIGINT UNSIGNED NOT NULL,
    transaction_id      VARCHAR(255) UNIQUE NOT NULL,
    amount              DECIMAL(10,2) NOT NULL,
    payment_method      ENUM('cash','card','e_wallet','bank_transfer') NOT NULL,
    payment_status      ENUM('pending','paid','failed','refunded') DEFAULT 'pending',
    payment_date        TIMESTAMP NULL,
    midtrans_response   JSON NULL,
    created_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    UNIQUE KEY unique_transaction_id (transaction_id),
    INDEX idx_transaction_booking (booking_id),
    INDEX idx_transaction_status (payment_status),
    INDEX idx_payment_date (payment_date)
);
```

> **📝 Fungsi**: Menyimpan data transaksi pembayaran untuk setiap booking

---

## **5️⃣ LOYALTY (Program Loyalitas)**

```sql
CREATE TABLE loyalties (
    id                 BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id            BIGINT UNSIGNED NOT NULL,
    points             INTEGER NOT NULL DEFAULT 0,
    points_earned      INTEGER NOT NULL DEFAULT 0,
    points_used        INTEGER NOT NULL DEFAULT 0,
    transaction_type   ENUM('earned','used') NOT NULL,
    description        TEXT NULL,
    created_at         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_loyalty_user (user_id),
    INDEX idx_loyalty_type (transaction_type)
);
```

> **📝 Fungsi**: Menyimpan riwayat poin loyalitas pelanggan

---

## **6️⃣ HAIRSTYLES (Gaya Rambut)**

```sql
CREATE TABLE hairstyles (
    id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(255) NOT NULL,
    description   TEXT NULL,
    image         VARCHAR(255) NULL,
    face_shape    JSON NULL COMMENT 'Array bentuk wajah yang cocok',
    hair_type     JSON NULL COMMENT 'Array jenis rambut yang cocok',
    gender        ENUM('male','female','unisex') DEFAULT 'unisex',
    is_active     BOOLEAN DEFAULT TRUE,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_hairstyle_gender (gender),
    INDEX idx_hairstyle_active (is_active)
);
```

> **📝 Fungsi**: Menyimpan data gaya rambut untuk sistem rekomendasi

---

## **7️⃣ CRITERIAS (Kriteria Penilaian)**

```sql
CREATE TABLE criterias (
    id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(255) NOT NULL,
    type          ENUM('face_shape','hair_type','preference','style') NOT NULL,
    description   TEXT NULL,
    weight        DECIMAL(3,2) NOT NULL DEFAULT 1.00,
    is_active     BOOLEAN DEFAULT TRUE,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_criteria_type (type),
    INDEX idx_criteria_active (is_active)
);
```

> **📝 Fungsi**: Menyimpan kriteria penilaian untuk algoritma AHP

---

## **8️⃣ HAIRSTYLE_SCORES (Skor Gaya Rambut)**

```sql
CREATE TABLE hairstyle_scores (
    id             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    hairstyle_id   BIGINT UNSIGNED NOT NULL,
    criteria_id    BIGINT UNSIGNED NOT NULL,
    score          DECIMAL(3,2) NOT NULL DEFAULT 0.00,
    weight         DECIMAL(3,2) NOT NULL DEFAULT 1.00,
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (hairstyle_id) REFERENCES hairstyles(id) ON DELETE CASCADE,
    FOREIGN KEY (criteria_id) REFERENCES criterias(id) ON DELETE CASCADE,
    UNIQUE KEY unique_hairstyle_criteria (hairstyle_id, criteria_id),
    INDEX idx_hairstyle_scores_hairstyle (hairstyle_id),
    INDEX idx_hairstyle_scores_criteria (criteria_id)
);
```

> **📝 Fungsi**: Menyimpan skor setiap gaya rambut berdasarkan kriteria tertentu

---

## **9️⃣ PAIRWISE_COMPARISONS (Perbandingan Berpasangan)**

```sql
CREATE TABLE pairwise_comparisons (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    criteria_1_id       BIGINT UNSIGNED NOT NULL,
    criteria_2_id       BIGINT UNSIGNED NOT NULL,
    comparison_value    DECIMAL(3,2) NOT NULL,
    consistency_ratio   DECIMAL(4,3) NOT NULL DEFAULT 0.000,
    created_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (criteria_1_id) REFERENCES criterias(id) ON DELETE CASCADE,
    FOREIGN KEY (criteria_2_id) REFERENCES criterias(id) ON DELETE CASCADE,
    UNIQUE KEY unique_criteria_comparison (criteria_1_id, criteria_2_id),
    INDEX idx_pairwise_criteria1 (criteria_1_id),
    INDEX idx_pairwise_criteria2 (criteria_2_id)
);
```

> **📝 Fungsi**: Menyimpan nilai perbandingan berpasangan antar kriteria untuk AHP

---

## 🔗 **PETA RELASI ANTAR ENTITAS**

### **📊 Matrix Relasi Database**

| **Entitas A**     | **Relasi** | **Entitas B**               | **Cardinalitas** | **Tipe Join** |
| :---------------- | :--------- | :-------------------------- | :--------------- | :------------ |
| 👥 **USERS**      | makes      | 📅 **BOOKINGS**             | `1:∞`            | INNER JOIN    |
| 🛠️ **SERVICES**   | provides   | 📅 **BOOKINGS**             | `1:∞`            | INNER JOIN    |
| 📅 **BOOKINGS**   | generates  | 💳 **TRANSACTIONS**         | `1:1`            | INNER JOIN    |
| 👥 **USERS**      | earns/uses | 🎁 **LOYALTY**              | `1:∞`            | LEFT JOIN     |
| 💇‍♂️ **HAIRSTYLES** | has scores | 📊 **HAIRSTYLE_SCORES**     | `1:∞`            | INNER JOIN    |
| 📏 **CRITERIAS**  | evaluates  | 📊 **HAIRSTYLE_SCORES**     | `1:∞`            | INNER JOIN    |
| 📏 **CRITERIAS**  | compares   | 🔄 **PAIRWISE_COMPARISONS** | `∞:∞`            | INNER JOIN    |
| 👥 **USERS**      | assigned   | 🔑 **ROLES**                | `∞:∞`            | LEFT JOIN     |
| 🔑 **ROLES**      | grants     | 🛡️ **PERMISSIONS**          | `∞:∞`            | LEFT JOIN     |

---

## 📖 **PENJELASAN RELASI DALAM BENTUK TEKS**

### **🏪 Skenario Bisnis Barbershop:**

Sistem Barbershop WOX dirancang untuk mengelola operasional bisnis barbershop dengan relasi yang saling terkait. Berikut penjelasan lengkap bagaimana setiap entitas berinteraksi:

---

### **👤 Alur Pelanggan (Customer Journey):**

**1. Registrasi & Login**

-   Pelanggan mendaftar ke sistem dengan membuat akun di tabel **USERS**
-   Sistem memberikan **role "pelanggan"** melalui tabel **MODEL_HAS_ROLES**
-   Data pelanggan disimpan dengan atribut seperti nama, email, dan phone

**2. Memilih Layanan**

-   Pelanggan melihat daftar layanan dari tabel **SERVICES**
-   Setiap layanan memiliki informasi nama, deskripsi, harga, durasi, dan kategori
-   Layanan dapat berupa haircut, styling, coloring, treatment, dll.

**3. Membuat Booking**

-   Pelanggan membuat reservasi yang tersimpan di tabel **BOOKINGS**
-   Sistem mencatat relasi **USERS (1) → BOOKINGS (∞)**: satu pelanggan bisa punya banyak booking
-   Sistem mencatat relasi **SERVICES (1) → BOOKINGS (∞)**: satu layanan bisa dipesan berkali-kali
-   Booking berisi informasi tanggal, waktu, status, catatan, dan total harga

**4. Proses Pembayaran**

-   Setiap booking menghasilkan satu transaksi di tabel **TRANSACTIONS**
-   Relasi **BOOKINGS (1) → TRANSACTIONS (1)**: satu booking = satu transaksi
-   Transaksi mencatat metode pembayaran (cash, card, e-wallet, bank transfer)
-   Status pembayaran dikelola melalui integrasi Midtrans (pending, paid, failed, refunded)

**5. Program Loyalitas**

-   Setelah pembayaran berhasil, pelanggan mendapat poin loyalitas
-   Relasi **USERS (1) → LOYALTY (∞)**: satu user punya banyak riwayat poin
-   Sistem mencatat poin yang diperoleh (earned) dan poin yang digunakan (used)
-   Pelanggan bisa menggunakan poin untuk diskon di booking berikutnya

---

### **💇‍♂️ Sistem Rekomendasi Gaya Rambut (AI Features):**

**1. Data Gaya Rambut**

-   Admin mengelola data gaya rambut di tabel **HAIRSTYLES**
-   Setiap gaya rambut memiliki atribut face_shape, hair_type, gender
-   Data disimpan dalam format JSON untuk fleksibilitas

**2. Kriteria Penilaian**

-   Sistem menggunakan tabel **CRITERIAS** untuk menyimpan kriteria penilaian AHP
-   Kriteria mencakup: face_shape, hair_type, preference, style
-   Setiap kriteria memiliki bobot (weight) untuk kalkulasi

**3. Pemberian Skor**

-   Relasi **HAIRSTYLES (1) → HAIRSTYLE_SCORES (∞)**: satu gaya rambut punya banyak skor
-   Relasi **CRITERIAS (1) → HAIRSTYLE_SCORES (∞)**: satu kriteria evaluasi banyak gaya rambut
-   Setiap kombinasi hairstyle-criteria mendapat skor untuk kalkulasi rekomendasi

**4. Perbandingan Berpasangan AHP**

-   Tabel **PAIRWISE_COMPARISONS** menyimpan perbandingan antar kriteria
-   Relasi **CRITERIAS (∞) → PAIRWISE_COMPARISONS (∞)**: kriteria dibandingkan dengan kriteria lain
-   Sistem menghitung consistency ratio untuk memastikan perbandingan valid
-   Hasil perbandingan digunakan untuk menentukan bobot akhir kriteria

---

### **🔐 Manajemen Hak Akses (Permission System):**

**1. Struktur Role**

-   Sistem memiliki 3 role utama: Admin, Pegawai, Pelanggan
-   Data role disimpan di tabel **ROLES** (dari Spatie Laravel Permission)

**2. Pemberian Permission**

-   Tabel **PERMISSIONS** menyimpan semua hak akses sistem
-   Relasi **ROLES (∞) → PERMISSIONS (∞)**: role dapat memiliki banyak permission
-   Menggunakan pivot table **ROLE_HAS_PERMISSIONS**

**3. Assignment ke User**

-   Relasi **USERS (∞) → ROLES (∞)**: user dapat memiliki banyak role
-   Menggunakan pivot table **MODEL_HAS_ROLES**
-   User juga bisa diberi permission langsung melalui **MODEL_HAS_PERMISSIONS**

**4. Level Akses**

-   **Admin**: Full access ke semua fitur (user management, reports, settings)
-   **Pegawai**: Akses operasional (booking management, transaction processing)
-   **Pelanggan**: Akses terbatas (booking, profile, loyalty points)

---

### **📊 Integrasi Data (Data Integration):**

**1. Booking ke Transaction Flow**

```
USERS → BOOKINGS → TRANSACTIONS → LOYALTY
```

-   User membuat booking
-   Booking menghasilkan transaction
-   Transaction yang berhasil memberikan poin loyalty
-   Data terintegrasi untuk tracking customer journey

**2. Recommendation Engine Flow**

```
CRITERIAS → PAIRWISE_COMPARISONS → HAIRSTYLE_SCORES → HAIRSTYLES
```

-   Kriteria dibandingkan secara berpasangan
-   Hasil perbandingan menentukan bobot kriteria
-   Gaya rambut dinilai berdasarkan kriteria berbobot
-   Sistem memberikan rekomendasi berdasarkan skor tertinggi

**3. Permission Management Flow**

```
USERS → MODEL_HAS_ROLES → ROLES → ROLE_HAS_PERMISSIONS → PERMISSIONS
```

-   User diberikan role
-   Role memiliki set permission tertentu
-   Sistem check permission sebelum mengizinkan akses fitur
-   Mendukung fine-grained access control

---

### **🔄 Business Process Integration:**

**1. Operasional Harian**

-   Pegawai cek booking hari ini dari relasi USERS-BOOKINGS-SERVICES
-   Update status booking dari pending ke confirmed/in_progress/completed
-   Process payment melalui TRANSACTIONS dengan integrasi Midtrans
-   Generate loyalty points berdasarkan nilai transaksi

**2. Customer Experience**

-   Pelanggan login dan lihat history booking
-   Gunakan sistem rekomendasi berdasarkan preferensi
-   Redeem loyalty points untuk discount
-   Track status booking real-time

**3. Admin Management**

-   Monitor semua transaksi dan revenue
-   Kelola user permissions dan roles
-   Analisis performa layanan berdasarkan booking frequency
-   Optimize recommendation system berdasarkan customer feedback

**4. Data Analytics**

-   Join multiple tables untuk comprehensive reporting
-   Track customer behavior patterns
-   Measure service popularity and profitability
-   Optimize business operations based on data insights

---

## 🔢 **PENJELASAN KARDINALITAS RELASI**

### **📋 Relasi One-to-Many (1:∞)**

#### **1️⃣ User → Bookings**

-   **1 User memiliki banyak Booking**
    -   Contoh: User "John Doe" bisa membuat booking pada 15 Agustus, 20 Agustus, dan 25 Agustus
    -   Dalam database: `users.id = 1` memiliki `bookings` dengan `user_id = 1` (multiple records)
-   **1 Booking dimiliki oleh 1 User**
    -   Contoh: Booking tanggal 15 Agustus jam 10:00 hanya milik User "John Doe"
    -   Dalam database: `bookings.id = 1` dengan `user_id = 1` (single record)

#### **2️⃣ Service → Bookings**

-   **1 Service dapat dipesan berkali-kali**
    -   Contoh: Service "Hair Cut" bisa dipesan oleh User A, User B, User C, dll
    -   Dalam database: `services.id = 1` memiliki banyak `bookings` dengan `service_id = 1`
-   **1 Booking hanya untuk 1 Service**
    -   Contoh: Booking tanggal 15 Agustus hanya untuk service "Hair Cut", tidak bisa untuk "Hair Color"
    -   Dalam database: `bookings.id = 1` hanya memiliki `service_id = 1`

#### **3️⃣ User → Loyalty**

-   **1 User memiliki banyak riwayat Loyalty**
    -   Contoh: User "John" punya riwayat: earned 50 poin (15 Aug), used 20 poin (20 Aug), earned 30 poin (25 Aug)
    -   Dalam database: `users.id = 1` memiliki multiple records di `loyalties` dengan `user_id = 1`
-   **1 Record Loyalty milik 1 User**
    -   Contoh: Record "earned 50 poin" hanya milik User "John"
    -   Dalam database: `loyalties.id = 1` dengan `user_id = 1`

#### **4️⃣ Hairstyle → HairstyleScores**

-   **1 Hairstyle memiliki banyak skor kriteria**
    -   Contoh: Hairstyle "Undercut" punya skor untuk kriteria "Face Shape" = 8.5, "Hair Type" = 9.0, "Style" = 8.0
    -   Dalam database: `hairstyles.id = 1` memiliki multiple `hairstyle_scores` dengan `hairstyle_id = 1`
-   **1 Skor hanya untuk 1 Hairstyle**
    -   Contoh: Skor 8.5 untuk kriteria "Face Shape" hanya untuk hairstyle "Undercut"
    -   Dalam database: `hairstyle_scores.id = 1` dengan `hairstyle_id = 1`

#### **5️⃣ Criteria → HairstyleScores**

-   **1 Criteria digunakan untuk menilai banyak Hairstyle**
    -   Contoh: Kriteria "Face Shape" digunakan untuk menilai "Undercut", "Pompadour", "Buzz Cut", dll
    -   Dalam database: `criterias.id = 1` memiliki multiple `hairstyle_scores` dengan `criteria_id = 1`
-   **1 Skor berdasarkan 1 Criteria**
    -   Contoh: Skor 8.5 hanya berdasarkan kriteria "Face Shape", bukan "Hair Type"
    -   Dalam database: `hairstyle_scores.id = 1` dengan `criteria_id = 1`

---

### **📋 Relasi One-to-One (1:1)**

#### **6️⃣ Booking ↔ Transaction**

-   **1 Booking menghasilkan 1 Transaction**
    -   Contoh: Booking "Hair Cut tanggal 15 Agustus" menghasilkan 1 transaksi pembayaran Rp 50.000
    -   Dalam database: `bookings.id = 1` memiliki 1 `transactions` dengan `booking_id = 1`
-   **1 Transaction hanya untuk 1 Booking**
    -   Contoh: Transaksi pembayaran Rp 50.000 hanya untuk booking "Hair Cut tanggal 15 Agustus"
    -   Dalam database: `transactions.id = 1` dengan `booking_id = 1` (unique)

---

### **📋 Relasi Many-to-Many (∞:∞)**

#### **7️⃣ User ↔ Role**

-   **1 User dapat memiliki banyak Role**
    -   Contoh: User "Admin" memiliki role "Admin" dan "Staff" (untuk testing)
    -   Dalam database: `users.id = 1` memiliki multiple records di `model_has_roles` dengan `model_id = 1`
-   **1 Role dapat dimiliki banyak User**
    -   Contoh: Role "Staff" dimiliki oleh User "Pegawai A", "Pegawai B", "Pegawai C"
    -   Dalam database: `roles.id = 2` memiliki multiple records di `model_has_roles` dengan `role_id = 2`

#### **8️⃣ Role ↔ Permission**

-   **1 Role memiliki banyak Permission**
    -   Contoh: Role "Admin" memiliki permission "create-user", "edit-booking", "view-report", dll
    -   Dalam database: `roles.id = 1` memiliki multiple records di `role_has_permissions` dengan `role_id = 1`
-   **1 Permission dapat diberikan ke banyak Role**
    -   Contoh: Permission "view-booking" diberikan ke role "Admin", "Staff", dan "Customer"
    -   Dalam database: `permissions.id = 5` memiliki multiple records di `role_has_permissions` dengan `permission_id = 5`

#### **9️⃣ Criteria ↔ PairwiseComparison (Self-Referencing)**

-   **1 Criteria dapat dibandingkan dengan banyak Criteria lain**
    -   Contoh: Criteria "Face Shape" dibandingkan dengan "Hair Type", "Style", "Preference"
    -   Dalam database: `criterias.id = 1` ada di multiple records sebagai `criteria_1_id` atau `criteria_2_id`
-   **Perbandingan berpasangan untuk AHP**
    -   Contoh: "Face Shape" vs "Hair Type" = 1.5, "Face Shape" vs "Style" = 2.0
    -   Dalam database: `pairwise_comparisons` dengan `criteria_1_id = 1, criteria_2_id = 2`

---

### **🔍 Contoh Praktis dalam Skenario Bisnis:**

#### **Skenario: Pelanggan "John Doe" menggunakan sistem**

1. **User Registration**

    - 1 record di `users`: John Doe (id=1)
    - 1 record di `model_has_roles`: user_id=1, role_id=3 (customer)

2. **Multiple Bookings**

    - John membuat 3 booking:
        - Booking 1: Hair Cut (15 Aug) → `bookings.id=1, user_id=1, service_id=1`
        - Booking 2: Hair Style (20 Aug) → `bookings.id=2, user_id=1, service_id=2`
        - Booking 3: Hair Color (25 Aug) → `bookings.id=3, user_id=1, service_id=3`
    - **Relasi**: 1 User (John) memiliki 3 Bookings

3. **Payment Transactions**

    - 3 transaksi pembayaran:
        - Transaction 1: booking_id=1, amount=50000
        - Transaction 2: booking_id=2, amount=75000
        - Transaction 3: booking_id=3, amount=100000
    - **Relasi**: Setiap 1 Booking menghasilkan 1 Transaction

4. **Loyalty Points**

    - 3 riwayat poin loyalty:
        - Loyalty 1: user_id=1, earned=50, type='earned' (dari booking 1)
        - Loyalty 2: user_id=1, earned=75, type='earned' (dari booking 2)
        - Loyalty 3: user_id=1, used=25, type='used' (discount booking 3)
    - **Relasi**: 1 User (John) memiliki 3 riwayat Loyalty

5. **Hair Recommendation**
    - John mencari rekomendasi gaya rambut:
        - Criteria "Face Shape" (id=1) menilai semua hairstyle
        - Hairstyle "Undercut" (id=1) dinilai oleh semua criteria
        - Multiple hairstyle_scores untuk kombinasi hairstyle-criteria
    - **Relasi**: 1 Criteria menilai banyak Hairstyle, 1 Hairstyle dinilai banyak Criteria

#### **Skenario: Service "Hair Cut" populer**

1. **Multiple Bookings untuk 1 Service**

    - Service "Hair Cut" (id=1) dipesan oleh:
        - User John → booking_id=1
        - User Jane → booking_id=4
        - User Bob → booking_id=7
    - **Relasi**: 1 Service memiliki banyak Bookings dari berbagai User

2. **Revenue Tracking**
    - Admin bisa melihat total revenue dari service "Hair Cut":
        - Transaction dari booking_id=1: Rp 50.000
        - Transaction dari booking_id=4: Rp 50.000
        - Transaction dari booking_id=7: Rp 50.000
        - Total: Rp 150.000
    - **Relasi**: Data terintegrasi melalui SERVICES → BOOKINGS → TRANSACTIONS

---

### **💡 Kesimpulan Kardinalitas:**

| **Relasi**                  | **Contoh Praktis**        | **Implementasi Database**           |
| :-------------------------- | :------------------------ | :---------------------------------- |
| **1:∞ User→Bookings**       | John punya 3 booking      | `user_id` di table bookings         |
| **1:∞ Service→Bookings**    | Hair Cut dipesan 10x      | `service_id` di table bookings      |
| **1:1 Booking→Transaction** | 1 booking = 1 payment     | `booking_id` unique di transactions |
| **1:∞ User→Loyalty**        | John punya 5 riwayat poin | `user_id` di table loyalties        |
| **1:∞ Hairstyle→Scores**    | Undercut punya 4 skor     | `hairstyle_id` di hairstyle_scores  |
| **1:∞ Criteria→Scores**     | Face Shape nilai 20 style | `criteria_id` di hairstyle_scores   |
| **∞:∞ User→Role**           | John = Admin+Staff        | pivot table model_has_roles         |
| **∞:∞ Role→Permission**     | Admin punya 50 permission | pivot table role_has_permissions    |
| **∞:∞ Criteria→Comparison** | Face Shape vs Hair Type   | self-referencing pairwise table     |

---

### **🔗 Primary Relationships**

#### **1️⃣ User ↔ Booking (One-to-Many)**

```sql
-- Relasi: users(id) ←→ bookings(user_id)
-- Kardinalitas: 1:∞
-- Join Type: INNER JOIN
```

-   ✅ Satu user dapat memiliki banyak booking
-   ✅ Setiap booking hanya dimiliki oleh satu user
-   🔧 **Foreign Key**: `bookings.user_id → users.id`

#### **2️⃣ Service ↔ Booking (One-to-Many)**

```sql
-- Relasi: services(id) ←→ bookings(service_id)
-- Kardinalitas: 1:∞
-- Join Type: INNER JOIN
```

-   ✅ Satu service dapat dipesan berkali-kali
-   ✅ Setiap booking hanya untuk satu service
-   🔧 **Foreign Key**: `bookings.service_id → services.id`

#### **3️⃣ Booking ↔ Transaction (One-to-One)**

```sql
-- Relasi: bookings(id) ←→ transactions(booking_id)
-- Kardinalitas: 1:1
-- Join Type: INNER JOIN
```

-   ✅ Satu booking memiliki satu transaksi pembayaran
-   ✅ Satu transaksi hanya untuk satu booking
-   🔧 **Foreign Key**: `transactions.booking_id → bookings.id`

#### **4️⃣ User ↔ Loyalty (One-to-Many)**

```sql
-- Relasi: users(id) ←→ loyalties(user_id)
-- Kardinalitas: 1:∞
-- Join Type: LEFT JOIN
```

-   ✅ Satu user dapat memiliki banyak riwayat poin loyalty
-   ✅ Setiap record loyalty hanya untuk satu user
-   🔧 **Foreign Key**: `loyalties.user_id → users.id`

---

### **🤖 Recommendation System Relationships**

#### **5️⃣ Hairstyle ↔ HairstyleScore (One-to-Many)**

```sql
-- Relasi: hairstyles(id) ←→ hairstyle_scores(hairstyle_id)
-- Kardinalitas: 1:∞
-- Join Type: INNER JOIN
```

-   ✅ Satu hairstyle dapat memiliki banyak skor kriteria
-   ✅ Setiap skor hanya untuk satu hairstyle
-   🔧 **Foreign Key**: `hairstyle_scores.hairstyle_id → hairstyles.id`

#### **6️⃣ Criteria ↔ HairstyleScore (One-to-Many)**

```sql
-- Relasi: criterias(id) ←→ hairstyle_scores(criteria_id)
-- Kardinalitas: 1:∞
-- Join Type: INNER JOIN
```

-   ✅ Satu criteria dapat digunakan untuk banyak hairstyle
-   ✅ Setiap skor berdasarkan satu criteria
-   🔧 **Foreign Key**: `hairstyle_scores.criteria_id → criterias.id`

#### **7️⃣ Criteria ↔ PairwiseComparison (Self-Referencing)**

```sql
-- Relasi: criterias(id) ←→ pairwise_comparisons(criteria_1_id, criteria_2_id)
-- Kardinalitas: ∞:∞
-- Join Type: INNER JOIN
```

-   ✅ Setiap criteria dapat dibandingkan dengan criteria lain
-   ✅ Digunakan untuk menghitung bobot dalam AHP
-   🔧 **Foreign Keys**:
    -   `pairwise_comparisons.criteria_1_id → criterias.id`
    -   `pairwise_comparisons.criteria_2_id → criterias.id`

---

### **🔐 Permission System Relationships**

#### **8️⃣ User ↔ Role (Many-to-Many)**

```sql
-- Relasi: users(id) ←→ model_has_roles(model_id) ←→ roles(id)
-- Kardinalitas: ∞:∞
-- Pivot Table: model_has_roles
```

-   ✅ User dapat memiliki multiple roles
-   ✅ Role dapat dimiliki multiple users

#### **9️⃣ Role ↔ Permission (Many-to-Many)**

```sql
-- Relasi: roles(id) ←→ role_has_permissions(role_id) ←→ permissions(id)
-- Kardinalitas: ∞:∞
-- Pivot Table: role_has_permissions
```

-   ✅ Role dapat memiliki multiple permissions
-   ✅ Permission dapat diberikan ke multiple roles

#### **🔟 User ↔ Permission (Many-to-Many Direct)**

```sql
-- Relasi: users(id) ←→ model_has_permissions(model_id) ←→ permissions(id)
-- Kardinalitas: ∞:∞
-- Pivot Table: model_has_permissions
```

-   ✅ User dapat diberikan permission langsung
-   ✅ Permission dapat diberikan langsung ke multiple users

---

## 📋 **BUSINESS RULES & CONSTRAINTS**

### **🎯 Booking Business Rules**

| 🔢  | 📋 **Rule**            | 📝 **Deskripsi**                                              |
| :-- | :--------------------- | :------------------------------------------------------------ |
| 1️⃣  | **Future Date Only**   | User hanya bisa booking untuk tanggal yang akan datang        |
| 2️⃣  | **No Double Booking**  | Tidak boleh double booking pada waktu yang sama               |
| 3️⃣  | **Status Flow**        | Status: `pending` → `confirmed` → `in_progress` → `completed` |
| 4️⃣  | **Cancel Restriction** | Booking yang dibatalkan tidak bisa diubah statusnya lagi      |

### **💳 Transaction Business Rules**

| 🔢  | 📋 **Rule**               | 📝 **Deskripsi**                                   |
| :-- | :------------------------ | :------------------------------------------------- |
| 1️⃣  | **Mandatory Transaction** | Setiap booking harus memiliki transaksi            |
| 2️⃣  | **Unique Transaction ID** | Transaction_id harus unique untuk setiap transaksi |
| 3️⃣  | **Midtrans Flow**         | Payment status mengikuti flow Midtrans             |
| 4️⃣  | **Refund Rules**          | Refund hanya bisa dilakukan untuk status 'paid'    |

### **🎁 Loyalty Business Rules**

| 🔢  | 📋 **Rule**               | 📝 **Deskripsi**                                            |
| :-- | :------------------------ | :---------------------------------------------------------- |
| 1️⃣  | **Paid Transaction Only** | Poin hanya diperoleh dari transaksi berStatus 'paid'        |
| 2️⃣  | **Sufficient Balance**    | Poin yang digunakan tidak boleh melebihi poin yang dimiliki |
| 3️⃣  | **Transaction History**   | Setiap transaksi poin harus tercatat (earned/used)          |

### **🤖 Recommendation Business Rules**

| 🔢  | 📋 **Rule**         | 📝 **Deskripsi**                                      |
| :-- | :------------------ | :---------------------------------------------------- |
| 1️⃣  | **Score Range**     | Hairstyle score harus antara 0.00 - 10.00             |
| 2️⃣  | **AHP Consistency** | Consistency ratio untuk AHP harus < 0.1 (konsisten)   |
| 3️⃣  | **Weight Sum**      | Weight untuk setiap criteria harus dijumlahkan = 1.00 |

### **🔐 Permission Business Rules**

| 🔢  | 📋 **Rule**           | 📝 **Deskripsi**                                       |
| :-- | :-------------------- | :----------------------------------------------------- |
| 1️⃣  | **Admin Full Access** | Admin memiliki akses penuh ke semua fitur              |
| 2️⃣  | **Staff Operational** | Pegawai hanya akses operasional (booking, transaction) |
| 3️⃣  | **Customer Limited**  | Pelanggan hanya akses booking dan profile              |

---

## 🗃️ **OPTIMIZED QUERY EXAMPLES**

### **1️⃣ Get User Bookings with Service Details**

```sql
SELECT
    b.id,
    u.name AS customer_name,
    s.name AS service_name,
    s.price AS service_price,
    b.booking_date,
    b.booking_time,
    b.status,
    b.total_price,
    t.payment_status
FROM bookings b
INNER JOIN users u ON b.user_id = u.id
INNER JOIN services s ON b.service_id = s.id
LEFT JOIN transactions t ON b.id = t.booking_id
WHERE u.id = ?
    AND b.booking_date >= CURDATE()
ORDER BY b.booking_date ASC, b.booking_time ASC;
```

### **2️⃣ Get Daily Revenue Summary**

```sql
SELECT
    DATE(t.payment_date) AS payment_date,
    COUNT(DISTINCT t.id) AS total_transactions,
    COUNT(DISTINCT b.user_id) AS unique_customers,
    SUM(t.amount) AS total_revenue,
    AVG(t.amount) AS avg_transaction,
    t.payment_method
FROM transactions t
INNER JOIN bookings b ON t.booking_id = b.id
WHERE t.payment_status = 'paid'
    AND t.payment_date BETWEEN ? AND ?
GROUP BY DATE(t.payment_date), t.payment_method
ORDER BY payment_date DESC, total_revenue DESC;
```

### **3️⃣ Get Top Hairstyle Recommendations**

```sql
SELECT
    h.id,
    h.name,
    h.image,
    h.gender,
    ROUND(AVG(hs.score * hs.weight), 2) AS weighted_score,
    COUNT(hs.criteria_id) AS total_criteria
FROM hairstyles h
INNER JOIN hairstyle_scores hs ON h.id = hs.hairstyle_id
INNER JOIN criterias c ON hs.criteria_id = c.id
WHERE h.is_active = TRUE
    AND c.is_active = TRUE
    AND h.gender IN ('unisex', ?)
GROUP BY h.id, h.name, h.image, h.gender
HAVING total_criteria >= 3
ORDER BY weighted_score DESC, h.name ASC
LIMIT 5;
```

### **4️⃣ Get User Loyalty Summary**

```sql
SELECT
    u.id,
    u.name,
    COALESCE(SUM(CASE WHEN l.transaction_type = 'earned' THEN l.points ELSE 0 END), 0) AS total_earned,
    COALESCE(SUM(CASE WHEN l.transaction_type = 'used' THEN l.points ELSE 0 END), 0) AS total_used,
    COALESCE(
        SUM(CASE WHEN l.transaction_type = 'earned' THEN l.points ELSE 0 END) -
        SUM(CASE WHEN l.transaction_type = 'used' THEN l.points ELSE 0 END), 0
    ) AS current_balance,
    COUNT(DISTINCT b.id) AS total_bookings,
    COUNT(DISTINCT CASE WHEN t.payment_status = 'paid' THEN t.id END) AS paid_transactions
FROM users u
LEFT JOIN loyalties l ON u.id = l.user_id
LEFT JOIN bookings b ON u.id = b.user_id
LEFT JOIN transactions t ON b.id = t.booking_id
WHERE u.id = ?
GROUP BY u.id, u.name;
```

### **5️⃣ Get AHP Consistency Check**

```sql
SELECT
    c1.name AS criteria_1,
    c2.name AS criteria_2,
    pc.comparison_value,
    pc.consistency_ratio,
    CASE
        WHEN pc.consistency_ratio < 0.1 THEN '✅ Konsisten'
        ELSE '❌ Tidak Konsisten'
    END AS consistency_status
FROM pairwise_comparisons pc
INNER JOIN criterias c1 ON pc.criteria_1_id = c1.id
INNER JOIN criterias c2 ON pc.criteria_2_id = c2.id
WHERE c1.is_active = TRUE
    AND c2.is_active = TRUE
ORDER BY pc.consistency_ratio ASC;
```

---

## 📈 **DATABASE PERFORMANCE OPTIMIZATION**

### **🚀 Critical Indexes**

```sql
-- Performance Critical Indexes
CREATE INDEX idx_bookings_date_status ON bookings(booking_date, status);
CREATE INDEX idx_transactions_payment_date_status ON transactions(payment_date, payment_status);
CREATE INDEX idx_loyalties_user_type ON loyalties(user_id, transaction_type);
CREATE INDEX idx_hairstyle_scores_composite ON hairstyle_scores(hairstyle_id, criteria_id, score);
```

### **🔧 Composite Indexes**

```sql
-- Multi-column Indexes for Complex Queries
CREATE INDEX idx_bookings_user_date_status ON bookings(user_id, booking_date, status);
CREATE INDEX idx_transactions_booking_status_date ON transactions(booking_id, payment_status, payment_date);
```

---

**🎯 ERD ini menggambarkan seluruh struktur database sistem Barbershop WOX dengan detail lengkap, optimasi performa, dan panduan implementasi yang komprehensif untuk mendukung semua fitur aplikasi secara efisien.**
WHERE h.is_active = true
AND c.is_active = true
AND h.gender IN ('unisex', ?)
GROUP BY h.id, h.name, h.image
ORDER BY weighted_score DESC
LIMIT 5;

````

## **4. Get User Loyalty Points**

```sql
SELECT
    u.id,
    u.name,
    SUM(CASE WHEN l.transaction_type = 'earned' THEN l.points ELSE 0 END) as total_earned,
    SUM(CASE WHEN l.transaction_type = 'used' THEN l.points ELSE 0 END) as total_used,
    (
        SUM(CASE WHEN l.transaction_type = 'earned' THEN l.points ELSE 0 END) -
        SUM(CASE WHEN l.transaction_type = 'used' THEN l.points ELSE 0 END)
    ) as current_balance
FROM users u
LEFT JOIN loyalties l ON u.id = l.user_id
WHERE u.id = ?
GROUP BY u.id, u.name;
````

---

**ERD ini menggambarkan seluruh struktur database sistem Barbershop WOX dengan detail atribut, tipe data, relasi, constraints, dan business rules yang komprehensif untuk mendukung semua fitur aplikasi.**

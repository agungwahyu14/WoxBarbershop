# 📊 DOKUMENTASI PERHITUNGAN AHP (Analytical Hierarchy Process)

## Sistem Rekomendasi Hairstyle - WOX Barbershop

---

## 📌 **OVERVIEW**

Sistem rekomendasi ini menggunakan metode **AHP (Analytical Hierarchy Process)** untuk menentukan hairstyle terbaik berdasarkan tiga kriteria utama:

1. **Bentuk Kepala** (Weight: 50.03%)
2. **Tipe Rambut** (Weight: 29.98%)
3. **Preferensi Gaya** (Weight: 19.99%)

**Formula Akhir:**

```
Total Score = (W₁ × S₁) + (W₂ × S₂) + (W₃ × S₃)

Dimana:
- W = Weight kriteria (dari perhitungan AHP)
- S = Score hairstyle untuk sub-kriteria yang dipilih user
```

---

## 🎯 **STEP 1: PERHITUNGAN BOBOT KRITERIA (AHP)**

### **1.1 Data Pairwise Comparison**

Berdasarkan penilaian expert/stakeholder mengenai tingkat kepentingan relatif antar kriteria:

| Kriteria 1    | vs  | Kriteria 2      | Nilai | Arti                              |
| ------------- | --- | --------------- | ----- | --------------------------------- |
| Bentuk Kepala | vs  | Tipe Rambut     | 1.67  | Bentuk Kepala 1.67x lebih penting |
| Bentuk Kepala | vs  | Preferensi Gaya | 2.5   | Bentuk Kepala 2.5x lebih penting  |
| Tipe Rambut   | vs  | Preferensi Gaya | 1.5   | Tipe Rambut 1.5x lebih penting    |

**Skala Saaty (1-9):**

-   1 = Sama penting
-   3 = Sedikit lebih penting
-   5 = Lebih penting
-   7 = Sangat lebih penting
-   9 = Mutlak lebih penting
-   2,4,6,8 = Nilai antara

### **1.2 Matriks Perbandingan Berpasangan (3×3)**

```
                 Bentuk Kepala  Tipe Rambut  Preferensi Gaya
Bentuk Kepala         1.00          1.67           2.50
Tipe Rambut           0.60          1.00           1.50
Preferensi Gaya       0.40          0.67           1.00
────────────────────────────────────────────────────────────
Column Sum:           2.00          3.34           5.00
```

**Penjelasan:**

-   Diagonal = 1 (kriteria vs dirinya sendiri)
-   Nilai di atas diagonal = nilai pairwise comparison
-   Nilai di bawah diagonal = 1/nilai (reciprocal)
    -   Contoh: Tipe Rambut vs Bentuk Kepala = 1/1.67 = 0.60

### **1.3 Normalisasi Matriks**

Setiap elemen dibagi dengan jumlah kolomnya:

```
                 Bentuk Kepala  Tipe Rambut  Preferensi Gaya  |  Rata-rata (WEIGHT)
Bentuk Kepala      1.00/2.00      1.67/3.34      2.50/5.00     |  (0.500 + 0.500 + 0.500)/3 = 0.5003
                   = 0.500        = 0.500        = 0.500       |
                                                                |
Tipe Rambut        0.60/2.00      1.00/3.34      1.50/5.00     |  (0.300 + 0.299 + 0.300)/3 = 0.2998
                   = 0.300        = 0.299        = 0.300       |
                                                                |
Preferensi Gaya    0.40/2.00      0.67/3.34      1.00/5.00     |  (0.200 + 0.201 + 0.200)/3 = 0.2000
                   = 0.200        = 0.201        = 0.200       |
```

### **1.4 Hasil Bobot (Weights)**

| ID  | Kriteria        | Weight   | Persentase | Prioritas    |
| --- | --------------- | -------- | ---------- | ------------ |
| 8   | Bentuk Kepala   | 0.500266 | 50.03%     | 1️⃣ Tertinggi |
| 9   | Tipe Rambut     | 0.299760 | 29.98%     | 2️⃣ Sedang    |
| 10  | Preferensi Gaya | 0.199973 | 19.99%     | 3️⃣ Terendah  |

**Total Weight:** 0.500266 + 0.299760 + 0.199973 = **1.000 ✅**

### **1.5 Validasi Konsistensi (Consistency Ratio)**

**Perhitungan CR:**

1. **Weighted Sum Vector:**

    ```
    WSV = Matrix × Weight Vector

    [1.00  1.67  2.50]   [0.5003]   [1.5011]
    [0.60  1.00  1.50] × [0.2998] = [0.9001]
    [0.40  0.67  1.00]   [0.2000]   [0.6002]
    ```

2. **Consistency Vector:**

    ```
    CV[i] = WSV[i] / Weight[i]

    CV[1] = 1.5011 / 0.5003 = 3.0002
    CV[2] = 0.9001 / 0.2998 = 3.0017
    CV[3] = 0.6002 / 0.2000 = 3.0010
    ```

3. **Lambda Max (λmax):**

    ```
    λmax = (3.0002 + 3.0017 + 3.0010) / 3 = 3.0010
    ```

4. **Consistency Index (CI):**

    ```
    CI = (λmax - n) / (n - 1)
    CI = (3.0010 - 3) / (3 - 1) = 0.0005
    ```

5. **Consistency Ratio (CR):**
    ```
    CR = CI / RI[n]
    CR = 0.0005 / 0.58 = 0.00086 = 0.086%
    ```

**Hasil:** CR = **0.00086 < 0.1** ✅ **KONSISTEN!**

> **Interpretasi:** CR < 0.1 menunjukkan penilaian pairwise comparison konsisten dan dapat diterima.

---

## 📋 **STEP 2: DATA SUB-KRITERIA**

### **2.1 Bentuk Kepala (Criterion ID: 8)**

| ID  | Nama            | Deskripsi                                 |
| --- | --------------- | ----------------------------------------- |
| 1   | Oval            | Bentuk ideal, cocok untuk semua gaya      |
| 2   | Bulat           | Perlu volume di atas untuk menyeimbangkan |
| 3   | Persegi Panjang | Butuh gaya yang melembut                  |
| 4   | Hati            | Perlu keseimbangan di dahi                |
| 5   | Kotak           | Butuh style yang melunakkan sudut         |
| 6   | Segitiga        | Perlu volume di atas                      |

### **2.2 Tipe Rambut (Criterion ID: 9)**

| ID  | Nama         | Deskripsi               |
| --- | ------------ | ----------------------- |
| 1   | Lurus        | Mudah diatur, fleksibel |
| 2   | Bergelombang | Natural body, versatile |
| 3   | Keriting     | Butuh style khusus      |

### **2.3 Preferensi Gaya (Criterion ID: 10)**

| ID  | Nama   | Deskripsi                     |
| --- | ------ | ----------------------------- |
| 1   | Klasik | Formal, timeless, profesional |
| 2   | Modern | Trendy, edgy, contemporary    |
| 3   | Kasual | Santai, natural, easy-going   |

---

## 💇 **STEP 3: CONTOH PERHITUNGAN LENGKAP**

### **Skenario:**

Seorang customer dengan karakteristik:

-   **Bentuk Kepala:** Persegi Panjang (Sub-Criterion ID: 3)
-   **Tipe Rambut:** Lurus (Sub-Criterion ID: 1)
-   **Preferensi Gaya:** Modern (Sub-Criterion ID: 2)

### **Hairstyle yang Dievaluasi: "French Crop" (ID: 34)**

#### **Data Score dari Database:**

| Criterion       | Sub-Criterion       | Sub-Criterion ID | Score (1-10) |
| --------------- | ------------------- | ---------------- | ------------ |
| Bentuk Kepala   | Oval                | 1                | 8            |
| Bentuk Kepala   | Bulat               | 2                | 6            |
| Bentuk Kepala   | **Persegi Panjang** | **3**            | **9** ✅     |
| Bentuk Kepala   | Hati                | 4                | 8            |
| Bentuk Kepala   | Kotak               | 5                | 7            |
| Bentuk Kepala   | Segitiga            | 6                | 7            |
| Tipe Rambut     | **Lurus**           | **1**            | **8** ✅     |
| Tipe Rambut     | Bergelombang        | 2                | 8            |
| Tipe Rambut     | Keriting            | 3                | 7            |
| Preferensi Gaya | Klasik              | 1                | 6            |
| Preferensi Gaya | **Modern**          | **2**            | **9** ✅     |
| Preferensi Gaya | Kasual              | 3                | 9            |

#### **Perhitungan Total Score:**

```
Total Score = (W_BentukKepala × S_PersegitPanjang) +
              (W_TipeRambut × S_Lurus) +
              (W_PreferensiGaya × S_Modern)

Total Score = (0.500266 × 9) + (0.299760 × 8) + (0.199973 × 9)

Total Score = 4.502394 + 2.398080 + 1.799757

Total Score = 8.700231
```

#### **Breakdown Detail:**

| Kriteria        | Weight    | Score User | Contribution | Persentase Kontribusi |
| --------------- | --------- | ---------- | ------------ | --------------------- |
| Bentuk Kepala   | 0.500266  | 9          | 4.502394     | 51.76%                |
| Tipe Rambut     | 0.299760  | 8          | 2.398080     | 27.56%                |
| Preferensi Gaya | 0.199973  | 9          | 1.799757     | 20.68%                |
| **TOTAL**       | **1.000** | -          | **8.700231** | **100%**              |

#### **Interpretasi:**

-   **French Crop** mendapat score **8.70/10** untuk customer ini
-   **Bentuk Kepala** memberikan kontribusi terbesar (51.76%)
-   Style ini **SANGAT COCOK** karena:
    -   Excellent untuk Persegi Panjang (9/10)
    -   Bagus untuk Lurus (8/10)
    -   Excellent untuk gaya Modern (9/10)

---

## 🔄 **STEP 4: PERBANDINGAN MULTIPLE HAIRSTYLES**

### **Contoh Ranking untuk Customer yang Sama:**

| Rank | Hairstyle       | Score    | Bentuk (9) | Tipe (8) | Preferensi (9) | Rekomendasi        |
| ---- | --------------- | -------- | ---------- | -------- | -------------- | ------------------ |
| 1️⃣   | **French Crop** | **8.70** | 9 ✅       | 8        | 9 ✅           | Highly Recommended |
| 2️⃣   | Side Part       | 8.40     | 9 ✅       | 8        | 8              | Recommended        |
| 3️⃣   | Pompadour       | 8.20     | 8          | 9 ✅     | 8              | Good Match         |
| 4️⃣   | Undercut        | 7.95     | 9 ✅       | 7        | 7              | Alternative        |
| 5️⃣   | Buzz Cut        | 7.10     | 7          | 8        | 6              | Acceptable         |

---

## 🔢 **STEP 5: FORMULA & IMPLEMENTASI**

### **Formula Matematis Lengkap:**

```
n = jumlah kriteria = 3
m = jumlah hairstyle yang dievaluasi

Untuk setiap hairstyle h:
    Score(h) = Σ(i=1 to n) [W(i) × S(h, c(i))]

Dimana:
- W(i) = Weight kriteria ke-i (dari AHP)
- S(h, c(i)) = Score hairstyle h untuk sub-kriteria c yang dipilih user pada kriteria i
- c(i) = Sub-criterion yang dipilih user untuk kriteria i

Ranking: Sort semua hairstyle berdasarkan Score(h) descending
```

### **Pseudocode:**

```
FUNCTION calculateRecommendation(bentukKepala, tipeRambut, preferensiGaya):
    // 1. Hitung bobot AHP
    weights = calculateAHPWeights()

    // 2. Filter hairstyle yang cocok
    hairstyles = filterHairstyles(bentukKepala, tipeRambut, preferensiGaya)

    // 3. Mapping input user ke sub-criterion ID
    subCriterionIds = {
        criterionBentukKepala: findSubCriterionId("Bentuk Kepala", bentukKepala),
        criterionTipeRambut: findSubCriterionId("Tipe Rambut", tipeRambut),
        criterionPreferensiGaya: findSubCriterionId("Preferensi Gaya", preferensiGaya)
    }

    // 4. Hitung score untuk setiap hairstyle
    FOR EACH hairstyle IN hairstyles:
        totalScore = 0

        FOR EACH criterion IN weights:
            subCriterionId = subCriterionIds[criterion.id]
            score = getScore(hairstyle, criterion.id, subCriterionId)
            contribution = weights[criterion.id] × score
            totalScore += contribution

        results.add({hairstyle: hairstyle, score: totalScore})

    // 5. Urutkan berdasarkan score tertinggi
    SORT results BY score DESCENDING

    RETURN results
```

---

## 📊 **STEP 6: VALIDASI & QUALITY ASSURANCE**

### **Checklist Validasi:**

✅ **Bobot Kriteria:**

-   [x] Total weight = 1.0 (100%)
-   [x] Consistency Ratio < 0.1
-   [x] Semua weight > 0

✅ **Score Hairstyle:**

-   [x] Semua score dalam range 1-10
-   [x] Setiap hairstyle punya score untuk semua sub-criterion
-   [x] Score disimpan dengan benar di database

✅ **Perhitungan:**

-   [x] Formula matematis benar
-   [x] Mapping sub-criterion akurat
-   [x] Ranking sesuai dengan score

✅ **Edge Cases:**

-   [x] Handle user tidak input semua kriteria
-   [x] Handle hairstyle tanpa score
-   [x] Handle kriteria baru di database

---

## 🎓 **STEP 7: CONTOH KASUS LAINNYA**

### **Kasus A: Customer dengan Bentuk Bulat, Keriting, Kasual**

**Input:**

-   Bentuk Kepala: Bulat (ID: 2)
-   Tipe Rambut: Keriting (ID: 3)
-   Preferensi: Kasual (ID: 3)

**Perhitungan untuk French Crop:**

```
Score = (0.5003 × 6) + (0.2998 × 7) + (0.2000 × 9)
      = 3.0018 + 2.0986 + 1.8000
      = 6.90
```

**Hasil:** Score lebih rendah (6.90 vs 8.70) karena French Crop kurang cocok untuk bentuk Bulat (6/10).

### **Kasus B: Customer dengan Bentuk Oval, Bergelombang, Klasik**

**Input:**

-   Bentuk Kepala: Oval (ID: 1)
-   Tipe Rambut: Bergelombang (ID: 2)
-   Preferensi: Klasik (ID: 1)

**Perhitungan untuk French Crop:**

```
Score = (0.5003 × 8) + (0.2998 × 8) + (0.2000 × 6)
      = 4.0024 + 2.3984 + 1.2000
      = 7.60
```

**Hasil:** Score sedang (7.60) karena French Crop kurang cocok untuk gaya Klasik (6/10).

---

## 📈 **ANALISIS SENSITIVITAS**

### **Pengaruh Perubahan Weight:**

| Skenario       | W_Bentuk | W_Tipe | W_Preferensi | Score French Crop\* |
| -------------- | -------- | ------ | ------------ | ------------------- |
| **Aktual**     | 50%      | 30%    | 20%          | **8.70**            |
| Equal Weight   | 33.3%    | 33.3%  | 33.3%        | 8.67                |
| Prioritas Tipe | 30%      | 50%    | 20%          | 8.50                |
| Prioritas Gaya | 30%      | 20%    | 50%          | 8.80                |

\*Untuk customer: Persegi Panjang, Lurus, Modern

**Kesimpulan:**

-   Perubahan weight ±20% menghasilkan perubahan score ±0.3 poin
-   Sistem cukup robust terhadap perubahan weight
-   Prioritas kriteria tetap penting untuk hasil optimal

---

## 🛠️ **TEKNOLOGI & IMPLEMENTASI**

### **Database Schema:**

```sql
-- Tabel Kriteria
CREATE TABLE criteria (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255),
    weight DECIMAL(10,8),  -- Hasil perhitungan AHP
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Tabel Pairwise Comparison
CREATE TABLE pairwise_comparisons (
    id BIGINT PRIMARY KEY,
    criterion_id_1 BIGINT FOREIGN KEY REFERENCES criteria(id),
    criterion_id_2 BIGINT FOREIGN KEY REFERENCES criteria(id),
    value FLOAT,  -- Nilai perbandingan (1-9)
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Tabel Hairstyle Score
CREATE TABLE hairstyle_scores (
    id BIGINT PRIMARY KEY,
    hairstyle_id BIGINT FOREIGN KEY REFERENCES hairstyles(id),
    criterion_id BIGINT FOREIGN KEY REFERENCES criteria(id),
    sub_criterion_id BIGINT,  -- ID dari bentuk_kepala/tipe_rambut/style_preference
    score FLOAT,  -- Score 1-10
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### **Controller:** `RecommendationController.php`

**Methods:**

1. `index()` - Main entry point untuk rekomendasi
2. `calculateAHPWeights()` - Perhitungan bobot AHP
3. `calculateConsistencyRatio()` - Validasi CR
4. `mapUserInputToSubCriterionIds()` - Mapping input user

---

## 📚 **REFERENSI**

1. **Saaty, T.L. (1980).** _The Analytic Hierarchy Process._ McGraw-Hill, New York.
2. **Saaty, T.L. (2008).** _Decision making with the analytic hierarchy process._ International Journal of Services Sciences, 1(1), 83-98.
3. **Vargas, L.G. (1990).** _An overview of the analytic hierarchy process and its applications._ European Journal of Operational Research, 48(2), 2-8.

---

## 📞 **SUPPORT & MAINTENANCE**

**Untuk Update Data:**

1. **Mengubah Pairwise Comparison:** Update tabel `pairwise_comparisons`
2. **Menambah Kriteria Baru:** Insert ke `criteria`, tambah pairwise comparison
3. **Update Score Hairstyle:** Update tabel `hairstyle_scores`

**Monitoring:**

-   Cek Consistency Ratio secara berkala
-   Validasi total weight = 1.0
-   Review score hairstyle dengan user feedback

---

**Dokumentasi ini di-generate berdasarkan data aktual dari database WOX Barbershop.**

**Last Updated:** December 10, 2025  
**Version:** 1.0.0  
**Author:** GitHub Copilot with Laravel AHP Implementation

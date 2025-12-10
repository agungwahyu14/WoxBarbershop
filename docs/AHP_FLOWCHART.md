# 📊 AHP Recommendation System - Visual Flowchart

## 🔄 **FLOW DIAGRAM LENGKAP**

```
┌─────────────────────────────────────────────────────────────────────────┐
│                    SISTEM REKOMENDASI HAIRSTYLE AHP                     │
└─────────────────────────────────────────────────────────────────────────┘

                                    ↓
┌─────────────────────────────────────────────────────────────────────────┐
│  STEP 1: USER INPUT                                                     │
├─────────────────────────────────────────────────────────────────────────┤
│  • Bentuk Kepala: "Persegi Panjang"                                    │
│  • Tipe Rambut: "Lurus"                                                 │
│  • Preferensi Gaya: "Modern"                                            │
└─────────────────────────────────────────────────────────────────────────┘
                                    ↓
┌─────────────────────────────────────────────────────────────────────────┐
│  STEP 2: PERHITUNGAN BOBOT AHP                                          │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  2a. Pairwise Comparison Matrix (3×3)                                   │
│  ┌───────────────────────────────────────────────────┐                  │
│  │           │  Bentuk  │  Tipe   │  Preferensi  │                     │
│  ├───────────────────────────────────────────────────┤                  │
│  │  Bentuk   │   1.00   │  1.67   │     2.50     │                     │
│  │  Tipe     │   0.60   │  1.00   │     1.50     │                     │
│  │  Pref     │   0.40   │  0.67   │     1.00     │                     │
│  └───────────────────────────────────────────────────┘                  │
│                                                                         │
│  2b. Normalisasi & Hitung Rata-rata                                     │
│  ┌──────────────────────────────────────────┐                           │
│  │  Kriteria        │  Weight  │  Priority  │                           │
│  ├──────────────────────────────────────────┤                           │
│  │  Bentuk Kepala   │  0.5003  │    🥇      │                           │
│  │  Tipe Rambut     │  0.2998  │    🥈      │                           │
│  │  Preferensi Gaya │  0.2000  │    🥉      │                           │
│  └──────────────────────────────────────────┘                           │
│                                                                         │
│  2c. Consistency Ratio                                                  │
│  CR = 0.00086 < 0.1 ✅ KONSISTEN                                        │
└─────────────────────────────────────────────────────────────────────────┘
                                    ↓
┌─────────────────────────────────────────────────────────────────────────┐
│  STEP 3: MAPPING SUB-CRITERION                                          │
├─────────────────────────────────────────────────────────────────────────┤
│  User Input → Database ID                                               │
│  • "Persegi Panjang" → Criterion 8, Sub-Criterion 3                    │
│  • "Lurus" → Criterion 9, Sub-Criterion 1                               │
│  • "Modern" → Criterion 10, Sub-Criterion 2                             │
└─────────────────────────────────────────────────────────────────────────┘
                                    ↓
┌─────────────────────────────────────────────────────────────────────────┐
│  STEP 4: FILTER HAIRSTYLES                                              │
├─────────────────────────────────────────────────────────────────────────┤
│  Query Database:                                                        │
│  SELECT * FROM hairstyles                                               │
│  WHERE bentuk_kepala CONTAINS "Persegi Panjang"                         │
│    AND tipe_rambut CONTAINS "Lurus"                                     │
│    AND style_preference CONTAINS "Modern"                               │
│                                                                         │
│  Result: 25 hairstyles yang cocok                                       │
└─────────────────────────────────────────────────────────────────────────┘
                                    ↓
┌─────────────────────────────────────────────────────────────────────────┐
│  STEP 5: CALCULATE SCORES                                               │
├─────────────────────────────────────────────────────────────────────────┤
│  Untuk setiap hairstyle:                                                │
│                                                                         │
│  Contoh: "French Crop" (ID: 34)                                         │
│  ┌──────────────────────────────────────────────────────────┐           │
│  │  Kriteria         │ Weight │ Score │ Contribution        │           │
│  ├──────────────────────────────────────────────────────────┤           │
│  │  Bentuk Kepala    │ 0.5003 │   9   │ 0.5003×9 = 4.5024  │           │
│  │  Tipe Rambut      │ 0.2998 │   8   │ 0.2998×8 = 2.3980  │           │
│  │  Preferensi Gaya  │ 0.2000 │   9   │ 0.2000×9 = 1.8000  │           │
│  ├──────────────────────────────────────────────────────────┤           │
│  │  TOTAL SCORE                        │      8.70          │           │
│  └──────────────────────────────────────────────────────────┘           │
│                                                                         │
│  Formula:                                                               │
│  Total = Σ(Weight × Score_UserSubCriterion)                             │
└─────────────────────────────────────────────────────────────────────────┘
                                    ↓
┌─────────────────────────────────────────────────────────────────────────┐
│  STEP 6: RANKING & OUTPUT                                               │
├─────────────────────────────────────────────────────────────────────────┤
│  Sort by Total Score (Descending)                                       │
│                                                                         │
│  ┌────────────────────────────────────────────────────────┐             │
│  │ Rank │ Hairstyle      │ Score │ Recommendation        │             │
│  ├────────────────────────────────────────────────────────┤             │
│  │  1️⃣  │ French Crop    │ 8.70  │ ⭐⭐⭐ Highly Rec.   │             │
│  │  2️⃣  │ Side Part      │ 8.40  │ ⭐⭐⭐ Recommended   │             │
│  │  3️⃣  │ Pompadour      │ 8.20  │ ⭐⭐ Good Match     │             │
│  │  4️⃣  │ Undercut       │ 7.95  │ ⭐⭐ Alternative    │             │
│  │  5️⃣  │ Buzz Cut       │ 7.10  │ ⭐ Acceptable      │             │
│  └────────────────────────────────────────────────────────┘             │
└─────────────────────────────────────────────────────────────────────────┘
                                    ↓
┌─────────────────────────────────────────────────────────────────────────┐
│  OUTPUT: DISPLAY TO USER                                                │
├─────────────────────────────────────────────────────────────────────────┤
│  • Tampilkan top 5 recommendations                                      │
│  • Sertakan gambar, deskripsi, dan alasan                               │
│  • User dapat booking langsung                                          │
└─────────────────────────────────────────────────────────────────────────┘
```

---

## 📐 **DETAIL PERHITUNGAN MATEMATIS**

### **1. Matriks Perbandingan Berpasangan**

```
Pairwise Comparison Input:
┌──────────────────────────────────────────┐
│ Bentuk Kepala vs Tipe Rambut = 1.67     │
│ Bentuk Kepala vs Preferensi  = 2.5      │
│ Tipe Rambut vs Preferensi    = 1.5      │
└──────────────────────────────────────────┘

Matrix Construction:
        C8     C9     C10
    ┌─────────────────────┐
C8  │ 1.00   1.67   2.50  │
C9  │ 0.60   1.00   1.50  │
C10 │ 0.40   0.67   1.00  │
    └─────────────────────┘
     2.00   3.34   5.00   ← Column Sum
```

### **2. Normalisasi Matriks**

```
Normalize (divide by column sum):

        C8          C9          C10        Average
    ┌────────────────────────────────────────────────┐
C8  │ 1.00/2.00   1.67/3.34   2.50/5.00   0.500266 │
    │ = 0.500     = 0.500     = 0.500              │
C9  │ 0.60/2.00   1.00/3.34   1.50/5.00   0.299760 │
    │ = 0.300     = 0.299     = 0.300              │
C10 │ 0.40/2.00   0.67/3.34   1.00/5.00   0.199973 │
    │ = 0.200     = 0.201     = 0.200              │
    └────────────────────────────────────────────────┘

WEIGHT = Average of normalized row
```

### **3. Consistency Ratio**

```
Step 3.1: Weighted Sum Vector
WSV = Matrix × Weight

[1.00  1.67  2.50]   [0.5003]   [1.5011]
[0.60  1.00  1.50] × [0.2998] = [0.9001]
[0.40  0.67  1.00]   [0.2000]   [0.6002]

Step 3.2: Consistency Vector
CV[i] = WSV[i] / Weight[i]

CV = [1.5011/0.5003, 0.9001/0.2998, 0.6002/0.2000]
   = [3.0002, 3.0017, 3.0010]

Step 3.3: Lambda Max
λmax = average(CV) = (3.0002 + 3.0017 + 3.0010) / 3 = 3.0010

Step 3.4: Consistency Index
CI = (λmax - n) / (n - 1)
   = (3.0010 - 3) / (3 - 1)
   = 0.0005

Step 3.5: Consistency Ratio
CR = CI / RI[n]
   = 0.0005 / 0.58  (RI untuk n=3 adalah 0.58)
   = 0.00086
   = 0.086%

Result: CR < 0.1 ✅ KONSISTEN
```

---

## 🎯 **CONTOH SKENARIO LENGKAP**

### **Skenario A: Customer Profesional**

```
┌─────────────────────────────────────────┐
│ USER PROFILE                            │
├─────────────────────────────────────────┤
│ Nama: Budi Santoso                      │
│ Usia: 32 tahun                          │
│ Pekerjaan: Manager                      │
│                                         │
│ KARAKTERISTIK:                          │
│ • Bentuk Kepala: Persegi Panjang        │
│ • Tipe Rambut: Lurus                    │
│ • Preferensi: Modern (Professional)     │
└─────────────────────────────────────────┘
                ↓
┌─────────────────────────────────────────┐
│ TOP RECOMMENDATION                      │
├─────────────────────────────────────────┤
│ 🥇 French Crop (Score: 8.70)            │
│    ✓ Excellent untuk Persegi (9/10)    │
│    ✓ Bagus untuk Lurus (8/10)          │
│    ✓ Modern & Professional (9/10)      │
│                                         │
│ 🥈 Side Part (Score: 8.40)              │
│ 🥉 Pompadour (Score: 8.20)              │
└─────────────────────────────────────────┘
```

### **Skenario B: Customer Casual**

```
┌─────────────────────────────────────────┐
│ USER PROFILE                            │
├─────────────────────────────────────────┤
│ Nama: Andi Wijaya                       │
│ Usia: 22 tahun                          │
│ Pekerjaan: Mahasiswa                    │
│                                         │
│ KARAKTERISTIK:                          │
│ • Bentuk Kepala: Oval                   │
│ • Tipe Rambut: Keriting                 │
│ • Preferensi: Kasual                    │
└─────────────────────────────────────────┘
                ↓
┌─────────────────────────────────────────┐
│ TOP RECOMMENDATION                      │
├─────────────────────────────────────────┤
│ 🥇 Curly Top (Score: 8.90)              │
│    ✓ Perfect untuk Oval (9/10)         │
│    ✓ Excellent untuk Keriting (10/10)  │
│    ✓ Kasual & Trendy (8/10)            │
│                                         │
│ 🥈 Textured Crop (Score: 8.50)          │
│ 🥉 Messy Quiff (Score: 8.30)            │
└─────────────────────────────────────────┘
```

---

## 📊 **DIAGRAM DATABASE RELATIONSHIP**

```
┌──────────────────┐
│    CRITERIA      │
├──────────────────┤
│ id (PK)          │
│ name             │◄─────────┐
│ weight           │          │
└──────────────────┘          │
                              │
                              │
┌──────────────────┐          │
│ PAIRWISE_COMP.   │          │
├──────────────────┤          │
│ id (PK)          │          │
│ criterion_id_1   │──────────┤
│ criterion_id_2   │──────────┘
│ value            │
└──────────────────┘


┌──────────────────┐         ┌────────────────────┐
│  HAIRSTYLES      │         │  HAIRSTYLE_SCORES  │
├──────────────────┤         ├────────────────────┤
│ id (PK)          │◄────────┤ hairstyle_id (FK)  │
│ name             │         │ criterion_id (FK)  │──┐
│ description      │         │ sub_criterion_id   │  │
│ image            │         │ score (1-10)       │  │
└──────────────────┘         └────────────────────┘  │
                                                     │
                             ┌───────────────────────┘
                             │
┌─────────────────────────┐  │
│  SUB-CRITERIA           │  │
├─────────────────────────┤  │
│ BENTUK_KEPALA           │◄─┤
│ • Oval (1)              │  │
│ • Bulat (2)             │  │
│ • Persegi Panjang (3)   │  │
│                         │  │
│ TIPE_RAMBUT             │◄─┤
│ • Lurus (1)             │  │
│ • Bergelombang (2)      │  │
│ • Keriting (3)          │  │
│                         │  │
│ STYLE_PREFERENCE        │◄─┘
│ • Klasik (1)            │
│ • Modern (2)            │
│ • Kasual (3)            │
└─────────────────────────┘
```

---

## 🔍 **VALIDATION CHECKLIST**

```
✅ DATA INTEGRITY
├─ ✓ Total weight kriteria = 1.0 (100%)
├─ ✓ Consistency Ratio < 0.1
├─ ✓ Semua scores dalam range 1-10
├─ ✓ Foreign key relationships valid
└─ ✓ No missing data in critical fields

✅ CALCULATION ACCURACY
├─ ✓ Pairwise comparison symmetric
├─ ✓ Matrix normalization correct
├─ ✓ Weight calculation verified
├─ ✓ Score mapping accurate
└─ ✓ Ranking logic consistent

✅ BUSINESS LOGIC
├─ ✓ Filter returns relevant hairstyles
├─ ✓ Sub-criterion mapping correct
├─ ✓ Score calculation matches formula
├─ ✓ Top recommendations make sense
└─ ✓ Edge cases handled properly
```

---

## 📖 **REFERENSI & RESOURCES**

-   **📄 Dokumentasi Lengkap:** [DOKUMENTASI_AHP.md](../DOKUMENTASI_AHP.md)
-   **💻 Implementation:** `app/Http/Controllers/RecommendationController.php`
-   **🗄️ Database Schema:** `database/migrations/`
-   **📚 AHP Theory:** Saaty, T.L. (1980). The Analytic Hierarchy Process

---

**Generated:** December 10, 2025  
**Version:** 1.0.0

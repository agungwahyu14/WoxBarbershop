# 🎯 AHP Quick Reference Card

## 📊 **BOBOT KRITERIA (CURRENT)**

```
┌────────────────────────────────────────────┐
│  Kriteria         Weight    Prioritas      │
├────────────────────────────────────────────┤
│  Bentuk Kepala    50.03%    🥇 Tertinggi  │
│  Tipe Rambut      29.98%    🥈 Sedang     │
│  Preferensi Gaya  19.99%    🥉 Terendah   │
└────────────────────────────────────────────┘
```

## 🧮 **FORMULA CEPAT**

### Total Score

```
Score = (0.5003 × S_BentukKepala) +
        (0.2998 × S_TipeRambut) +
        (0.2000 × S_PreferensiGaya)
```

### Consistency Ratio

```
CR = 0.00086 < 0.1 ✅ KONSISTEN
```

## 📋 **SUB-KRITERIA**

### Bentuk Kepala

```
ID  Nama               Deskripsi
─────────────────────────────────────────
1   Oval               Bentuk ideal
2   Bulat              Butuh volume atas
3   Persegi Panjang    Butuh pelembut
4   Hati               Balance di dahi
5   Kotak              Lunakkan sudut
6   Segitiga           Butuh volume atas
```

### Tipe Rambut

```
ID  Nama               Deskripsi
─────────────────────────────────────────
1   Lurus              Fleksibel
2   Bergelombang       Versatile
3   Keriting           Butuh style khusus
```

### Preferensi Gaya

```
ID  Nama               Deskripsi
─────────────────────────────────────────
1   Klasik             Formal, timeless
2   Modern             Trendy, edgy
3   Kasual             Santai, natural
```

## 🎯 **CONTOH CEPAT**

### Input

```
Bentuk: Persegi Panjang (3)
Tipe:   Lurus (1)
Gaya:   Modern (2)
```

### Hairstyle: French Crop

```
Scores: Bentuk=9, Tipe=8, Gaya=9

Calculation:
= (0.5003 × 9) + (0.2998 × 8) + (0.2000 × 9)
= 4.5024 + 2.3980 + 1.8000
= 8.70 ⭐⭐⭐ HIGHLY RECOMMENDED
```

## 💡 **INTERPRETASI SCORE**

```
9.0 - 10.0  ⭐⭐⭐⭐⭐  Perfect Match
8.5 - 8.9   ⭐⭐⭐⭐   Excellent
8.0 - 8.4   ⭐⭐⭐    Highly Recommended
7.5 - 7.9   ⭐⭐⭐    Recommended
7.0 - 7.4   ⭐⭐     Good
6.5 - 6.9   ⭐⭐     Acceptable
< 6.5       ⭐       Not Recommended
```

## 🔧 **TROUBLESHOOTING**

### CR > 0.1

```
❌ Problem: Inconsistent pairwise comparison
✅ Solution: Review dan adjust comparison values
```

### Total Weight ≠ 1.0

```
❌ Problem: Weight calculation error
✅ Solution: Check normalization process
```

### Missing Scores

```
❌ Problem: Hairstyle tidak punya score untuk sub-criterion
✅ Solution: Add missing scores in database
```

## 📂 **FILE LOCATIONS**

```
Controller:     app/Http/Controllers/RecommendationController.php
Models:         app/Models/Criteria.php
                app/Models/HairstyleScore.php
                app/Models/PairwiseComparison.php
Migrations:     database/migrations/*criteria*.php
                database/migrations/*hairstyle_scores*.php
Documentation:  DOKUMENTASI_AHP.md
                docs/AHP_FLOWCHART.md
```

## 🚀 **QUICK COMMANDS**

### Test Recommendation

```bash
php artisan tinker

$result = app(\App\Http\Controllers\RecommendationController::class)
    ->index(request()->merge([
        'bentuk_kepala' => 'Persegi Panjang',
        'tipe_rambut' => 'Lurus',
        'preferensi_gaya' => 'Modern'
    ]));
```

### Check Weights

```bash
php artisan tinker
\App\Models\Criteria::all(['id', 'name', 'weight']);
```

### Check Pairwise Comparisons

```bash
php artisan tinker
\App\Models\PairwiseComparison::all();
```

### Recalculate AHP

```bash
# Weights akan auto-recalculate setiap request
# Atau manual update via:
php artisan tinker
(new \App\Http\Controllers\RecommendationController())
    ->calculateAHPWeights();
```

## 📊 **PAIRWISE COMPARISON SCALE**

```
1   = Sama penting
2   = Sedikit lebih penting (antara 1-3)
3   = Cukup lebih penting
4   = Lebih penting (antara 3-5)
5   = Sangat lebih penting
6   = Sangat lebih penting+ (antara 5-7)
7   = Jauh lebih penting
8   = Ekstrem lebih penting (antara 7-9)
9   = Mutlak lebih penting
```

## 📈 **CURRENT COMPARISONS**

```
Bentuk vs Tipe       = 1.67  (Bentuk 1.67x lebih penting)
Bentuk vs Preferensi = 2.5   (Bentuk 2.5x lebih penting)
Tipe vs Preferensi   = 1.5   (Tipe 1.5x lebih penting)
```

---

**Quick Access:** Keep this card for fast reference!  
**Last Updated:** December 10, 2025

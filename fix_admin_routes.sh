#!/bin/bash

# Script untuk memperbaiki route names di admin views

echo "Memperbaiki route names di admin views..."

# User routes
find resources/views/admin -name "*.blade.php" -exec sed -i '' 's/route('\''users\./route('\''admin.users./g' {} \;

# Service routes
find resources/views/admin -name "*.blade.php" -exec sed -i '' 's/route('\''services\./route('\''admin.services./g' {} \;

# Hairstyle routes
find resources/views/admin -name "*.blade.php" -exec sed -i '' 's/route('\''hairstyles\./route('\''admin.hairstyles./g' {} \;

# Booking routes
find resources/views/admin -name "*.blade.php" -exec sed -i '' 's/route('\''bookings\./route('\''admin.bookings./g' {} \;

# Transaction routes
find resources/views/admin -name "*.blade.php" -exec sed -i '' 's/route('\''transactions\./route('\''admin.transactions./g' {} \;

# Loyalty routes
find resources/views/admin -name "*.blade.php" -exec sed -i '' 's/route('\''loyalties\./route('\''admin.loyalties./g' {} \;

# Role routes
find resources/views/admin -name "*.blade.php" -exec sed -i '' 's/route('\''roles\./route('\''admin.roles./g' {} \;

echo "Selesai memperbaiki route names!"

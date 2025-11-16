#!/bin/bash
echo "=== MIGRATIONS ==="
ls -1 database/migrations 2>/dev/null || echo "No migrations yet"

echo -e "\n=== MODELS ==="
ls -1 app/Models 2>/dev/null || echo "No models yet"

echo -e "\n=== LIVEWIRE COMPONENTS ==="
ls -1 app/Livewire 2>/dev/null | sed 's/^/   /' 2>/dev/null || echo "   No Livewire folder"
ls -1 app/Livewire/Admin 2>/dev/null | sed 's/^/      Admin → /' 2>/dev/null || echo "      Admin folder missing"
ls -1 app/Livewire/Student 2>/dev/null | sed 's/^/      Student → /' 2>/dev/null || echo "      Student folder missing"

echo -e "\n=== SPATIE PERMISSION ==="
php artisan | grep -q "permission" && echo "Spatie is installed and published" || echo "Spatie not installed/published yet"

echo -e "\n=== ALL DONE! Paste this output ===\n"

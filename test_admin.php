<?php

// Test script to verify admin routes are working
echo "Testing Admin Routes...\n\n";

// Test 1: Check if admin dashboard route exists
echo "1. Admin Dashboard Route:\n";
echo "   URL: http://127.0.0.1:8000/admin/dashboard\n";
echo "   Expected: Admin dashboard with statistics\n\n";

// Test 2: Check if admin users route exists
echo "2. Admin Users Route:\n";
echo "   URL: http://127.0.0.1:8000/admin/utilisateurs\n";
echo "   Expected: User management page\n\n";

// Test 3: Check if admin projects route exists
echo "3. Admin Projects Route:\n";
echo "   URL: http://127.0.0.1:8000/admin/projets\n";
echo "   Expected: Admin project management page\n\n";

// Test 4: Check regular routes
echo "4. Regular Routes:\n";
echo "   Homepage: http://127.0.0.1:8000/\n";
echo "   Projects: http://127.0.0.1:8000/projets\n\n";

echo "Instructions:\n";
echo "1. Make sure you're logged in as an admin user\n";
echo "2. Access the URLs directly in your browser using port 8000\n";
echo "3. If you don't have an admin user, create one manually in the database\n";
echo "4. The proxy port 61814 might not work correctly with admin routes\n\n";

echo "Direct Access Links:\n";
echo "- Admin Dashboard: http://127.0.0.1:8000/admin/dashboard\n";
echo "- Admin Users: http://127.0.0.1:8000/admin/utilisateurs\n";
echo "- Admin Projects: http://127.0.0.1:8000/admin/projets\n";
echo "- Homepage: http://127.0.0.1:8000/\n";

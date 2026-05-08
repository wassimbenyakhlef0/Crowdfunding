<?php

// Create an admin user for testing
require_once __DIR__ . '/vendor/autoload.php';

try {
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // Check if admin user exists
    $adminExists = \App\Models\User::where('role', 'admin')->exists();
    
    if ($adminExists) {
        echo "Admin user already exists.\n";
        
        // Show existing admin users
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            echo "Admin: {$admin->name} ({$admin->email})\n";
        }
    } else {
        // Create admin user
        $admin = \App\Models\User::create([
            'name' => 'Admin User',
            'prenom' => 'Admin',
            'email' => 'admin@campusfund.com',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
            'role' => 'admin',
            'telephone' => '0123456789'
        ]);
        
        echo "Admin user created successfully!\n";
        echo "Email: admin@campusfund.com\n";
        echo "Password: admin123\n";
        echo "Role: admin\n";
    }
    
    echo "\nLogin with these credentials to access admin panel:\n";
    echo "http://127.0.0.1:8000/login\n";
    echo "Then access: http://127.0.0.1:8000/admin/dashboard\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

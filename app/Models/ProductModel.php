<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    
    // Allowed fields for CRUD operations
    protected $allowedFields = ['name', 'description', 'price_usd', 'image_url'];

    // Auto-timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // Not using updated_at for simplicity
}
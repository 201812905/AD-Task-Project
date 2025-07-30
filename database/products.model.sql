CREATE TABLE IF NOT EXISTS products (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock_quantity INTEGER NOT NULL DEFAULT 0,
    category VARCHAR(100),
    image_url VARCHAR(500),
    status VARCHAR(50) NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample data for products
INSERT INTO products (name, description, price, stock_quantity, category, image_url) VALUES
('Sacred Health Tonic', 'Blessed healing potion infused with the Omnissiah''s wisdom', 299.99, 50, 'Remedies', '/assets/img/products/tonic.jpg'),
('Imperial Medkit', 'Complete field medical kit approved by the Adeptus Mechanicus', 1999.99, 25, 'Medical Equipment', '/assets/img/products/medkit.jpg'),
('Tech-Priest Vitamins', 'Essential nutrients for maintaining sacred flesh and blessed machinery', 499.99, 100, 'Supplements', '/assets/img/products/vitamins.jpg'),
('Omnissiah Pain Relief', 'Divine analgesic for both organic and mechanical ailments', 399.99, 75, 'Remedies', '/assets/img/products/pain-relief.jpg')
ON CONFLICT (id) DO NOTHING;

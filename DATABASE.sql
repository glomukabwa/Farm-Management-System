CREATE DATABASE farm_management;
USE farm_management;

-- Lookup Tables
CREATE TABLE animal_statuses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    status_name VARCHAR(50) NOT NULL,-- Healthy, Sick, Quarantined
    description TEXT
);

CREATE TABLE care_tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,-- Morning Feeding, Evening Feeding, Water Refill
    description TEXT
);

CREATE TABLE product_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(50) NOT NULL,-- Dairy, Poultry, Crops, Aquaculture
    description TEXT
);

-- Core Tables
CREATE TABLE users (-- record of all existing animals
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone_number VARCHAR(20) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin','manager','staff') NOT NULL,
    status ENUM('pending','approved','suspended') DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE animals (-- record of all existing animals
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL,-- Either cow, bull, chicken etc
    breed VARCHAR(50),-- This is important especially for cows
    gender ENUM('male','female'),
    date_of_birth DATE,
    health_status_id INT,
    status ENUM('active','sold','dead') DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (health_status_id) REFERENCES animal_statuses(id)
);

CREATE TABLE feeds (-- record of all existing feeds
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    quantity DECIMAL(10,2),
    unit VARCHAR(20) NOT NULL,-- kgs, bales etc
    expiry_date DATE,
    reorder_level DECIMAL(10,2),-- quantity that triggers alert system
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE daily_animal_care (-- To track whether the morning meal, evening meal & water refill have been done
    id INT AUTO_INCREMENT PRIMARY KEY,
    animal_id INT NOT NULL,
    task_id INT NOT NULL,
    status ENUM('done','not_done') DEFAULT 'not_done',
    performed_by INT,
    performed_at DATETIME,
    FOREIGN KEY (animal_id) REFERENCES animals(id),
    FOREIGN KEY (task_id) REFERENCES care_tasks(id),
    FOREIGN KEY (performed_by) REFERENCES users(id)
);

CREATE TABLE feeding_records (-- To track amount of feeds to respective animal per day
    id INT AUTO_INCREMENT PRIMARY KEY,
    animal_id INT NOT NULL,
    feed_id INT NOT NULL,
    task_id INT NOT NULL,
    quantity DECIMAL(10,2),
    unit VARCHAR(20) NOT NULL,
    recorded_by INT,
    performed_at DATETIME,
    FOREIGN KEY (animal_id) REFERENCES animals(id),
    FOREIGN KEY (feed_id) REFERENCES feeds(id),
    FOREIGN KEY (task_id) REFERENCES care_tasks(id),
    FOREIGN KEY (recorded_by) REFERENCES users(id)
);

CREATE TABLE products (-- record of all products produced by the farm. It's used as a reference for actual production
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,-- Eg milk
    category_id INT,-- For milk it'll belong to category dairy
    unit VARCHAR(20),-- Eg litres, kgs, trays
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES product_categories(id)
);

CREATE TABLE production_records (-- This tracks the active production of the products 
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,-- This will just be the number. The unit is in the products table
    recorded_by INT,
    created_at DATETIME,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (recorded_by) REFERENCES users(id)
);

CREATE TABLE suppliers (-- record of all farm suppliers
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone_number VARCHAR(20) UNIQUE NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE purchases (-- Tracks purchases of products from supplier to farm eg bulls are purchased then fattened
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    unit_cost DECIMAL(10,2),
    total_cost DECIMAL(10,2) 
        GENERATED ALWAYS AS (quantity * unit_cost) STORED,
    supplier_id INT,
    purchase_date DATETIME,
    recorded_by INT,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY(supplier_id) REFERENCES suppliers(id),
    FOREIGN KEY (recorded_by) REFERENCES users(id)
);

CREATE TABLE product_inventory (-- Now that products have been produced, how much do we have in the store that hasn't been sold?
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    quantity_available DECIMAL(10,2) DEFAULT 0,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE sales (-- Tracks the selling of products to buyers
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2),
    sale_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    sold_by INT,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (sold_by) REFERENCES users(id)
);

CREATE TABLE alerts (-- Things that the user needs to be reminded of
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    alert_date DATETIME NOT NULL,-- Date of whatever event the user wants to be reminded of eg vaccination day
    user_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

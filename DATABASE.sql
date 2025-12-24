CREATE DATABASE farm_management;
USE farm_management;

-- Lookup Tables
CREATE TABLE animal_statuses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    status_name VARCHAR(50) NOT NULL,
    description TEXT
);

CREATE TABLE care_tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT
);

CREATE TABLE product_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(50) NOT NULL,
    description TEXT
);

-- Core Tables
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin','manager','staff') NOT NULL,
    status ENUM('pending','approved','suspended') DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE animals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tag_number VARCHAR(50) UNIQUE NOT NULL,
    type VARCHAR(50) NOT NULL,
    breed VARCHAR(50),
    gender ENUM('male','female'),
    date_of_birth DATE,
    health_status_id INT,
    status ENUM('active','sold','dead') DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (health_status_id) REFERENCES animal_statuses(id)
);

CREATE TABLE feeds (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    quantity DECIMAL(10,2),
    unit VARCHAR(20) NOT NULL,
    expiry_date DATE,
    reorder_level DECIMAL(10,2),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE daily_animal_care (
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

CREATE TABLE feeding_records (
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

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category_id INT,
    unit VARCHAR(20),
    is_active BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES product_categories(id)
);

CREATE TABLE production_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    source_type VARCHAR(50),
    source_id INT,
    recorded_by INT,
    performed_at DATETIME,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (recorded_by) REFERENCES users(id)
);

CREATE TABLE purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    unit_cost DECIMAL(10,2),
    supplier_name VARCHAR(100),
    purchase_date DATETIME,
    recorded_by INT,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (recorded_by) REFERENCES users(id)
);

CREATE TABLE product_inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    quantity_available DECIMAL(10,2) DEFAULT 0,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2),
    sale_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    sold_by INT,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (sold_by) REFERENCES users(id)
);

CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    event_date DATETIME NOT NULL,
    user_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

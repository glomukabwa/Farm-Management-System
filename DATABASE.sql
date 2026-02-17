CREATE DATABASE farm_management;
USE farm_management;

-- Lookup Tables
CREATE TABLE animal_statuses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    status_name VARCHAR(50) NOT NULL-- Healthy, Sick, Quarantined
);

CREATE TABLE care_tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,-- Morning Feeding, Evening Feeding, Water Refill
    description TEXT,
    category TEXT
);

CREATE TABLE product_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(50) NOT NULL-- Dairy, Poultry, Crops, Aquaculture, Livestock
    description TEXT
);

CREATE TABLE animal_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE  -- Cow, Goat, Chicken, Fish
);

CREATE TABLE breeds (
    id INT AUTO_INCREMENT PRIMARY KEY,
    animal_type_id INT NOT NULL,
    name VARCHAR(50) NOT NULL,
    FOREIGN KEY (animal_type_id) REFERENCES animal_types(id),
    UNIQUE (animal_type_id, name)
);

CREATE TABLE animal_lifecycle_statuses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL UNIQUE,   -- active, sold, dead
    description TEXT
);

CREATE TABLE purchase_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

-- Core Tables
CREATE TABLE users (-- record of users
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    second_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone_number VARCHAR(20) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin','manager','staff') NOT NULL,
    status ENUM('pending','approved','suspended') DEFAULT 'pending',
    created_at DATE NOT NULL DEFAULT CURRENT_DATE
);

CREATE TABLE animals (-- record of all existing animals
    id INT AUTO_INCREMENT PRIMARY KEY,
    animal_type_id INT NOT NULL,-- Either cow, bull, chicken etc
    breed_id INT,-- This is important especially for cows however it is optional
    tag_number VARCHAR(50) UNIQUE,-- This is optional just incase the farm uses tags
    lifecycle_status_id INT NOT NULL DEFAULT 1,
    gender ENUM('male','female') NOT NULL,
    health_status_id INT,
    created_at DATE NOT NULL DEFAULT CURRENT_DATE,
    FOREIGN KEY (animal_type_id) REFERENCES animal_types(id),
    FOREIGN KEY (breed_id) REFERENCES breeds(id),
    FOREIGN KEY (lifecycle_status_id) REFERENCES animal_lifecycle_statuses(id),
    FOREIGN KEY (health_status_id) REFERENCES animal_statuses(id)
    --Later on, plz confirm if age is needed here
);

CREATE TABLE feeds (-- record of all existing feeds
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    unit VARCHAR(20) NOT NULL,-- kgs, bales etc
    expiry_date DATE,
    reorder_level DECIMAL(10,2) NOT NULL,-- quantity that triggers alert system
    created_at DATE NOT NULL DEFAULT CURRENT_DATE
);

CREATE TABLE daily_animal_care (-- To track whether morning meal, evening meal and water refill have been done
    -- This feels like a repeat of feeding_records below but the reason I include it is for separation purposes
    -- If I merge the two tables, I'll have to make the feed_id in feeding_records null cz when someone records water refill, they don't use a feed
    -- I don't think its ideal to have the feed id be nullable so instead we'll have feeding_records below focusing on tracking feeding and feed quantities
    -- and then we'll have this one for tracking just if all the care tasks have been done. Additionally, if I want to add a new care task like grooming, I can
    -- just do that easily. It keeps the tables clean and easy to understand
    id INT AUTO_INCREMENT PRIMARY KEY,
    animal_type_id INT NOT NULL,
    care_task_id INT NOT NULL,
    performed_by INT NOT NULL,
    performed_at DATE NOT NULL DEFAULT CURRENT_DATE,
    status BOOLEAN DEFAULT FALSE, -- tick or X
    FOREIGN KEY (animal_type_id) REFERENCES animal_types(id),
    FOREIGN KEY (care_task_id) REFERENCES care_tasks(id),
    FOREIGN KEY (performed_by) REFERENCES users(id),

    CONSTRAINT unique_daily_task
        UNIQUE (animal_type_id, care_task_id, performed_at)
);

CREATE TABLE feeding_records (-- To track amount of feeds to respective animal per day
    id INT AUTO_INCREMENT PRIMARY KEY,
    animal_type_id INT NOT NULL,
    feed_id INT NOT NULL,
    care_task_id INT NOT NULL, -- Morning meal, Evening meal
    quantity_used DECIMAL(10,2) NOT NULL,
    fed_at DATE NOT NULL DEFAULT CURRENT_DATE,
    recorded_by INT NOT NULL,
    FOREIGN KEY (animal_type_id) REFERENCES animal_types(id),
    FOREIGN KEY (feed_id) REFERENCES feeds(id),
    FOREIGN KEY (care_task_id) REFERENCES care_tasks(id),
    FOREIGN KEY (recorded_by) REFERENCES users(id)
);


CREATE TABLE products (-- record of all products produced by the farm. It's used as a reference for actual production
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,-- Eg milk
    category_id INT NOT NULL,-- For milk it'll belong to category dairy
    unit VARCHAR(20) NOT NULL,-- Eg litres, kgs, trays
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES product_categories(id)
);

CREATE TABLE production_records (-- This tracks the active production of the products 
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    quantity INT NOT NULL,-- This will just be the number. The unit is in the products table
    recorded_by INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    comment TEXT,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (recorded_by) REFERENCES users(id)
);

CREATE TABLE suppliers (-- record of all farm suppliers
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    second_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    phone_number VARCHAR(20) UNIQUE NOT NULL,
    created_at DATE NOT NULL DEFAULT CURRENT_DATE
);

CREATE TABLE purchases (-- Tracks purchases of products from supplier to farm eg bulls are purchased then fattened
    id INT AUTO_INCREMENT PRIMARY KEY,
    purchase_name VARCHAR(100) NOT NULL,
    purchase_category_id INT NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    unit_cost DECIMAL(10,2) NOT NULL,
    total_cost DECIMAL(10,2) 
        GENERATED ALWAYS AS (quantity * unit_cost) STORED,
    supplier_name VARCHAR(100) NOT NULL,
    supplier_phone_number VARCHAR(50),
    purchase_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    recorded_by INT NOT NULL,
    FOREIGN KEY (purchase_category_id) REFERENCES purchase_categories(id),
    FOREIGN KEY (recorded_by) REFERENCES users(id)
);

CREATE TABLE product_inventory (-- Now that products have been produced, how much do we have in the store that hasn't been sold?
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    quantity_available DECIMAL(10,2) DEFAULT 0.00,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE product_sales (-- Tracks the selling of products to buyers
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    unit_cost DECIMAL(10,2) NOT NULL,
    total_cost DECIMAL(10,2) 
        GENERATED ALWAYS AS (quantity * unit_cost) STORED,
    sale_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    sold_by INT,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (sold_by) REFERENCES users(id)
);

CREATE TABLE animal_sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    animal_type_id INT NOT NULL,
    gender ENUM('male','female') NOT NULL,
    quantity INT NOT NULL,
    unit_cost DECIMAL(10,2) NOT NULL,
    total_cost DECIMAL(10,2) 
        GENERATED ALWAYS AS (quantity * unit_cost) STORED,
    sale_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    sold_by INT,
    FOREIGN KEY (animal_type_id) REFERENCES animals(animal_type_id),
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

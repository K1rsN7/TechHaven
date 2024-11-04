-- Удаление таблиц, если они существуют
DROP TABLE IF EXISTS `order_item`;
DROP TABLE IF EXISTS `review`;
DROP TABLE IF EXISTS `cart`;
DROP TABLE IF EXISTS `order`;
DROP TABLE IF EXISTS `product`;
DROP TABLE IF EXISTS `brand`;
DROP TABLE IF EXISTS `user`;
DROP TABLE IF EXISTS `category`;

-- Таблица категорий товаров
CREATE TABLE `category` (
    id_category INT AUTO_INCREMENT,
    name_category VARCHAR(200) NOT NULL,
    PRIMARY KEY (id_category)
);

-- Пользователи
CREATE TABLE `user` (
    id_user BIGINT AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL,
    image_user text,
    login VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_user)
);


-- Бренды 
CREATE TABLE `brand` (
    id_brand INT AUTO_INCREMENT,
    name_brand VARCHAR(200) NOT NULL,
    image_brand text, 
    PRIMARY KEY (id_brand)
);


-- Продукты
CREATE TABLE `product` (
    id_product INT AUTO_INCREMENT,
    image text,
    name_product VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    delivery_days INT NOT NULL,
    id_category INT,
    id_brand INT,
    guarantee_months INT,
    PRIMARY KEY (id_product),
    FOREIGN KEY (id_category) REFERENCES `category`(id_category),
    FOREIGN KEY (id_brand) REFERENCES `brand`(id_brand)
);

-- Заказы пользователей
CREATE TABLE `order` (
    id_order BIGINT AUTO_INCREMENT,
    id_user BIGINT,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'completed', 'canceled', 'on the way') DEFAULT 'pending',
    total DECIMAL(20, 2),
    PRIMARY KEY (id_order),
    FOREIGN KEY (id_user) REFERENCES `user`(id_user)
);

-- Товары в заказе
CREATE TABLE `order_items` (
    id_order_item BIGINT AUTO_INCREMENT,
    id_order BIGINT,
    id_product INT,
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(10, 2) NOT NULL,
    PRIMARY KEY (id_order_item),
    FOREIGN KEY (id_order) REFERENCES `order`(id_order),
    FOREIGN KEY (id_product) REFERENCES `product`(id_product)
);

-- Отзывы
CREATE TABLE `review` (
    id_review BIGINT AUTO_INCREMENT,
    id_user BIGINT,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    comment VARCHAR(250) NOT NULL,
    review_date DATE DEFAULT CURRENT_DATE(),
    PRIMARY KEY (id_review),
    FOREIGN KEY (id_user) REFERENCES `user`(id_user) 
);

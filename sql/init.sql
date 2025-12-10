-- Database Initialization SQL

-- 管理者テーブル
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password_hash VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 活動記録テーブル
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(50),
    content text,
    image_path VARCHAR(255),
    post_date DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- チャットテーブル
CREATE TABLE IF NOT EXISTS chats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_name VARCHAR(50),
    message text,
    is_admin INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- お知らせテーブル
CREATE TABLE IF NOT EXISTS infos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content text,
    post_date DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 作品テーブル
CREATE TABLE IF NOT EXISTS works (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100),
    content text,
    image_path VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- サイト設定テーブル
CREATE TABLE IF NOT EXISTS site_settings (
    setting_key VARCHAR(50) PRIMARY KEY,
    setting_value text
);

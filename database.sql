-- Mahasiswa Manager Database
CREATE DATABASE IF NOT EXISTS mahasiswa_db;
USE mahasiswa_db;

-- Table admin
CREATE TABLE admin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Table mahasiswa
CREATE TABLE mahasiswa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nim VARCHAR(20) UNIQUE NOT NULL,
    nama VARCHAR(100) NOT NULL,
    jurusan VARCHAR(50) NOT NULL,
    semester INT NOT NULL,
    foto VARCHAR(255)
);

-- Insert default admin (password: admin123)
INSERT INTO admin (username, password) VALUES 
('admin', 'admin123');

-- Sample data
INSERT INTO mahasiswa (nim, nama, jurusan, semester, foto) VALUES 
('20210001', 'Budi Santoso', 'Informatika', 5, NULL),
('20210002', 'Sari Dewi', 'Sistem Informasi', 3, NULL),
('20210003', 'Ahmad Rizki', 'Teknik Komputer', 7, NULL); 
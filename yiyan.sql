CREATE DATABASE IF NOT EXISTS hitokoto_db DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE yiyan_db;

CREATE TABLE IF NOT EXISTS sentence (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT '唯一ID',
    sentence TEXT NOT NULL COMMENT '一言内容',
    `from` VARCHAR(255) DEFAULT NULL COMMENT '出处（可选）',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='存储一言内容';

INSERT INTO sentence (sentence, `from`) VALUES 
('这只是一个测试句子', '测试');

CREATE TABLE IF NOT EXISTS api_counter (
    id INT PRIMARY KEY DEFAULT 1 COMMENT '固定ID',
    request_count INT NOT NULL DEFAULT 0 COMMENT 'API请求计数'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='API请求计数表';

INSERT INTO api_counter (id, request_count) VALUES (1, 0) ON DUPLICATE KEY UPDATE request_count = request_count;

SHOW TABLES;

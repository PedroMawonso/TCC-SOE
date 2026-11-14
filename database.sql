-- IMTLA StudyHub
-- Importar este arquivo no phpMyAdmin com o XAMPP em execucao.

CREATE DATABASE IF NOT EXISTS `imtla_studyhub`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `imtla_studyhub`;

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `email` VARCHAR(190) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('student', 'admin') NOT NULL DEFAULT 'student',
  `course` VARCHAR(120) DEFAULT NULL,
  `avatar` VARCHAR(255) DEFAULT NULL,
  `theme` ENUM('light', 'dark') NOT NULL DEFAULT 'light',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`),
  KEY `idx_users_role` (`role`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `subjects` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `teacher` VARCHAR(150) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_subjects_user_id` (`user_id`),
  CONSTRAINT `fk_subjects_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `tasks` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `title` VARCHAR(180) NOT NULL,
  `subject` VARCHAR(150) NOT NULL,
  `due_date` DATE DEFAULT NULL,
  `status` VARCHAR(30) NOT NULL DEFAULT 'Por iniciar',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tasks_user_id` (`user_id`),
  KEY `idx_tasks_due_date` (`due_date`),
  CONSTRAINT `fk_tasks_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `schedules` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `day` VARCHAR(30) NOT NULL,
  `period` VARCHAR(80) NOT NULL,
  `activity` VARCHAR(180) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_schedules_user_id` (`user_id`),
  CONSTRAINT `fk_schedules_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `materials` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `title` VARCHAR(180) NOT NULL,
  `subject` VARCHAR(150) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_materials_user_id` (`user_id`),
  CONSTRAINT `fk_materials_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- Conta inicial: admin@imtla.ao / Admin@123
INSERT INTO `users` (`name`, `email`, `password`, `role`, `course`)
VALUES (
  'Administrador IMTLA',
  'admin@imtla.ao',
  '$2y$10$NiawZU0J7MJC6olmYlAQPOxSCsMiL.1nyljxtGoqoAeZ4lkLUO2jW',
  'admin',
  'Administracao'
)
ON DUPLICATE KEY UPDATE
  `name` = VALUES(`name`),
  `password` = VALUES(`password`),
  `role` = VALUES(`role`),
  `course` = VALUES(`course`);

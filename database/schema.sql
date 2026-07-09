-- Database Schema for cobalms (MySQL)
-- Adapted from postgresql schema in vccbe, retaining only tables used by lmsppbj

SET FOREIGN_KEY_CHECKS = 0;

-- -------------------------------------------------------------
-- Table: users (Mengubah tabel yang sudah ada dari Laravel)
-- -------------------------------------------------------------
ALTER TABLE `users`
  ADD COLUMN `username` VARCHAR(191) NOT NULL AFTER `id`,
  ADD COLUMN `role` VARCHAR(50) NOT NULL COMMENT "'guru' | 'siswa'" AFTER `password`,
  ADD COLUMN `bio` TEXT DEFAULT NULL AFTER `email`,
  ADD COLUMN `avatar` VARCHAR(255) DEFAULT NULL AFTER `bio`,
  ADD COLUMN `streak` INT NOT NULL DEFAULT 0 AFTER `avatar`,
  ADD COLUMN `totalXP` INT NOT NULL DEFAULT 0 AFTER `streak`,
  ADD COLUMN `gems` INT NOT NULL DEFAULT 0 AFTER `gems`,
  ADD COLUMN `league` VARCHAR(50) NOT NULL DEFAULT 'Bronze' AFTER `gems`,
  ADD UNIQUE KEY `users_username_key` (`username`);


-- -------------------------------------------------------------
-- Table: sections
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `sections`;
CREATE TABLE `sections` (
  `id` VARCHAR(191) NOT NULL,
  `name` VARCHAR(191) NOT NULL, -- e.g., "N5", "N4", "N3", "N2", "N1"
  `description` TEXT DEFAULT NULL,
  `createdAt` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updatedAt` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  UNIQUE KEY `sections_name_key` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table: units
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `units`;
CREATE TABLE `units` (
  `id` VARCHAR(191) NOT NULL,
  `sectionId` VARCHAR(191) NOT NULL,
  `title` VARCHAR(191) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `type` VARCHAR(50) NOT NULL, -- "grammar", "kanji", "vocabulary", "bunpou"
  `order` INT NOT NULL DEFAULT 0,
  `createdAt` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updatedAt` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  KEY `units_sectionId_idx` (`sectionId`),
  CONSTRAINT `units_sectionId_fkey` FOREIGN KEY (`sectionId`) REFERENCES `sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table: nodes
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `nodes`;
CREATE TABLE `nodes` (
  `id` VARCHAR(191) NOT NULL,
  `unitId` VARCHAR(191) NOT NULL,
  `title` VARCHAR(191) NOT NULL,
  `type` VARCHAR(50) NOT NULL, -- "lesson", "quiz", "checkpoint"
  `order` INT NOT NULL DEFAULT 0,
  `createdAt` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updatedAt` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  KEY `nodes_unitId_idx` (`unitId`),
  CONSTRAINT `nodes_unitId_fkey` FOREIGN KEY (`unitId`) REFERENCES `units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table: questions
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `questions`;
CREATE TABLE `questions` (
  `id` VARCHAR(191) NOT NULL,
  `nodeId` VARCHAR(191) NOT NULL,
  `type` VARCHAR(50) NOT NULL, -- "multiple-choice", "drag-drop"
  `question` TEXT NOT NULL,
  `options` JSON NOT NULL, -- Default "[]"
  `correctAnswer` JSON NOT NULL, -- Default "{}"
  `relatedCharacters` JSON NOT NULL, -- Stores array of strings, maps to postgresql String[]
  `order` INT NOT NULL DEFAULT 0,
  `createdAt` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updatedAt` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  KEY `questions_nodeId_idx` (`nodeId`),
  CONSTRAINT `questions_nodeId_fkey` FOREIGN KEY (`nodeId`) REFERENCES `nodes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table: stories
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `stories`;
CREATE TABLE `stories` (
  `id` VARCHAR(191) NOT NULL,
  `sectionId` VARCHAR(191) NOT NULL,
  `title` VARCHAR(191) NOT NULL,
  `content` JSON NOT NULL, -- Default "[]"
  `order` INT NOT NULL DEFAULT 0,
  `createdAt` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updatedAt` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  KEY `stories_sectionId_idx` (`sectionId`),
  CONSTRAINT `stories_sectionId_fkey` FOREIGN KEY (`sectionId`) REFERENCES `sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table: user_progress
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `user_progress`;
CREATE TABLE `user_progress` (
  `id` VARCHAR(191) NOT NULL,
  `userId` BIGINT UNSIGNED NOT NULL,
  `nodeId` VARCHAR(191) NOT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'completed',
  `createdAt` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updatedAt` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_progress_userId_nodeId_key` (`userId`, `nodeId`),
  KEY `user_progress_nodeId_idx` (`nodeId`),
  CONSTRAINT `user_progress_userId_fkey` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_progress_nodeId_fkey` FOREIGN KEY (`nodeId`) REFERENCES `nodes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table: question_responses
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `question_responses`;
CREATE TABLE `question_responses` (
  `id` VARCHAR(191) NOT NULL,
  `userId` BIGINT UNSIGNED NOT NULL,
  `questionId` VARCHAR(191) NOT NULL,
  `isCorrect` BOOLEAN NOT NULL,
  `createdAt` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  KEY `question_responses_userId_questionId_idx` (`userId`, `questionId`),
  KEY `question_responses_questionId_idx` (`questionId`),
  CONSTRAINT `question_responses_userId_fkey` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `question_responses_questionId_fkey` FOREIGN KEY (`questionId`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

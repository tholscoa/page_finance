CREATE DATABASE `pagefi`;
USE `pagefi`;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
LOCK TABLES `users` WRITE;
UNLOCK TABLES;

DROP TABLE IF EXISTS `wallet_types`;
CREATE TABLE `wallet_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `minimum_balance` double NOT NULL,
  `interest_rate` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wallet_types_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
LOCK TABLES `wallet_types` WRITE;
UNLOCK TABLES;

DROP TABLE IF EXISTS `user_wallets`;
CREATE TABLE `user_wallets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `wallet_type` bigint unsigned NOT NULL,
  `wallet_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `balance` double NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_wallets_wallet_id_unique` (`wallet_id`),
  KEY `user_wallets_user_id_foreign` (`user_id`),
  KEY `user_wallets_wallet_type_foreign` (`wallet_type`),
  CONSTRAINT `user_wallets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `user_wallets_wallet_type_foreign` FOREIGN KEY (`wallet_type`) REFERENCES `wallet_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
LOCK TABLES `user_wallets` WRITE;
UNLOCK TABLES;

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double unsigned NOT NULL,
  `transaction_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `narrative` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No description',
  `source_wallet_id` varchar(255) COLLATE utf8mb4_unicode_ci,
  `beneficiary_wallet_id` varchar(255) COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transactions_source_wallet_id_foreign` (`source_wallet_id`),
  KEY `transactions_beneficiary_wallet_id_foreign` (`beneficiary_wallet_id`),
  CONSTRAINT `transactions_beneficiary_wallet_id_foreign` FOREIGN KEY (`beneficiary_wallet_id`) REFERENCES `user_wallets` (`wallet_id`),
  CONSTRAINT `transactions_source_wallet_id_foreign` FOREIGN KEY (`source_wallet_id`) REFERENCES `user_wallets` (`wallet_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
LOCK TABLES `transactions` WRITE;
UNLOCK TABLES;





-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: localhost    Database: laravel
-- ------------------------------------------------------
-- Server version	9.1.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `appointments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `doctor_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `age` int NOT NULL,
  `cccd` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `appointment_date` date NOT NULL,
  `shift` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointments`
--

LOCK TABLES `appointments` WRITE;
/*!40000 ALTER TABLE `appointments` DISABLE KEYS */;
INSERT INTO `appointments` VALUES (28,3,'Trinh Luong','luong.trinh.yp464@gmail.com','0816260406',23,'21111111111111111','đau bụng quá','2025-02-10','morning','rejected','2025-02-13 02:22:04','2025-03-16 06:50:04'),(41,4,'Trinh Luong','a123@gmail.com','0816260406',22,'111111111111111','gggggggg','2025-03-10','morning','approved','2025-03-14 07:57:09','2025-03-14 08:06:08'),(42,8,'Trinh Luong','tauseed@test.com','0816260406',32,'111111111111111','Tôi bị viêm da','2025-03-27','morning','approved','2025-03-16 07:03:48','2025-03-16 07:03:48'),(43,18,'Phan Viết Anh Tuấn','sssssssssssssssssss@gmail.com','0816260406',23,'111111111111111','aaaaa','2025-03-19','morning','approved','2025-03-16 20:19:32','2025-03-16 20:19:32'),(44,18,'Trinh Luong','sdc@gmail.com','0816260406',23,'05555555555','dddddddd','2025-03-19','morning','approved','2025-03-16 20:20:22','2025-03-16 20:20:22'),(45,3,'Cô Nga','sdc@gmail.com','0816260406',23,'111111111111111','aaaaaaaaaa','2025-03-20','afternoon','approved','2025-03-16 20:25:31','2025-03-16 20:25:31'),(46,3,'Mai Thúy Nga','tauseed@test.com','0816260406',30,'111111111111111','gggggggggggg','2025-03-20','afternoon','approved','2025-03-16 21:26:47','2025-03-16 21:26:47'),(47,5,'Trương Long Khánh','sdc@gmail.com','0816260406',23,'05555555555','HAHAHA','2025-03-26','afternoon','approved','2025-03-21 02:49:07','2025-03-21 02:49:07'),(48,3,'Uchiha Madara','sdc@gmail.com','0816260406',50,'111111111111111','hjhjhjhj','2025-03-24','morning','approved','2025-03-21 03:03:40','2025-03-21 03:03:40'),(49,3,'Uchiha hahah','sdc@gmail.com','0123456789',50,'1111111111','hahahah','2025-03-24','morning','approved','2025-03-21 03:12:08','2025-03-21 03:12:08'),(50,3,'Uchiha Sasuke','sdc@gmail.com','0816260406',42,'05555555555','kkkkkkkkkkkkk','2025-03-31','morning','approved','2025-03-21 07:58:57','2025-03-21 07:58:57');
/*!40000 ALTER TABLE `appointments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctors`
--

DROP TABLE IF EXISTS `doctors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `specialty` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bio` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `working_hours` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `doctors_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctors`
--

LOCK TABLES `doctors` WRITE;
/*!40000 ALTER TABLE `doctors` DISABLE KEYS */;
INSERT INTO `doctors` VALUES (3,'Nguyễn Văn B','b123@gmail.com','$2y$10$96Bq/HSrkL6ujXMx2/cXlecAo6qN2c.O51vtAZO8THS0BdebFYNX2','Điều trị viêm da','0816260406','Chữa viêm da cơ địa, viêm da dầu, viêm da tiếp xúc','img/1738864493_1736923416_b.jpg','[{\"day\": \"Monday\", \"shift\": \"morning\"}, {\"day\": \"Tuesday\", \"shift\": \"morning\"}, {\"day\": \"Wednesday\", \"shift\": \"morning\"}, {\"day\": \"Thursday\", \"shift\": \"afternoon\"}]','2025-02-06 10:54:54','2025-03-21 02:44:44'),(4,'Nguyễn Văn C','c123@gmail.com','$2y$10$H6nZPd9N0GQt4eOts0l1QuEhIe5QWjB6PsYNdsqt8DFXxm63kXNfa','Trị sẹo rỗ, sẹo lõm','0816260406','Phương pháp PRP, lăn kim, laser để tái tạo da','img/1739029484_1736923780_c.jpg','[{\"day\": \"Monday\", \"shift\": \"morning\"}, {\"day\": \"Tuesday\", \"shift\": \"morning\"}, {\"day\": \"Wednesday\", \"shift\": \"morning\"}, {\"day\": \"Thursday\", \"shift\": \"morning\"}]','2025-02-08 08:44:44','2025-03-16 03:12:51'),(5,'Nguyễn Thị D','d123@gmail.com','$2y$10$VEHPHYnhRAl6ZHDrCjH.Ge4ogr7xNvj7swIwo1HoCTLU8Y0TIQbiW','Điều trị mụn','0816260405','Chăm sóc da, điều trị mụn trứng cá, mụn bọc, mụn đầu đen','img/1739347643_doctor_02.jpg','[{\"day\": \"Wednesday\", \"shift\": \"afternoon\"}, {\"day\": \"Thursday\", \"shift\": \"afternoon\"}, {\"day\": \"Friday\", \"shift\": \"afternoon\"}, {\"day\": \"Saturday\", \"shift\": \"afternoon\"}]','2025-02-12 01:07:23','2025-03-16 03:16:21'),(7,'Nguyễn Thị E','e123@gmail.com','$2y$10$UMu20RbjbP96vc3.TZZZr.enQkTmH3YlbMuKrEm7a4VsYtyTXwxPS','Chăm sóc da','0816260406','Các liệu pháp làm sạch da, dưỡng ẩm, tái tạo da và chống lão hóa','uploads/1742091957_67d636b56bf26.jpg','[{\"day\": \"Wednesday\", \"shift\": \"morning\"}, {\"day\": \"Thursday\", \"shift\": \"morning\"}, {\"day\": \"Friday\", \"shift\": \"morning\"}, {\"day\": \"Saturday\", \"shift\": \"morning\"}]','2025-03-15 19:25:57','2025-03-16 03:18:09'),(9,'Trịnh Xuân F','f123@gmail.com','$2y$10$zO7Ao5rbMJTlqRvRfeKmmupkji8rbhbQ8w6gF8fU9WjKGAhYCF4Ma','Trị nám, tàn nhang','0816260406','Dùng laser hoặc phương pháp khác để làm mờ nám, tàn nhang','uploads/1742093733_67d63da55de31.jpg','[{\"day\": \"Tuesday\", \"shift\": \"afternoon\"}, {\"day\": \"Wednesday\", \"shift\": \"afternoon\"}, {\"day\": \"Thursday\", \"shift\": \"afternoon\"}, {\"day\": \"Friday\", \"shift\": \"afternoon\"}]','2025-03-15 19:55:33','2025-03-16 02:58:12'),(10,'Trần Xuân H','h123@gmail.com','$2y$10$k/BWizmH99f54HFBGnuSR.25gxWPf2KVbUl/uFg5rmQjiIO6E1F8W','Điều trị viêm da','0816260406','Chăm sóc da, điều trị mụn trứng cá, mụn bọc, mụn đầu đen','uploads/1742118320_67d69db0946ce.jpg','[{\"day\": \"Monday\", \"shift\": \"afternoon\"}, {\"day\": \"Tuesday\", \"shift\": \"afternoon\"}, {\"day\": \"Friday\", \"shift\": \"morning\"}, {\"day\": \"Saturday\", \"shift\": \"morning\"}]','2025-03-16 02:45:20','2025-03-16 03:11:12'),(11,'Lê Minh I','i123@gmail.com','$2y$10$rIun0GdCTbFFvS6YdvKaY.CF5OhKptE26D04UwS7sf7T1Zvnw5.R.','Điều trị mụn','0816260406','Chăm sóc da, điều trị mụn trứng cá, mụn bọc, mụn đầu đen','uploads/1742118379_67d69debbf795.jpg','[{\"day\": \"Monday\", \"shift\": \"morning\"}, {\"day\": \"Tuesday\", \"shift\": \"morning\"}, {\"day\": \"Wednesday\", \"shift\": \"morning\"}, {\"day\": \"Thursday\", \"shift\": \"morning\"}]','2025-03-16 02:46:19','2025-03-16 03:17:06'),(12,'Phan Văn J','j123@gmail.com','$2y$10$hpU.BK4z//R6GjbJStGAN.pqu9OaNZm7CEBF6NFRjQpt6Q2QRKzPe','Điều trị viêm da','0816260406','Chăm sóc da, điều trị mụn trứng cá, mụn bọc, mụn đầu đen','uploads/1742118482_67d69e5262ae6.jpg','[{\"day\": \"Wednesday\", \"shift\": \"afternoon\"}, {\"day\": \"Thursday\", \"shift\": \"morning\"}, {\"day\": \"Friday\", \"shift\": \"afternoon\"}, {\"day\": \"Saturday\", \"shift\": \"afternoon\"}]','2025-03-16 02:48:02','2025-03-16 03:12:19'),(13,'Lê Bá K','k123@gmail.com','$2y$10$wovdPYPcX2vDCOPIBFB/KOkmU5v/tWodsIehmYtV04xy3Vh8SQ0Sa','Trị sẹo rỗ, sẹo lõm','0816260406','Điều trị các loại sẹo sau mụn hoặc sẹo phẫu thuật bằng các phương pháp như laser, filler hoặc phẫu thuật.','uploads/1742118562_67d69ea2db6b2.jpg','[{\"day\": \"Monday\", \"shift\": \"afternoon\"}, {\"day\": \"Tuesday\", \"shift\": \"afternoon\"}, {\"day\": \"Friday\", \"shift\": \"morning\"}, {\"day\": \"Saturday\", \"shift\": \"morning\"}]','2025-03-16 02:49:23','2025-03-16 03:13:49'),(14,'Lê Văn L','l123@gmail.com','$2y$10$XP91y.MvxYPpb34dGiUShuA2J/KZA3mP6u7EOFYDweODzRuGzJpxq','Trị sẹo rỗ, sẹo lõm','0816260406','Điều trị các loại sẹo sau mụn hoặc sẹo phẫu thuật bằng các phương pháp như laser, filler hoặc phẫu thuật.','uploads/1742118667_67d69f0b1838b.jpg','[{\"day\": \"Friday\", \"shift\": \"afternoon\"}, {\"day\": \"Saturday\", \"shift\": \"afternoon\"}, {\"day\": \"Wednesday\", \"shift\": \"afternoon\"}, {\"day\": \"Thursday\", \"shift\": \"afternoon\"}]','2025-03-16 02:51:07','2025-03-16 03:15:41'),(15,'Trịnh Thị M','m123@gmail.com','$2y$10$vmeCJw6.TQEATpJF0Ldh5uDMn0TGZhYe3n7A469twH9sq9xENgyC6','Điều trị mụn','0816260406','Điều trị mụn trứng cá, mụn bọc, mụn đầu đen, mụn viêm bằng các phương pháp thuốc, laser, ánh sáng.','uploads/1742118788_67d69f84a8a70.jpg','[{\"day\": \"Monday\", \"shift\": \"afternoon\"}, {\"day\": \"Tuesday\", \"shift\": \"afternoon\"}, {\"day\": \"Friday\", \"shift\": \"morning\"}, {\"day\": \"Saturday\", \"shift\": \"morning\"}]','2025-03-16 02:53:08','2025-03-16 03:17:35'),(16,'Trương Thị N','n123@gmail.com','$2y$10$lSlgNly6MNzyYMycGZC7deSpSr03FFUZo6679bhgdqXLZj7wP.fse','Chăm sóc da','0816260406','Các liệu pháp làm sạch da, dưỡng ẩm, tái tạo da và chống lão hóa','uploads/1742118846_67d69fbebbc10.jpg','[{\"day\": \"Monday\", \"shift\": \"afternoon\"}, {\"day\": \"Tuesday\", \"shift\": \"afternoon\"}, {\"day\": \"Wednesday\", \"shift\": \"afternoon\"}, {\"day\": \"Thursday\", \"shift\": \"afternoon\"}]','2025-03-16 02:54:06','2025-03-16 03:18:29'),(17,'Lê Minh O','o123@gmail.com','$2y$10$d8pBRxV30WwFzkRljnSVOeo5DvD6XbHLRVcFsUwcTFQs9ZAg1DVFq','Chăm sóc da','0816260406','Các liệu pháp làm sạch da, dưỡng ẩm, tái tạo da và chống lão hóa','uploads/1742118885_67d69fe5c3890.jpg','[{\"day\": \"Monday\", \"shift\": \"morning\"}, {\"day\": \"Tuesday\", \"shift\": \"morning\"}, {\"day\": \"Friday\", \"shift\": \"afternoon\"}, {\"day\": \"Saturday\", \"shift\": \"afternoon\"}]','2025-03-16 02:54:45','2025-03-16 03:18:51'),(18,'Nguyễn Văn A','a123@gmail.com','$2y$10$9qLNjfeQoaT9axtw/8lcM.qxaUjlTvbvvjh8Bp2X0P9H7hKfA3p3.','Trị nám, tàn nhang','0816260406','Các liệu pháp làm sạch da, dưỡng ẩm, tái tạo da và chống lão hóa','uploads/1742139119_67d6eeefe6113.jpg','[{\"day\": \"Monday\", \"shift\": \"morning\"}, {\"day\": \"Tuesday\", \"shift\": \"morning\"}, {\"day\": \"Wednesday\", \"shift\": \"morning\"}, {\"day\": \"Thursday\", \"shift\": \"morning\"}]','2025-03-16 08:32:00','2025-03-16 08:32:00'),(20,'Trịnh Trần Phương G','g123@gmail.com','$2y$10$0nXLolBpNlw/2X7HQzHMYe3RdAcb4wYZxZABBKKV81uSqbTdX9zci','Trị nám, tàn nhang','0816260406','Dùng laser hoặc phương pháp khác để làm mờ nám, tàn nhang','uploads/1742139415_67d6f017707ef.jpg','[{\"day\": \"Monday\", \"shift\": \"afternoon\"}, {\"day\": \"Friday\", \"shift\": \"morning\"}, {\"day\": \"Saturday\", \"shift\": \"morning\"}, {\"day\": \"Saturday\", \"shift\": \"afternoon\"}]','2025-03-16 08:36:55','2025-03-16 08:51:27');
/*!40000 ALTER TABLE `doctors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exam_date` date NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost` bigint DEFAULT NULL,
  `invoice_date` date NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `services_medicines` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('Đã thanh toán','Chưa thanh toán') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Chưa thanh toán',
  `medical_record_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoices_medical_record_id_foreign` (`medical_record_id`),
  CONSTRAINT `invoices_medical_record_id_foreign` FOREIGN KEY (`medical_record_id`) REFERENCES `medical_records` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES (21,'Trinh Luong','2025-03-19','0816260406',500000,'2025-03-16',500000.00,'Spa da, sửa mũi, lấy mụn đầu đen; 1 thuốc bổ','Đã thanh toán',19,'2025-03-16 06:59:32','2025-03-16 06:59:32'),(22,'Mai Thúy Nga','2025-03-20','0816260406',500000,'2025-03-17',900000.00,'Spa da, sửa mũi, lấy mụn đầu đen; 3 liều thuốc viêm da','Đã thanh toán',20,'2025-03-16 21:31:14','2025-03-16 21:31:14');
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medical_records`
--

DROP TABLE IF EXISTS `medical_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `medical_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `doctor_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `age` int NOT NULL,
  `cccd` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `service` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exam_date` date NOT NULL,
  `cost` decimal(8,2) DEFAULT NULL,
  `status` enum('paid','unpaid') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `diagnosis` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prescription` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medical_records`
--

LOCK TABLES `medical_records` WRITE;
/*!40000 ALTER TABLE `medical_records` DISABLE KEYS */;
INSERT INTO `medical_records` VALUES (19,2,'Trinh Luong','tauseed@test.com','0816260406',34,'21111111111111111','Spa da, sửa mũi, lấy mụn đầu đen','2025-03-19',500000.00,'paid','viêm da','1 thuốc bổ','1 liều 1 ngày','2025-03-16 06:59:04','2025-03-16 06:59:04'),(20,3,'Mai Thúy Nga','tauseed@test.com','0816260406',30,'111111111111111','Spa da, sửa mũi, lấy mụn đầu đen','2025-03-20',500000.00,'unpaid','viêm da','3 liều thuốc viêm da','1 liều 1 ngày','2025-03-16 21:29:41','2025-03-16 21:29:41');
/*!40000 ALTER TABLE `medical_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2025_01_02_021510_create_patients_table',1),(6,'2025_01_07_110150_create_doctors_table',2),(7,'2025_01_07_110304_create_appointments_table',2),(8,'2025_01_09_094158_add_role_to_users_table',3),(9,'2025_01_13_162036_create_appointments_table',4),(10,'2025_01_13_171749_create_appointments_table',5),(11,'2025_01_15_081912_create_supports_table',4),(12,'2025_02_15_072146_create_invoices_table',6),(13,'2025_02_15_091610_create_invoices_table',7),(14,'2025_02_19_063753_create_services_table',8);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patients`
--

DROP TABLE IF EXISTS `patients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `age` int NOT NULL,
  `gender` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patients`
--

LOCK TABLES `patients` WRITE;
/*!40000 ALTER TABLE `patients` DISABLE KEYS */;
/*!40000 ALTER TABLE `patients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (1,'Tiêm Filler xóa nhăn –  phương pháp trẻ hóa hiện đại','uploads/1739952584_67b591c864648.jpg','2025-02-19 01:09:44','2025-02-19 06:51:34'),(2,'Khám và điều trị các bệnh về Da liễuuuu','uploads/1739952593_67b591d1d48f9.jpg','2025-02-19 01:09:53','2025-03-16 06:51:43'),(3,'Điều trị nám – tàn nhang – bớt Ota – cafe','uploads/1739952608_67b591e0f0144.jpg','2025-02-19 01:10:08','2025-02-19 01:10:08'),(4,'Xóa xăm triệt để và không để lại sẹo','uploads/1739952617_67b591e9adb8c.jpg','2025-02-19 01:10:17','2025-02-19 01:10:17'),(5,'Điều trị triệt lông bằng công nghệ IPL','uploads/1739952626_67b591f291082.jpg','2025-02-19 01:10:26','2025-02-19 01:10:26'),(6,'Điều trị các loại sẹo (lồi, lõm, xấu.......)','uploads/1739952634_67b591face4df.jpg','2025-02-19 01:10:34','2025-03-16 20:17:29'),(7,'Điều trị Laser nốt ruồi – hạt cơm – u nhú','uploads/1739952641_67b59201ecd6f.jpg','2025-02-19 01:10:41','2025-02-19 01:10:41'),(8,'Phẫu thuật nốt ruồi – bớt bẩm sinh – các u da lành','uploads/1739952649_67b5920935632.jpg','2025-02-19 01:10:49','2025-03-14 07:53:35');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supports`
--

DROP TABLE IF EXISTS `supports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `supports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `age` int NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supports`
--

LOCK TABLES `supports` WRITE;
/*!40000 ALTER TABLE `supports` DISABLE KEYS */;
INSERT INTO `supports` VALUES (1,'Trấn Thành',23,'0816260406','sssssssssssssssssss@gmail.com','sssssssssssssssssssss','2025-01-16 02:14:18','2025-01-16 02:14:18'),(2,'Trinh Luong',24,'0816260406','tauseed@test.com','22222222222222222222222','2025-01-16 02:44:25','2025-01-16 02:44:25'),(3,'aaaa',2333,'0816260406','a123@gmail.com','aaa','2025-01-16 02:54:53','2025-01-16 02:54:53'),(4,'Nguyễn Thị D',28,'0816260406','trickshotsvn@gmail.com','hhhhhhhhhhhhhhhhhhhh','2025-01-17 02:43:20','2025-01-17 02:43:20'),(5,'Nguyễn Thcus Thùy Tiên',23,'0816260406','luong.trinh.yp464@gmail.com','22222','2025-01-17 02:43:51','2025-01-17 02:43:51'),(6,'Trinh Phúc',32,'0816260406','trickshotsvn@gmail.com','Tôi mất FB','2025-02-06 10:52:59','2025-02-06 10:52:59'),(7,'Lê Dương Bảo Lâm',33,'0816260406','sdc@gmail.com','hahaahhaahha','2025-02-23 10:38:06','2025-02-23 10:38:06'),(8,'Trinh Luong',40,'0816260406','tauseed@test.com','Tôi muốn biết cách đặt lịch khám','2025-03-16 07:04:50','2025-03-16 07:04:50');
/*!40000 ALTER TABLE `supports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admindoctor',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Trịnh Phúc Lương','luong.trinh.yp464@gmail.com','2025-01-09 17:00:00','$2y$10$WRcm6NcHNVVW7kE5DMl9OeGTntKKL0bXrl3OFUV2PGOS8DJpjhDCS','w8Hq80YEFPAUAmNwynX4Y22C7PKtChufWroIuFgiQa7zJmvEe8i6EWFAexhc','2025-01-09 17:00:00','2025-01-09 17:00:00','admin'),(3,'Nguyễn Văn B','b123@gmail.com',NULL,'$2y$10$AZ4GgbAdQqckRoSNirTVPugtdx/8LWgT7tprvTtH/v6z54SCgJnvG',NULL,'2025-02-06 10:54:54','2025-02-06 10:54:54','admindoctor'),(4,'Nguyễn Văn C','c123@gmail.com',NULL,'$2y$10$2iSNC1oUBoP3Eij64fAH4.7xCIRL7ZJyYrJ70ww/rNL7ExUamYYBq',NULL,'2025-02-08 08:44:44','2025-02-08 08:44:44','admindoctor'),(5,'Nguyễn Thị D','d123@gmail.com',NULL,'$2y$10$O8lxVqKyCGnrV8TCl2nr1./4gYW0fzTGHW1/FRclknbMclhFugLs2',NULL,'2025-02-12 01:07:23','2025-03-15 19:22:00','admindoctor'),(8,'Nguyễn Thị E','e123@gmail.com',NULL,'$2y$10$vmV4wq3Ga9dUxHgAWEcebe1nLFTQbp3s/croU8wnwfPuSGGhRm5n6',NULL,'2025-03-15 19:25:57','2025-03-15 19:25:57','admindoctor'),(10,'Trịnh Xuân F','f123@gmail.com',NULL,'$2y$10$UV1shhu2BSiTRcuejwum1.FEc3qLP7lZRTBd9.MuzWCFeYBFNx6XW',NULL,'2025-03-15 19:55:33','2025-03-15 19:55:33','admindoctor'),(11,'Trần Xuân H','h123@gmail.com',NULL,'$2y$10$MlKK63IPArCMQrkBWfcCw.eUGtU5aNdEM6r3B1SW5bxCV5wzyz/UK',NULL,'2025-03-16 02:45:20','2025-03-16 02:45:20','admindoctor'),(12,'Lê Minh I','i123@gmail.com',NULL,'$2y$10$zYFpN1MoEJAWz3AhMRnLZ.FRba4ciKpGc7NTMsksGijlaEV1Bmzz6',NULL,'2025-03-16 02:46:19','2025-03-16 02:46:19','admindoctor'),(13,'Phan Văn J','j123@gmail.com',NULL,'$2y$10$SssZL7QUVJvLnzj2NtrKAu6X2bO.Zgc1X8b847pSUlWw7bf2P6wTW',NULL,'2025-03-16 02:48:02','2025-03-16 02:48:02','admindoctor'),(14,'Lê Bá K','k123@gmail.com',NULL,'$2y$10$L50Bq2w/MUb5K0CoCZULy.KEYthpHmcHuDiOIuQhakbUHo6kaFoqm',NULL,'2025-03-16 02:49:23','2025-03-16 02:49:23','admindoctor'),(15,'Lê Văn L','l123@gmail.com',NULL,'$2y$10$Hhwqdl/yhJbarmdzYICcq.TihtN90WNimAVLbE7FNvChUy00s0Y5O',NULL,'2025-03-16 02:51:07','2025-03-16 02:51:07','admindoctor'),(16,'Trịnh Thị M','m123@gmail.com',NULL,'$2y$10$D/X0CocicsS5B.QV99.AdOcfIelz2dDiZ6CIuxVsIHaE4Eu.D7iAi',NULL,'2025-03-16 02:53:08','2025-03-16 02:53:08','admindoctor'),(17,'Trương Thị N','n123@gmail.com',NULL,'$2y$10$PFXXr31h8PIAAIm6FH5JT.I.4i2ktcMYGOnKKokNXMV1Dc3mmCJ6e',NULL,'2025-03-16 02:54:06','2025-03-16 02:54:06','admindoctor'),(18,'Lê Minh O','o123@gmail.com',NULL,'$2y$10$o244GcUZlPQnF/xlwDD4gOMaeoQtscxK3EKpvnIP256EKI7URPqfy',NULL,'2025-03-16 02:54:45','2025-03-16 02:54:45','admindoctor'),(19,'Nguyễn Văn A','a123@gmail.com',NULL,'$2y$10$geDKNEd5s1wR1zIbBQqXSe/UkkPw3ScvYXbq92n2GA4dMGvW5mkhW',NULL,'2025-03-16 08:32:00','2025-03-16 08:32:00','admindoctor'),(21,'Trịnh Trần Phương G','g123@gmail.com',NULL,'$2y$10$INpzunu3PFwZRHPasBW6duwtNWbKak7SMiaaTmfZV0sZJw8tw7Ivm',NULL,'2025-03-16 08:36:55','2025-03-16 08:36:55','admindoctor');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'laravel'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-03-21 23:08:59

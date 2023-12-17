-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2023 at 10:25 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sp_release_v8`
--

-- --------------------------------------------------------

--
-- Table structure for table `sp_accounts`
--

DROP TABLE IF EXISTS `sp_accounts`;
CREATE TABLE IF NOT EXISTS `sp_accounts` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ids` varchar(255) DEFAULT NULL,
  `module` varchar(255) DEFAULT NULL,
  `social_network` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `login_type` int(11) DEFAULT NULL,
  `can_post` int(1) DEFAULT NULL,
  `pid` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `token` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `tmp` text DEFAULT NULL,
  `data` mediumtext DEFAULT NULL,
  `proxy` longtext DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `changed` int(11) DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_blogs`
--

DROP TABLE IF EXISTS `sp_blogs`;
CREATE TABLE IF NOT EXISTS `sp_blogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` varchar(32) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `desc` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `tags` text DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `status` int(1) DEFAULT 1,
  `created` int(11) DEFAULT NULL,
  `changed` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_captions`
--

DROP TABLE IF EXISTS `sp_captions`;
CREATE TABLE IF NOT EXISTS `sp_captions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` varchar(255) NOT NULL,
  `team_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `changed` int(11) NOT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_coinpayments_history`
--

DROP TABLE IF EXISTS `sp_coinpayments_history`;
CREATE TABLE IF NOT EXISTS `sp_coinpayments_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` varchar(32) DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  `plan_id` int(11) DEFAULT NULL,
  `plan_by` int(11) DEFAULT NULL,
  `txn_id` varchar(255) DEFAULT NULL,
  `coin_amount` float DEFAULT NULL,
  `amount` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_faqs`
--

DROP TABLE IF EXISTS `sp_faqs`;
CREATE TABLE IF NOT EXISTS `sp_faqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` varchar(32) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `status` int(1) DEFAULT 1,
  `changed` int(11) DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_files`
--

DROP TABLE IF EXISTS `sp_files`;
CREATE TABLE IF NOT EXISTS `sp_files` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ids` mediumtext DEFAULT NULL,
  `is_folder` int(1) NOT NULL DEFAULT 0,
  `pid` int(11) DEFAULT 0,
  `team_id` int(11) DEFAULT NULL,
  `name` mediumtext DEFAULT NULL,
  `file` mediumtext DEFAULT NULL,
  `type` mediumtext DEFAULT NULL,
  `extension` mediumtext DEFAULT NULL,
  `detect` text DEFAULT NULL,
  `size` float DEFAULT NULL,
  `is_image` int(11) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `note` mediumtext DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_groups`
--

DROP TABLE IF EXISTS `sp_groups`;
CREATE TABLE IF NOT EXISTS `sp_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` varchar(32) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `data` longtext DEFAULT NULL,
  `changed` int(11) DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_language`
--

DROP TABLE IF EXISTS `sp_language`;
CREATE TABLE IF NOT EXISTS `sp_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` varchar(32) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `slug` varchar(32) DEFAULT NULL,
  `text` text DEFAULT NULL,
  `custom` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12377 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_language_category`
--

DROP TABLE IF EXISTS `sp_language_category`;
CREATE TABLE IF NOT EXISTS `sp_language_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` varchar(32) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `dir` varchar(3) NOT NULL,
  `is_default` int(1) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_options`
--

DROP TABLE IF EXISTS `sp_options`;
CREATE TABLE IF NOT EXISTS `sp_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `value` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=157 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_payment_history`
--

DROP TABLE IF EXISTS `sp_payment_history`;
CREATE TABLE IF NOT EXISTS `sp_payment_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` varchar(32) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `plan` int(11) DEFAULT NULL,
  `type` varchar(32) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `by` int(1) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_payment_subscriptions`
--

DROP TABLE IF EXISTS `sp_payment_subscriptions`;
CREATE TABLE IF NOT EXISTS `sp_payment_subscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` text DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `plan` int(11) DEFAULT NULL,
  `by` int(1) DEFAULT NULL,
  `type` text DEFAULT NULL,
  `subscription_id` text DEFAULT NULL,
  `customer_id` text DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_plans`
--

DROP TABLE IF EXISTS `sp_plans`;
CREATE TABLE IF NOT EXISTS `sp_plans` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ids` varchar(32) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `price_monthly` float DEFAULT NULL,
  `price_annually` float DEFAULT NULL,
  `plan_type` int(1) DEFAULT NULL,
  `number_accounts` int(11) DEFAULT NULL,
  `trial_day` float DEFAULT NULL,
  `featured` int(11) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `permissions` mediumtext DEFAULT NULL,
  `data` mediumtext DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sp_plans`
--

INSERT INTO `sp_plans` (`id`, `ids`, `name`, `description`, `type`, `price_monthly`, `price_annually`, `plan_type`, `number_accounts`, `trial_day`, `featured`, `position`, `permissions`, `data`, `status`) VALUES
(1, 'de39a2bd850', 'Free & Trial', 'Try us out today', 1, 0, 0, 1, 100, -1, 0, 0, '{\"dashboard\":\"1\",\"post\":\"1\",\"facebook_post\":\"1\",\"google_business_profile_post\":\"1\",\"instagram_post\":\"1\",\"linkedin_post\":\"1\",\"ok_post\":\"1\",\"pinterest_post\":\"1\",\"reddit_post\":\"1\",\"telegram_post\":\"1\",\"tumblr_post\":\"1\",\"twitter_post\":\"1\",\"vk_post\":\"1\",\"youtube_post\":\"1\",\"bulk_post\":\"1\",\"rss_post\":\"1\",\"analytics\":\"1\",\"facebook_analytics\":\"1\",\"instagram_analytics\":\"1\",\"twitter_analytics\":\"1\",\"whatsapp\":\"1\",\"whatsapp_profile\":\"1\",\"whatsapp_bulk\":\"1\",\"whatsapp_autoresponder\":\"1\",\"whatsapp_chatbot\":\"1\",\"whatsapp_export_participants\":\"1\",\"whatsapp_contact\":\"1\",\"whatsapp_api\":\"1\",\"whatsapp_button_template\":\"1\",\"whatsapp_list_message_template\":\"1\",\"whatsapp_send_media\":\"1\",\"whatsapp_autoresponser_delay\":\"1\",\"whatsapp_chatbot_item_limit\":\"200\",\"whatsapp_bulk_schedule_by_times\":\"1\",\"whatsapp_bulk_max_run\":\"1000\",\"whatsapp_bulk_max_contact_group\":\"1000\",\"whatsapp_bulk_max_phone_numbers\":\"600000\",\"whatsapp_message_per_month\":\"1000000\",\"drafts\":\"1\",\"schedules\":\"1\",\"account_manager\":\"1\",\"whatsapp_profiles\":\"1\",\"facebook_profiles\":\"1\",\"facebook_groups\":\"1\",\"facebook_pages\":\"1\",\"instagram_profiles\":\"1\",\"twitter_profiles\":\"1\",\"youtube_profiles\":\"1\",\"google_business_profiles\":\"1\",\"linkedin_profiles\":\"1\",\"linkedin_pages\":\"1\",\"pinterest_boards\":\"1\",\"pinterest_profiles\":\"1\",\"reddit_profiles\":\"1\",\"tumblr_blogs\":\"1\",\"telegram_channels\":\"1\",\"telegram_groups\":\"1\",\"ok_groups\":\"1\",\"vk_profiles\":\"1\",\"vk_pages\":\"1\",\"vk_groups\":\"1\",\"file_manager\":\"1\",\"file_manager_google_drive\":\"1\",\"file_manager_dropbox\":\"1\",\"file_manager_onedrive\":\"1\",\"file_manager_photo\":\"1\",\"file_manager_video\":\"1\",\"file_manager_other_type\":\"1\",\"file_manager_image_editor\":\"1\",\"max_storage_size\":\"10000\",\"max_file_size\":\"100\",\"tools\":\"1\",\"watermark\":\"1\",\"group_manager\":\"1\",\"caption\":\"1\",\"teams\":\"1\",\"proxies\":\"1\",\"shortlink\":\"1\",\"openai\":\"1\",\"openai_content\":\"1\",\"openai_image\":\"1\",\"openai_limit_tokens\":\"1000000\",\"plan_type\":1,\"number_accounts\":\"100\"}', NULL, 1),
(2, 'de39a2bd851', 'Standard', 'Affordable and accessible', 2, 29, 19, 2, 3, 0, 0, 5, '{\"dashboard\":\"1\",\"post\":\"1\",\"facebook_post\":\"1\",\"instagram_post\":\"1\",\"linkedin_post\":\"1\",\"ok_post\":\"1\",\"pinterest_post\":\"1\",\"reddit_post\":\"1\",\"telegram_post\":\"1\",\"tumblr_post\":\"1\",\"twitter_post\":\"1\",\"vk_post\":\"1\",\"youtube_post\":\"1\",\"bulk_post\":\"1\",\"rss_post\":\"1\",\"analytics\":\"1\",\"facebook_analytics\":\"1\",\"instagram_analytics\":\"1\",\"twitter_analytics\":\"1\",\"whatsapp\":\"1\",\"whatsapp_profile\":\"1\",\"whatsapp_bulk\":\"1\",\"whatsapp_autoresponder\":\"1\",\"whatsapp_chatbot\":\"1\",\"whatsapp_export_participants\":\"1\",\"whatsapp_contact\":\"1\",\"whatsapp_api\":\"1\",\"whatsapp_button_template\":\"1\",\"whatsapp_list_message_template\":\"1\",\"whatsapp_send_media\":\"1\",\"whatsapp_autoresponser_delay\":\"1\",\"whatsapp_chatbot_item_limit\":\"50\",\"whatsapp_bulk_schedule_by_times\":\"1\",\"whatsapp_bulk_max_run\":\"10\",\"whatsapp_bulk_max_contact_group\":\"50\",\"whatsapp_bulk_max_phone_numbers\":\"5000\",\"whatsapp_message_per_month\":\"50000\",\"drafts\":\"1\",\"schedules\":\"1\",\"account_manager\":\"1\",\"whatsapp_profiles\":\"1\",\"facebook_profiles\":\"1\",\"facebook_groups\":\"1\",\"facebook_pages\":\"1\",\"instagram_profiles\":\"1\",\"twitter_profiles\":\"1\",\"youtube_profiles\":\"1\",\"google_business_profiles\":\"1\",\"linkedin_profiles\":\"1\",\"linkedin_pages\":\"1\",\"pinterest_boards\":\"1\",\"pinterest_profiles\":\"1\",\"reddit_profiles\":\"1\",\"tumblr_blogs\":\"1\",\"telegram_channels\":\"1\",\"telegram_groups\":\"1\",\"ok_groups\":\"1\",\"vk_profiles\":\"1\",\"vk_pages\":\"1\",\"vk_groups\":\"1\",\"file_manager\":\"1\",\"file_manager_google_drive\":\"1\",\"file_manager_dropbox\":\"1\",\"file_manager_onedrive\":\"1\",\"file_manager_photo\":\"1\",\"file_manager_video\":\"1\",\"file_manager_other_type\":\"1\",\"file_manager_image_editor\":\"1\",\"max_storage_size\":\"100\",\"max_file_size\":\"2\",\"tools\":\"1\",\"watermark\":\"1\",\"group_manager\":\"1\",\"caption\":\"1\",\"teams\":\"1\",\"proxies\":\"1\",\"shortlink\":\"1\",\"openai\":\"1\",\"openai_content\":\"1\",\"openai_image\":\"1\",\"openai_limit_tokens\":\"1000\",\"plan_type\":2,\"number_accounts\":\"3\"}', NULL, 1),
(3, 'de39a2bd852', 'Premium', 'Elevate your experience', 2, 39, 29, 1, 6, 0, 1, 10, '{\"dashboard\":\"1\",\"post\":\"1\",\"facebook_post\":\"1\",\"google_business_profile_post\":\"1\",\"instagram_post\":\"1\",\"linkedin_post\":\"1\",\"ok_post\":\"1\",\"pinterest_post\":\"1\",\"reddit_post\":\"1\",\"telegram_post\":\"1\",\"tumblr_post\":\"1\",\"twitter_post\":\"1\",\"vk_post\":\"1\",\"youtube_post\":\"1\",\"bulk_post\":\"1\",\"rss_post\":\"1\",\"analytics\":\"1\",\"facebook_analytics\":\"1\",\"instagram_analytics\":\"1\",\"twitter_analytics\":\"1\",\"whatsapp\":\"1\",\"whatsapp_profile\":\"1\",\"whatsapp_bulk\":\"1\",\"whatsapp_autoresponder\":\"1\",\"whatsapp_chatbot\":\"1\",\"whatsapp_export_participants\":\"1\",\"whatsapp_contact\":\"1\",\"whatsapp_api\":\"1\",\"whatsapp_button_template\":\"1\",\"whatsapp_list_message_template\":\"1\",\"whatsapp_send_media\":\"1\",\"whatsapp_autoresponser_delay\":\"1\",\"whatsapp_chatbot_item_limit\":\"20\",\"whatsapp_bulk_schedule_by_times\":\"1\",\"whatsapp_bulk_max_run\":\"5\",\"whatsapp_bulk_max_contact_group\":\"5\",\"whatsapp_bulk_max_phone_numbers\":\"5000\",\"whatsapp_message_per_month\":\"10000\",\"drafts\":\"1\",\"schedules\":\"1\",\"account_manager\":\"1\",\"whatsapp_profiles\":\"1\",\"facebook_profiles\":\"1\",\"facebook_groups\":\"1\",\"facebook_pages\":\"1\",\"instagram_profiles\":\"1\",\"twitter_profiles\":\"1\",\"youtube_profiles\":\"1\",\"google_business_profiles\":\"1\",\"linkedin_profiles\":\"1\",\"linkedin_pages\":\"1\",\"pinterest_boards\":\"1\",\"pinterest_profiles\":\"1\",\"reddit_profiles\":\"1\",\"tumblr_blogs\":\"1\",\"telegram_channels\":\"1\",\"telegram_groups\":\"1\",\"ok_groups\":\"1\",\"vk_profiles\":\"1\",\"vk_pages\":\"1\",\"vk_groups\":\"1\",\"file_manager\":\"1\",\"file_manager_google_drive\":\"1\",\"file_manager_dropbox\":\"1\",\"file_manager_onedrive\":\"1\",\"file_manager_photo\":\"1\",\"file_manager_video\":\"1\",\"file_manager_other_type\":\"1\",\"file_manager_image_editor\":\"1\",\"max_storage_size\":\"500\",\"max_file_size\":\"5\",\"tools\":\"1\",\"watermark\":\"1\",\"group_manager\":\"1\",\"caption\":\"1\",\"teams\":\"1\",\"proxies\":\"1\",\"shortlink\":\"1\",\"openai\":\"1\",\"openai_content\":\"1\",\"openai_image\":\"1\",\"openai_limit_tokens\":\"10000\",\"plan_type\":1,\"number_accounts\":\"6\"}', NULL, 1),
(4, 'de39a2bd853', 'Entrepreneur', 'Your path to success', 2, 69, 59, 1, 10, 0, 0, 15, '{\"dashboard\":\"1\",\"post\":\"1\",\"facebook_post\":\"1\",\"google_business_profile_post\":\"1\",\"instagram_post\":\"1\",\"linkedin_post\":\"1\",\"ok_post\":\"1\",\"pinterest_post\":\"1\",\"reddit_post\":\"1\",\"telegram_post\":\"1\",\"tumblr_post\":\"1\",\"twitter_post\":\"1\",\"vk_post\":\"1\",\"youtube_post\":\"1\",\"bulk_post\":\"1\",\"rss_post\":\"1\",\"analytics\":\"1\",\"facebook_analytics\":\"1\",\"instagram_analytics\":\"1\",\"twitter_analytics\":\"1\",\"whatsapp\":\"1\",\"whatsapp_profile\":\"1\",\"whatsapp_bulk\":\"1\",\"whatsapp_autoresponder\":\"1\",\"whatsapp_chatbot\":\"1\",\"whatsapp_export_participants\":\"1\",\"whatsapp_contact\":\"1\",\"whatsapp_api\":\"1\",\"whatsapp_button_template\":\"1\",\"whatsapp_list_message_template\":\"1\",\"whatsapp_send_media\":\"1\",\"whatsapp_autoresponser_delay\":\"1\",\"whatsapp_chatbot_item_limit\":\"50\",\"whatsapp_bulk_schedule_by_times\":\"1\",\"whatsapp_bulk_max_run\":\"100\",\"whatsapp_bulk_max_contact_group\":\"100\",\"whatsapp_bulk_max_phone_numbers\":\"50000\",\"whatsapp_message_per_month\":\"100000\",\"drafts\":\"1\",\"schedules\":\"1\",\"account_manager\":\"1\",\"whatsapp_profiles\":\"1\",\"facebook_profiles\":\"1\",\"facebook_groups\":\"1\",\"facebook_pages\":\"1\",\"instagram_profiles\":\"1\",\"twitter_profiles\":\"1\",\"youtube_profiles\":\"1\",\"google_business_profiles\":\"1\",\"linkedin_profiles\":\"1\",\"linkedin_pages\":\"1\",\"pinterest_boards\":\"1\",\"pinterest_profiles\":\"1\",\"reddit_profiles\":\"1\",\"tumblr_blogs\":\"1\",\"telegram_channels\":\"1\",\"telegram_groups\":\"1\",\"ok_groups\":\"1\",\"vk_profiles\":\"1\",\"vk_pages\":\"1\",\"vk_groups\":\"1\",\"file_manager\":\"1\",\"file_manager_google_drive\":\"1\",\"file_manager_dropbox\":\"1\",\"file_manager_onedrive\":\"1\",\"file_manager_photo\":\"1\",\"file_manager_video\":\"1\",\"file_manager_other_type\":\"1\",\"file_manager_image_editor\":\"1\",\"max_storage_size\":\"1000\",\"max_file_size\":\"10\",\"tools\":\"1\",\"watermark\":\"1\",\"group_manager\":\"1\",\"caption\":\"1\",\"teams\":\"1\",\"proxies\":\"1\",\"shortlink\":\"1\",\"openai\":\"1\",\"openai_content\":\"1\",\"openai_image\":\"1\",\"openai_limit_tokens\":\"50000\",\"plan_type\":1,\"number_accounts\":\"10\"}', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sp_posts`
--

DROP TABLE IF EXISTS `sp_posts`;
CREATE TABLE IF NOT EXISTS `sp_posts` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ids` varchar(32) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `social_network` varchar(100) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `function` varchar(50) NOT NULL,
  `api_type` int(1) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `data` longtext DEFAULT NULL,
  `time_post` int(11) DEFAULT NULL,
  `delay` int(11) DEFAULT NULL,
  `repost_frequency` int(11) DEFAULT NULL,
  `repost_until` int(11) DEFAULT NULL,
  `result` text DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `changed` int(11) DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_proxies`
--

DROP TABLE IF EXISTS `sp_proxies`;
CREATE TABLE IF NOT EXISTS `sp_proxies` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ids` varchar(32) DEFAULT NULL,
  `team_id` int(11) DEFAULT 0,
  `is_system` int(11) DEFAULT NULL,
  `proxy` varchar(255) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `limit` float DEFAULT NULL,
  `plans` varchar(255) DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `changed` int(11) DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_purchases`
--

DROP TABLE IF EXISTS `sp_purchases`;
CREATE TABLE IF NOT EXISTS `sp_purchases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` varchar(32) DEFAULT NULL,
  `item_id` varchar(32) DEFAULT NULL,
  `is_main` int(11) DEFAULT NULL,
  `purchase_code` varchar(64) DEFAULT NULL,
  `version` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_smtp`
--

DROP TABLE IF EXISTS `sp_smtp`;
CREATE TABLE IF NOT EXISTS `sp_smtp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` varchar(32) DEFAULT NULL,
  `server` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  `encryption` varchar(32) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_team`
--

DROP TABLE IF EXISTS `sp_team`;
CREATE TABLE IF NOT EXISTS `sp_team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` mediumtext DEFAULT NULL,
  `owner` int(11) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `permissions` longtext DEFAULT NULL,
  `data` longtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sp_team`
--

INSERT INTO `sp_team` (`id`, `ids`, `owner`, `pid`, `permissions`, `data`) VALUES
(1, 'TEAM_IDS', 1, 1, '{\"dashboard\":\"1\",\"post\":\"1\",\"facebook_post\":\"1\",\"google_business_profile_post\":\"1\",\"instagram_post\":\"1\",\"linkedin_post\":\"1\",\"ok_post\":\"1\",\"pinterest_post\":\"1\",\"reddit_post\":\"1\",\"telegram_post\":\"1\",\"tumblr_post\":\"1\",\"twitter_post\":\"1\",\"vk_post\":\"1\",\"youtube_post\":\"1\",\"bulk_post\":\"1\",\"rss_post\":\"1\",\"analytics\":\"1\",\"facebook_analytics\":\"1\",\"instagram_analytics\":\"1\",\"twitter_analytics\":\"1\",\"whatsapp\":\"1\",\"whatsapp_profile\":\"1\",\"whatsapp_bulk\":\"1\",\"whatsapp_autoresponder\":\"1\",\"whatsapp_chatbot\":\"1\",\"whatsapp_export_participants\":\"1\",\"whatsapp_contact\":\"1\",\"whatsapp_api\":\"1\",\"whatsapp_button_template\":\"1\",\"whatsapp_list_message_template\":\"1\",\"whatsapp_send_media\":\"1\",\"whatsapp_autoresponser_delay\":\"1\",\"whatsapp_chatbot_item_limit\":\"200\",\"whatsapp_bulk_schedule_by_times\":\"1\",\"whatsapp_bulk_max_run\":\"1000\",\"whatsapp_bulk_max_contact_group\":\"1000\",\"whatsapp_bulk_max_phone_numbers\":\"600000\",\"whatsapp_message_per_month\":\"1000000\",\"drafts\":\"1\",\"schedules\":\"1\",\"account_manager\":\"1\",\"whatsapp_profiles\":\"1\",\"facebook_profiles\":\"1\",\"facebook_groups\":\"1\",\"facebook_pages\":\"1\",\"instagram_profiles\":\"1\",\"twitter_profiles\":\"1\",\"youtube_profiles\":\"1\",\"google_business_profiles\":\"1\",\"linkedin_profiles\":\"1\",\"linkedin_pages\":\"1\",\"pinterest_profiles\":\"1\",\"pinterest_boards\":\"1\",\"reddit_profiles\":\"1\",\"tumblr_blogs\":\"1\",\"telegram_channels\":\"1\",\"telegram_groups\":\"1\",\"ok_groups\":\"1\",\"vk_profiles\":\"1\",\"vk_pages\":\"1\",\"vk_groups\":\"1\",\"file_manager\":\"1\",\"file_manager_google_drive\":\"1\",\"file_manager_dropbox\":\"1\",\"file_manager_onedrive\":\"1\",\"file_manager_photo\":\"1\",\"file_manager_video\":\"1\",\"file_manager_other_type\":\"1\",\"file_manager_image_editor\":\"1\",\"max_storage_size\":\"10000\",\"max_file_size\":\"100\",\"tools\":\"1\",\"watermark\":\"1\",\"group_manager\":\"1\",\"caption\":\"1\",\"teams\":\"1\",\"proxies\":\"1\",\"shortlink\":\"1\",\"openai\":\"1\",\"openai_content\":\"1\",\"openai_image\":\"1\",\"openai_limit_tokens\":\"1000000\",\"number_accounts\":\"100\"}', '{\"facebook_post_success_count\":0,\"facebook_post_error_count\":0,\"facebook_post_media_count\":0,\"facebook_post_link_count\":0,\"facebook_post_text_count\":0,\"instagram_post_success_count\":0,\"instagram_post_error_count\":0,\"instagram_post_media_count\":0,\"instagram_post_link_count\":0,\"instagram_post_text_count\":0,\"twitter_post_success_count\":0,\"twitter_post_error_count\":0,\"twitter_post_media_count\":0,\"twitter_post_link_count\":0,\"twitter_post_text_count\":0,\"youtube_post_success_count\":0,\"youtube_post_error_count\":0,\"youtube_post_media_count\":0,\"youtube_post_link_count\":0,\"youtube_post_text_count\":0,\"google_business_profile_post_success_count\":0,\"google_business_profile_post_error_count\":0,\"google_business_profile_post_media_count\":0,\"google_business_profile_post_link_count\":0,\"google_business_profile_post_text_count\":0,\"linkedin_post_success_count\":0,\"linkedin_post_error_count\":0,\"linkedin_post_media_count\":0,\"linkedin_post_link_count\":0,\"linkedin_post_text_count\":0,\"pinterest_post_success_count\":0,\"pinterest_post_error_count\":0,\"pinterest_post_media_count\":0,\"pinterest_post_link_count\":0,\"pinterest_post_text_count\":0,\"reddit_post_success_count\":0,\"reddit_post_error_count\":0,\"reddit_post_media_count\":0,\"reddit_post_link_count\":0,\"reddit_post_text_count\":0,\"tumblr_post_success_count\":0,\"tumblr_post_error_count\":0,\"tumblr_post_media_count\":0,\"tumblr_post_link_count\":0,\"tumblr_post_text_count\":0,\"telegram_post_success_count\":0,\"telegram_post_error_count\":0,\"telegram_post_media_count\":0,\"telegram_post_link_count\":0,\"telegram_post_text_count\":0,\"vk_post_success_count\":0,\"vk_post_error_count\":0,\"vk_post_media_count\":0,\"vk_post_link_count\":0,\"vk_post_text_count\":0,\"ok_post_success_count\":0,\"ok_post_error_count\":0,\"ok_post_media_count\":0,\"ok_post_link_count\":0,\"ok_post_text_count\":0,\"shortlink_status\":0,\"bulk_delay\":60,\"bitly_access_token\":\"\",\"openai_usage_tokens\":258,\"watermark_mask\":\"\",\"watermark_size\":30,\"watermark_opacity\":70,\"watermark_position\":\"lb\",\"telegram_post_count\":0}');

-- --------------------------------------------------------

--
-- Table structure for table `sp_team_member`
--

DROP TABLE IF EXISTS `sp_team_member`;
CREATE TABLE IF NOT EXISTS `sp_team_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` mediumtext DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `permissions` longtext DEFAULT NULL,
  `pending` text DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_users`
--

DROP TABLE IF EXISTS `sp_users`;
CREATE TABLE IF NOT EXISTS `sp_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` mediumtext DEFAULT NULL,
  `pid` text DEFAULT NULL,
  `is_admin` int(1) DEFAULT NULL,
  `role` int(11) DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `plan` int(11) DEFAULT NULL,
  `expiration_date` int(11) DEFAULT NULL,
  `timezone` mediumtext DEFAULT NULL,
  `language` varchar(30) DEFAULT NULL,
  `login_type` mediumtext DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `data` mediumtext DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `last_login` int(11) DEFAULT NULL,
  `recovery_key` varchar(32) DEFAULT NULL,
  `changed` int(11) DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sp_users`
--

INSERT INTO `sp_users` (`id`, `ids`, `pid`, `is_admin`, `role`, `fullname`, `username`, `email`, `password`, `plan`, `expiration_date`, `timezone`, `language`, `login_type`, `avatar`, `data`, `status`, `last_login`, `recovery_key`, `changed`, `created`) VALUES
(1, 'ADMIN_IDS', NULL, 1, 0, 'ADMIN_FULLNAME', 'ADMIN_USERNAME', 'ADMIN_EMAIL', 'ADMIN_PASSWORD', 1, 2145916800, 'ADMIN_TIMEZONE', 'en', 'direct', 'https://ui-avatars.com/api/?name=Hi&background=0674ec&color=fff', NULL, 2, NULL, NULL, 1681286037, 1681286037);

-- --------------------------------------------------------

--
-- Table structure for table `sp_user_roles`
--

DROP TABLE IF EXISTS `sp_user_roles`;
CREATE TABLE IF NOT EXISTS `sp_user_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` varchar(32) NOT NULL,
  `name` varchar(255) NOT NULL,
  `permissions` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
COMMIT;

--
-- Estrutura da tabela `sp_rss`
--
DROP TABLE IF EXISTS `sp_rss`;
CREATE TABLE `sp_rss` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` text DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `name` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `url` text DEFAULT NULL,
  `data` text DEFAULT NULL,
  `next_action` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `changed` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sp_rss_accounts`
--
DROP TABLE IF EXISTS `sp_rss_accounts`;
CREATE TABLE `sp_rss_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` text DEFAULT NULL,
  `rss_id` int(11) DEFAULT NULL,
  `account_id` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sp_rss_posts`
--
DROP TABLE IF EXISTS `sp_rss_posts`;
CREATE TABLE `sp_rss_posts` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ids` text DEFAULT NULL,
  `rss_id` int(11) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `social_network` text DEFAULT NULL,
  `category` text DEFAULT NULL,
  `function` varchar(50) DEFAULT NULL,
  `api_type` int(1) DEFAULT NULL,
  `type` text DEFAULT NULL,
  `data` longtext DEFAULT NULL,
  `time_post` int(11) DEFAULT NULL,
  `delay` int(11) DEFAULT NULL,
  `repost_frequency` int(11) DEFAULT NULL,
  `repost_until` int(11) DEFAULT NULL,
  `result` text DEFAULT NULL,
  `link` text DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `changed` int(11) DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Estrutura da tabela `sp_twitter_analytics`
--
DROP TABLE IF EXISTS `sp_twitter_analytics`;
CREATE TABLE `sp_twitter_analytics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` varchar(32) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `account_id` varchar(32) DEFAULT NULL,
  `data` longtext DEFAULT NULL,
  `next_action` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sp_twitter_analytics_stats`
--
DROP TABLE IF EXISTS `sp_twitter_analytics_stats`;
CREATE TABLE `sp_twitter_analytics_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` varchar(32) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `account_id` varchar(32) DEFAULT NULL,
  `data` longtext DEFAULT NULL,
  `date` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Estrutura da tabela `sp_coupons`
--
DROP TABLE IF EXISTS `sp_coupons`;
CREATE TABLE `sp_coupons` (
  `id` int(11) NOT NULL,
  `ids` varchar(32) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(32) DEFAULT NULL,
  `by` int(11) DEFAULT '1',
  `price` float DEFAULT NULL,
  `expiration_date` int(11) DEFAULT NULL,
  `plans` text,
  `status` int(11) DEFAULT '1',
  `changed` int(11) DEFAULT NULL,
  `created` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- --------------------------------------------------------


--
-- Estrutura da tabela `sp_whatsapp_autoresponder`
--
DROP TABLE IF EXISTS `sp_whatsapp_autoresponder`;
CREATE TABLE `sp_whatsapp_autoresponder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` text DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `instance_id` text DEFAULT NULL,
  `type` int(1) DEFAULT NULL,
  `template` int(11) DEFAULT NULL,
  `caption` text DEFAULT NULL,
  `media` longtext DEFAULT NULL,
  `except` longtext DEFAULT NULL,
  `path` text DEFAULT NULL,
  `delay` int(11) DEFAULT NULL,
  `result` text DEFAULT NULL,
  `sent` int(11) DEFAULT NULL,
  `failed` int(11) DEFAULT NULL,
  `send_to` int(1) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `changed` int(11) DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sp_whatsapp_chatbot`
--
DROP TABLE IF EXISTS `sp_whatsapp_chatbot`;
CREATE TABLE `sp_whatsapp_chatbot` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ids` text DEFAULT NULL,
  `name` text DEFAULT NULL,
  `keywords` text DEFAULT NULL,
  `instance_id` text DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `type_search` int(11) DEFAULT 1,
  `template` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `caption` text DEFAULT NULL,
  `media` text DEFAULT NULL,
  `run` int(1) DEFAULT 1,
  `sent` int(11) DEFAULT NULL,
  `failed` int(11) DEFAULT NULL,
  `send_to` int(1) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `changed` int(11) DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sp_whatsapp_contacts`
--
DROP TABLE IF EXISTS `sp_whatsapp_contacts`;
CREATE TABLE `sp_whatsapp_contacts` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ids` varchar(32) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `changed` int(11) DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sp_whatsapp_phone_numbers`
--
DROP TABLE IF EXISTS `sp_whatsapp_phone_numbers`;
CREATE TABLE `sp_whatsapp_phone_numbers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` varchar(15) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `params` text DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sp_whatsapp_schedules`
--
DROP TABLE IF EXISTS `sp_whatsapp_schedules`;
CREATE TABLE `sp_whatsapp_schedules` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ids` varchar(32) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `accounts` text DEFAULT NULL,
  `next_account` int(11) DEFAULT NULL,
  `contact_id` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT 1,
  `template` int(11) DEFAULT NULL,
  `time_post` int(11) DEFAULT NULL,
  `min_delay` int(11) DEFAULT NULL,
  `schedule_time` varchar(255) DEFAULT NULL,
  `timezone` varchar(100) DEFAULT NULL,
  `max_delay` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `caption` text DEFAULT NULL,
  `media` text DEFAULT NULL,
  `sent` int(11) DEFAULT 0,
  `failed` int(11) DEFAULT 0,
  `result` text DEFAULT NULL,
  `run` int(11) DEFAULT 0,
  `status` int(11) DEFAULT NULL,
  `changed` int(11) DEFAULT NULL,
  `created` int(11) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sp_whatsapp_sessions`
--
DROP TABLE IF EXISTS `sp_whatsapp_sessions`;
CREATE TABLE `sp_whatsapp_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` varchar(32) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `instance_id` varchar(255) DEFAULT NULL,
  `data` longtext DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sp_whatsapp_stats`
--
DROP TABLE IF EXISTS `sp_whatsapp_stats`;
CREATE TABLE `sp_whatsapp_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` varchar(32) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `wa_total_sent_by_month` int(11) DEFAULT NULL,
  `wa_total_sent` int(11) DEFAULT NULL,
  `wa_chatbot_count` int(11) DEFAULT NULL,
  `wa_autoresponder_count` int(11) DEFAULT NULL,
  `wa_api_count` int(11) DEFAULT NULL,
  `wa_bulk_total_count` int(11) DEFAULT NULL,
  `wa_bulk_sent_count` int(11) DEFAULT NULL,
  `wa_bulk_failed_count` int(11) DEFAULT NULL,
  `wa_time_reset` int(11) DEFAULT NULL,
  `next_update` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sp_whatsapp_template`
--
DROP TABLE IF EXISTS `sp_whatsapp_template`;
CREATE TABLE `sp_whatsapp_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` varchar(32) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `type` int(1) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `data` longtext DEFAULT NULL,
  `changed` int(11) DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sp_whatsapp_webhook`
--
DROP TABLE IF EXISTS `sp_whatsapp_webhook`;
CREATE TABLE `sp_whatsapp_webhook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` text DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `instance_id` text DEFAULT NULL,
  `webhook_url` text DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;





CREATE TABLE IF NOT EXISTS `sp_account_sessions` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `team_id` int(11) DEFAULT NULL,
  `social_network` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `settings` longtext DEFAULT NULL,
  `cookies` longtext DEFAULT NULL,
  `last_modified` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `sp_tinder_activities`
--


CREATE TABLE IF NOT EXISTS `sp_tinder_activities` ( 
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` varchar(50) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `account_id` varchar(50) DEFAULT NULL,
  `data` text DEFAULT NULL,
  `next_action` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_tinder_activities_log`
--


CREATE TABLE IF NOT EXISTS `sp_tinder_activities_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` varchar(50) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `user_id` varchar(100) DEFAULT NULL,
  `media_id` text DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE IF NOT EXISTS `sp_tiktok_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` text,
  `team_id` int(11) DEFAULT NULL,
  `instance_id` text,
  `data` longtext,
  `proxy` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;

ALTER TABLE `sp_accounts` CHANGE `proxy` `proxy` INT NULL DEFAULT NULL;

ALTER TABLE `sp_language_category` ADD `auto_translate` VARCHAR(32) NULL AFTER `is_default`;



-- ----------------------------- MOD ----------------------

CREATE TABLE IF NOT EXISTS `sp_whatsapp_ai` ( 
	`id` INT NOT NULL AUTO_INCREMENT , 
	`team_id` INT NOT NULL , 
	`instance_id` TEXT NOT NULL , 
	`status` INT NOT NULL , 
	`apikey` TEXT NULL , 
	`temperature` TEXT NULL , 
	`model` TEXT NULL , 
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `sp_whatsapp_subscriber` (
  `id` int NOT NULL AUTO_INCREMENT,
  `team_id` int NOT NULL,
  `instance_id` TEXT NOT NULL,
  `chatid` text NOT NULL,
  `data` longtext,
  `tags` text NULL DEFAULT NULL,
  `kanban_group` text NULL DEFAULT NULL,
  `last_chatbot_id` int DEFAULT NULL,
  `last_response` longtext NULL DEFAULT NULL,
  `last_response_time` INT NULL DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `enabled_chatbot` int not null DEFAULT '1',  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `sp_whatsapp_messages` ( 
  `id` varchar(50) NOT NULL, 
  `instance_id` TEXT NOT NULL , 
  `remoteJid` TEXT NOT NULL,
  `contactId` TEXT NULL  , 
  `participant` TEXT NULL  , 
  `ack` TEXT NULL, 
  `read` BOOLEAN NOT NULL DEFAULT FALSE , 
  `fromMe` BOOLEAN NOT NULL DEFAULT FALSE , 
  `body` TEXT NOT NULL , 
  `mediaUrl` TEXT NULL , 
  `mediaType` TEXT NOT NULL , 
  `isDeleted` BOOLEAN NOT NULL DEFAULT FALSE , 
  `createdAt` INT NOT NULL , 
  `updatedAt` INT NOT NULL, 
  `dataJson` LONGTEXT NOT NULL , 
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE IF NOT EXISTS `sp_whatsapp_funnels` ( 
  `id` INT NOT NULL AUTO_INCREMENT , 
  `name` TEXT NOT NULL , 
  `desc` TEXT NOT NULL , 
  `order` INT NOT NULL , 
  `color` VARCHAR(20) NOT NULL , 
  `instance_id` TEXT NOT NULL , 
  `team_id` INT NOT NULL , 
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE IF NOT EXISTS `sp_whatsapp_fail_decode_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` text,
  `team_id` int(11) DEFAULT NULL,
  `instance_id` text,
  `type` int(11) DEFAULT NULL,
  `template` int(11) DEFAULT NULL,
  `caption` text,
  `media` longtext,
  `path` text,
  `result` text,
  `sent` int(11) DEFAULT NULL,
  `failed` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `changed` int(11) DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



ALTER TABLE `sp_whatsapp_chatbot` ADD `presenceTime` INT NOT NULL DEFAULT '0';
ALTER TABLE `sp_whatsapp_chatbot` ADD `presenceType` INT NOT NULL DEFAULT '0';
ALTER TABLE `sp_whatsapp_chatbot` ADD `nextBot` TEXT NULL;
ALTER TABLE `sp_whatsapp_chatbot` ADD `description` TEXT NULL;
ALTER TABLE `sp_whatsapp_chatbot` ADD `use_ai` INT NULL; 
ALTER TABLE `sp_whatsapp_chatbot` ADD `is_default` INT NULL;
ALTER TABLE `sp_whatsapp_chatbot` ADD `save_data` INT NOT NULL DEFAULT '0';
ALTER TABLE `sp_whatsapp_chatbot` ADD `inputname` TEXT NULL DEFAULT NULL; 
ALTER TABLE `sp_whatsapp_chatbot` ADD `api_config` LONGTEXT NULL;
ALTER TABLE `sp_whatsapp_chatbot` ADD `api_url` TEXT NULL;
ALTER TABLE `sp_whatsapp_chatbot` ADD `get_api_data` INT NOT NULL DEFAULT '1' ; 

ALTER TABLE `sp_whatsapp_phone_numbers` ADD `is_valid` INT NULL;
ALTER TABLE `sp_whatsapp_phone_numbers` CHANGE `phone` `phone` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL; 
ALTER TABLE `sp_whatsapp_schedules` CHANGE `result` `result` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NULL DEFAULT NULL;

ALTER TABLE `sp_whatsapp_subscriber` ADD `kanban_order` INT(11) NULL DEFAULT '0'; 
ALTER TABLE `sp_whatsapp_subscriber` CHANGE `data` `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL; 
ALTER TABLE `sp_whatsapp_subscriber` ADD `contact_data` LONGTEXT NULL DEFAULT NULL;
ALTER TABLE `sp_whatsapp_subscriber` ADD `unreadMessages` INT NULL DEFAULT '0';
ALTER TABLE `sp_whatsapp_subscriber` ADD `lastMessage` TEXT NULL;
ALTER TABLE `sp_whatsapp_subscriber` ADD `lastMessageTime` INT NULL; 


ALTER TABLE `sp_whatsapp_ai` ADD `key_enable` TEXT NULL;
ALTER TABLE `sp_whatsapp_ai` ADD `key_disable` TEXT NULL; 
ALTER TABLE `sp_whatsapp_messages` CHANGE `dataJson` `dataJson` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL; 

ALTER TABLE `sp_blogs` ADD `internal` INT NULL DEFAULT '0';
ALTER TABLE `sp_blogs` ADD `show_order` INT NOT NULL DEFAULT '0'; 




/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
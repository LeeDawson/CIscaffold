-- phpMyAdmin SQL Dump
-- version 4.4.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2017-12-11 23:23:26
-- 服务器版本： 5.6.35
-- PHP Version: 7.0.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jqd`
--

-- --------------------------------------------------------

--
-- 表的结构 `adv`
--

CREATE TABLE IF NOT EXISTS `adv` (
  `adv_id` int(10) unsigned NOT NULL,
  `adv_name` varchar(32) NOT NULL COMMENT '广告名称',
  `adv_text` text NOT NULL COMMENT '广告文字',
  `adv_meta` varchar(32) NOT NULL COMMENT '广告调用标签',
  `adv_imgs` text NOT NULL COMMENT '广告图 JSON',
  `is_show` smallint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `date_add` int(11) unsigned NOT NULL COMMENT '添加时间',
  `if_show` int(11) NOT NULL COMMENT '软删除字段'
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COMMENT='广告图表';

--
-- 转存表中的数据 `adv`
--

INSERT INTO `adv` (`adv_id`, `adv_name`, `adv_text`, `adv_meta`, `adv_imgs`, `is_show`, `date_add`, `if_show`) VALUES
(5, '首页轮播图', '首页轮播图', 'index_adv1', '[{"jumpurls":"http://baidu.com","sortranks":"0","description":"","imgs":"public/uploads/37d8ad1d95ec71679a51d1612608a317.jpg"},{"jumpurls":"","sortranks":"0","description":"","imgs":"public/uploads/61ddd0a5812f798d8c8e05508a012126.jpg"},{"jumpurls":"","sortranks":"0","description":"","imgs":"public/uploads/a887555e69efac25df1cddd997d16e15.jpg"}]', 1, 1510898598, 1),
(3, '优惠特价', '优惠特价123123', 'Special', '[{"jumpurls":"http://jqd.newcloudlive.com/wechat/Shop/goods_list/category_id/11","sortranks":"1","description":"阿斯达","imgs":"public/uploads/accc7d7e4abc9676428ffac159b436ed.jpg"}]', 1, 1510898454, 1),
(6, '预约试听', '预约试听', 'yuyueshiting', '[{"jumpurls":"","sortranks":"0","description":"","imgs":"public/uploads/ebefc985b1e2055592196c154434696d.jpg"}]', 1, 1510899881, 1),
(7, '商城首页轮播图', '123', 'index_adv2', '[{"jumpurls":"","sortranks":"1","description":"123","imgs":"public/uploads/cd4cd8bf7d82e0b625693bd438910ff3.jpg"},{"jumpurls":"","sortranks":"2","description":"","imgs":"public/uploads/823ec7cd20e64140e57e2d532621b9d4.jpg"},{"jumpurls":"","sortranks":"3","description":"","imgs":"public/uploads/0a1beb7c91fb646eae443c88b2790301.jpg"}]', 1, 1510906519, 1),
(8, '关于我们广告位', '关于我们广告位', 'about', '[{"jumpurls":"","sortranks":"0","description":"百度跳转","imgs":"public/uploads/9f27e32c0f80cf123de02f02f191bc59.JPG"},{"jumpurls":"","sortranks":"0","description":"跳转新浪","imgs":"public/uploads/6695f87a6a8cb6de4d5339baa091914c.JPG"},{"jumpurls":"","sortranks":"0","description":"跳转搜狐","imgs":"public/uploads/43e57a52e7e05a97d8b41e8db70284bf.JPG"}]', 1, 1511332666, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adv`
--
ALTER TABLE `adv`
  ADD PRIMARY KEY (`adv_id`),
  ADD UNIQUE KEY `unique_adv_meta` (`adv_meta`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adv`
--
ALTER TABLE `adv`
  MODIFY `adv_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

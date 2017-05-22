-- phpMyAdmin SQL Dump
-- version 4.4.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 12, 2016 at 01:21 PM
-- Server version: 10.0.19-MariaDB-1~trusty-log
-- PHP Version: 5.5.9-1ubuntu4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `RecipeStack`
--
CREATE DATABASE IF NOT EXISTS `RecipeStack` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `RecipeStack`;

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

DROP TABLE IF EXISTS `answers`;
CREATE TABLE IF NOT EXISTS `answers` (
  `aid` int(10) NOT NULL,
  `adesc` varchar(2000) NOT NULL,
  `qid` int(10) NOT NULL,
  `uid_ans` int(11) NOT NULL,
  `answered_date` date NOT NULL,
  `best_ans` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`aid`, `adesc`, `qid`, `uid_ans`, `answered_date`, `best_ans`) VALUES
(1, 'You need bread and filling', 1, 3, '2016-09-24', 1),
(2, 'get some bread and toaster', 1, 4, '2016-09-25', 0),
(3, 'you need potatoes and lots of oil and spices', 3, 7, '2016-09-25', 1),
(8, 'Cut the potatoes in *&quot;small&quot;&lt;&gt;      pieces.', 3, 4, '2016-09-25', 0),
(13, 'go to subway', 1, 2, '2016-09-30', 0),
(14, 'Mix together flour and cocoa powder', 2, 2, '2016-09-30', 0),
(15, 'Yes you can', 4, 2, '2016-09-30', 0),
(16, 'Test answer', 5, 2, '2016-10-03', 0),
(17, 'Zuul test answer', 5, 13, '2016-10-04', 0),
(22, 'Cut the potatoes in ''select * from quiz     pieces.', 3, 4, '2016-10-04', 0),
(23, 'You cannot cook in 2 mins', 6, 1, '2016-10-04', 0),
(24, 'go to dominos', 9, 4, '2016-10-17', 1),
(25, 'California Pizza Kitchen is the best place in Norfolk!', 11, 8, '2016-10-17', 1),
(26, 'Get ready to eat Dhokla from the Indian stores. Saves a lot of you time.', 14, 7, '2016-10-17', 0),
(27, 'Mazzika in colley avenue.', 15, 7, '2016-10-17', 0),
(28, '<p>I guess <strong>buffalo</strong> wild wings</p>', 10, 10, '2016-10-17', 0),
(29, 'Buy the frozen pizza from seven 11 and just heat it.', 13, 10, '2016-10-17', 1),
(32, 'hello', 2, 8, '2016-10-17', 0),
(36, 'hello :) smiley', 2, 10, '2016-10-18', 1),
(37, 'hello :)', 2, 10, '2016-10-18', 0),
(38, 'hello :)', 9, 10, '2016-10-18', 0),
(40, 'hello :)', 12, 1, '2016-10-18', 0),
(41, 'Wine should be at room temperature :)', 4, 1, '2016-10-18', 1),
(42, 'select * from pizza', 11, 1, '2016-10-18', 0),
(43, 'I believe we can..', 6, 2, '2016-10-18', 0),
(44, 'test comment', 18, 1, '2016-10-18', 0),
(45, 'test comment 1', 18, 12, '2016-10-18', 0),
(46, '', 10, 17, '2016-10-20', 0),
(47, 'Five guys', 10, 1, '2016-10-25', 0),
(48, 'Try Del Vecchios', 10, 1, '2016-10-25', 0),
(49, 'Ynot pizza', 11, 1, '2016-10-25', 0),
(50, 'Dominos', 11, 1, '2016-10-25', 0),
(51, 'Fry chopped potatoes', 3, 1, '2016-10-25', 0),
(52, 'This is another answer', 2, 17, '2016-10-25', 0),
(55, 'Wines are not chilled', 4, 17, '2016-11-01', 0),
(56, 'Check out some videos on youtube', 14, 17, '2016-11-01', 0),
(58, 'Try Mc D', 10, 2, '2016-11-12', 0),
(59, 'Pagination test page 2 ans', 10, 2, '2016-11-12', 0),
(62, 'tinymce test fail', 10, 1, '2016-11-16', 1),
(63, '&lt;p&gt;tiny MCE &lt;strong&gt;test&lt;/strong&gt; 1&lt;/p&gt;', 10, 1, '2016-11-16', 0),
(64, '<p>Tiny mce <strong><em>test</em> </strong>2</p>', 10, 1, '2016-11-16', 0),
(65, '<p><strong>pagination</strong> test</p>', 2, 2, '2016-11-21', 0),
(66, '<p><img src="http://city4.xn--e1akkdfpb6a.xn--p1ai/images1/visakhapatnam-india-5.jpg" alt="" width="665" height="514" /></p>', 2, 2, '2016-11-21', 0),
(68, '&lt;p&gt;this is a ', 23, 17, '2016-12-12', 0),
(69, '&lt;p&gt;this is a ', 23, 17, '2016-12-12', 0),
(70, '<p>this is a ', 23, 17, '2016-12-12', 0),
(75, '&lt;p&gt;this is a ', 23, 17, '2016-12-12', 0);

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

DROP TABLE IF EXISTS `question`;
CREATE TABLE IF NOT EXISTS `question` (
  `Qid` int(10) NOT NULL,
  `qtitle` varchar(200) NOT NULL,
  `qcontent` varchar(800) NOT NULL,
  `uid` int(11) NOT NULL,
  `created_date` date NOT NULL,
  `views` int(200) NOT NULL,
  `freeze` int(11) NOT NULL,
  `hide` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`Qid`, `qtitle`, `qcontent`, `uid`, `created_date`, `views`, `freeze`, `hide`) VALUES
(1, 'How to make a sandwich?', '<p>Please tell me how to make a <strong>sandwich</strong> in 5 mins</p>', 1, '2016-09-22', 0, 1, 0),
(2, 'How to make a vanilla cake', '<p>I want to know the procedure for <strong>baking</strong> vanilla cake</p><p>Edited <strong>Content</strong></p>', 1, '2016-09-22', 0, 0, 0),
(3, 'Crisp Roasted Potatoes', 'How to make crisp roasted potatoes in microwave', 2, '2016-09-23', 0, 0, 0),
(4, 'Re chill wine', 'Can you re chill wine?', 1, '2016-09-23', 0, 0, 0),
(5, 'Samosa', 'How to make samosa', 1, '2016-09-23', 0, 0, 0),
(6, 'Pumpkin Apple Streusel Muffins', '<p>How to bake pumpkin <strong>muffins</strong> in 2 mins</p>', 2, '2016-10-04', 0, 1, 0),
(9, 'Garlic Breads', 'how to make stuffed garlic breads &lt;b&gt;', 1, '2016-10-17', 0, 0, 0),
(10, 'Potato fries', '<p>Where can I <strong>find</strong> the [b]best[/b] loaded potato fries in <strong>Norfolk</strong>?</p>', 7, '2016-10-17', 0, 0, 0),
(11, 'Pizza', 'Best place to have pizza in Norfolk', 9, '2016-10-17', 0, 0, 0),
(12, 'Street food', 'Please suggest a good place for street food', 9, '2016-10-17', 0, 0, 1),
(13, 'Pizza', 'How to make pizzas at home?', 8, '2016-10-17', 0, 0, 0),
(14, 'Dhokla', 'How do we make dhokla', 9, '2016-10-17', 0, 0, 0),
(15, 'Arabic food', 'please suggest a good place which serves Arabic food ', 10, '2016-10-17', 0, 0, 0),
(16, 'Burritos', 'How to make burritos', 10, '2016-10-17', 0, 0, 0),
(18, '&quot;what does &lt;!-- mean&quot;', 'test', 1, '2016-10-18', 0, 1, 0),
(22, 'Brinjal Curry', 'How to cook spicy brinjal curry', 1, '2016-10-22', 0, 0, 0),
(23, 'Best eateries around ODU', '<p>please suggest some good eateries around Norfolk.</p>', 17, '2016-10-25', 0, 0, 0),
(24, 'This is &lt;b&gt; a question&lt;/b&gt; !', 'This is &lt;b&gt; a question&lt;/b&gt; !', 17, '2016-10-31', 0, 0, 1),
(25, 'Chinese food', 'Which is the best Chinese restaurant in Norfolk?', 86, '2016-10-31', 0, 0, 1),
(26, 'Title test 1', '<p>Test content 1</p>', 1, '2016-11-22', 0, 0, 0),
(27, 'Test Title 2', '<p><br data-mce-bogus="1"></p>', 1, '2016-11-22', 0, 0, 0),
(28, 'Test title 3', '<p>Test <strong>content</strong> 3<br data-mce-bogus="1"></p>', 1, '2016-11-22', 0, 0, 0),
(29, 'Multi Tag Test', '<p>Multi Tag Testing with [b]2 tags[/b]<br data-mce-bogus="1"></p>', 2, '2016-12-02', 0, 0, 0),
(30, 'Unknown Tag', '<p>unknown tag test</p>', 1, '2016-12-05', 0, 0, 0),
(31, 'New Tag creation', '<p>New Tag creation test<br data-mce-bogus="1"></p>', 1, '2016-12-05', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `question_tag`
--

DROP TABLE IF EXISTS `question_tag`;
CREATE TABLE IF NOT EXISTS `question_tag` (
  `qid_fk` int(10) NOT NULL,
  `tag_id_fk` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `question_tag`
--

INSERT INTO `question_tag` (`qid_fk`, `tag_id_fk`) VALUES
(22, 1),
(10, 9),
(5, 1),
(14, 1),
(1, 9),
(2, 9),
(3, 9),
(11, 9),
(4, 9),
(6, 9),
(23, 1),
(24, 1),
(25, 2),
(10, 1),
(15, 1),
(15, 5),
(29, 6),
(29, 7),
(30, 1),
(31, 16),
(31, 5);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
CREATE TABLE IF NOT EXISTS `tags` (
  `tag_id` int(11) NOT NULL,
  `tags` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`tag_id`, `tags`) VALUES
(1, 'Indian'),
(2, 'Chinese'),
(3, 'French'),
(4, 'French'),
(5, 'Greek'),
(6, 'Italian'),
(7, 'Thai'),
(8, 'Mediterrian'),
(9, 'American'),
(10, 'Continental'),
(11, 'Cuban'),
(12, 'Mexican'),
(13, 'Malaysian'),
(14, 'Singapore'),
(15, 'Spanish'),
(16, 'Russian');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `uid` int(11) NOT NULL,
  `firstname` varchar(30) DEFAULT NULL,
  `lastname` varchar(30) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `contact` varchar(10) DEFAULT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `upic` longtext,
  `pic_pref` tinyint(1) NOT NULL DEFAULT '0',
  `created_on` date DEFAULT NULL,
  `role` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`uid`, `firstname`, `lastname`, `address`, `email`, `contact`, `username`, `password`, `upic`, `pic_pref`, `created_on`, `role`) VALUES
(1, 'Kumar', 'Kallepalli', 'Norfolk, VA', 'kkall002@odu.edu', '7072171427', 'kkall002@odu.edu', '1234', 'profiles/1.jpg', 0, '2016-09-20', 0),
(2, 'Admin', 'admin', 'Norfolk, VA', 'admin@xyz.com', '123-234-34', 'admin', 'cs518pa$$', 'profiles/2.png', 0, NULL, 1),
(3, 'Justin', 'Brunelle', 'Norfolk, VA', 'jbrun@xyz.com', '342458697', 'jbrunelle', 'M0n@rch$', NULL, 0, NULL, 0),
(4, 'Peter', 'Venkman', 'Norfolk, VA', 'pven@xyz.com', '343453466', 'pvenkman', 'imadoctor', 'profiles/4.jpg', 0, NULL, 0),
(5, 'Ray', 'stantz', 'Norfolk, VA', 'rstan@xyz.com', '756756645', 'rstantz', '"; INSERT INTO Customers (CustomerName,Address,City) Values(@0,@1,@2); --', 'profiles/5.jpg', 0, NULL, 0),
(6, 'Dana', 'Barrett', 'Norfolk, VA', 'dbar@xyz.com', '977565645', 'dbarrett', 'fr1ed3GGS', 'profiles/6.jpg', 0, NULL, 0),
(7, 'Louis', 'tully', 'Norfolk, VA', 'ltul@xyz.com', '5656454343', 'ltully', '1234', 'profiles/7.jpg', 0, NULL, 0),
(8, 'Egon ', 'Spengler', 'Norfolk, VA', 'espen@xyz.com', '4564564564', 'espengler', 'don''t cross the streams', 'profiles/8.jpg', 0, NULL, 0),
(9, 'Janine ', 'Melnitz', 'Norfolk, VA', 'jmel@xyz.com', '6456454578', 'janine', '--!drop tables;', 'profiles/9.jpg', 0, NULL, 0),
(10, 'Winston ', 'Zeddemore', 'Norfolk, VA', 'wzed@xyz.com', '367786655', 'winston', 'zeddM0r3', 'profiles/10.jpg', 0, NULL, 0),
(11, 'gozer', 'gozer', 'Norfolk, VA', 'gozer@xyz.com', '7455656454', 'gozer', 'd3$truct0R', NULL, 0, NULL, 0),
(12, 'slimer', 'slimer', 'Norfolk, VA', 'slimer@xyz.com', '7566453453', 'slimer', 'f33dM3', NULL, 0, NULL, 0),
(13, 'Zuul', 'Zuul', 'Norfolk, VA', 'zuul@xyz.com', '365767454', 'zuul', '105"; DROP TABLE', NULL, 0, NULL, 0),
(14, 'keymaster', 'keymaster', 'Norfolk, VA', 'keymas@xyz.com', '423547345', 'keymaster', 'n0D@na', NULL, 0, NULL, 0),
(15, 'gatekeeper', 'gatekeeper', 'Norfolk, VA', 'gatek@xyz.com', '7567238348', 'gatekeeper', '$l0r', NULL, 0, NULL, 0),
(16, 'Stay', 'Puft', 'Norfolk, VA', 'stayp@xyz.com', '723739843', 'staypuft', 'm@r$hM@ll0w', NULL, 0, NULL, 0),
(17, 'Satya', 'Narayan', 'Norfolk, VA', 'satya.aquarian@gmail.com', '7575757575', 'satya', 'pass123', 'profiles/17.jpg', 1, '2016-10-19', 0),
(50, 'Maheedhar', 'Gunnam', 'Norfolk, VA', 'getreadytomail@gmail.com', '956389456', 'maheedhar', '1234', NULL, 1, '2016-10-21', 0),
(55, 'Deepthi', 'Lakshminarayana', 'Norfolk, VA', 'deepthi@xyz.com', '784548743', 'Deepthi', '1234', NULL, 0, '2016-10-21', 0),
(86, 'Surbhi', 'Shankar', 'Norfolk, VA', 'abc@xyz.com', '123-234-34', 'sshankar', 'abcd', 'profiles/86.jpg', 0, '2016-10-24', 0),
(89, NULL, NULL, NULL, NULL, NULL, 'kkallepalli', '', 'https://avatars.githubusercontent.com/u/22042508?v=3', 0, '2016-12-06', 0);

-- --------------------------------------------------------

--
-- Table structure for table `votes_ans`
--

DROP TABLE IF EXISTS `votes_ans`;
CREATE TABLE IF NOT EXISTS `votes_ans` (
  `vid_ans` int(10) NOT NULL,
  `vote_ans` int(10) NOT NULL,
  `aid` int(10) NOT NULL,
  `vote_ans_uid` int(10) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `votes_ans`
--

INSERT INTO `votes_ans` (`vid_ans`, `vote_ans`, `aid`, `vote_ans_uid`) VALUES
(5, 1, 1, 4),
(6, -1, 1, 5),
(8, 1, 1, 6),
(9, 1, 28, 1),
(14, 1, 40, 1),
(15, 1, 8, 1),
(17, -1, 3, 1),
(19, 1, 23, 1),
(20, 1, 36, 17),
(21, -1, 14, 17),
(24, 1, 32, 17),
(25, -1, 37, 17),
(29, 1, 52, 17),
(33, 1, 48, 10),
(35, 1, 49, 10),
(37, 1, 43, 17),
(38, 1, 47, 17),
(39, 1, 26, 17),
(42, 1, 2, 17),
(43, 1, 23, 17);

-- --------------------------------------------------------

--
-- Table structure for table `votes_ques`
--

DROP TABLE IF EXISTS `votes_ques`;
CREATE TABLE IF NOT EXISTS `votes_ques` (
  `vid_ques` int(10) NOT NULL,
  `vote_ques` int(10) NOT NULL,
  `qid` int(10) NOT NULL,
  `vote_ques_uid` int(10) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `votes_ques`
--

INSERT INTO `votes_ques` (`vid_ques`, `vote_ques`, `qid`, `vote_ques_uid`) VALUES
(8, 1, 1, 2),
(10, -1, 1, 1),
(11, 1, 2, 2),
(14, 1, 2, 7),
(16, 1, 10, 10),
(18, 1, 9, 7),
(21, -1, 12, 7),
(24, 1, 6, 1),
(26, 1, 10, 17),
(27, 1, 2, 17),
(30, 1, 5, 17),
(32, 1, 14, 17),
(34, 1, 6, 17),
(35, 1, 3, 17),
(36, 1, 14, 1),
(38, 1, 10, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`aid`),
  ADD KEY `uid` (`uid_ans`),
  ADD KEY `answers_ibfk_1` (`qid`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`Qid`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `question_tag`
--
ALTER TABLE `question_tag`
  ADD KEY `tag_id_fk` (`tag_id_fk`),
  ADD KEY `Qid_fk` (`qid_fk`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`tag_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `votes_ans`
--
ALTER TABLE `votes_ans`
  ADD PRIMARY KEY (`vid_ans`),
  ADD UNIQUE KEY `aid` (`aid`,`vote_ans_uid`),
  ADD UNIQUE KEY `aid_2` (`aid`,`vote_ans_uid`),
  ADD KEY `ans_uid` (`vote_ans_uid`);

--
-- Indexes for table `votes_ques`
--
ALTER TABLE `votes_ques`
  ADD PRIMARY KEY (`vid_ques`),
  ADD UNIQUE KEY `qid` (`qid`,`vote_ques_uid`),
  ADD KEY `V_uid` (`vote_ques_uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `aid` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=76;
--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `Qid` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=91;
--
-- AUTO_INCREMENT for table `votes_ans`
--
ALTER TABLE `votes_ans`
  MODIFY `vid_ans` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=45;
--
-- AUTO_INCREMENT for table `votes_ques`
--
ALTER TABLE `votes_ques`
  MODIFY `vid_ques` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=39;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`qid`) REFERENCES `question` (`Qid`),
  ADD CONSTRAINT `answers_ibfk_2` FOREIGN KEY (`uid_ans`) REFERENCES `user` (`uid`);

--
-- Constraints for table `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `question_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`);

--
-- Constraints for table `votes_ans`
--
ALTER TABLE `votes_ans`
  ADD CONSTRAINT `votes_ans_ibfk_1` FOREIGN KEY (`aid`) REFERENCES `answers` (`aid`),
  ADD CONSTRAINT `votes_ans_ibfk_2` FOREIGN KEY (`vote_ans_uid`) REFERENCES `user` (`uid`);

--
-- Constraints for table `votes_ques`
--
ALTER TABLE `votes_ques`
  ADD CONSTRAINT `votes_ques_ibfk_1` FOREIGN KEY (`qid`) REFERENCES `question` (`Qid`),
  ADD CONSTRAINT `votes_ques_ibfk_2` FOREIGN KEY (`vote_ques_uid`) REFERENCES `user` (`uid`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

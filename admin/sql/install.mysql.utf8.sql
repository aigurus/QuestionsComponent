CREATE TABLE `#__questions_comments` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `question` tinyint(1) NOT NULL DEFAULT 0,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `#__questions_core`
--

CREATE TABLE `#__questions_core` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `alias` text NOT NULL,
  `text` text NOT NULL,
  `submitted` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  `userid_creator` int(11) NOT NULL,
  `userid_modifier` int(11) DEFAULT NULL,
  `question` int(11) NOT NULL,
  `votes_positive` int(11) NOT NULL,
  `votes_negative` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `impressions` int(11) NOT NULL DEFAULT 0,
  `published` tinyint(3) NOT NULL,
  `likes` int(11) NOT NULL DEFAULT 0,
  `chosen` int(11) NOT NULL,
  `pinned` tinyint(2) NOT NULL,
  `locked` tinyint(2) NOT NULL,
  `closed` tinyint(1) NOT NULL DEFAULT 0,
  `flagged` tinyint(1) NOT NULL DEFAULT 0,
  `name` text DEFAULT NULL,
  `ip` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `refurl1` varchar(255) NOT NULL,
  `refurl2` varchar(255) NOT NULL,
  `refurl3` varchar(255) NOT NULL,
  `groups` text NOT NULL,
  `catid` int(11) NOT NULL DEFAULT 0,
  `users_voted` text DEFAULT NULL,
  `qtags` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__questions_core_logs`
--

CREATE TABLE `#__questions_core_logs` (
  `id` int(11) NOT NULL,
  `qid` int(11) NOT NULL,
  `title` text NOT NULL,
  `alias` text NOT NULL,
  `text` text NOT NULL,
  `submitted` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  `userid_creator` int(11) NOT NULL,
  `userid_modifier` int(11) DEFAULT NULL,
  `question` int(11) NOT NULL,
  `votes_positive` int(11) NOT NULL,
  `votes_negative` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `impressions` int(11) NOT NULL DEFAULT 0,
  `published` tinyint(3) NOT NULL,
  `likes` int(11) NOT NULL DEFAULT 0,
  `chosen` int(11) NOT NULL,
  `pinned` tinyint(2) NOT NULL,
  `locked` tinyint(2) NOT NULL,
  `closed` tinyint(1) NOT NULL DEFAULT 0,
  `flagged` tinyint(1) NOT NULL DEFAULT 0,
  `name` text DEFAULT NULL,
  `ip` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `refurl1` varchar(255) NOT NULL,
  `refurl2` varchar(255) NOT NULL,
  `refurl3` varchar(255) NOT NULL,
  `groups` text NOT NULL,
  `catid` int(11) NOT NULL DEFAULT 0,
  `users_voted` text DEFAULT NULL,
  `qtags` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__questions_favourite`
--

CREATE TABLE `#__questions_favourite` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `ansfav` varchar(250) NOT NULL,
  `quesfav` varchar(250) NOT NULL,
  `userfav` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__questions_groups`
--

CREATE TABLE `#__questions_groups` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `moderators` text NOT NULL,
  `published` int(2) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `modified` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip` varchar(55) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__questions_notification`
--

CREATE TABLE `#__questions_notification` (
  `id` int(10) NOT NULL,
  `to_user` int(10) NOT NULL DEFAULT 0,
  `from_user` int(10) NOT NULL DEFAULT 0,
  `reference` int(10) NOT NULL DEFAULT 0,
  `type` enum('groupadd','repin') NOT NULL,
  `seen` tinyint(4) NOT NULL DEFAULT 0,
  `timestamp` varchar(12) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__questions_ranks`
--

CREATE TABLE `#__questions_ranks` (
  `id` int(11) NOT NULL,
  `rank` text NOT NULL,
  `pointsreq` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__questions_reports`
--

CREATE TABLE `#__questions_reports` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `bugreport` text NOT NULL,
  `qareport` text NOT NULL,
  `qid` int(11) NOT NULL,
  `submitted` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ip` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `title` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__questions_userlocation`
--

CREATE TABLE `#__questions_userlocation` (
  `id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `address` varchar(80) NOT NULL,
  `lat` float(10,6) NOT NULL,
  `lng` float(10,6) NOT NULL,
  `type` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__questions_userprofile`
--

CREATE TABLE `#__questions_userprofile` (
  `id` int(11) NOT NULL,
  `description` text NOT NULL,
  `url1` varchar(55) NOT NULL,
  `url2` varchar(55) NOT NULL,
  `url3` varchar(55) NOT NULL,
  `userid` int(11) NOT NULL,
  `location` varchar(25) NOT NULL,
  `company` varchar(25) NOT NULL,
  `position` varchar(25) NOT NULL,
  `workno` varchar(25) NOT NULL,
  `mobno` varchar(25) NOT NULL,
  `workaddress` varchar(255) NOT NULL,
  `username` varchar(25) NOT NULL,
  `answered` int(11) NOT NULL,
  `asked` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `rank` varchar(25) NOT NULL,
  `chosen` int(11) NOT NULL,
  `subs` varchar(255) NOT NULL,
  `logdate` date DEFAULT NULL,
  `email` varchar(55) DEFAULT NULL,
  `groups` varchar(255) NOT NULL,
  `blocked` int(11) NOT NULL,
  `impressions` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `#__questions_comments`
--
ALTER TABLE `#__questions_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `#__questions_core`
--
ALTER TABLE `#__questions_core`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `#__questions_core_logs`
--
ALTER TABLE `#__questions_core_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `#__questions_favourite`
--
ALTER TABLE `#__questions_favourite`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `#__questions_groups`
--
ALTER TABLE `#__questions_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `#__questions_notification`
--
ALTER TABLE `#__questions_notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `to_user` (`to_user`),
  ADD KEY `from_user` (`from_user`),
  ADD KEY `reference` (`reference`);

--
-- Indexes for table `#__questions_ranks`
--
ALTER TABLE `#__questions_ranks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `#__questions_reports`
--
ALTER TABLE `#__questions_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `#__questions_userlocation`
--
ALTER TABLE `#__questions_userlocation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `#__questions_userprofile`
--
ALTER TABLE `#__questions_userprofile`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `userid` (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `#__questions_comments`
--
ALTER TABLE `#__questions_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `#__questions_core`
--
ALTER TABLE `#__questions_core`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `#__questions_core_logs`
--
ALTER TABLE `#__questions_core_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `#__questions_favourite`
--
ALTER TABLE `#__questions_favourite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `#__questions_groups`
--
ALTER TABLE `#__questions_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `#__questions_notification`
--
ALTER TABLE `#__questions_notification`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `#__questions_ranks`
--
ALTER TABLE `#__questions_ranks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `#__questions_reports`
--
ALTER TABLE `#__questions_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `#__questions_userlocation`
--
ALTER TABLE `#__questions_userlocation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `#__questions_userprofile`
--
ALTER TABLE `#__questions_userprofile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

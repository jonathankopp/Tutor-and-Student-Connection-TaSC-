CREATE TABLE `users` (
	`userid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`first_names` varchar(100) NOT NULL,
	`last_name` varchar(100)  NULL,
	`year` char(4) NOT NULL,
	`email` varchar(100) NOT NULL,
	`password` varchar(100) NOT NULL,
	`description` varchar(1000) NOT NULL,
	`score` int(10) NOT NULL,
	PRIMARY KEY (`userid`)
);

CREATE TABLE `connections` (
	`tutorid` int(10) unsigned NOT NULL,
	`studentid` int(10) unsigned NOT NULL,
	`subject` varchar(100) NOT NULL,
	PRIMARY KEY (`tutorid`, `studentid`, `subject`)
);

CREATE TABLE `student_subjects` (
	`userid` int(10) unsigned NOT NULL,
	`course` varchar(100) NOT NULL,
	PRIMARY KEY (`userid`, `course`)
);

CREATE TABLE `tutor_subjects` (
	`userid` int(10) unsigned NOT NULL,
	`course` varchar(100) NOT NULL,
	PRIMARY KEY (`userid`, `course`)
);

CREATE TABLE `subject` (
	`subjectid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`course` varchar(100) NOT NULL,
	PRIMARY KEY (`subjectid`)
);

CREATE TABLE `forum` (
	`postid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`courseid` int(10) unsigned NOT NULL,
	`topic` varchar(1000) NOT NULL,
	`post` varchar(1000) NOT NULL,
	`postdate` date NOT NULL,
	`userid` int(10) unsigned NOT NULL,
	PRIMARY KEY (`postid`)
);

CREATE TABLE `comments` (
  `postid` int(10) UNSIGNED NOT NULL,
  `comment` varchar(1000) NOT NULL,
  `commentdate` date DEFAULT NULL,
  `uid` int(10) NOT NULL
);

CREATE TABLE `reviews` (
	`revieweremail` varchar(100) NOT NULL,
	`reviewedemail` varchar(100) NOT NULL,
	`createdat` datetime,
	`rating` tinyint(1) NOT NULL,
	`review` varchar(1000),
	PRIMARY KEY (`revieweremail`,`reviewedemail`)
);

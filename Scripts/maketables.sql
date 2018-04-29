CREATE TABLE `users` (
	`userid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`first_names` varchar(100) NOT NULL,
	`last_name` varchar(100)  NULL,
	`year` char(4) NOT NULL,
	`email` varchar(100) NOT NULL,
	`password` varchar(100) NOT NULL,
	`description` varchar(1000) NOT NULL,
	`tutor` boolean NOT NULL,
	PRIMARY KEY (`userid`)
);

CREATE TABLE `connections` (
	`tutorid` int(10) unsigned NOT NULL,
	`studentid` int(10) unsigned NOT NULL,
	`subject` varchar(100) NOT NULL
);

CREATE TABLE `user_subjects` (
	`userid` int(10) unsigned NOT NULL,
	`course` varchar(100) NOT NULL
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
	`postid` int(10) unsigned NOT NULL,
	`comment` varchar(1000) NOT NULL,
	`commentdate` date 
);
/*



Success: 1 movie added to database.
Post about: a has been added


*/
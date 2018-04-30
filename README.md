Tutor and Student Connection (TaSC)

This web application is designed to connect students and tutors to 
eachother, and allow them to ask and answer questions via a discussion forum.

Instructions to install and run this application:

1. Create a database named "tasc" in phpMyAdmin. 

2. To create the tables and populate the database with fake users, import tasc.sql from the Scripts folder
	Note: If you just wish to create the empty tables, import maketables.sql from the Scripts folder.

3. Change the password for the database to your phpMyAdmin password on lines:
	comment.php lines 23 and 110
	connect.php line 37
	connectionmade.php line 32
	find.php line 42
	forum.php lines 39 and 79
	index.php line 25
	makepost.php line 48
	signup.php line 19

4. The page is now ready to be used.
Navigate to index.php to login and create a new account by clicking the link provided.

5. Once a new account is created, you will be brought back to the login page where you can 
now login with your newly created email and password.

6. Once logged in, you are free to navigate the site to connect with students or view and post on the discussion forum. 
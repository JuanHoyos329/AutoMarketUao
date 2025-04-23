create database if not exists usersautomarketuao;
use usersautomarketuao;

create table if not exists users (
userId int unique auto_increment not null,
name varchar(100) not null,
last_name varchar(100) not null,
username varchar(50) unique not null,
password varchar(255) not null,
email varchar(100) primary key not null, 
phone varchar(50) not null,
role ENUM('user', 'admin') NOT NULL DEFAULT 'user'
);

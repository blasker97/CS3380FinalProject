# CS3380FinalProject

#### Hosting login:
* **username**: mcwv35@mail.missouri.edu
* **password**: database2018


### Team Members: 
* Brian Lasker
* Madison Williams
* Bradley Worthen

### Application Description: 
This application, *Golf Tracker*, was built to serve a golf club to keep records of members and their round history. Upon launch of the application the user must enter their member credentials: a username and password. The user is then redirected to view their game history. This page features: their score on each hole (1-9), their overall round score, and the date in which the round was played. From this screen, the user can open a form to add information for a newly played game, edit previously entered game data, and delete old rounds all together. When at the form to enter in new game data, the user simply inputs their score on each hole and then click on the **submit** button. If the user needs to go back to the game history page they can click on the **cancel** button. Once the user is ready to leave the site they can click on the **logout** button from the game history page.

### Schema for Database: 

##### Users: 
```SQL
CREATE TABLE users (
  id int NOT NULL AUTO_INCREMENT,
  loginID varchar(255) NOT NULL,
  password varchar(255) NOT NULL,
  firstName varchar(128) NOT NULL,
  lastName varchar(128) NOTNULL
)
```

##### Games: 
```SQL
CREATE TABLE games (
  id int NOT NULL AUTO_INCREMENT,
  addDate datetime,
  userID int NOT NULL,
  hole1 int NOT NULL,
  hole2 int NOT NULL,
  hole3 int NOT NULL,
  hole4 int NOT NULL,
  hole5 int NOT NULL,
  hole6 int NOT NULL,
  hole7 int NOT NULL,
  hole8 int NOT NULL,
  hole9 int NOT NULL,
)
```

### ERD: [Entity Relationship Diagram](https://github.com/blasker97/CS3380FinalProject/blob/master/GolfERD.png)

### Explanation of where the app is doing create, read, update, and delete
* Create: The user can *create* new records (enter data for recently played rounds) via a form launched by clicking on the **Add Game** button.

* Read: The user can read data back through the game history page. There the user can order the rounds by date played, hole(1-9) score, and total score.

* Update: The user can *update* records (adjust scores on individual holes) via the round's corresponding **Edit** button on the game history page.

* Delete: The user can *delete* records (data for previously entered rounds) by clicking on the round's **Delete** button on the game history page.


### Video: 


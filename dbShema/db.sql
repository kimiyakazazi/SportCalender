DROP TABLE IF EXISTS Event;
DROP TABLE IF EXISTS Team;
DROP TABLE IF EXISTS Category;
DROP TABLE IF EXISTS Location;



CREATE TABLE Team
(
  id INT NOT NULL,
  name VARCHAR(50) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE Location
(
  id INT NOT NULL,
  name VARCHAR(50) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE Category
(
  name VARCHAR(50) NOT NULL,
  id INT NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE Event
(
  date datetime NOT NULL,
  id INT NOT NULL,

  _id_first_team INT NOT NULL,
  _id_second_team INT NOT NULL,
  _id_Category INT NOT NULL,
  _id_location INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (_id_first_team) REFERENCES Team(id),
  FOREIGN KEY (_id_second_team) REFERENCES Team(id),
  FOREIGN KEY (_id_Category) REFERENCES Category(id),
  FOREIGN KEY (_id_location) REFERENCES Location(id)

);